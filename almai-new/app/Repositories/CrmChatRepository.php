<?php
namespace App\Repositories;

class CrmChatRepository
{
    private $db;
    public function __construct(){ $this->db=\Config\Database::connect(); }

    public function summary(): array {
        $today=date('Y-m-d'); $statuses=['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP','DONE']; $out=[];
        foreach($statuses as $s) $out[$s]=(int)$this->db->table('cs_conversations')->where('status',$s)->countAllResults();
        $out['TOTAL_TODAY']=(int)$this->db->table('users')->where('DATE(created_at)',$today)->countAllResults();
        return $out;
    }

    public function paginate(array $filters): array {
        $page=max(1,(int)($filters['page']??1)); $limit=min(100,max(10,(int)($filters['limit']??20)));
        $builder=$this->db->table('cs_conversations c')->select('c.*,u.email,u.phone,u.created_at user_registered_at,a.name assigned_name')
            ->join('users u','u.id=c.user_id','left')->join('users a','a.id=c.assigned_to','left');
        if(!empty($filters['search'])) $builder->groupStart()->like('c.customer_name',$filters['search'])->orLike('u.phone',$filters['search'])->orLike('c.last_message',$filters['search'])->groupEnd();
        if(!empty($filters['status'])) $builder->where('c.status',$filters['status']);
        if(!empty($filters['pic'])) $builder->where('c.assigned_to',(int)$filters['pic']);
        if(!empty($filters['date'])) $builder->where('DATE(c.created_at)',$filters['date']);
        $count=clone $builder;
        $total=(int)$count->countAllResults();
        $priority="FIELD(c.status,'UNREAD','FOLLOW_UP','IN_PROGRESS','NEW','AUTO_REPLIED','DONE')";
        $rows=$builder->orderBy($priority,'ASC',false)->orderBy('c.last_message_at','DESC')->limit($limit,($page-1)*$limit)->get()->getResultArray();
        return ['data'=>$rows,'pagination'=>['page'=>$page,'limit'=>$limit,'total'=>$total,'pages'=>(int)ceil($total/$limit)]];
    }

    public function detail(int $id): ?array {
        $session=$this->db->table('cs_conversations c')->select('c.*,u.email,u.phone,u.created_at user_registered_at,a.name assigned_name')->join('users u','u.id=c.user_id','left')->join('users a','a.id=c.assigned_to','left')->where('c.id',$id)->get()->getRowArray();
        if(!$session)return null;
        $session['messages']=$this->db->table('cs_messages')->where('conversation_id',$id)->orderBy('id','ASC')->get()->getResultArray();
        $session['timeline']=$this->db->table('crm_status_history h')->select('h.*,u.name changed_by_name')->join('users u','u.id=h.changed_by','left')->where('chat_session_id',$id)->orderBy('h.id','ASC')->get()->getResultArray();
        return $session;
    }
}
