<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserDataTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'user_pro_statement' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'professional_trading_experience' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'identity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'identity_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'npwp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'profession' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'company' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'business_line' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'tenure' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'company_address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'company_postcode' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'annually_income' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'investment_experience' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'investment_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'trading_experience' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'trading_company' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'length_of_trading' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'registration_purpose' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'trader_family' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'bankrupt' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'involved_in_crime' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'deposit' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'other_assets' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'asset_value' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'photo_identity_card' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'photo_selfie' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'account_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'photo_savings_account' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'type_of_risk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'investment_goals' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'additional_document' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'statement_of_truth' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'agreed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'agreed_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'risk_reward_expectation' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'loss_tolerance' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'prepared_modal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'other_investment_experience' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'birth_place_and_date' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'address_as_per_ID' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'community_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'founding_details' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'community_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'number_of_members' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'trading_strategy' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'strategy_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'has_cwpa_certificate' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'tlup_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'bank_branch' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_data');
    }

    public function down()
    {
        $this->forge->dropTable('user_data');
    }
}
