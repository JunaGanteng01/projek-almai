<?php

namespace App\Controllers;

use App\Models\ProfirmEaAccountModel;
use App\Models\ProfirmEaTradeModel;

class Portofolio extends BaseController
{
    public function index()
    {
        $model = new ProfirmEaAccountModel();
        
        $search = $this->request->getGet('search');
        
        if (!empty($search)) {
            $model->groupStart()
                  ->like('account_name', $search)
                  ->orLike('account_login', $search)
                  ->orLike('profirm', $search)
                  ->groupEnd();
        }

        // Ambil data dengan pagination
        $accounts = $model->orderBy('updated_at', 'DESC')->paginate(12, 'portofolios');
        $pager = $model->pager;

        $portofolios = [];
        foreach ($accounts as $acc) {
            // Kalkulasi Pertumbuhan Riil (Real Growth)
            // Profit / Deposit * 100
            $real_profit = $acc['total_profit'];
            $base_cap    = $acc['total_deposits'] > 0 ? $acc['total_deposits'] : $acc['balance'];
            $growth      = ($base_cap > 0) ? ($real_profit / $base_cap) * 100 : 0;
            
            $portofolios[] = [
                'id'           => $acc['account_login'],
                'name'         => $acc['account_name'] ?: 'Trader ' . $acc['account_login'],
                'author'       => $acc['community_name'] ?: 'ALMAI Community',
                'logo'         => $acc['logo'] ?? null,
                'growth'       => $growth,
                'growth_since' => date('Y', strtotime($acc['created_at'])),
                'reliability'  => 5,
                'algo_trading' => 100,
                'subscribers'  => 0,
                'price'        => 0,
                'broker'       => $acc['broker'],
                'server'       => $acc['server'],
                'balance'      => $acc['balance'],
                'equity'       => $acc['equity'],
                'initial_deposit' => $acc['total_deposits'] ?: $acc['balance']
            ];
        }

        return view('pages/portofolio/index', [
            'title'       => 'Portofolio Almai ID',
            'portofolios' => $portofolios,
            'pager'       => $pager,
            'searchQuery' => $search
        ]);
    }

