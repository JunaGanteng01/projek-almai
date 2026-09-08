<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;

class Pages extends BaseController
{
    // AI Command Center
    public function aiAssistant()
    {
        return view('ea/ai_assistant', ['title' => 'AI Assistant', 'activeMenu' => 'ai-assistant']);
    }

    public function aiRiskAnalysis()
    {
        return view('ea/ai_risk_analysis', ['title' => 'AI Risk Analysis', 'activeMenu' => 'ai-risk-analysis']);
    }

    public function aiRecommendation()
    {
        return view('ea/ai_recommendation', ['title' => 'AI Recommendations', 'activeMenu' => 'ai-recommendation']);
    }

    public function aiMarketIntelligence()
    {
        return view('ea/ai_market_intelligence', ['title' => 'AI Market Intelligence', 'activeMenu' => 'ai-market-intelligence']);
    }

    // Financial Intelligence
    public function revenueAnalytics()
    {
        return view('ea/revenue_analytics', ['title' => 'Revenue Analytics', 'activeMenu' => 'revenue-analytics']);
    }

    public function cashFlow()
    {
        return view('ea/cash_flow', ['title' => 'Cash Flow', 'activeMenu' => 'cash-flow']);
    }

    public function investmentPortfolio()
    {
        return view('ea/investment_portfolio', ['title' => 'Investment Portfolio', 'activeMenu' => 'investment-portfolio']);
    }

    public function profitLoss()
    {
        return view('ea/profit_loss', ['title' => 'Profit & Loss', 'activeMenu' => 'profit-loss']);
    }

    // Business Intelligence
    public function kpiDashboard()
    {
        return view('ea/kpi_dashboard', ['title' => 'KPI Dashboard', 'activeMenu' => 'kpi-dashboard']);
    }

    public function userAnalytics()
    {
        return view('ea/user_analytics', ['title' => 'User Analytics', 'activeMenu' => 'user-analytics']);
    }

    public function growthMetrics()
    {
        return view('ea/growth_metrics', ['title' => 'Growth Metrics', 'activeMenu' => 'growth-metrics']);
    }

    public function salesPerformance()
    {
        return view('ea/sales_performance', ['title' => 'Sales Performance', 'activeMenu' => 'sales-performance']);
    }

    public function marketingAnalytics()
    {
        return view('ea/marketing_analytics', ['title' => 'Marketing Analytics', 'activeMenu' => 'marketing-analytics']);
    }

    // Executive Management
    public function strategicProjects()
    {
        return view('ea/strategic_projects', ['title' => 'Strategic Projects', 'activeMenu' => 'strategic-projects']);
    }

    public function companyRoadmap()
    {
        return view('ea/company_roadmap', ['title' => 'Company Roadmap', 'activeMenu' => 'company-roadmap']);
    }

    // Monitoring Center
    public function systemStatus()
    {
        return view('ea/system_status', ['title' => 'System Status', 'activeMenu' => 'system-status']);
    }

    public function serverMonitoring()
    {
        return view('ea/server_monitoring', ['title' => 'Server Monitoring', 'activeMenu' => 'server-monitoring']);
    }

    public function securityCenter()
    {
        return view('ea/security_center', ['title' => 'Security Center', 'activeMenu' => 'security-center']);
    }

    // Human Capital
    public function employeeAnalytics()
    {
        return view('ea/employee_analytics', ['title' => 'Employee Analytics', 'activeMenu' => 'employee-analytics']);
    }

    public function wpaPerformance()
    {
        return view('ea/wpa_performance', ['title' => 'WPA Performance', 'activeMenu' => 'wpa-performance']);
    }

    public function cwpaPerformance()
    {
        return view('ea/cwpa_performance', ['title' => 'CWPA Performance', 'activeMenu' => 'cwpa-performance']);
    }

    public function recruitment()
    {
        return view('ea/recruitment', ['title' => 'Recruitment', 'activeMenu' => 'recruitment']);
    }

    public function organizationChart()
    {
        return view('ea/organization_chart', ['title' => 'Organization Chart', 'activeMenu' => 'organization-chart']);
    }

    // Investor Relations
    public function investorDashboard()
    {
        return view('ea/investor_dashboard', ['title' => 'Investor Dashboard', 'activeMenu' => 'investor-dashboard']);
    }

    public function shareholderReport()
    {
        return view('ea/shareholder_report', ['title' => 'Shareholder Report', 'activeMenu' => 'shareholder-report']);
    }

    public function partnership()
    {
        return view('ea/partnership', ['title' => 'Partnership', 'activeMenu' => 'partnership']);
    }

    public function mediaPr()
    {
        return view('ea/media_pr', ['title' => 'Media & PR', 'activeMenu' => 'media-pr']);
    }

    // Settings
    public function profile()
    {
        return view('ea/profile', ['title' => 'Profile', 'activeMenu' => 'profile']);
    }

    public function companySettings()
    {
        return view('ea/company_settings', ['title' => 'Company Settings', 'activeMenu' => 'company-settings']);
    }

    public function apiManagement()
    {
        return view('ea/api_management', ['title' => 'API Management', 'activeMenu' => 'api-management']);
    }

    public function todaySchedule()
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

        return view('ea/today_schedule', [
            'title' => "Calendar & Schedule", 
            'activeMenu' => 'today-schedule', 
            'events' => $events,
            'eventsJson' => json_encode($events)
        ]);
    }

    public function approvalWaiting()
    {
        $approvalModel = new \App\Models\EaApprovalModel();
        
        $approvals = $approvalModel->where('status', 'Waiting')
                                   ->orderBy('created_at', 'ASC')
                                   ->findAll();

        foreach ($approvals as &$app) {
            $app['time'] = date('d M Y, H:i', strtotime($app['created_at']));
            // Fallback description to module / amount if empty
            if (empty($app['description'])) {
                $app['description'] = $app['module'] . ' - Rp ' . number_format($app['amount'], 0, ',', '.');
            }
        }

        return view('ea/approval_waiting', ['title' => 'Approval Waiting', 'activeMenu' => 'approval-waiting', 'approvals' => $approvals]);
    }



    public function aiSummary()
    {
        $insights = [
            ['time' => 'Today', 'title' => 'Revenue Trend', 'content' => 'Q3 revenue is projected to exceed target by 12% based on current run rate.'],
            ['time' => 'Yesterday', 'title' => 'Market Risk', 'content' => 'Competitor announced new product line. Recommend reviewing pricing strategy.']
        ];
        return view('ea/ai/summary', ['title' => 'AI Summary', 'activeMenu' => 'ai-summary', 'insights' => $insights]);
    }
}
