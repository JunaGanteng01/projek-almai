<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCrmMonitoring extends Migration
{
    public function up()
    {
        $columns = [
            'assigned_to' => ['type'=>'BIGINT','unsigned'=>true,'null'=>true],
            'sla_started_at' => ['type'=>'DATETIME','null'=>true],
            'follow_up_due_at' => ['type'=>'DATETIME','null'=>true],
            'auto_replied_at' => ['type'=>'DATETIME','null'=>true],
            'status_changed_at' => ['type'=>'DATETIME','null'=>true],
            'reminder_sent_at' => ['type'=>'DATETIME','null'=>true],
        ];
        foreach ($columns as $name => $definition) {
            if (!$this->db->fieldExists($name, 'cs_conversations')) $this->forge->addColumn('cs_conversations', [$name=>$definition]);
        }
        foreach ([
            'is_auto_reply'=>['type'=>'TINYINT','constraint'=>1,'default'=>0],
            'is_internal_note'=>['type'=>'TINYINT','constraint'=>1,'default'=>0],
        ] as $name=>$definition) {
            if (!$this->db->fieldExists($name, 'cs_messages')) $this->forge->addColumn('cs_messages', [$name=>$definition]);
        }

        if (!$this->db->tableExists('crm_status_history')) {
            $this->forge->addField([
                'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
                'chat_session_id'=>['type'=>'INT','unsigned'=>true],
                'from_status'=>['type'=>'VARCHAR','constraint'=>20,'null'=>true],
                'to_status'=>['type'=>'VARCHAR','constraint'=>20],
                'changed_by'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],
                'note'=>['type'=>'VARCHAR','constraint'=>500,'null'=>true],
                'created_at'=>['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true); $this->forge->addKey(['chat_session_id','created_at']);
            $this->forge->createTable('crm_status_history');
        }
        if (!$this->db->tableExists('automation_workflows')) {
            $this->forge->addField([
                'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
                'name'=>['type'=>'VARCHAR','constraint'=>190],
                'trigger'=>['type'=>'VARCHAR','constraint'=>80],
                'workflow_json'=>['type'=>'LONGTEXT'],
                'is_active'=>['type'=>'TINYINT','constraint'=>1,'default'=>1],
                'created_by'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],
                'created_at'=>['type'=>'DATETIME','null'=>true], 'updated_at'=>['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true); $this->forge->addKey(['trigger','is_active']);
            $this->forge->createTable('automation_workflows');
        }
        if (!$this->db->tableExists('crm_jobs')) {
            $this->forge->addField([
                'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
                'type'=>['type'=>'VARCHAR','constraint'=>80], 'payload'=>['type'=>'LONGTEXT'],
                'run_at'=>['type'=>'DATETIME'], 'status'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'PENDING'],
                'attempts'=>['type'=>'INT','default'=>0], 'last_error'=>['type'=>'TEXT','null'=>true],
                'created_at'=>['type'=>'DATETIME','null'=>true], 'updated_at'=>['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true); $this->forge->addKey(['status','run_at']); $this->forge->createTable('crm_jobs');
        }
        if (!$this->db->tableExists('crm_notifications')) {
            $this->forge->addField([
                'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true], 'chat_session_id'=>['type'=>'INT','unsigned'=>true],
                'type'=>['type'=>'VARCHAR','constraint'=>40], 'message'=>['type'=>'VARCHAR','constraint'=>500],
                'is_read'=>['type'=>'TINYINT','constraint'=>1,'default'=>0], 'created_at'=>['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true); $this->forge->createTable('crm_notifications');
        }

        // Normalisasi data dari fitur Customer Service lama agar langsung terlihat.
        $this->db->query("UPDATE cs_conversations SET status = CASE LOWER(status) WHEN 'new' THEN 'NEW' WHEN 'bot' THEN 'AUTO_REPLIED' WHEN 'waiting' THEN 'UNREAD' WHEN 'handled' THEN 'IN_PROGRESS' WHEN 'follow_up' THEN 'FOLLOW_UP' WHEN 'resolved' THEN 'DONE' WHEN 'closed' THEN 'DONE' ELSE UPPER(status) END");
        $this->db->query("UPDATE cs_conversations SET assigned_to = handled_by WHERE assigned_to IS NULL AND handled_by IS NOT NULL");
    }

    public function down() { /* Preserve CRM history on rollback. */ }
}
