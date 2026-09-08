<?php
namespace App\Services;

use App\Libraries\Balesotomatis;
use App\Models\CsConversationModel;
use App\Models\CsMessageModel;
use App\Models\UserModel;

class CrmService
{
    public const STATUSES=['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP','DONE'];
    private $db; private CsConversationModel $sessions; private CsMessageModel $messages;
    public function __construct(){ $this->db=\Config\Database::connect(); $this->sessions=new CsConversationModel(); $this->messages=new CsMessageModel(); }

    public function onUserRegistered(int $userId): ?int {
        $user=(new UserModel())->find($userId); if(!$user || !in_array((int)($user['level_id']??0),[1,2,3,4],true))return null;
        $existing=$this->db->table('cs_conversations')->where('user_id',$userId)->whereIn('status',array_slice(self::STATUSES,0,5))->get()->getRowArray();
        if($existing)return (int)$existing['id'];
        $id=(int)$this->sessions->insert(['user_id'=>$userId,'platform'=>'whatsapp','platform_user_id'=>$this->phone($user['phone']??''),'customer_name'=>$user['name'],'status'=>'NEW','unread_count'=>0,'last_message'=>'User baru terdaftar','last_message_at'=>date('Y-m-d H:i:s'),'status_changed_at'=>date('Y-m-d H:i:s')],true);
        $this->history($id,null,'NEW',null,'User registered');
        $welcome="Halo {$user['name']} 👋\n\nSelamat datang.\nAkun Anda berhasil dibuat.\nTim kami siap membantu Anda.";
        if($this->send($id,$user,$welcome,true)) {
            $assigned=$this->roundRobin();
            $this->sessions->update($id,['status'=>'AUTO_REPLIED','assigned_to'=>$assigned,'auto_replied_at'=>date('Y-m-d H:i:s'),'sla_started_at'=>date('Y-m-d H:i:s'),'status_changed_at'=>date('Y-m-d H:i:s')]);
            $this->history($id,'NEW','AUTO_REPLIED',null,'Welcome sent and PIC assigned');
        }
        $this->triggerWorkflows('user_registered',['chat_session_id'=>$id,'user_id'=>$userId]);
        return $id;
    }

    public function onMessageReceived(string $phone,string $message,?string $platformId=null): ?int {
        $phone=$this->phone($phone); $user=(new UserModel())->like('phone',substr($phone,-9),'before')->first(); if(!$user)return null;
        $session=$this->db->table('cs_conversations')->where('user_id',$user['id'])->whereIn('status',array_slice(self::STATUSES,0,5))->orderBy('id','DESC')->get()->getRowArray();
        $id=$session?(int)$session['id']:$this->onUserRegistered((int)$user['id']); if(!$id)return null;
        if($platformId && $this->db->table('cs_messages')->where('platform_message_id',$platformId)->countAllResults())return $id;
        $this->messages->insert(['conversation_id'=>$id,'platform_message_id'=>$platformId,'sender_type'=>'user','message_body'=>$message,'status'=>'received']);
        $from=$session['status']??'AUTO_REPLIED';
        $this->sessions->update($id,['status'=>'UNREAD','unread_count'=>((int)($session['unread_count']??0))+1,'last_message'=>$message,'last_message_at'=>date('Y-m-d H:i:s'),'status_changed_at'=>date('Y-m-d H:i:s')]);
        if($from!=='UNREAD')$this->history($id,$from,'UNREAD',null,'Message received');
        $userCount=$this->db->table('cs_messages')->where('conversation_id',$id)->where('sender_type','user')->countAllResults();
        if($userCount===1){
            $menu=$this->outsideHours()?"Terima kasih. Saat ini di luar jam operasional. Pesan Anda sudah masuk ke antrean.":"Silakan pilih kebutuhan Anda:\n1️⃣ Bantuan\n2️⃣ Verifikasi\n3️⃣ Deposit\n4️⃣ Hubungi CS";
            $this->send($id,$user,$menu,true);
        }
        $this->triggerWorkflows('message_received',['chat_session_id'=>$id,'user_id'=>$user['id']]);
        return $id;
    }

    public function changeStatus(int $id,string $to,?int $actor=null,?string $note=null): bool {
        $to=strtoupper($to); if(!in_array($to,self::STATUSES,true))return false; $current=$this->sessions->find($id); if(!$current)return false;
        $data=['status'=>$to,'status_changed_at'=>date('Y-m-d H:i:s')];
        if($to==='IN_PROGRESS'){ $data['handled_by']=$actor;$data['assigned_to']=$actor;$data['handled_at']=date('Y-m-d H:i:s');$data['unread_count']=0; }
        if($to==='FOLLOW_UP')$data['follow_up_due_at']=date('Y-m-d H:i:s',time()+86400);
        if($to==='DONE'){$data['resolved_at']=date('Y-m-d H:i:s');$data['unread_count']=0;}
        $ok=$this->sessions->update($id,$data); if($ok){$this->history($id,$current['status'],$to,$actor,$note);$this->triggerWorkflows('status_changed',['chat_session_id'=>$id,'user_id'=>$current['user_id'],'from'=>$current['status'],'to'=>$to]);} return $ok;
    }

