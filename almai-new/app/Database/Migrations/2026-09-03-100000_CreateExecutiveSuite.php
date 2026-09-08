<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExecutiveSuite extends Migration
{
    public function up()
    {
        $this->createMeetings();
        $this->createTasks();
        $this->createTaskComments();
        $this->createReminders();
        $this->createApprovals();
        $this->createExecutiveNotifications();
        $this->createBills();
        $this->createGoals();
    }

    private function createMeetings(): void
    {
        if (!$this->db->tableExists('ea_meetings')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'title' => ['type' => 'VARCHAR', 'constraint' => 190],
                'location' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'meet_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'start_time' => ['type' => 'DATETIME'],
                'end_time' => ['type' => 'DATETIME', 'null' => true],
                'attendees' => ['type' => 'TEXT', 'null' => true],
                'category' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'meeting'],
                'priority' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'medium'],
                'description' => ['type' => 'TEXT', 'null' => true],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'upcoming'],
                'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['status', 'start_time']);
            $this->forge->createTable('ea_meetings');
        }

        $this->addMissingColumns('ea_meetings', [
            'meet_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'meeting'],
            'priority' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'medium'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
    }

    private function createTasks(): void
    {
        if (!$this->db->tableExists('ea_tasks')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'title' => ['type' => 'VARCHAR', 'constraint' => 190],
                'description' => ['type' => 'TEXT', 'null' => true],
                'assigned_to' => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
                'priority' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'medium'],
                'module' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => 'General'],
                'deadline' => ['type' => 'DATETIME', 'null' => true],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
                'attachment' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'activity_log' => ['type' => 'LONGTEXT', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['status', 'deadline']);
            $this->forge->createTable('ea_tasks');
        }
    }

    private function createTaskComments(): void
    {
        if ($this->db->tableExists('ea_task_comments')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'task_id' => ['type' => 'INT', 'unsigned' => true],
            'user_name' => ['type' => 'VARCHAR', 'constraint' => 190],
            'comment' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['task_id', 'created_at']);
        $this->forge->createTable('ea_task_comments');
    }

    private function createReminders(): void
    {
        if ($this->db->tableExists('ea_reminders')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 190],
            'type' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'normal'],
            'reminder_time' => ['type' => 'DATETIME'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['status', 'reminder_time']);
        $this->forge->createTable('ea_reminders');
    }

    private function createApprovals(): void
    {
        if (!$this->db->tableExists('ea_approvals')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'approval_code' => ['type' => 'VARCHAR', 'constraint' => 80],
                'title' => ['type' => 'VARCHAR', 'constraint' => 190],
                'module' => ['type' => 'VARCHAR', 'constraint' => 80],
                'requested_by' => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
                'amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
                'description' => ['type' => 'TEXT', 'null' => true],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('approval_code');
            $this->forge->createTable('ea_approvals');
        }

        $this->addMissingColumns('ea_approvals', [
            'source_type' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'source_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'requested_by_user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'priority' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'medium'],
            'due_date' => ['type' => 'DATETIME', 'null' => true],
            'attachment' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'approver_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'approved_at' => ['type' => 'DATETIME', 'null' => true],
            'rejected_at' => ['type' => 'DATETIME', 'null' => true],
            'decision_reason' => ['type' => 'TEXT', 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'version' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
        ]);
    }

    private function createExecutiveNotifications(): void
    {
        if (!$this->db->tableExists('ea_notifications')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
                'type' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'info'],
                'title' => ['type' => 'VARCHAR', 'constraint' => 190],
                'message' => ['type' => 'TEXT'],
                'link' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'is_read' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['user_id', 'is_read']);
            $this->forge->createTable('ea_notifications');
        }

        $this->addMissingColumns('ea_notifications', [
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'link' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
    }

    private function createBills(): void
    {
        if ($this->db->tableExists('ceo_bills')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'vendor' => ['type' => 'VARCHAR', 'constraint' => 190],
            'reference' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'currency' => ['type' => 'CHAR', 'constraint' => 3, 'default' => 'IDR'],
            'due_date' => ['type' => 'DATE'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'approval_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['status', 'due_date']);
        $this->forge->createTable('ceo_bills');
    }

    private function createGoals(): void
    {
        if ($this->db->tableExists('ceo_goals')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'objective' => ['type' => 'VARCHAR', 'constraint' => 255],
            'owner' => ['type' => 'VARCHAR', 'constraint' => 190],
            'period_start' => ['type' => 'DATE'],
            'period_end' => ['type' => 'DATE'],
            'target_value' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'actual_value' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'number'],
            'confidence' => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 50],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'on_track'],
            'update_note' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['status', 'period_end']);
        $this->forge->createTable('ceo_goals');
    }

    private function addMissingColumns(string $table, array $columns): void
    {
        foreach ($columns as $name => $definition) {
            if (!$this->db->fieldExists($name, $table)) {
                $this->forge->addColumn($table, [$name => $definition]);
            }
        }
    }

    public function down()
    {
        // Only remove tables introduced exclusively by this migration. Existing
        // EA operational tables are deliberately preserved on rollback.
        $this->forge->dropTable('ceo_goals', true);
        $this->forge->dropTable('ceo_bills', true);
    }
}