    public function detail($id)
    {
        $model = new ProfirmEaAccountModel();
        // Cari akun berdasarkan account_login (karena $id di loop di atas memakai account_login)
        $acc = $model->where('account_login', $id)->first();

        if (!$acc) {
            return redirect()->to('/portofolio')->with('error', 'Portofolio tidak ditemukan.');
        }

        $profit = $acc['equity'] - $acc['balance']; // Asumsi profit berjalan atau total profit
        $growth = $acc['balance'] > 0 ? ($profit / $acc['balance']) * 100 : 0;

        $tradeModel = new ProfirmEaTradeModel();
        
        // Kalkulasi Pertumbuhan (Growth) yang Hebat
        // Rumus: (Total Profit / Total Deposit) * 100
        $total_profit_growth = $acc['total_profit'];
        $total_deposit_val   = $acc['total_deposits'] > 0 ? $acc['total_deposits'] : $acc['balance']; // fallback jika belum ada data depo
        $growth = ($total_deposit_val > 0) ? ($total_profit_growth / $total_deposit_val) * 100 : 0;

        // Get history with pagination - TERBARU DI ATAS (DESC)
        $perPage = 50;
        $page = $this->request->getGet('page') ?? 1;
        
        $history = $tradeModel->where('account_login', $id)
                             ->orderBy('close_time', 'DESC') // Terbaru di atas
                             ->paginate($perPage, 'history');
        
        $pager = $tradeModel->pager;

        // --- Statistik Detail ---
        $allTrades = $tradeModel->where('account_login', $id)->orderBy('close_time', 'ASC')->findAll();
        $total_trades = count($allTrades);
        
        // --- Build Chart Data from Real Trading History ---
        $chartData = $this->buildChartData($allTrades, $acc);
        
        $max_consecutive_wins = 0;
        $max_consecutive_losses = 0;
        $current_wins = 0;
        $current_losses = 0;
        
        foreach ($allTrades as $t) {
            if ($t['profit'] > 0) {
                $current_wins++;
                $current_losses = 0;
                $max_consecutive_wins = max($max_consecutive_wins, $current_wins);
            } else {
                $current_losses++;
                $current_wins = 0;
                $max_consecutive_losses = max($max_consecutive_losses, $current_losses);
            }
        }

        $profit_count = $tradeModel->where('account_login', $id)->where('profit >', 0)->countAllResults();
        $loss_count   = $total_trades - $profit_count;
        $win_rate     = $total_trades > 0 ? ($profit_count / $total_trades) * 100 : 0;
        
        $gross_profit = $tradeModel->where('account_login', $id)->where('profit >', 0)->selectSum('profit')->first()['profit'] ?? 0;
        $gross_loss   = $tradeModel->where('account_login', $id)->where('profit <', 0)->selectSum('profit')->first()['profit'] ?? 0;
        
        $best_trade   = $tradeModel->where('account_login', $id)->selectMax('profit')->first()['profit'] ?? 0;
        $worst_trade  = $tradeModel->where('account_login', $id)->selectMin('profit')->first()['profit'] ?? 0;
        
        $long_trades  = $tradeModel->where(['account_login' => $id, 'type' => 'BUY'])->countAllResults();
        $short_trades = $tradeModel->where(['account_login' => $id, 'type' => 'SELL'])->countAllResults();
        
        $profit_factor = abs($gross_loss) > 0 ? $gross_profit / abs($gross_loss) : $gross_profit;
        
        // Distribusi Simbol (Detailed: Buy/Sell/Count)
        $symbols = $tradeModel->select('symbol')
                             ->where('account_login', $id)
                             ->groupBy('symbol')
                             ->findAll();
        
        $distribution = [];
        foreach ($symbols as $s) {
            $sym = $s['symbol'];
            $distribution[] = [
                'symbol' => $sym,
                'count'  => $tradeModel->where(['account_login' => $id, 'symbol' => $sym])->countAllResults(),
                'buy'    => $tradeModel->where(['account_login' => $id, 'symbol' => $sym, 'type' => 'BUY'])->countAllResults(),
                'sell'   => $tradeModel->where(['account_login' => $id, 'symbol' => $sym, 'type' => 'SELL'])->countAllResults(),
                'profit' => $tradeModel->where(['account_login' => $id, 'symbol' => $sym])->selectSum('profit')->first()['profit'] ?? 0,
            ];
        }
        
        // Sort by count
        usort($distribution, function($a, $b) { return $b['count'] <=> $a['count']; });
        $distribution = array_slice($distribution, 0, 5);

        $porto = [
            'id'                => $acc['account_login'],
            'name'              => $acc['account_name'] ?: 'Trader ' . $acc['account_login'],
            'author'            => $acc['profirm'] ?: 'Almai Trader',
            'logo'              => $acc['logo'] ?? null,
            'growth'            => $growth,
            'growth_since'      => date('Y', strtotime($acc['created_at'])),
            'reliability'       => 5,
            'weeks'             => max(1, round((time() - strtotime($acc['created_at'])) / (60 * 60 * 24 * 7))),
            'subscribers'       => 0,
            'subscribers_funds' => '0 USD',
            'price'             => 0,
            
            'algo_trading'      => 100,
            'max_drawdown'      => 15,
            'profit_trades'     => $win_rate,
            'loss_trades'       => 100 - $win_rate,
            'trading_activity'  => 5.5,
            'max_deposit_load'  => ($acc['balance'] > 0) ? ($acc['margin'] / $acc['balance'] * 100) : 0,
            
            'balance'           => $acc['balance'],
            'equity'            => $acc['equity'],
            'profit'            => $acc['total_profit'],
            'initial_deposit'   => $acc['total_deposits'], 
            'withdrawals'       => $acc['total_withdrawals'],
            'deposits'          => $acc['total_deposits'],
            
            'leverage'          => $acc['leverage'] ?? 100,
            'broker'            => $acc['broker'] ?: $acc['server'],
            'currency'          => $acc['currency'] ?: 'USD',
            'open_trades'       => $acc['open_trades'],
            'margin_level'      => $acc['margin_level'],
            'history'           => $history,
            'pager'             => $pager,
            'updated_at'        => $acc['updated_at'],
            'community_name'    => $acc['community_name'],

            // Stats Terperinci untuk View
            'stats' => [
                'total_trades'   => $total_trades,
                'profit_trades'  => $profit_count,
                'loss_trades'    => $loss_count,
                'win_rate'       => $win_rate,
                'gross_profit'   => $gross_profit,
                'gross_loss'     => $gross_loss,
                'best_trade'     => $best_trade,
                'worst_trade'    => $worst_trade,
                'long_trades'    => $long_trades,
                'short_trades'   => $short_trades,
                'profit_factor'  => $profit_factor,
                'distribution'   => $distribution,
                'max_cons_wins'  => $max_consecutive_wins,
                'max_cons_loss'  => $max_consecutive_losses,
                'avg_slippage'   => $tradeModel->where('account_login', $id)->selectAvg('slippage')->first()['slippage'] ?? 0,
                'avg_speed'      => $tradeModel->where('account_login', $id)->selectAvg('execution_speed')->first()['execution_speed'] ?? 0,
            ],
            
            // Chart Data from Real Trading History
            'chartData' => $chartData
        ];

        return view('pages/portofolio/detail', [
            'title' => 'Detail Portofolio - ' . $porto['name'],
            'porto' => $porto
        ]);
    }
    