    public function assign(int $id,int $pic,?int $actor=null): bool { $user=(new UserModel())->find($pic); if(!$user)return false; $ok=$this->sessions->update($id,['assigned_to'=>$pic,'handled_by'=>$pic,'handler_name'=>$user['name'],'sla_started_at'=>date('Y-m-d H:i:s')]); if($ok)$this->history($id,null,$this->sessions->find($id)['status'],$actor,'Assigned to '.$user['name']); return $ok; }

    public function sendAdmin(int $id,string $message,int $actor,bool $note=false): bool {
        $session=$this->sessions->find($id); if(!$session)return false;
        if($note){$this->messages->insert(['conversation_id'=>$id,'sender_type'=>'admin','message_body'=>$message,'status'=>'sent','is_internal_note'=>1]);return true;}
        $user=(new UserModel())->find($session['user_id']); if(!$user)return false; $ok=$this->send($id,$user,$message,false,'admin'); if($ok)$this->changeStatus($id,'FOLLOW_UP',$actor,'Admin replied'); return $ok;
    }

    public function sendAutomatic(int $id,string $message): bool {
        $session=$this->sessions->find($id);if(!$session)return false;$user=(new UserModel())->find($session['user_id']);
        return $user ? $this->send($id,$user,$message,true) : false;
    }

    public function processReminders(): array {
        $now=date('Y-m-d H:i:s');$five=date('Y-m-d H:i:s',time()-300);$fifteen=date('Y-m-d H:i:s',time()-900);$reminders=0;$alerts=0;
        $rows=$this->db->table('cs_conversations')->where('status','UNREAD')->where('last_message_at <=',$five)->get()->getResultArray();
        foreach($rows as $s){
            if(empty($s['reminder_sent_at'])){$user=(new UserModel())->find($s['user_id']);if($user&&$this->send($s['id'],$user,'Halo, pesan Anda sudah kami terima. Tim CS akan segera membantu.',true)){$this->sessions->update($s['id'],['reminder_sent_at'=>$now]);$this->db->table('crm_notifications')->insert(['chat_session_id'=>$s['id'],'type'=>'SLA_REMINDER','message'=>'UNREAD lebih dari 5 menit','created_at'=>$now]);$reminders++;}}
            if(($s['last_message_at']??$now)<=$fifteen && !$this->db->table('crm_notifications')->where('chat_session_id',$s['id'])->where('type','SLA_UNREAD')->countAllResults()){$this->db->table('crm_notifications')->insert(['chat_session_id'=>$s['id'],'type'=>'SLA_UNREAD','message'=>'UNREAD lebih dari 15 menit','created_at'=>$now]);$alerts++;}
        }
        return ['reminders'=>$reminders,'alerts'=>$alerts];
    }

