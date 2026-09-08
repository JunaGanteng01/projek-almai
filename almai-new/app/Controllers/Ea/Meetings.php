<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\EaMeetingModel;
use App\Models\NotificationModel;

class Meetings extends BaseController
{
    private EaMeetingModel $meetingModel;

    public function __construct()
    {
        $this->meetingModel = new EaMeetingModel();
    }

    public function index()
    {
        $meetings = $this->meetingModel->orderBy('start_time', 'DESC')->findAll();
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $todayMeetings = array_filter($meetings, static fn(array $m): bool => $m['start_time'] >= $todayStart && $m['start_time'] <= $todayEnd);
        $completedToday = array_filter($todayMeetings, static fn(array $m): bool => strtolower($m['status']) === 'completed');

        return view('ea/meetings/index', [
            'title' => 'Manajemen Meeting',
            'meetings' => $meetings,
            'today_total' => count($todayMeetings),
            'today_completed' => count($completedToday),
            'today_pending' => count($todayMeetings) - count($completedToday),
        ]);
    }

    public function create()
    {
        return view('ea/meetings/create', ['title' => 'Buat Meeting Baru']);
    }

    public function store()
    {
        $payload = $this->validatedPayload();
        if ($payload instanceof \CodeIgniter\HTTP\RedirectResponse) return $payload;
        if ($this->hasConflict($payload['start_time'], $payload['end_time'])) {
            return redirect()->back()->withInput()->with('error', 'Jadwal bentrok dengan meeting lain.');
        }
        $id = (int) $this->meetingModel->insert($payload);
        $this->afterMutation('Create meeting', $id, null, $payload);
        return redirect()->to('/ceo/calendar')->with('success', 'Meeting berhasil dijadwalkan dan muncul di kalender.');
    }

    public function edit(int $id)
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) return redirect()->to('/ceo/meetings')->with('error', 'Meeting tidak ditemukan.');
        return view('ea/meetings/edit', ['title' => 'Edit Meeting', 'meeting' => $meeting]);
    }

    public function update(int $id)
    {
        $before = $this->meetingModel->find($id);
        if (!$before) return redirect()->to('/ceo/meetings')->with('error', 'Meeting tidak ditemukan.');
        $payload = $this->validatedPayload();
        if ($payload instanceof \CodeIgniter\HTTP\RedirectResponse) return $payload;
        if ($this->hasConflict($payload['start_time'], $payload['end_time'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Jadwal bentrok dengan meeting lain.');
        }
        $this->meetingModel->update($id, $payload);
        $this->afterMutation('Update meeting', $id, $before, $payload);
        return redirect()->to('/ceo/meetings')->with('success', 'Meeting berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $before = $this->meetingModel->find($id);
        if (!$before) return redirect()->to('/ceo/meetings')->with('error', 'Meeting tidak ditemukan.');
        $this->meetingModel->delete($id);
        $this->afterMutation('Delete meeting', $id, $before, null);
        return redirect()->to('/ceo/meetings')->with('success', 'Meeting berhasil dihapus.');
    }

    private function validatedPayload(): array|\CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'title' => 'required|max_length[190]',
            'date' => 'required|valid_date[Y-m-d]',
            'time' => 'required|regex_match[/^([01]\\d|2[0-3]):[0-5]\\d$/]',
            'status' => 'required|in_list[Upcoming,Completed,Cancelled]',
            'link' => 'permit_empty|max_length[500]',
        ];
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        $start = $this->request->getPost('date') . ' ' . $this->request->getPost('time') . ':00';
        $link = trim((string) $this->request->getPost('link'));
        $isHttps = filter_var($link, FILTER_VALIDATE_URL) && strtolower((string) parse_url($link, PHP_URL_SCHEME)) === 'https';
        return [
            'title' => trim((string) $this->request->getPost('title')),
            'start_time' => $start,
            'end_time' => date('Y-m-d H:i:s', strtotime($start . ' +1 hour')),
            'location' => $isHttps ? 'Online' : ($link ?: 'TBD'),
            'meet_url' => $isHttps ? $link : null,
            'status' => strtolower((string) $this->request->getPost('status')),
            'category' => 'meeting',
            'created_by' => (int) session()->get('userId'),
        ];
    }

    private function hasConflict(string $start, string $end, ?int $ignoreId = null): bool
    {
        $builder = $this->meetingModel->whereNotIn('status', ['cancelled', 'Cancelled'])->where('start_time <', $end)->where('end_time >', $start);
        if ($ignoreId) $builder->where('id !=', $ignoreId);
        return $builder->countAllResults() > 0;
    }

    private function afterMutation(string $action, int $id, ?array $before, ?array $after): void
    {
        AuditLogModel::record($action, 'ceo_meeting', $id, ['before' => $before, 'after' => $after]);
        (new NotificationModel())->createNotification((int) session()->get('userId'), 'Kalender eksekutif diperbarui', ($after['title'] ?? $before['title'] ?? 'Meeting') . ' telah diperbarui.', 'info', '/ceo/calendar');
    }
}
