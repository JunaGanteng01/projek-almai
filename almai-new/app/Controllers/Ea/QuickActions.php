<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;

class QuickActions extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tindakan Strategis Cepat',
        ];
        
        return view('ea/quick_actions', $data);
    }

    public function createTask()
    {
        return view('ea/forms/create_task', ['title' => 'Buat Task Baru']);
    }

    public function storeTask()
    {
        if (!$this->validate(['title' => 'required|max_length[190]', 'due_date' => 'required|valid_date[Y-m-d]', 'priority' => 'required|in_list[low,medium,high]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $taskModel = new \App\Models\EaTaskModel();
        
        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'deadline'    => $this->request->getPost('due_date') . ' 17:00:00',
            'status'      => 'pending',
            'priority'    => $this->request->getPost('priority') ?? 'medium',
            'module'      => 'Executive',
            'activity_log'=> json_encode([['time' => date('Y-m-d H:i:s'), 'action' => 'Task dibuat oleh CEO']], JSON_UNESCAPED_UNICODE),
        ];

        $id = $taskModel->insert($data);
        $this->recordCreation('task', (int) $id, (string) $data['title'], '/ceo/tasks/show/' . $id);
        
        return redirect()->to('/ceo/calendar')->with('success', 'Task berhasil dibuat dan ditambahkan ke kalender.');
    }

    public function createMeeting()
    {
        return view('ea/forms/create_meeting', ['title' => 'Jadwalkan Meeting']);
    }

    public function storeMeeting()
    {
        if (!$this->validate(['title' => 'required|max_length[190]', 'date' => 'required|valid_date[Y-m-d]', 'time' => 'required|regex_match[/^([01]\\d|2[0-3]):[0-5]\\d$/]', 'link' => 'permit_empty|valid_url_strict[https]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $meetingModel = new \App\Models\EaMeetingModel();
        $start = $this->request->getPost('date') . ' ' . $this->request->getPost('time') . ':00';
        $data = [
            'title'       => $this->request->getPost('title'),
            'start_time'  => $start,
            'end_time'    => date('Y-m-d H:i:s', strtotime($start . ' +1 hour')),
            'attendees'   => $this->request->getPost('participants'),
            'meet_url'    => $this->request->getPost('link') ?: null,
            'location'    => $this->request->getPost('link') ? 'Online' : 'TBD',
            'category'    => 'meeting',
            'priority'    => 'medium',
            'status'      => 'upcoming',
            'created_by'  => (int) session()->get('userId'),
        ];

        $id = $meetingModel->insert($data);
        $this->recordCreation('meeting', (int) $id, (string) $data['title'], '/ceo/calendar');
        
        return redirect()->to('/ceo/calendar')->with('success', 'Meeting berhasil dijadwalkan dan ditambahkan ke kalender.');
    }

    public function createReminder()
    {
        return view('ea/forms/create_reminder', ['title' => 'Buat Reminder']);
    }

    public function storeReminder()
    {
        if (!$this->validate(['title' => 'required|max_length[190]', 'date' => 'required|valid_date[Y-m-d]', 'time' => 'required|regex_match[/^([01]\\d|2[0-3]):[0-5]\\d$/]', 'priority' => 'required|in_list[normal,important,urgent]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $reminderModel = new \App\Models\EaReminderModel();
        
        $data = [
            'title'       => $this->request->getPost('title'),
            'reminder_time' => $this->request->getPost('date') . ' ' . $this->request->getPost('time') . ':00',
            'type'        => $this->request->getPost('priority') ?? 'normal',
            'status'      => 'pending',
        ];

        $id = $reminderModel->insert($data);
        $this->recordCreation('reminder', (int) $id, (string) $data['title'], '/ceo/calendar');
        
        return redirect()->to('/ceo/calendar')->with('success', 'Reminder berhasil dibuat dan ditambahkan ke kalender.');
    }

    private function recordCreation(string $type, int $id, string $title, string $link): void
    {
        \App\Models\AuditLogModel::record('Create executive ' . $type, 'ceo_' . $type, $id, ['title' => $title]);
        (new \App\Models\NotificationModel())->createNotification(
            (int) session()->get('userId'),
            ucfirst($type) . ' berhasil dibuat',
            $title . ' telah tersimpan dan terhubung ke kalender eksekutif.',
            'success',
            $link
        );
    }
}
