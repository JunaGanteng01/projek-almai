<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Query;

class MigrateOldUsers extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'migrate:old-users';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Migrate users from old_users table to users table with mapping.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'migrate:old-users';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // 1. Check if old_users table exists
        if (!$db->tableExists('old_users')) {
            CLI::error("Table 'old_users' does not exist. Please import the fixed SQL file first.");
            return;
        }

        CLI::write("Starting migration from 'old_users' to 'users'...", 'yellow');

        $oldUsers = $db->table('old_users')->get()->getResultArray();
        $total = count($oldUsers);
        CLI::write("Found $total users to migrate.", 'white');

        $migrated = 0;
        $usersModel = new \App\Models\UserModel(); // Ensure you have a UserModel or use Builder
        // Using builder for direct insert to bypass potential model events if needed, but manual is safer here.
        $builder = $db->table('users');
        
        // Disable foreign key checks for safer Insert of IDs
        $db->query('SET FOREIGN_KEY_CHECKS=0');

        foreach ($oldUsers as $row) {
            
            // MAP Level ID to Role
            $role = 'user';
            switch ($row['level_id']) {
                case 1:
                    $role = 'user';
                    break;
                case 7:
                    $role = 'admin';
                    break;
                case 2:
                case 3:
                case 4:
                    $role = 'wpa'; // Assuming intermediate levels are WPA
                    break;
                default:
                    $role = 'user';
                    break;
            }

            // Clean Profile Path
            $pk = $row['id'];
            
            // Prepare Data
            $newData = [
                'id' => $row['id'], // Keep ID
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'password' => $row['password'], // Hash is likely compatible
                'role' => $role,
                'affiliate_code' => null, // We don't have a direct map for this new field yet?
                'avatar' => $row['profile'],
                'status' => ($row['status'] == 1) ? 'active' : 'inactive',
                'referral_code' => $row['code_referral'],
                'is_pro' => 0,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                // 'referred_by' will be updated in pass 2
            ];

            // Check if user exists
            if ($builder->where('id', $pk)->countAllResults() > 0) {
                 CLI::write("Skipping ID $pk (Already Exists)", 'red');
                 continue;
            }

            $builder->insert($newData);
            $migrated++;
            
            if ($migrated % 50 == 0) {
                CLI::write("Migrated $migrated / $total...", 'cyan');
            }
        }

        CLI::write("Phase 1 Complete. Migrated $migrated users.", 'green');
        CLI::write("Starting Phase 2: Linking Referrals...", 'yellow');

        // Phase 2: Link Referrals
        // Logic: users.referred_by should be the ID of the user whose referral_code matches old_users.affiliator_code
        
        $updates = 0;
        foreach ($oldUsers as $row) {
            if (!empty($row['affiliator_code'])) {
                // Find the referrer
                $referrer = $db->table('users')
                             ->where('referral_code', $row['affiliator_code'])
                             ->get()
                             ->getRowArray();
                
                if ($referrer) {
                    $builder->where('id', $row['id'])->update(['referred_by' => $referrer['id']]);
                    $updates++;
                }
            }
        }

        $db->query('SET FOREIGN_KEY_CHECKS=1');

        CLI::write("Phase 2 Complete. Linked $updates referrals.", 'green');
        CLI::write("Migration Finished!", 'green');
    }
}
