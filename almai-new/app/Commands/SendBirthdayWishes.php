<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\NotificationModel;
use App\Helpers\BirthdayHelper;

class SendBirthdayWishes extends BaseCommand
{
    protected $group       = 'Notification';
    protected $name        = 'notif:send-birthday-wishes';
    protected $description = 'Sends birthday wishes to users dashboard automatically';
    protected $usage       = 'notif:send-birthday-wishes [options]';
    protected $arguments   = [];
    protected $options     = [
        '--dry-run' => 'Don\'t actually save notifications, just show list',
    ];

    public function run(array $params)
    {
        helper('BirthdayHelper'); // Load helper
        
        $db = \Config\Database::connect();
        $notifModel = new NotificationModel();
        
        $dryRun = array_key_exists('dry-run', $params);
        $currentYear = date('Y');
        $today = date('d-m');
        
        CLI::write("Checking for birthdays today ($today)...", "yellow");
        
        // Query users with birthday data
        $users = $db->table('user_data')
                    ->select('user_data.user_id, users.name, user_data.birth_place_and_date')
                    ->join('users', 'users.id = user_data.user_id')
                    ->where('birth_place_and_date IS NOT NULL')
                    ->get()
                    ->getResultArray();
        
        $birthdayCount = 0;
        $notifiedCount = 0;
        
        foreach ($users as $user) {
            if (BirthdayHelper::isBirthdayToday($user['birth_place_and_date'])) {
                $birthdayCount++;
                $title = "Selamat Ulang Tahun! 🎂";
                $message = "Halo " . $user['name'] . ", ALMAI.ID mengucapkan selamat ulang tahun! Semoga sehat selalu, panjang umur, dan sukses terus dalam trading dan karirnya. Happy Birthday! 🥳";
                $trackingTitle = "Selamat Ulang Tahun! $currentYear";
                
                if ($dryRun) {
                    CLI::write("[DRY RUN] Would notify: " . $user['name'] . " (ID: " . $user['user_id'] . ")");
                    continue;
                }
                
                // Check if already notified this year
                $prevNotif = $notifModel->where('user_id', $user['user_id'])
                                        ->where('title', $trackingTitle)
                                        ->first();
                
                if (!$prevNotif) {
                    $notifModel->createNotification(
                        $user['user_id'],
                        $trackingTitle,
                        $message,
                        'info',
                        '/user/dashboard'
                    );
                    CLI::write("Notified: " . $user['name'], "green");
                    $notifiedCount++;
                } else {
                    CLI::write("Already notified: " . $user['name'] . " (skipped)", "gray");
                }
            }
        }
        
        if ($dryRun) {
            CLI::write("\nSUMMARY: $birthdayCount users found with birthday today.", "cyan");
        } else {
            CLI::write("\nSUMMARY: $birthdayCount users found, $notifiedCount new notifications sent.", "cyan");
        }
    }
}
