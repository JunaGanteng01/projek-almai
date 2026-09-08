<?= $this->extend('user/partials/layout') ?>


<?= $this->section('styles') ?>
<style>
    .portfolio-page { display: flex; flex-direction: column; gap: 16px; }

    .portfolio-summary {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, rgba(18, 22, 21, 0.98) 0%, rgba(11, 13, 14, 0.98) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 18px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
    }
    .portfolio-summary::before {
        content: '';
        position: absolute;
        inset: auto -12% -45% auto;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(124, 255, 0, 0.18) 0%, rgba(124, 255, 0, 0) 68%);
        pointer-events: none;
    }
    .summary-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }
    .summary-copy {
        min-width: 0;
    }
    .summary-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.68);
        margin-bottom: 4px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .summary-val {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
        letter-spacing: 0;
        color: #f5f7f3;
    }
    .summary-eye-button {
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
        flex-shrink: 0;
    }
    .summary-eye-button:focus-visible {
        outline: 2px solid rgba(124, 255, 0, 0.45);
        outline-offset: 3px;
        border-radius: 999px;
    }
    .summary-eye {
        width: 14px;
        height: 14px;
        opacity: 0.75;
        flex-shrink: 0;
    }
    .summary-profit {
        margin-top: 4px;
        color: var(--primary);
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
    }
    .summary-sub {
        margin-top: 4px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(124, 255, 0, 0.12);
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
    }
    .summary-chart {
        position: absolute;
        right: 0;
        bottom: 0;
        width: min(62%, 280px);
        opacity: 0.98;
        pointer-events: none;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .section-link {
        color: var(--primary);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .exchanges-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .exchange-card {
        position: relative;
        overflow: hidden;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 16px;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, border-color 0.2s;
    }
    .exchange-card:hover {
        transform: translateY(-2px);
        border-color: rgba(124, 255, 0, 0.28);
    }
    .exchange-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .exchange-name {
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .exchange-logo {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #000;
        font-weight: 800;
        overflow: hidden;
    }
    .exchange-balance {
        font-size: 24px;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }
    .exchange-status {
        font-size: 11px;
        padding: 5px 9px;
        border-radius: 999px;
        font-weight: 700;
        background: rgba(124,255,0,0.12);
        color: var(--primary);
        border: 1px solid rgba(124,255,0,0.2);
    }

    .panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 16px;
    }

    .allocation-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        align-items: center;
    }
    @media(min-width: 768px) {
        .allocation-layout {
            grid-template-columns: 220px 1fr;
        }
    }
    .donut {
        width: 100%;
        max-width: 220px;
        margin: 0 auto;
    }
    .donut .center-label {
        font-size: 18px;
        font-weight: 800;
        fill: #f5f7f3;
    }
    .donut .center-sub {
        font-size: 11px;
        font-weight: 600;
        fill: rgba(255,255,255,0.62);
        letter-spacing: 0.04em;
    }
    .alloc-legend {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }
    @media(min-width: 768px) {
        .alloc-legend {
            grid-template-columns: 1fr 1fr;
        }
    }
    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-sec);
    }
    .legend-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
    }
    .history-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .history-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        flex-shrink: 0;
    }
    .icon-buy { background: rgba(124,255,0,0.12); color: var(--success); }
    .icon-sell { background: rgba(255,50,50,0.12); color: var(--danger); }
    .history-asset {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .history-date {
        font-size: 11px;
        color: var(--text-muted);
    }
    .history-right {
        text-align: right;
        flex-shrink: 0;
    }
    .history-amount {
        font-size: 13px;
        font-weight: 700;
    }
    .history-pnl {
        font-size: 12px;
        margin-top: 2px;
        font-weight: 700;
    }

    .secure-card {
        background: linear-gradient(180deg, rgba(18, 28, 18, 0.92) 0%, rgba(11, 18, 11, 0.92) 100%);
        border: 1px solid rgba(124,255,0,0.18);
        border-radius: 18px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }
    .secure-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .secure-badge {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: rgba(124,255,0,0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        flex-shrink: 0;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06);
    }
    .secure-title { font-size: 14px; font-weight: 800; margin-bottom: 2px; }
    .secure-sub { font-size: 12px; color: var(--text-muted); }
    .secure-arrow { color: var(--text-muted); flex-shrink: 0; }

    .summary-note {
        margin-top: 6px;
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }
    .portfolio-empty {
        padding: 22px;
        text-align: center;
        color: var(--text-muted);
        border: 1px dashed var(--border);
        border-radius: 16px;
        background: rgba(255,255,255,0.02);
    }
    .holdings-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .holding-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
    }
    .holding-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .holding-badge {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b0e14;
        font-weight: 800;
        font-size: 12px;
        flex-shrink: 0;
    }
    .holding-icon {
        object-fit: cover;
        padding: 0;
        background: #11161d;
    }
    .holding-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .holding-sub {
        font-size: 11px;
        color: var(--text-muted);
    }
    .holding-right {
        text-align: right;
        flex-shrink: 0;
    }
    .holding-value {
        font-size: 13px;
        font-weight: 700;
    }
    .holding-percent {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 3px;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="portfolio-page">
    <div style="margin-bottom: 16px;"><a href="<?= base_url('user/dashboard/rwa') ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--primary);text-decoration:none;font-weight:600;background:var(--card);padding:8px 16px;border-radius:12px;border:1px solid var(--border);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg> Kembali ke Dashboard</a></div>
    <div class="portfolio-summary">
        <div class="summary-top">
            <div class="summary-copy">
                <div class="summary-label">
                    <span>Total Portfolio Balance</span>
                    <button type="button" class="summary-eye-button" id="portfolioBalanceToggle" aria-label="Hide total portfolio balance" aria-pressed="true">
                        <svg class="summary-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                <div class="summary-val" id="portfolioBalanceDisplay" data-value="<?= $portfolioSummary['total_balance_str'] ?? 'Rp 0' ?>">*****</div>
                <div class="summary-note"><?= $portfolioSummary['live_note'] ?? 'Real balance aggregated from connected exchanges.' ?></div>
                <div class="summary-sub">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    <?= ($portfolioSummary['connected_count'] ?? 0) ?> of <?= ($portfolioSummary['exchange_count'] ?? 0) ?> exchanges connected
                </div>
            </div>
        </div>

        <?php
            $summarySegments = $portfolioSummary['allocation_segments'] ?? [];
            $allocationGradient = !empty($summarySegments)
                ? 'conic-gradient(' . implode(', ', $summarySegments) . ')'
                : 'conic-gradient(#2b2f35 0% 100%)';
        ?>
        <svg class="summary-chart" viewBox="0 0 320 140" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <linearGradient id="portfolioLineFill" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#7CFF00" stop-opacity="0.28" />
                    <stop offset="100%" stop-color="#7CFF00" stop-opacity="0" />
                </linearGradient>
            </defs>
            <path d="M0 118L30 118L58 111L83 116L107 102L133 106L160 92L182 84L207 90L232 72L258 65L284 58L320 47V140H0Z" fill="url(#portfolioLineFill)" />
            <polyline points="0,118 30,118 58,111 83,116 107,102 133,106 160,92 182,84 207,90 232,72 258,65 284,58 320,47" stroke="#7CFF00" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <div class="section-title">
        <span>Connected Exchanges</span>
        <span class="section-link">Real-time balances</span>
    </div>

    <?php if (!empty($portfolioExchanges)): ?>
        <div class="exchanges-grid">
            <?php foreach ($portfolioExchanges as $exchange): ?>
                <div class="exchange-card">
                    <div class="exchange-header">
                        <div class="exchange-name">
                            <img src="<?= $exchange['logo'] ?>" width="28" height="28" alt="<?= $exchange['exchange'] ?>" style="border-radius:8px; object-fit:cover;">
                            <?= $exchange['exchange'] ?>
                        </div>
                        <div class="exchange-status"><?= $exchange['status'] ?></div>
                    </div>
                    <div class="summary-label">Account</div>
                    <div class="exchange-balance"><?= $exchange['account_name'] ?></div>
                    <div class="summary-label" style="font-size:11px;margin-top:10px;">Balance: <?= $exchange['balance_str'] ?></div>
                    <div class="summary-label" style="font-size:11px;margin-top:2px;">Assets: <?= $exchange['assets'] ?></div>
                    <?php if (!empty($exchange['error'])): ?>
                        <div class="summary-note" style="color:var(--danger);"><?= $exchange['error'] ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="portfolio-empty">
            No exchange balance data found. Connect an exchange key to load real portfolio values.
        </div>
    <?php endif; ?>

    <div class="section-title">Asset Allocation</div>
    <div class="panel">
        <div class="allocation-layout">
            <div class="donut" data-allocation-gradient="<?= $allocationGradient ?>" style="width: 220px; height: 220px; border-radius: 50%; position: relative; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04);">
                <div style="width: 128px; height: 128px; border-radius: 50%; background: #111417; display:flex; align-items:center; justify-content:center; flex-direction:column; text-align:center; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05);">
                    <div class="center-label">100%</div>
                    <div class="center-sub">Total</div>
                </div>
            </div>

            <div class="alloc-legend">
                <?php if (!empty($portfolioHoldings)): foreach ($portfolioHoldings as $holding): ?>
                    <div class="legend-item" data-holding-color="<?= $holding['color'] ?>">
                        <div class="legend-left">
                            <div class="legend-dot"></div>
                            <span><?= $holding['asset_code'] ?> (<?= $holding['quantity_str'] ?>)</span>
                        </div>
                        <span><?= number_format($holding['percent'], 2) ?>%</span>
                    </div>
                <?php endforeach; else: ?>
                    <div class="portfolio-empty" style="grid-column: 1 / -1;">No allocation data available yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="section-title">
        <span>Holdings Breakdown</span>
        <span class="section-link">Live value in IDR</span>
    </div>

    <?php if (!empty($portfolioHoldings)): ?>
        <div class="holdings-list">
            <?php foreach ($portfolioHoldings as $holding): ?>
                <div class="holding-item" data-holding-color="<?= $holding['color'] ?>">
                    <div class="holding-left">
                        <?php if (!empty($holding['icon_path'])): ?>
                            <img class="holding-badge holding-icon" src="<?= $holding['icon_path'] ?>" alt="<?= $holding['asset_code'] ?>">
                        <?php else: ?>
                            <div class="holding-badge"><?= substr($holding['asset_code'], 0, 3) ?></div>
                        <?php endif; ?>
                        <div>
                            <div class="holding-title"><?= $holding['asset_code'] ?></div>
                            <div class="holding-sub"><?= $holding['sources_text'] ?? 'Connected exchange balance' ?></div>
                        </div>
                    </div>
                    <div class="holding-right">
                        <div class="holding-value"><?= $holding['value_str'] ?></div>
                        <div class="holding-percent">Qty: <?= $holding['quantity_str'] ?> · <?= number_format($holding['percent'], 2) ?>%</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="portfolio-empty">No holdings available to display.</div>
    <?php endif; ?>

    <div class="secure-card">
        <div class="secure-left">
            <div class="secure-badge">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V6l-8-3-8 3v6c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <div>
                <div class="secure-title">Secure & Connected</div>
                <div class="secure-sub">Balances are fetched from your connected exchange keys and never shown in plaintext in the browser.</div>
            </div>
        </div>
        <div class="secure-arrow">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
    const balanceDisplay = document.getElementById('portfolioBalanceDisplay');
    const toggleButton = document.getElementById('portfolioBalanceToggle');
    const donut = document.querySelector('.donut[data-allocation-gradient]');

    if (donut) {
        donut.style.background = donut.dataset.allocationGradient || 'conic-gradient(#2b2f35 0% 100%)';
    }

    document.querySelectorAll('[data-holding-color]').forEach((element) => {
        const color = element.getAttribute('data-holding-color');
        if (!color) {
            return;
        }

        const badge = element.querySelector('.holding-badge');
        const dot = element.querySelector('.legend-dot');

        if (badge) {
            badge.style.background = color;
        }

        if (dot) {
            dot.style.background = color;
        }
    });

    if (!balanceDisplay || !toggleButton) {
        return;
    }

    const realValue = balanceDisplay.getAttribute('data-value') || 'Rp 0';
    let hidden = true;

    const updateState = () => {
        balanceDisplay.textContent = hidden ? '*****' : realValue;
        toggleButton.setAttribute('aria-pressed', hidden ? 'true' : 'false');
        toggleButton.setAttribute('aria-label', hidden ? 'Show total portfolio balance' : 'Hide total portfolio balance');
    };

    toggleButton.addEventListener('click', () => {
        hidden = !hidden;
        updateState();
    });

    updateState();
})();
</script>
<?= $this->endSection() ?>