    public function processJobs(int $limit=50): array {
        if(!$this->db->tableExists('crm_jobs'))return ['processed'=>0,'failed'=>0];
        $jobs=$this->db->table('crm_jobs')->where('status','PENDING')->where('run_at <=',date('Y-m-d H:i:s'))->orderBy('id')->limit($limit)->get()->getResultArray();
        $processed=0;$failed=0;
        foreach($jobs as $job){
            try{
                $payload=json_decode($job['payload']??'[]',true)?:[];
                $flow=$this->db->table('automation_workflows')->where('id',(int)($payload['workflow_id']??0))->get()->getRowArray();
                if(!$flow)throw new \RuntimeException('Workflow tidak ditemukan');
                $definition=json_decode($flow['workflow_json']??'[]',true)?:[];
                $steps=$definition['steps']??$definition;$start=(int)($payload['step_index']??0);$deferred=false;
                foreach($steps as $index=>$step){
                    if($index<$start)continue;
                    $sessionId=(int)($payload['chat_session_id']??0);
                    if(!empty($step['delay'])){$seconds=$this->delaySeconds((string)$step['delay']);$payload['step_index']=$index+1;$this->db->table('crm_jobs')->where('id',$job['id'])->update(['payload'=>json_encode($payload),'run_at'=>date('Y-m-d H:i:s',time()+$seconds),'attempts'=>(int)$job['attempts']+1,'updated_at'=>date('Y-m-d H:i:s')]);$deferred=true;break;}
                    if(($step['condition']??'')==='no_reply' && $this->db->table('cs_messages')->where('conversation_id',$sessionId)->where('sender_type','user')->countAllResults()>0)continue;
                    $action=strtoupper((string)($step['action']??''));
                    if($action==='CHANGE_STATUS')$this->changeStatus($sessionId,(string)($step['status']??''),null,'Automation: '.$flow['name']);
                    if($action==='ASSIGN_PIC'&&!empty($step['pic_id']))$this->assign($sessionId,(int)$step['pic_id']);
                    if(in_array($action,['ASSIGN_CS','ASSIGN_TO_CS'],true) && ($pic=$this->roundRobin()))$this->assign($sessionId,$pic);
                    if($action==='SEND_MESSAGE'&&!empty($step['message'])){$s=$this->sessions->find($sessionId);$u=$s?(new UserModel())->find($s['user_id']):null;if($u)$this->send($sessionId,$u,(string)$step['message'],true);}
                    if($action==='SEND_WELCOME'){$s=$this->sessions->find($sessionId);$u=$s?(new UserModel())->find($s['user_id']):null;if($u)$this->send($sessionId,$u,"Halo {$u['name']} 👋\n\nSelamat datang. Akun Anda berhasil dibuat. Tim kami siap membantu Anda.",true);}
                }
                if(!$deferred)$this->db->table('crm_jobs')->where('id',$job['id'])->update(['status'=>'DONE','attempts'=>(int)$job['attempts']+1,'updated_at'=>date('Y-m-d H:i:s')]);$processed++;
            }catch(\Throwable $e){$this->db->table('crm_jobs')->where('id',$job['id'])->update(['status'=>'FAILED','attempts'=>(int)$job['attempts']+1,'last_error'=>$e->getMessage(),'updated_at'=>date('Y-m-d H:i:s')]);$failed++;}
        }
        return ['processed'=>$processed,'failed'=>$failed];
    }

    private function send(int $sessionId,array $user,string $message,bool $auto,string $sender='assistant'): bool {
        $phone=$this->phone($user['phone']??'');$success=false;$error=null;
        if($phone&&env('BALESOTOMATIS_SECRET_KEY')&&env('BALESOTOMATIS_LICENSES_KEY')){Balesotomatis::configure(env('BALESOTOMATIS_SECRET_KEY'),env('BALESOTOMATIS_LICENSES_KEY'));$res=Balesotomatis::sendPersonalMessage($phone,$message);$success=!empty($res['success']);$error=$res['error']??null;}
        $this->messages->insert(['conversation_id'=>$sessionId,'sender_type'=>$sender,'message_body'=>$message,'status'=>$success?'sent':'failed','is_auto_reply'=>$auto?1:0]);
        $this->sessions->update($sessionId,['last_message'=>$message,'last_message_at'=>date('Y-m-d H:i:s')]); if(!$success)log_message('error','CRM send failed: '.($error?:'credentials/phone missing')); return $success;
    }
    private function roundRobin(): ?int { $admins=$this->db->table('users')->select('id')->whereIn('level_id',[5,7])->groupStart()->where('crm_role','cs')->orWhere('crm_role IS NULL')->groupEnd()->orderBy('id')->get()->getResultArray();if(!$admins)return null;$counts=[];foreach($admins as $a)$counts[$a['id']]=$this->db->table('cs_conversations')->where('assigned_to',$a['id'])->whereIn('status',['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP'])->countAllResults();asort($counts);return (int)array_key_first($counts); }
    private function history(int $id,?string $from,string $to,?int $by,?string $note): void {$this->db->table('crm_status_history')->insert(['chat_session_id'=>$id,'from_status'=>$from,'to_status'=>$to,'changed_by'=>$by,'note'=>$note,'created_at'=>date('Y-m-d H:i:s')]);}
    private function triggerWorkflows(string $trigger,array $payload): void { if(!$this->db->tableExists('automation_workflows'))return;$flows=$this->db->table('automation_workflows')->where('trigger',$trigger)->where('is_active',1)->get()->getResultArray();foreach($flows as $f)$this->db->table('crm_jobs')->insert(['type'=>'WORKFLOW','payload'=>json_encode(['workflow_id'=>$f['id']]+$payload),'run_at'=>date('Y-m-d H:i:s'),'status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]); }
    private function outsideHours(): bool {$h=(int)date('G');return $h<8||$h>=17;}
    private function delaySeconds(string $delay): int {$delay=strtolower(trim($delay));if(preg_match('/^(\d+)\s*([smhd])$/',$delay,$m)){return max(1,(int)$m[1]*(['s'=>1,'m'=>60,'h'=>3600,'d'=>86400][$m[2]]));}return 60;}
    private function phone(string $p): string {$p=preg_replace('/\D+/','',$p)?:'';if(str_starts_with($p,'0'))$p='62'.substr($p,1);return $p;}
}