    /**
     * Build chart data from real trading history
     */
    private function buildChartData($allTrades, $account)
    {
        $initial_deposit = $account['total_deposits'] > 0 ? $account['total_deposits'] : $account['balance'];
        
        // If no trades, return flat line data
        if (empty($allTrades)) {
            return [
                'labels' => ['Start'],
                'balance' => [$initial_deposit],
                'equity' => [$initial_deposit],
                'growth' => [0],
                'drawdown' => [0],
                'daily' => []
            ];
        }
        
        $labels = [];
        $balanceData = [];
        $equityData = [];
        $growthData = [];
        $drawdownData = [];
        $dailyData = [];
        
        $runningBalance = $initial_deposit;
        $maxBalance = $initial_deposit;
        $currentDate = null;
        $dailyProfit = 0;
        
        // Add starting point
        $startDate = date('d M Y', strtotime($account['created_at']));
        $labels[] = $startDate;
        $balanceData[] = $initial_deposit;
        $equityData[] = $initial_deposit;
        $growthData[] = 0;
        $drawdownData[] = 0;
        
        // Process each trade - group by DATE (not month)
        foreach ($allTrades as $trade) {
            // Use close_time, fallback to open_time if close_time is invalid
            $tradeTime = $trade['close_time'];
            if (empty($tradeTime) || $tradeTime == '0000-00-00 00:00:00' || strtotime($tradeTime) <= 0) {
                $tradeTime = $trade['open_time'] ?? date('Y-m-d H:i:s');
            }
            
            $closeTime = strtotime($tradeTime);
            
            // Skip if still invalid
            if ($closeTime <= 0) {
                continue;
            }
            
            $tradeDate = date('d M Y', $closeTime);
            
            // Update running balance
            $runningBalance += $trade['profit'];
            
            // Track max balance for drawdown calculation
            if ($runningBalance > $maxBalance) {
                $maxBalance = $runningBalance;
            }
            
            // Calculate drawdown percentage
            $drawdown = $maxBalance > 0 ? (($maxBalance - $runningBalance) / $maxBalance) * 100 : 0;
            
            // Calculate growth percentage from initial deposit
            $growth = $initial_deposit > 0 ? (($runningBalance - $initial_deposit) / $initial_deposit) * 100 : 0;
            
            // Group by DATE (not month)
            if ($currentDate !== $tradeDate) {
                // Save previous date data if exists
                if ($currentDate !== null) {
                    $dailyData[] = [
                        'date' => $currentDate,
                        'profit' => $dailyProfit
                    ];
                }
                
                // Start new date
                $currentDate = $tradeDate;
                $dailyProfit = $trade['profit'];
                
                // Add data point for this date
                $labels[] = $tradeDate;
                $balanceData[] = $runningBalance;
                $equityData[] = $runningBalance;
                $growthData[] = $growth;
                $drawdownData[] = $drawdown;
            } else {
                // Same date, accumulate profit
                $dailyProfit += $trade['profit'];
                
                // Update last data point (multiple trades in same day)
                $balanceData[count($balanceData) - 1] = $runningBalance;
                $equityData[count($equityData) - 1] = $runningBalance;
                $growthData[count($growthData) - 1] = $growth;
                $drawdownData[count($drawdownData) - 1] = $drawdown;
            }
        }
        
        // Save last date data
        if ($currentDate !== null) {
            $dailyData[] = [
                'date' => $currentDate,
                'profit' => $dailyProfit
            ];
        }
        
        return [
            'labels' => $labels,
            'balance' => $balanceData,
            'equity' => $equityData,
            'growth' => $growthData,
            'drawdown' => $drawdownData,
            'daily' => $dailyData
        ];
    }
}
