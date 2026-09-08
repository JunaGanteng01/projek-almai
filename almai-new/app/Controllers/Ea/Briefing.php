<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Services\CeoDashboardService;
use App\Services\ExecutiveCalendarService;

class Briefing extends BaseController
{
    public function index()
    {
        $dashboard = (new CeoDashboardService())->getDashboard('mtd');
        $events = (new ExecutiveCalendarService())->getEvents(
            date('Y-m-d 00:00:00'),
            date('Y-m-d 23:59:59', strtotime('+7 days'))
        );
        $hour = (int) date('H');
        $greeting = $hour < 12 ? 'Pagi' : ($hour < 15 ? 'Siang' : ($hour < 18 ? 'Sore' : 'Malam'));

        return view('ea/briefings/index', [
            'title' => 'CEO Daily Briefing',
            'activeMenu' => 'briefing',
            'greeting' => 'Selamat ' . $greeting . ', ' . (session()->get('userName') ?: 'Chief'),
            'briefing_date' => date('d F Y'),
            'dashboard' => $dashboard,
            'events' => array_slice($events, 0, 8),
            'briefing' => $this->deterministicBriefing($dashboard),
        ]);
    }

    private function deterministicBriefing(array $dashboard): string
    {
        $summary = $dashboard['summary'];
        $parts = [
            'Pendapatan bulan berjalan tercatat Rp ' . number_format($summary['revenue'], 0, ',', '.') . ' dari ' . number_format($summary['transactions_confirmed']) . ' transaksi confirmed non-poin.',
            $summary['approvals']['pending'] > 0
                ? $summary['approvals']['pending'] . ' approval senilai Rp ' . number_format($summary['approvals']['amount'], 0, ',', '.') . ' menunggu keputusan.'
                : 'Tidak ada approval yang menunggu keputusan.',
            count($dashboard['alerts']) > 0
                ? count($dashboard['alerts']) . ' alert berbasis aturan membutuhkan perhatian, termasuk ' . ($dashboard['alerts'][0]['title'] ?? 'risiko operasional') . '.'
                : 'Tidak ada alert operasional aktif berdasarkan aturan saat ini.',
        ];
        return implode(' ', $parts);
    }
}
