<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CrmDemoSeeder extends Seeder
{
    public function run()
    {
        $user=$this->db->table('users')->select('id,name,phone')->whereIn('level_id',[1,2,3,4])->where('phone IS NOT NULL')->get()->getRowArray();
        if(!$user)return;
        $active=['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP'];
        $session=$this->db->table('cs_conversations')->where('user_id',$user['id'])->whereIn('status',$active)->get()->getRowArray();
        if(!$session){$this->db->table('cs_conversations')->insert(['user_id'=>$user['id'],'platform'=>'whatsapp','platform_user_id'=>$user['phone'],'customer_name'=>$user['name'],'last_message'=>'Halo, saya membutuhkan bantuan.','last_message_at'=>date('Y-m-d H:i:s'),'status'=>'UNREAD','unread_count'=>1,'sla_started_at'=>date('Y-m-d H:i:s'),'status_changed_at'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);$id=$this->db->insertID();$this->db->table('cs_messages')->insert(['conversation_id'=>$id,'sender_type'=>'user','message_body'=>'Halo, saya membutuhkan bantuan.','status'=>'received','created_at'=>date('Y-m-d H:i:s')]);}
    }
}
