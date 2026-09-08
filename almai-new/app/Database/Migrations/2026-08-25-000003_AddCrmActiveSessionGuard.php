<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCrmActiveSessionGuard extends Migration
{
    public function up()
    {
        if(!$this->db->fieldExists('active_user_id','cs_conversations')){
            $this->db->query("ALTER TABLE cs_conversations ADD active_user_id BIGINT UNSIGNED GENERATED ALWAYS AS (CASE WHEN status IN ('NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP') THEN user_id ELSE NULL END) STORED");
            $this->db->query('CREATE UNIQUE INDEX uq_cs_active_user ON cs_conversations (active_user_id)');
        }
    }
    public function down(){ /* Guard dipertahankan untuk mencegah sesi aktif ganda. */ }
}
