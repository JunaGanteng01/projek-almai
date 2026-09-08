<?php
/**
 * @var string $tradeMode
 * @var array $botsData
 */
?>
<?= $this->extend('user/partials/layout') ?>


<?= $this->section('content') ?>
<style>
    :root {
        --card: #171a1d;
        --border: rgba(255, 255, 255, 0.1);
        --text-muted: #9ca3af;
        --text-sec: #d1d5db;
        --primary: #33e818;
        --success: #33e818;
        --danger: #ef4444;
        --surface: #111111;
    }
    .action-header { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 24px; }
    .btn-emergency { background: rgba(255,50,50,0.15); color: var(--danger); font-weight: 700; padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(255,50,50,0.3); cursor: pointer; display: flex; align-items: center; gap: 8px; }
    
    .bot-grid { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
    @media(min-width: 768px) { .bot-grid { grid-template-columns: 1fr 1fr; } }
    @media(min-width: 1024px) { .bot-grid { grid-template-columns: repeat(3, 1fr); } }
    
    .bot-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
    .bot-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .bot-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
    .status-badge { font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600; }
    .status-active { background: rgba(124,255,0,0.1); color: var(--success); border: 1px solid rgba(124,255,0,0.2); }
    .status-stopped { background: rgba(255,255,255,0.1); color: var(--text-muted); border: 1px solid var(--border); }
    
    .bot-stats { display: flex; gap: 16px; margin-bottom: 16px; }
    .bot-stat-item { flex: 1; }
    .bot-stat-label { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
    .bot-stat-val { font-size: 16px; font-weight: 600; }
    
    .bot-actions { display: flex; gap: 10px; border-top: 1px solid var(--border); padding-top: 16px; }
    .btn-action { flex: 1; padding: 8px; text-align: center; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; }
    .btn-stop { background: var(--danger); color: #000; border: none; }
    .btn-start { background: var(--primary); color: #000; }
    .btn-settings { background: transparent; color: var(--text-sec); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; width: 40px; flex: none; }
    
    .modal-actions { display: flex; gap: 10px; margin-top: 24px; justify-content: flex-end; }
    .btn-cancel { padding: 8px 16px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: transparent; color: #fff; cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s; }
    .btn-save { padding: 8px 16px; border-radius: 6px; border: none; background: var(--primary); color: #000; cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s; }
    
    .swal-rwa-popup { border: 1px solid rgba(255,255,255,0.1); border-radius: 16px !important; }
    
    .performance-card { background: linear-gradient(135deg, rgba(124,255,0,0.05), transparent); border: 1px solid rgba(124,255,0,0.2); border-radius: 14px; padding: 20px; }
    .perf-title { font-size: 14px; font-weight: 600; margin-bottom: 16px; }
    .perf-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    @media (max-width: 768px) {
        .perf-stats { grid-template-columns: 1fr 1fr; }
    }
    .perf-item { background: var(--surface); padding: 12px; border-radius: 8px; border: 1px solid var(--border); }
    
    /* Modal styles */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.8);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .modal-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        max-width: 480px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }
</style>

<?php // tradeMode is passed from controller ?>
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('user/dashboard/rwa') ?>" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>
<!-- Mode Switcher -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--border); padding-bottom:16px;">
    <div style="color:var(--text-muted);font-size:13px;">Trade Mode: <strong style="color: <?= $tradeMode === 'live' ? '#3b82f6' : 'var(--primary)' ?>"><?= strtoupper($tradeMode) ?></strong></div>
    <div style="color:var(--text-muted);font-size:13px;">
        Status Lisensi Bot: 
        <?php if ($botStatusData['is_lifetime']): ?>
            <strong style="color: var(--primary)">Lifetime (Aktif)</strong>
        <?php elseif ($botStatusData['days_left'] !== null): ?>
            <?php if ($botStatusData['days_left'] > 0): ?>
                <strong style="color: var(--primary)"><?= $botStatusData['days_left'] ?> Hari Tersisa</strong>
            <?php else: ?>
                <strong style="color: var(--danger)">Kedaluwarsa</strong>
            <?php endif; ?>
        <?php else: ?>
            <strong style="color: var(--danger)">Tidak Aktif</strong>
        <?php endif; ?>
    </div>
</div>

<?php if ($tradeMode === 'live'): ?>
<div style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); color: #93c5fd; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    <div>
        <strong>Perhatian:</strong> Dalam Mode Live, setiap kali bot mengeksekusi pesanan (Buy/Sell), saldo Anda akan dipotong sebesar <strong>1 Poin Almai</strong> sebagai biaya operasional (Per-Trade Execution).
    </div>
</div>
<?php endif; ?>

<div class="bot-grid">


    <?php foreach ($botsData as $bot): ?>
    <div class="bot-card">
        <div class="bot-header">
            <div class="bot-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $bot['icon'] ?></svg>
                <?= $bot['name'] ?>
            </div>
            <?php if ($bot['status'] === 'active'): ?>
                <div class="status-badge status-active">Active</div>
            <?php else: ?>
                <div class="status-badge status-stopped">Stopped</div>
            <?php endif; ?>
        </div>
        <div style="font-size: 13px; color: var(--text-sec); margin-bottom: 12px; line-height: 1.5; min-height: 38px;">
            <?= $bot['description'] ?>
        </div>
        <div class="bot-stats" style="margin-bottom: 16px; border-top: 1px solid rgba(255,255,255,0.03); padding-top: 12px;">
            <div class="bot-stat-item">
                <div class="bot-stat-label">Trading Pair</div>
                <div class="bot-stat-val" style="font-size: 14px;"><?= $bot['pair'] ?></div>
            </div>
            <div class="bot-stat-item">
                <div class="bot-stat-label">Timeframe</div>
                <div class="bot-stat-val" style="font-size: 14px; color: var(--primary);"><?= $bot['timeframe'] ?></div>
            </div>
            <div class="bot-stat-item">
                <div class="bot-stat-label">Capital Allocation</div>
                <div class="bot-stat-val" style="font-size: 14px;"><?= $bot['allocation_percent'] ?>%</div>
            </div>
            <div class="bot-stat-item">
                <div class="bot-stat-label">Stop Loss</div>
                <div class="bot-stat-val" style="font-size: 14px; color: <?= ($bot['stop_loss_percent'] ?? 0) > 0 ? 'var(--danger)' : 'var(--text-muted)' ?>;"><?= ($bot['stop_loss_percent'] ?? 0) > 0 ? ($bot['stop_loss_percent'] . '%') : 'OFF' ?></div>
            </div>
        </div>
        <div class="bot-actions">
            <?php if ($bot['status'] === 'active'): ?>
                <form action="<?= base_url('user/dashboard/rwa/bots/stop/' . $bot['symbol']) ?>" method="POST" style="flex: 1;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-stop" style="width: 100%;">Stop Bot</button>
                </form>
            <?php else: ?>
                <form action="<?= base_url('user/dashboard/rwa/bots/start/' . $bot['symbol']) ?>" method="POST" class="form-start-bot" style="flex: 1;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-start" style="width: 100%;">Start Bot</button>
                </form>
            <?php endif; ?>
            <button type="button" class="btn-action btn-settings" data-symbol="<?= $bot['symbol'] ?>" data-name="<?= $bot['name'] ?>" data-allocation="<?= $bot['allocation_percent'] ?>" data-stoploss="<?= $bot['stop_loss_percent'] ?? 0 ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="performance-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div class="perf-title" style="margin-bottom: 0;">Kinerja Bot Secara Keseluruhan</div>
        <a href="<?= base_url('user/poin/buy') ?>" style="text-decoration: none; background: rgba(255,184,0,0.15); color: #FFB800; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid rgba(255,184,0,0.3); display: flex; align-items: center; gap: 4px; transition: all 0.2s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            Top Up Almai Poin
        </a>
    </div>
    <div class="perf-stats">
        <div class="perf-item">
            <div class="bot-stat-label">Total Profit</div>
            <div class="bot-stat-val" style="color: <?= (isset($globalTotalProfit) && $globalTotalProfit > 0) ? 'var(--success)' : ((isset($globalTotalProfit) && $globalTotalProfit < 0) ? 'var(--danger)' : 'inherit') ?>">
                <?= (isset($globalTotalProfit) && $globalTotalProfit >= 0) ? '+' : '' ?>Rp <?= number_format($globalTotalProfit ?? 0, 0, ',', '.') ?>
            </div>
        </div>
        <div class="perf-item">
            <div class="bot-stat-label">Avg Win Rate</div>
            <div class="bot-stat-val"><?= $globalAvgWinRate ?? 0 ?>%</div>
        </div>
        <div class="perf-item">
            <div class="bot-stat-label">Total Trades</div>
            <div class="bot-stat-val"><?= $globalTotalTrades ?? 0 ?></div>
        </div>
        <div class="perf-item">
            <div class="bot-stat-label">Active Uptime</div>
            <div class="bot-stat-val" style="color: var(--success);">100%</div>
        </div>
    </div>
</div>

<div class="bot-card" style="margin-top: 24px; overflow: hidden; max-width: 100%; width: 100%; box-sizing: border-box; padding: 16px;">
    <div class="bot-header" style="margin-bottom: 20px;">
        <div class="bot-title" style="display:flex;align-items:center;gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Riwayat Pesanan Bot Terbaru
        </div>
    </div>
    
    <?php if (empty($recentOrders)): ?>
        <div style="text-align: center; color: var(--text-muted); padding: 30px; font-size: 13px;">
            Belum ada pesanan yang dieksekusi oleh bot. Mulai bot Anda untuk memulai perdagangan!
        </div>
    <?php else: ?>
        <div class="table-responsive" style="max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; white-space: nowrap;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted);">
                        <th style="padding: 12px 8px;">Date</th>
                        <th style="padding: 12px 8px;">Symbol</th>
                        <th style="padding: 12px 8px;">Side</th>
                        <th style="padding: 12px 8px;">Action</th>
                        <th style="padding: 12px 8px;">Price</th>
                        <th style="padding: 12px 8px;">Amount</th>
                        <th style="padding: 12px 8px;">Status</th>
                        <th style="padding: 12px 8px; text-align: right;">PnL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <?php
                            $actionType = 'Entry Buy';
                            $actionColor = 'var(--text-muted)';
                            if ($order->side === 'sell') {
                                if ($order->pnl > 0) {
                                    $actionType = 'Take Profit';
                                    $actionColor = 'var(--success)';
                                } else {
                                    $actionType = 'Stop Loss';
                                    $actionColor = 'var(--danger)';
                                }
                            }
                        ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 12px 8px; color: var(--text-muted);">
                                <?= date('d M H:i:s', strtotime($order->created_at)) ?>
                            </td>
                            <td style="padding: 12px 8px; font-weight: 700;">
                                <?= str_replace('_', '/', $order->symbol) ?>
                            </td>
                            <td style="padding: 12px 8px;">
                                <span class="status-badge" style="background: <?= $order->side === 'buy' ? 'rgba(124,255,0,0.1)' : 'rgba(255,50,50,0.1)' ?>; color: <?= $order->side === 'buy' ? 'var(--success)' : 'var(--danger)' ?>;">
                                    <?= strtoupper($order->side) ?>
                                </span>
                            </td>
                            <td style="padding: 12px 8px;">
                                <span style="font-size: 11px; padding: 3px 6px; border-radius: 4px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: <?= $actionColor ?>;">
                                    <?= $actionType ?>
                                </span>
                            </td>
                            <td style="padding: 12px 8px; font-weight: 600;">
                                Rp <?= number_format($order->price, 0, ',', '.') ?>
                            </td>
                            <td style="padding: 12px 8px;">
                                <?= number_format($order->amount, 4) ?>
                            </td>
                            <td style="padding: 12px 8px; color: var(--text-muted);">
                                <?= ucfirst($order->status) ?>
                            </td>
                            <td style="padding: 12px 8px; text-align: right; font-weight: 700; color: <?= $order->pnl > 0 ? 'var(--success)' : ($order->pnl < 0 ? 'var(--danger)' : 'var(--text-muted)') ?>">
                                <?php if ($order->side === 'sell' && $order->pnl !== null): ?>
                                    <?= $order->pnl >= 0 ? '+' : '' ?>Rp <?= number_format($order->pnl, 0, ',', '.') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <?php if (!empty($recentOrders)): ?>
        <div style="margin-top:16px;font-size:12px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;border-top:1px solid rgba(255,255,255,0.03);padding-top:12px;">
            <?php if (true): ?>
                <span style="color:var(--text-muted);padding:6px 10px;">‹ Prev</span>
            <?php else: ?>
                <a href="#" style="color:var(--primary);padding:6px 10px;border:1px solid var(--border);border-radius:6px;text-decoration:none;">‹ Prev</a>
            <?php endif; ?>

            <span style="color:var(--text-muted);">Page 1 of 1</span>

            <?php if (false): ?>
                <a href="#" style="color:var(--primary);padding:6px 10px;border:1px solid var(--border);border-radius:6px;text-decoration:none;">Next ›</a>
            <?php else: ?>
                <span style="color:var(--text-muted);padding:6px 10px;">Next ›</span>
            <?php endif; ?>

            <span style="color:var(--text-muted);margin-left:auto;"><?= count($recentOrders) ?> orders</span>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Start Bot Modal -->
<div class="modal-overlay" id="startBotModal" style="display: none;">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:16px;font-weight:600;display:flex;align-items:center;gap:8px;">
                <span style="color:#FFB800;">⚠️</span> Disclaimer
            </h3>
            <span onclick="closeStartBotModal()" style="cursor:pointer;font-size:20px;color:var(--text-muted);">✕</span>
        </div>
        
        <div style="background: rgba(255, 184, 0, 0.1); border: 1px solid rgba(255, 184, 0, 0.25); padding: 16px; border-radius: 12px; font-size: 13px; color: #f3f5f2; line-height: 1.6; margin-bottom: 24px;">
            Trading aset kripto berisiko tinggi. Bot ini bukan penasihat investasi dan tidak menjamin keuntungan. Seluruh keputusan serta risiko keuntungan maupun kerugian menjadi tanggung jawab pengguna sepenuhnya.
        </div>

        <div class="modal-actions" style="margin-top:0;">
            <button type="button" class="btn-cancel" onclick="closeStartBotModal()">Batal</button>
            <button type="button" class="btn-save" id="btnConfirmStartBot">Saya Mengerti, Mulai Bot</button>
        </div>
    </div>
</div>

<!-- Bot Settings Modal -->
<div class="modal-overlay" id="settingsModal" style="display: none;">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:16px;font-weight:600;" id="settingsModalTitle">Bot Settings</h3>
            <span onclick="document.getElementById('settingsModal').style.display='none'" style="cursor:pointer;font-size:20px;color:var(--text-muted);">✕</span>
        </div>
        
        <!-- Info alert explaining settings -->
        <div style="background: rgba(0, 180, 255, 0.1); border: 1px solid rgba(0, 180, 255, 0.25); padding: 12px; border-radius: 8px; font-size: 12px; color: #00e5ff; line-height: 1.5; margin-bottom: 18px; display: flex; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <div>
                <strong>Penting:</strong> Harap atur terlebih dahulu persentase modal per trade sebelum menjalankan bot agar bot dapat mengalokasikan saldo secara tepat. Rekomendasi: <strong>20%</strong>.
            </div>
        </div>

        <form id="settingsModalForm" method="POST" action="">
            <?= csrf_field() ?>
            <div style="margin-bottom: 20px;">
                <label style="font-size:12px;color:var(--text-muted);display:block;margin-bottom:8px;">Alokasi Modal per Trade (%)</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" id="allocationRange" min="1" max="100" value="20" style="flex: 1; accent-color: var(--primary);" oninput="document.getElementById('allocationNumber').value = this.value">
                    <input type="number" id="allocationNumber" name="allocation_percent" min="1" max="100" value="20" style="width: 75px; padding: 8px; border-radius: 8px; border: 1px solid var(--border); background: rgba(255,255,255,0.02); color: inherit; text-align: center; font-weight: 600;" oninput="document.getElementById('allocationRange').value = this.value">
                    <span style="font-weight: 700; color: var(--text-muted);">%</span>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size:12px;color:var(--text-muted);display:block;margin-bottom:8px;">Stop Loss (%) — <span style="color:#9ca3af;">0 = tidak aktif</span></label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" id="stopLossRange" min="0" max="50" value="0" style="flex: 1; accent-color: var(--danger);" oninput="document.getElementById('stopLossNumber').value = this.value">
                    <input type="number" id="stopLossNumber" name="stop_loss_percent" min="0" max="50" value="0" style="width: 75px; padding: 8px; border-radius: 8px; border: 1px solid var(--border); background: rgba(255,255,255,0.02); color: inherit; text-align: center; font-weight: 600;" oninput="document.getElementById('stopLossRange').value = this.value">
                    <span style="font-weight: 700; color: var(--text-muted);">%</span>
                </div>
            </div>
            
            <button type="submit" class="btn" style="width:100%;padding:14px;font-weight:700;background:var(--primary);color:#000;border:none;border-radius:8px;cursor:pointer;font-size:14px;">Simpan Pengaturan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('settingsModal');
    if (modal) {
        // Append modal directly to body to avoid containment clipping
        document.body.appendChild(modal);

        // Close when clicking outside of modal content box
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Bind all setting buttons
    document.querySelectorAll('.btn-settings').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const symbol = btn.getAttribute('data-symbol');
            const name = btn.getAttribute('data-name');
            const allocation = btn.getAttribute('data-allocation');
            const stoploss = btn.getAttribute('data-stoploss') || '0';
            
            openSettingsModal(symbol, name, allocation, stoploss);
        });
    });
});

