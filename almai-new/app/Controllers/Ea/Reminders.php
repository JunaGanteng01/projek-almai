<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\EaReminderModel;

class Reminders extends BaseController
{
    protected $reminderModel;

    public function __construct()
    {
        $this->reminderModel = new EaReminderModel();
    }

    public function index()
    {
        $reminders = $this->reminderModel->orderBy('reminder_time', 'DESC')->findAll();
        
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        
        $todayReminders = array_filter($reminders, function($r) use ($todayStart, $todayEnd) {
            return $r['reminder_time'] >= $todayStart && $r['reminder_time'] <= $todayEnd;
        });
        
        $activeToday = array_filter($todayReminders, function($r) {
            return $r['status'] === 'Active';
        });

        $data = [
            'title' => 'Manajemen Reminder',
            'reminders' => $reminders,
            'today_total' => count($todayReminders),
            'today_active' => count($activeToday),
            'today_inactive' => count($todayReminders) - count($activeToday),
        ];

        return view('ea/reminders/index', $data);
    }

    public function edit($id)
    {
        $reminder = $this->reminderModel->find($id);
        if (!$reminder) {
            return redirect()->to(base_url('ea/reminders'))->with('error', 'Reminder tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Reminder',
            'reminder' => $reminder
        ];

        return view('ea/reminders/edit', $data);
    }

    public function update($id)
    {
        $reminder = $this->reminderModel->find($id);
        if (!$reminder) {
            return redirect()->to(base_url('ea/reminders'))->with('error', 'Reminder tidak ditemukan.');
        }

        if (!$this->validate(['title' => 'required|max_length[190]', 'date' => 'required|valid_date[Y-m-d]', 'time' => 'required|regex_match[/^([01]\\d|2[0-3]):[0-5]\\d$/]', 'priority' => 'required|in_list[normal,penting,kritis]', 'status' => 'required|in_list[Active,Completed,Archived]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $payload = [
            'title'         => $this->request->getPost('title'),
            'reminder_time' => $this->request->getPost('date') . ' ' . $this->request->getPost('time'),
            'type'          => $this->request->getPost('priority'),
            'status'        => $this->request->getPost('status')
        ];
        $this->reminderModel->update($id, $payload);
        \App\Models\AuditLogModel::record('Update executive reminder', 'ceo_reminder', $id, ['before' => $reminder, 'after' => $payload]);

        return redirect()->to(base_url('ea/reminders'))->with('success', 'Reminder berhasil diperbarui.');
    }

    public function delete($id)
    {
        $reminder = $this->reminderModel->find($id);
        if ($reminder) {
            $this->reminderModel->delete($id);
            \App\Models\AuditLogModel::record('Delete executive reminder', 'ceo_reminder', $id, ['title' => $reminder['title']]);
            return redirect()->to(base_url('ceo/reminders'))->with('success', 'Reminder berhasil dihapus.');
        }
        return redirect()->to(base_url('ea/reminders'))->with('error', 'Reminder tidak ditemukan.');
    }
}
