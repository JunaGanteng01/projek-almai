<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;

class Calenders extends BaseController
{
    public function index()
    {
        $meetingModel = new \App\Models\EaMeetingModel();
        $taskModel = new \App\Models\EaTaskModel();
        $reminderModel = new \App\Models\EaReminderModel();
        
        $events = [];
        
        // 1. Meetings
        $meetings = $meetingModel->where('status !=', 'Cancelled')->findAll();
        foreach ($meetings as $m) {
            $events[] = [
                'id' => 'm_' . $m['id'],
                'type' => 'Meeting',
                'title' => $m['title'],
                'start' => $m['start_time'],
                'end' => isset($m['end_time']) && !empty($m['end_time']) ? $m['end_time'] : date('Y-m-d H:i:s', strtotime($m['start_time'] . ' +1 hour')),
                'location' => $m['location'] ?? 'Google Meet',
                'color' => 'blue-500',
                'colorCode' => '#3b82f6'
            ];
        }

        // 2. Tasks (Deadlines)
        $tasks = $taskModel->where('status !=', 'Completed')->findAll();
        foreach ($tasks as $t) {
            if (!empty($t['deadline'])) {
                $events[] = [
                    'id' => 't_' . $t['id'],
                    'type' => 'Task',
                    'title' => 'Deadline: ' . $t['title'],
                    'start' => $t['deadline'],
                    'end' => $t['deadline'],
                    'location' => 'System',
                    'color' => 'emerald-500',
                    'colorCode' => '#10b981'
                ];
            }
        }

        // 3. Reminders
        $reminders = $reminderModel->where('status', 'Pending')->findAll();
        foreach ($reminders as $r) {
            if (!empty($r['reminder_time'])) {
                $events[] = [
                    'id' => 'r_' . $r['id'],
                    'type' => 'Reminder',
                    'title' => 'Reminder: ' . $r['title'],
                    'start' => $r['reminder_time'],
                    'end' => $r['reminder_time'],
                    'location' => 'System',
                    'color' => 'yellow-500',
                    'colorCode' => '#eab308'
                ];
            }
        }
        
        // 4. Mock Events: Webinar & Holiday
        // Holiday
        $events[] = [
            'id' => 'h_1',
            'type' => 'Holiday',
            'title' => 'Hari Libur Nasional',
            'start' => date('Y-m-d') . ' 00:00:00', // Today
            'end' => date('Y-m-d') . ' 23:59:59',
            'location' => 'Indonesia',
            'color' => 'red-500',
            'colorCode' => '#ef4444'
        ];
        
        // Webinar
        $events[] = [
            'id' => 'w_1',
            'type' => 'Webinar',
            'title' => 'Webinar: Q3 Strategy',
            'start' => date('Y-m-d', strtotime('+2 days')) . ' 13:00:00',
            'end' => date('Y-m-d', strtotime('+2 days')) . ' 15:00:00',
            'location' => 'Zoom',
            'color' => 'purple-500',
            'colorCode' => '#a855f7'
        ];

        // Sort by start_time
        usort($events, function($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        return view('ea/calenders/index', [
            'title' => "Calendar & Schedule", 
            'activeMenu' => 'calenders', 
            'events' => $events,
            'eventsJson' => json_encode($events)
        ]);
    }
}