function openSettingsModal(symbol, name, allocationPercent, stopLossPercent) {
    const modal = document.getElementById('settingsModal');
    if (!modal) return;

    document.getElementById('settingsModalTitle').textContent = name + ' Settings';
    document.getElementById('settingsModalForm').action = '<?= base_url('user/dashboard/rwa/bots/settings') ?>/' + symbol;
    document.getElementById('allocationRange').value = allocationPercent;
    document.getElementById('allocationNumber').value = allocationPercent;
    document.getElementById('stopLossRange').value = stopLossPercent;
    document.getElementById('stopLossNumber').value = stopLossPercent;
    modal.style.display = 'flex';
}

// Custom Modal for Start Bot
let currentStartForm = null;

document.addEventListener('DOMContentLoaded', () => {
    const startModal = document.getElementById('startBotModal');
    if (startModal) {
        document.body.appendChild(startModal);
        startModal.addEventListener('click', (e) => {
            if (e.target === startModal) {
                closeStartBotModal();
            }
        });
    }
});

document.querySelectorAll('.form-start-bot').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        currentStartForm = form;
        document.getElementById('startBotModal').style.display = 'flex';
    });
});

function closeStartBotModal() {
    document.getElementById('startBotModal').style.display = 'none';
    currentStartForm = null;
}

document.getElementById('btnConfirmStartBot').addEventListener('click', function() {
    if (currentStartForm) {
        currentStartForm.submit();
    }
});
</script>
<?= $this->endSection() ?>







