<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\EaTaskModel;

class Tasks extends BaseController
{
    protected $taskModel;

    public function __construct()
    {
        $this->taskModel = new EaTaskModel();
    }

    public function index()
    {
        $tasks = $this->taskModel->orderBy('created_at', 'DESC')->findAll();
        
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        
        $todayTasks = array_filter($tasks, function($t) use ($todayStart, $todayEnd) {
            return $t['created_at'] >= $todayStart && $t['created_at'] <= $todayEnd;
        });
        
        $completedToday = array_filter($todayTasks, function($t) {
            return $t['status'] === 'Completed';
        });

        $data = [
            'title' => 'Manajemen Task',
            'tasks' => $tasks,
            'today_total' => count($todayTasks),
            'today_completed' => count($completedToday),
            'today_pending' => count($todayTasks) - count($completedToday),
        ];

        return view('ea/tasks/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Task Baru'
        ];
        return view('ea/tasks/create', $data);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|max_length[190]',
            'due_date' => 'required|valid_date[Y-m-d]',
            'priority' => 'required|in_list[low,medium,high,Low,Medium,High]',
            'attachment' => 'permit_empty|max_size[attachment,5120]|ext_in[attachment,pdf,doc,docx,xls,xlsx,zip,png,jpg,jpeg]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $insertData = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description') ?? $task['description'],
            'deadline'    => $this->request->getPost('due_date'),
            'priority'    => ucfirst($this->request->getPost('priority')),
            'status'      => 'Pending',
            'module'      => 'General',
            'assigned_to' => $this->request->getPost('assigned_to') ?? $task['assigned_to']
        ];
        
        // Handle file upload
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/tasks', $newName);
            $insertData['attachment'] = 'uploads/tasks/' . $newName;
        }

        $activityLog = [];
        $activityLog[] = [
            'time' => date('Y-m-d H:i:s'),
            'action' => 'Task created'
        ];
        $insertData['activity_log'] = json_encode($activityLog);

        $id = (int) $this->taskModel->insert($insertData);
        \App\Models\AuditLogModel::record('Create executive task', 'ceo_task', $id, ['title' => $insertData['title'], 'deadline' => $insertData['deadline']]);
        (new \App\Models\NotificationModel())->createNotification((int) session()->get('userId'), 'Task eksekutif dibuat', $insertData['title'] . ' telah masuk kalender.', 'success', '/ceo/tasks/show/' . $id);

        return redirect()->to(base_url('ceo/tasks'))->with('success', 'Task baru berhasil ditambahkan dan masuk kalender.');
    }

    public function show($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to(base_url('ea/tasks'))->with('error', 'Task tidak ditemukan.');
        }

        $commentModel = new \App\Models\EaTaskCommentModel();
        $comments = $commentModel->where('task_id', $id)->orderBy('created_at', 'ASC')->findAll();
        
        $activityLog = [];
        if (!empty($task['activity_log'])) {
            $activityLog = json_decode($task['activity_log'], true) ?? [];
        }

        $data = [
            'title' => 'Task Details',
            'task' => $task,
            'comments' => $comments,
            'activityLog' => $activityLog
        ];

        return view('ea/tasks/show', $data);
    }

    public function edit($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to(base_url('ea/tasks'))->with('error', 'Task tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Task',
            'task' => $task
        ];

        return view('ea/tasks/edit', $data);
    }

    public function update($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to(base_url('ea/tasks'))->with('error', 'Task tidak ditemukan.');
        }

        if (!$this->validate(['title' => 'required|max_length[190]', 'due_date' => 'required|valid_date[Y-m-d]', 'priority' => 'required|in_list[low,medium,high,Low,Medium,High]', 'status' => 'required|in_list[Pending,In Progress,Completed,pending,in_progress,completed]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $updateData = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'deadline'    => $this->request->getPost('due_date'),
            'priority'    => ucfirst($this->request->getPost('priority')),
            'status'      => $this->request->getPost('status'),
            'module'      => $task['module'] ?: 'General',
            'assigned_to' => $this->request->getPost('assigned_to')
        ];
        
        // Handle file upload
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/tasks', $newName);
            $updateData['attachment'] = 'uploads/tasks/' . $newName;
        }

        // Activity log
        $activityLog = !empty($task['activity_log']) ? json_decode($task['activity_log'], true) : [];
        if ($task['status'] !== $updateData['status']) {
            $activityLog[] = [
                'time' => date('Y-m-d H:i:s'),
                'action' => 'Status changed from ' . $task['status'] . ' to ' . $updateData['status']
            ];
        }
        if ($task['assigned_to'] !== $updateData['assigned_to'] && !empty($updateData['assigned_to'])) {
            $activityLog[] = [
                'time' => date('Y-m-d H:i:s'),
                'action' => 'Assigned to ' . $updateData['assigned_to']
            ];
        }
        
        if (!empty($activityLog)) {
            $updateData['activity_log'] = json_encode($activityLog);
        }

        $this->taskModel->update($id, $updateData);
        \App\Models\AuditLogModel::record('Update executive task', 'ceo_task', $id, ['before' => $task, 'after' => $updateData]);

        return redirect()->to(base_url('ea/tasks/show/' . $id))->with('success', 'Task berhasil diperbarui.');
    }
    
    public function quickUpdate($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to(base_url('ea/tasks'))->with('error', 'Task tidak ditemukan.');
        }

        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');
        
        if (in_array($field, ['status', 'priority'])) {
            $activityLog = !empty($task['activity_log']) ? json_decode($task['activity_log'], true) : [];
            if ($task[$field] !== $value) {
                $activityLog[] = [
                    'time' => date('Y-m-d H:i:s'),
                    'action' => ucfirst($field) . ' changed from ' . $task[$field] . ' to ' . $value
                ];
            }
            
            $this->taskModel->update($id, [
                $field => $value,
                'activity_log' => json_encode($activityLog)
            ]);
        }

        return redirect()->to(base_url('ea/tasks'))->with('success', 'Berhasil memperbarui task.');
    }
    
    public function addComment($id)
    {
        $commentModel = new \App\Models\EaTaskCommentModel();
        $commentText = $this->request->getPost('comment');
        
        if (!empty($commentText)) {
            $commentModel->insert([
                'task_id' => $id,
                'user_name' => (string) (session()->get('userName') ?: 'CEO'),
                'comment' => $commentText
            ]);
        }
        
        return redirect()->to(base_url('ea/tasks/show/' . $id));
    }

    public function delete($id)
    {
        $task = $this->taskModel->find($id);
        if ($task) {
            $this->taskModel->delete($id);
            \App\Models\AuditLogModel::record('Delete executive task', 'ceo_task', $id, ['title' => $task['title']]);
            return redirect()->to(base_url('ceo/tasks'))->with('success', 'Task berhasil dihapus.');
        }
        return redirect()->to(base_url('ea/tasks'))->with('error', 'Task tidak ditemukan.');
    }
}
