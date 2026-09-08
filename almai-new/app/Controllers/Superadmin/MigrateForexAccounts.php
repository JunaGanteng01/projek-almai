<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;

class MigrateForexAccounts extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Check if ea_licenses table exists
        if (!$db->tableExists('ea_licenses')) {
            die("Table 'ea_licenses' does not exist.");
        }

        // Read SQL file
        $sqlPath = ROOTPATH . 'db_old/forex_accounts.sql';
        if (!file_exists($sqlPath)) {
            die("SQL file not found at: $sqlPath");
        }

        $sqlContent = file_get_contents($sqlPath);
        
        // Extract INSERT statements using regex
        preg_match_all("/INSERT INTO `forex_accounts` .*?;/s", $sqlContent, $matches);
        
        if (empty($matches[0])) {
            die("No INSERT statements found in SQL file.");
        }

        // Create temporary table for forex_accounts
        $db->query("DROP TABLE IF EXISTS temp_forex_accounts");
        $db->query("
            CREATE TABLE `temp_forex_accounts` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `user_company_name` varchar(255) NOT NULL,
              `account_trading_number` varchar(255) NOT NULL,
              `account_type` tinyint(4) NOT NULL,
              `is_approved` tinyint(1) NOT NULL DEFAULT 0,
              `license_key` varchar(255) DEFAULT NULL,
              `license_activated_at` timestamp NULL DEFAULT NULL,
              `license_email_sent` tinyint(1) NOT NULL DEFAULT 0,
              `license_email_sent_at` timestamp NULL DEFAULT NULL,
              `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
              `approved_at` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        echo "<h2>1. Loading Data to Temporary Table...</h2>";
        $count = 0;
        foreach ($matches[0] as $query) {
            // Replace table name in query
            $query = str_replace("INSERT INTO `forex_accounts`", "INSERT INTO `temp_forex_accounts`", $query);
            try {
                $db->query($query);
                $count++;
            } catch (\Exception $e) {
                echo "Error inserting chunk: " . $e->getMessage() . "<br>";
            }
        }
        echo "Loaded $count chunks of data into temporary table.<br>";

        echo "<h2>2. Migrating to ea_licenses...</h2>";
        
        // Get all data from temp table
        $oldData = $db->query("SELECT * FROM temp_forex_accounts")->getResultArray();
        
        $migrated = 0;
        $skipped = 0;
        $errors = 0;
        $activeModel = new \App\Models\EaLicenseModel();

        foreach ($oldData as $row) {
            // Skip if license_key is empty (unless you want to migrate pending requests too)
            // But ea_licenses suggests it holds Licenses. 
            // If license_key is null, maybe just created "inactive" license or pending?
            // Let's migrate only if we have valuable info. 
            // Requirements: migrate data.
            
            // Map fields
            $newData = [
                'user_id' => $row['user_id'],
                'broker_name' => $row['user_company_name'],
                'account_trading_number' => $row['account_trading_number'],
                'license_key' => $row['license_key'] ?: ('PENDING-' . uniqid()), // Handle null license keys
                'status' => $row['is_approved'] ? 'active' : 'pending',
                'license_activated_at' => $row['license_activated_at'],
                'license_email_sent' => $row['license_email_sent'],
                'license_email_sent_at' => $row['license_email_sent_at'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                // 'order_id' => null // No direct mapping available
            ];
            
            // Check for duplicates
            if ($activeModel->where('license_key', $newData['license_key'])->first()) {
                $skipped++;
                continue;
            }
            
            // Verify user exists
            $userExists = $db->table('users')->where('id', $row['user_id'])->countAllResults();
            if (!$userExists) {
                echo "Skipping Row ID {$row['id']}: User ID {$row['user_id']} does not exist in 'users' table.<br>";
                $errors++;
                continue;
            }

            try {
                $activeModel->insert($newData);
                $migrated++;
            } catch (\Exception $e) {
                echo "Error migrating Row ID {$row['id']}: " . $e->getMessage() . "<br>";
                $errors++;
            }
        }

        echo "<h3>Migration Summary:</h3>";
        echo "Total Records Found: " . count($oldData) . "<br>";
        echo "Migrated: $migrated<br>";
        echo "Skipped (Duplicate): $skipped<br>";
        echo "Errors (Missing User/Other): $errors<br>";

        // Cleanup
        $db->query("DROP TABLE temp_forex_accounts");
        echo "<br>Temporary table dropped. Done.";
    }
}
