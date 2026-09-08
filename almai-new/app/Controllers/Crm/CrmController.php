<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\AutomationWorkflowModel;
use App\Models\UserModel;
use App\Repositories\CrmChatRepository;
use App\Services\CrmService;

class CrmController extends BaseController
{
    private CrmChatRepository $repo;
    private CrmService $service;

    public function __construct()
    {
        $this->repo = new CrmChatRepository();
        $this->service = new CrmService();
    }

    public function index()
    {
        $prefix = service('uri')->getSegment(1) === 'admin' ? 'admin' : 'superadmin';
        return view('crm/index', [
            'title' => 'CRM Monitoring Chat',
            'activeMenu' => 'crm',
            'layout' => $prefix . '/layouts/main',
            'apiBase' => base_url($prefix . '/crm/api'),
            'pics' => $this->picsData(),
            'workflows' => (new AutomationWorkflowModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function summary() { return $this->response->setJSON(['success'=>true,'data'=>$this->repo->summary()]); }
    public function sessions() { return $this->response->setJSON(['success'=>true]+$this->repo->paginate($this->request->getGet())); }

    public function show(int $id)
    {
        $data=$this->repo->detail($id);
        if($data && in_array($data['status'],['NEW','AUTO_REPLIED','UNREAD'],true)){
            $this->service->changeStatus($id,'IN_PROGRESS',(int)session()->get('userId'),'Chat dibuka oleh CS');
            $data=$this->repo->detail($id);
        }
        return $data ? $this->response->setJSON(['success'=>true,'data'=>$data]) : $this->response->setStatusCode(404)->setJSON(['success'=>false,'message'=>'Sesi tidak ditemukan']);
    }

    public function updateStatus(int $id)
    {
        $status=(string)$this->request->getVar('status');
        $ok=$this->service->changeStatus($id,$status,(int)session()->get('userId'),$this->request->getVar('note'));
        return $this->result($ok,$ok?'Status diperbarui':'Status tidak valid atau sesi tidak ditemukan');
    }

    public function assign(int $id)
    {
        $ok=$this->service->assign($id,(int)$this->request->getPost('pic_id'),(int)session()->get('userId'));
        return $this->result($ok,$ok?'PIC diperbarui':'PIC tidak ditemukan');
    }

    public function send(int $id)
    {
        $message=trim((string)$this->request->getPost('message'));
        if($message==='')return $this->result(false,'Pesan wajib diisi',422);
        $ok=$this->service->sendAdmin($id,$message,(int)session()->get('userId'),$this->request->getPost('internal_note')==='1');
        return $this->result($ok,$ok?'Pesan diproses':'Pesan gagal dikirim. Periksa nomor dan konfigurasi Balesotomatis.',$ok?200:502);
    }

    public function pics() { return $this->response->setJSON(['success'=>true,'data'=>$this->picsData()]); }
    public function workflows() { return $this->response->setJSON(['success'=>true,'data'=>(new AutomationWorkflowModel())->orderBy('id','DESC')->findAll()]); }

    public function messageSend()
    {
        $id=(int)$this->request->getVar('chat_session_id');$message=trim((string)$this->request->getVar('message'));
        if(!$id||$message==='')return $this->result(false,'chat_session_id dan message wajib diisi',422);
        return $this->result($this->service->sendAdmin($id,$message,(int)session()->get('userId')),'Pesan diproses');
    }

    public function messageAuto()
    {
        $id=(int)$this->request->getVar('chat_session_id');$message=trim((string)$this->request->getVar('message'));
        if(!$id||$message==='')return $this->result(false,'chat_session_id dan message wajib diisi',422);
        return $this->result($this->service->sendAutomatic($id,$message),'Auto reply diproses');
    }

    public function recap()
    {
        $db=\Config\Database::connect();
        $daily=$db->table('cs_conversations')->select("DATE(created_at) label,COUNT(*) total,SUM(auto_replied_at IS NOT NULL) auto_replied,SUM(status='UNREAD') unread,SUM(status='IN_PROGRESS') in_progress,SUM(status='FOLLOW_UP') follow_up,SUM(status='DONE') done",false)->where('created_at >=',date('Y-m-d H:i:s',strtotime('-13 days')))->groupBy('DATE(created_at)')->orderBy('label')->get()->getResultArray();
        $byPic=$db->table('cs_conversations c')->select("COALESCE(u.name,'Belum ditugaskan') label,SUM(c.status IN ('NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS')) active,SUM(c.status='FOLLOW_UP') follow_up,SUM(c.status='DONE') done,COUNT(*) total",false)->join('users u','u.id=c.assigned_to','left')->groupBy('c.assigned_to')->orderBy('total','DESC')->get()->getResultArray();
        return $this->response->setJSON(['success'=>true,'data'=>['daily'=>$daily,'by_pic'=>$byPic]]);
    }

    public function workflowStore()
    {
        $json=(string)$this->request->getPost('workflow_json');
        if(!is_array(json_decode($json,true)))return $this->result(false,'Workflow JSON tidak valid',422);
        $id=(new AutomationWorkflowModel())->insert(['name'=>trim((string)$this->request->getPost('name')),'trigger'=>$this->request->getPost('trigger'),'workflow_json'=>$json,'is_active'=>$this->request->getPost('is_active')==='0'?0:1,'created_by'=>(int)session()->get('userId')]);
        return $this->result((bool)$id,$id?'Workflow disimpan':'Workflow gagal disimpan');
    }

    public function workflowUpdate(int $id)
    {
        $model=new AutomationWorkflowModel();$row=$model->find($id);if(!$row)return $this->result(false,'Workflow tidak ditemukan',404);
        $data=['is_active'=>$this->request->getVar('is_active')==='1'?1:0];
        if($this->request->getVar('name')!==null)$data['name']=trim((string)$this->request->getVar('name'));
        if($this->request->getVar('trigger')!==null)$data['trigger']=$this->request->getVar('trigger');
        if($this->request->getVar('workflow_json')!==null){$json=(string)$this->request->getVar('workflow_json');if(!is_array(json_decode($json,true)))return $this->result(false,'Workflow JSON tidak valid',422);$data['workflow_json']=$json;}
        return $this->result($model->update($id,$data),'Workflow diperbarui');
    }

    private function picsData(): array
    {
        return (new UserModel())->select('id,name,email')->whereIn('level_id',[5,7])->groupStart()->where('crm_role','cs')->orWhere('crm_role',null)->groupEnd()->orderBy('name')->findAll();
    }

    private function result(bool $ok,string $message,int $code=200)
    {
        return $this->response->setStatusCode($ok?$code:($code===200?400:$code))->setJSON(['success'=>$ok,'message'=>$message]);
    }
}
