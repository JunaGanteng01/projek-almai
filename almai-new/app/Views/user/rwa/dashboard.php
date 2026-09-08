<?php
/**
 * @var string $tradeMode
 * @var string $realBalanceStr
 * @var string|null $dailyPnL
 * @var string $dailyPnLPercentStr
 * @var bool $isConnected
 * @var int $activeBotsCount
 * @var int $tradesTodayCount
 * @var array $prices
 */
?>
<?= $this->extend('user/partials/layout') ?>
<?= $this->section('styles') ?>
<style>
    .balance-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, rgba(18, 24, 20, 0.98) 0%, rgba(12, 16, 14, 0.98) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 18px 18px 16px;
        margin-bottom: 16px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
    }
    .balance-card::before {
        content: '';
        position: absolute;
        inset: auto -20% -55% auto;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(124, 255, 0, 0.18) 0%, rgba(124, 255, 0, 0) 68%);
        pointer-events: none;
    }
    .balance-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }
    .balance-label-row {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .balance-eye-button {
        appearance: none;
        border: 0;
        background: transparent;
        padding: 0;
        margin: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: inherit;
    }
    .balance-eye-button:focus-visible {
        outline: 2px solid rgba(124, 255, 0, 0.45);
        outline-offset: 3px;
        border-radius: 999px;
    }
    .balance-eye {
        width: 14px;
        height: 14px;
        opacity: 0.75;
        flex-shrink: 0;
    }
    .balance-pill {
        position: relative;
        z-index: 1;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(124, 255, 0, 0.12);
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }
    .balance-amount {
        position: relative;
        z-index: 1;
        margin-top: 10px;
        font-size: 20px;
        line-height: 1;
        font-weight: 700;
        letter-spacing: 0;
        color: #f3f5f2;
    }
    .balance-bottom {
        position: relative;
        z-index: 1;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .balance-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.74);
        font-size: 12px;
        font-weight: 600;
    }
    .balance-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 0 6px rgba(124, 255, 0, 0.12);
        flex-shrink: 0;
    }
    .balance-chart {
        position: absolute;
        right: 0;
        bottom: 0;
        width: min(58%, 280px);
        height: auto;
        opacity: 0.98;
        pointer-events: none;
    }
    .balance-chart path,
    .balance-chart polyline {
        vector-effect: non-scaling-stroke;
    }
    
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px; }
    @media(max-width: 640px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .stat-box { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 16px; }
    .stat-box .title { font-size: 12px; color: #9ca3af; margin-bottom: 8px; display: flex; justify-content: space-between; }
    .stat-box .value { font-size: 20px; font-weight: 700; color: #fff; }
    .stat-box .sub { font-size: 11px; color: #33e818; margin-top: 4px; }
    .pnl-positive { color: #33e818 !important; }
    .pnl-negative { color: #ef4444 !important; }
    .market-change-positive { color: #33e818; }
    .market-change-negative { color: #ef4444; }
    
    .section-title { font-size: 16px; font-weight: 600; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center; color: #fff;}
    .section-title a { font-size: 13px; color: #33e818; }
    
    .market-list { margin-bottom: 28px; }
    .market-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; margin-bottom: 8px; }
    
    .activity-list { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; }
    .activity-item { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 14px; }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(124,255,0,0.1); color: #33e818; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .activity-details { flex: 1; }
    .activity-title { font-size: 13px; font-weight: 600; margin-bottom: 3px; color: #fff; }
    .activity-time { font-size: 11px; color: #9ca3af; }
    
    .action-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 28px; }
    @media(max-width: 640px) {
        .action-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .action-card { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 14px; text-align: center; color: #fff; text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 8px; transition: all 0.2s; }
    .action-card:hover { border-color: #33e818; background: rgba(124,255,0,0.02); transform: translateY(-2px); }
    .action-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #33e818; margin: 0 auto; }
    .action-title { font-size: 12px; font-weight: 600; }
    
    /* Variables for colors */
    :root {
        --primary: #33e818;
        --danger: #ef4444;
        --success: #33e818;
        --live-color: #3b82f6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Maintenance Notice
<div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 14px 16px; margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-start;">
    <div style="color: #ef4444; margin-top: 2px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
    </div>
    <div>
        <h4 style="color: #ef4444; font-size: 14px; font-weight: 600; margin: 0 0 4px 0;">Pemberitahuan Maintenance</h4>
        <p style="color: rgba(255,255,255,0.8); font-size: 12px; margin: 0; line-height: 1.5;">Saat ini sistem sedang dalam tahap maintenance dan pembaruan server. Mode Live tidak aktif sementara waktu. Mohon maaf atas ketidaknyamanan ini.</p>
    </div>
</div>
 -->
<!-- Mode Switcher -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h4 style="font-size:16px; font-weight:600; margin:0; color:#fff;">AIWE RWA</h4>
    <div style="display:flex; gap:6px; align-items:center; background:rgba(255,255,255,0.03); padding:4px; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
        <form action="<?= base_url('user/dashboard/rwa/set-mode') ?>" method="POST" style="display:inline-block; margin:0;">
            <?= csrf_field() ?>
            <input type="hidden" name="mode" value="demo">
            <button type="submit" style="padding:6px 12px; border-radius:6px; border:none; font-size:11px; font-weight:800; cursor:pointer; transition: all 0.2s; background: <?= $tradeMode === 'demo' ? 'var(--primary)' : 'transparent' ?>; color: <?= $tradeMode === 'demo' ? '#000' : '#9ca3af' ?>;">DEMO</button>
        </form>
        <form action="<?= base_url('user/dashboard/rwa/set-mode') ?>" method="POST" style="display:inline-block; margin:0;">
            <?= csrf_field() ?>
            <input type="hidden" name="mode" value="live">
            <button type="submit" style="padding:6px 12px; border-radius:6px; border:none; font-size:11px; font-weight:800; cursor:pointer; transition: all 0.2s; background: <?= $tradeMode === 'live' ? 'var(--live-color)' : 'transparent' ?>; color: <?= $tradeMode === 'live' ? '#fff' : '#9ca3af' ?>;">LIVE</button>
        </form>
    </div>
</div>

<!-- Total Asset -->
<div class="balance-card">
    <div class="balance-top">
        <div>
            <div class="balance-label-row">
                <span>Nilai Portofolio </span>
                <button type="button" class="balance-eye-button" id="balanceToggle" aria-label="Hide total asset value" aria-pressed="true">
                    <svg class="balance-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
            <div class="balance-amount" id="plDisplay" data-value="<?= $realBalanceStr ?>">*****</div>
        </div>
        <div class="balance-pill" style="background: <?= $dailyPnL >= 0 ? 'rgba(124,255,0,0.12)' : 'rgba(255,50,50,0.12)' ?>; color: <?= $dailyPnL >= 0 ? 'var(--primary)' : 'var(--danger)' ?>;"><?= $dailyPnLPercentStr ?></div>
    </div>

    <svg class="balance-chart" viewBox="0 0 320 140" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <defs>
            <linearGradient id="assetLineFill" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#7CFF00" stop-opacity="0.28" />
                <stop offset="100%" stop-color="#7CFF00" stop-opacity="0" />
            </linearGradient>
        </defs>
        <path d="M0 118L32 118L56 112L82 116L108 104L133 109L160 96L182 88L208 91L232 72L260 66L286 58L320 48V140H0Z" fill="url(#assetLineFill)" />
        <polyline points="0,118 32,118 56,112 82,116 108,104 133,109 160,96 182,88 208,91 232,72 260,66 286,58 320,48" stroke="#7CFF00" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <div class="balance-bottom">
        <div class="balance-status">
            <span class="balance-status-dot" style="background: <?= $tradeMode === 'demo' ? 'var(--primary)' : 'var(--live-color)' ?>; box-shadow: 0 0 0 6px <?= $tradeMode === 'demo' ? 'rgba(124,255,0,0.12)' : 'rgba(59,130,246,0.12)' ?>;"></span>
            <span><?= $tradeMode === 'demo' ? 'Virtual Demo Environment' : ($isConnected && !empty($connectedNames) ? 'Terhubung ke ' . implode(', ', $connectedNames) : 'Belum terhubung ke Exchange') ?></span>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-box">
        <div class="title">PnL Harian <span style="display:inline-flex;align-items:center;gap:4px;"><span style="font-size:9px; padding:1px 5px; border-radius:3px; font-weight:700; background: <?= $tradeMode === 'demo' ? 'rgba(124,255,0,0.15)' : 'rgba(59,130,246,0.15)' ?>; color: <?= $tradeMode === 'demo' ? 'var(--primary)' : 'var(--live-color)' ?>;"><?= strtoupper($tradeMode) ?></span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></span></div>
        <div class="value <?= ($dailyPnL ?? 0) >= 0 ? 'pnl-positive' : 'pnl-negative' ?>"><?= ($dailyPnL ?? 0) >= 0 ? '+Rp' : '-Rp' ?><?= number_format(abs($dailyPnL ?? 0), 0, ',', '.') ?></div>
        <div class="sub <?= ($dailyPnL ?? 0) >= 0 ? 'pnl-positive' : 'pnl-negative' ?>"><?= $dailyPnLPercentStr ?> Hari ini</div>
    </div>
    <div class="stat-box">
        <div class="title">Running Bots <span style="display:inline-flex;align-items:center;gap:4px;"><span style="font-size:9px; padding:1px 5px; border-radius:3px; font-weight:700; background: <?= $tradeMode === 'demo' ? 'rgba(124,255,0,0.15)' : 'rgba(59,130,246,0.15)' ?>; color: <?= $tradeMode === 'demo' ? 'var(--primary)' : 'var(--live-color)' ?>;"><?= strtoupper($tradeMode) ?></span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg></span></div>
        <div class="value"><?= $activeBotsCount ?> Active</div>
        <div class="sub" style="color:#9ca3af"><?= $tradesTodayCount ?> trades hari ini</div>
    </div>
</div>

<!-- Action Grid -->
<div class="action-grid">
    <a href="<?= base_url('user/dashboard/rwa/exchange-api') ?>" class="action-card">
        <div class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></div>
        <div class="action-title">Exchange API</div>
    </a>
    <a href="<?= base_url('user/dashboard/rwa/bots') ?>" class="action-card">
        <div class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg></div>
        <div class="action-title">Bot</div>
    </a>
    <a href="<?= base_url('user/dashboard/rwa/market') ?>" class="action-card">
        <div class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></div>
        <div class="action-title">Market</div>
    </a>
    <a href="<?= base_url('user/dashboard/rwa/backtest') ?>" class="action-card">
        <div class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg></div>
        <div class="action-title">Backtest</div>
    </a>
</div>

<!-- Quick Market (Real Data) -->
<div class="market-list">
    <div class="section-title">
        Quick Market
        <span style="font-size:10px; color:#9ca3af; display:inline-flex; align-items:center; gap:4px;" id="marketUpdateTime">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Live
        </span>
    </div>
    <?php foreach($prices as $p): ?>
        <?php
            $normalizedSymbol = str_replace('_', '', $p['symbol']);
        ?>
        <?php if(in_array($normalizedSymbol, ['PAXGIDR', 'XAUTIDR', 'SLVONIDR'])): ?>
            <?php 
                $iconMap = ['PAXGIDR' => 'PAXG', 'XAUTIDR' => 'XAUT', 'SLVONIDR' => 'SLVON'];
                $coinFile = $iconMap[$normalizedSymbol] ?? $normalizedSymbol;
                $coinName = ['PAXGIDR' => 'PAXG', 'XAUTIDR' => 'XAUT', 'SLVONIDR' => 'SLVON'][$normalizedSymbol] ?? $normalizedSymbol;
                $formattedPrice = number_format($p['price'], 0, ',', '.');
            ?>
            <div class="market-item" id="market-<?= strtolower($coinName) ?>">
                <div style="display:flex;align-items:center;gap:12px;">
                    <img src="<?= base_url('images/crypto_icons/'.$coinFile.'.png') ?>" width="24" height="24" alt="<?= $coinName ?>" style="border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; font-size:8px;" onerror="this.onerror=null; this.src=''; this.alt='<?= $coinName ?>'; this.style.color='#fff';">
                    <div style="font-weight:600; color: #fff;"><?= $p['name'] ?? ($coinName.'/IDR') ?></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:600; color: #fff;" class="market-price">Rp <?= $formattedPrice ?></div>
                    <div class="<?= $p['is_positive'] ? 'market-change-positive' : 'market-change-negative' ?>" style="font-size:11px;font-weight:600;" class="market-change">
                        <?= $p['is_positive'] ? '+' : '' ?><?= number_format($p['change_percent'], 2) ?>%
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Recent Activity -->
<div class="section-title">
    Aktivitas Terkini
    <span style="font-size:11px; color: <?= $tradeMode === 'demo' ? 'var(--primary)' : 'var(--live-color)' ?>; font-weight:700; text-transform:uppercase; padding:2px 8px; border-radius:4px; background: <?= $tradeMode === 'demo' ? 'rgba(124,255,0,0.1)' : 'rgba(59,130,246,0.1)' ?>;"><?= $tradeMode ?></span>
</div>
<div class="activity-list">
    <?php if(!empty($recentActivities)): ?>
        <?php foreach($recentActivities as $activity): ?>
            <?php 
                $isPnlPositive = ($activity['pnl'] ?? 0) >= 0;
                $sideIcon = strtolower($activity['side']) === 'buy' ? 'fa-arrow-down' : 'fa-arrow-up';
                $sideColor = strtolower($activity['side']) === 'buy' ? '#33e818' : '#ef4444';
                $timeAgo = \CodeIgniter\I18n\Time::parse($activity['time'])->humanize();
            ?>
            <div class="activity-item">
                <div class="activity-icon" style="color: <?= $sideColor ?>; background: <?= strtolower($activity['side']) === 'buy' ? 'rgba(51,232,24,0.1)' : 'rgba(239,68,68,0.1)' ?>;">
                    <i class="fas <?= $sideIcon ?>"></i>
                </div>
                <div class="activity-details">
                    <div class="activity-title"><?= esc($activity['bot_name']) ?> - <?= strtoupper($activity['side']) ?> <?= esc($activity['symbol']) ?></div>
                    <div class="activity-time"><?= $timeAgo ?> - <?= ucfirst($activity['status']) ?></div>
                </div>
                <div style="font-weight:600; font-size:13px; color: <?= $isPnlPositive ? '#33e818' : '#ef4444' ?>;">
                    <?php if($activity['status'] === 'closed' && $activity['pnl'] != 0): ?>
                        <?= $isPnlPositive ? '+' : '' ?>Rp <?= number_format($activity['pnl'], 0, ',', '.') ?>
                    <?php elseif($activity['status'] === 'open'): ?>
                        <span style="color:#FFB800; font-size:11px;">Open</span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif($isConnected): ?>
        <div class="activity-item">
            <div class="activity-icon" style="color: #9ca3af;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
            <div class="activity-details">
                <div class="activity-title" style="color: #9ca3af;">Belum ada aktivitas (<?= strtoupper($tradeMode) ?>)</div>
                <div class="activity-time">Aktifkan bot untuk mulai trading</div>
            </div>
            <div style="font-weight:600;font-size:13px;color:#9ca3af;">-</div>
        </div>
    <?php else: ?>
        <div class="activity-item" style="justify-content:center; color:#9ca3af; font-size:13px; padding: 24px;">
            Belum ada data aktivitas. Silakan hubungkan API Exchange terlebih dahulu.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
    const balanceDisplay = document.getElementById('plDisplay');
    const toggleButton = document.getElementById('balanceToggle');

    if (!balanceDisplay || !toggleButton) {
        return;
    }

    const realValue = balanceDisplay.getAttribute('data-value') || 'Rp 0';
    let hidden = true;

    const updateState = () => {
        balanceDisplay.textContent = hidden ? '*****' : realValue;
        toggleButton.setAttribute('aria-pressed', hidden ? 'true' : 'false');
        toggleButton.setAttribute('aria-label', hidden ? 'Show total asset value' : 'Hide total asset value');
    };

    toggleButton.addEventListener('click', () => {
        hidden = !hidden;
        updateState();
    });

    updateState();
})();

// Auto-refresh market prices setiap 30 detik
(function refreshMarketPrices() {
    const REFRESH_INTERVAL = 30000; // 30 detik
    
    async function fetchPrices() {
        try {
            const response = await fetch('<?= base_url("user/dashboard/rwa/market/summary") ?>');
            if (!response.ok) return;
            const data = await response.json();
            if (!data.success || !data.data) return;
            
            Object.entries(data.data).forEach(([key, p]) => {
                const symbol = key.replace('_', '').toUpperCase();
                const coinMap = { 'PAXGIDR': 'paxg', 'XAUTIDR': 'xaut', 'SLVONIDR': 'slvon' };
                const coinKey = coinMap[symbol];
                if (!coinKey) return;
                
                const el = document.getElementById('market-' + coinKey);
                if (!el) return;
                
                const priceEl = el.querySelector('.market-price') || el.querySelectorAll('div[style*="font-weight:600"]')[1];
                const changeEl = el.querySelector('[class*="market-change-"]');
                
                if (priceEl) {
                    const priceVal = parseFloat(p.last || p.price || 0);
                    priceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(priceVal));
                }
                if (changeEl) {
                    const changePercent = parseFloat(p.change_percent || 0);
                    const isPositive = changePercent >= 0;
                    changeEl.className = isPositive ? 'market-change-positive' : 'market-change-negative';
                    changeEl.textContent = (isPositive ? '+' : '') + changePercent.toFixed(2) + '%';
                }
            });
            
            const updateEl = document.getElementById('marketUpdateTime');
            if (updateEl) {
                const now = new Date();
                updateEl.innerHTML = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ' + now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
            }
        } catch (e) {
            // Silent fail
        }
    }
    
    setInterval(fetchPrices, REFRESH_INTERVAL);
})();
</script>
<?= $this->endSection() ?>
