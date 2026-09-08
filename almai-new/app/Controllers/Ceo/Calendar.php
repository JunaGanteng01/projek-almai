<?php

namespace App\Controllers\Ceo;

use App\Controllers\BaseController;
use App\Services\ExecutiveCalendarService;

class Calendar extends BaseController
{
    public function index()
    {
        $start = (string) ($this->request->getGet('start') ?? date('Y-m-01 00:00:00'));
        $end = (string) ($this->request->getGet('end') ?? date('Y-m-t 23:59:59'));
        $events = (new ExecutiveCalendarService())->getEvents($start, $end);

        return view('ea/calenders/index', [
            'title' => 'Kalender Eksekutif Terpadu',
            'activeMenu' => 'calenders',
            'events' => $events,
            'eventsJson' => json_encode($events, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function events()
    {
        $events = (new ExecutiveCalendarService())->getEvents($this->request->getGet('start'), $this->request->getGet('end'));
        return $this->response->setJSON(['ok' => true, 'data' => $events]);
    }
}
