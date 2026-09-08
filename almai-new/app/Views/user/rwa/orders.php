<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('user/dashboard/rwa') ?>" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<style>
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 18px; }
    .summary-card { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 16px; color:#fff;}
    .summary-card .label { font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .summary-card .value { font-size: 22px; font-weight: 800; }
    .summary-card .note { font-size: 12px; color: #9ca3af; margin-top: 4px; }
    .panel { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 18px; margin-bottom: 18px; color:#fff;}
    .panel-head { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 14px; }
    .panel-head h3 { font-size: 15px; font-weight: 700; }
    .panel-head p { font-size: 12px; color: #9ca3af; margin-top: 3px; }
    .filter-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
    .filter-chip { padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.03); color: #9ca3af; font-size: 12px; }
    .orders-table { display: grid; gap: 10px; }
    .order-row { display: grid; grid-template-columns: 1fr 90px 120px 100px 100px; gap: 12px; align-items: center; padding: 12px 14px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; background: rgba(255,255,255,0.02); }
    .order-main .symbol { font-size: 14px; font-weight: 700; }
    .order-main .meta { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .badge { display: inline-flex; align-items: center; justify-content: center; padding: 6px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .buy { background: rgba(92,255,0,0.12); color: #33e818; }
    .sell { background: rgba(255,59,59,0.12); color: #ef4444; }
    .filled { background: rgba(92,255,0,0.10); color: #33e818; }
    .pending { background: rgba(255,193,7,0.10); color: #ffc107; }
    .cancelled { background: rgba(255,255,255,0.08); color: #9ca3af; }
    .order-row .cell { font-size: 13px; }
    .order-row .muted { color: #9ca3af; font-size: 11px; }
    .split-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 16px; }
    .timeline { display: grid; gap: 10px; }
    .timeline-item { display: flex; gap: 10px; padding: 12px 14px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; background: rgba(255,255,255,0.02); }
    .dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 4px; background: #33e818; box-shadow: 0 0 0 4px rgba(124,255,0,0.08); flex: none; }
    .timeline-item .title { font-size: 13px; font-weight: 600; }
    .timeline-item .desc { font-size: 11px; color: #9ca3af; margin-top: 3px; line-height: 1.5; }
    .mini-stat { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 13px; }
    .mini-stat:last-child { border-bottom: none; }
    .btn { padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; }
    .btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; }
    .btn-primary { background: #33e818; color: #000; }
    @media (max-width: 1000px) {
        .summary-grid, .split-grid { grid-template-columns: 1fr; }
        .order-row { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="summary-grid mt-4">
    <div class="summary-card"><div class="label">Open Orders</div><div class="value">0</div><div class="note">Pending execution</div></div>
    <div class="summary-card"><div class="label">Filled Today</div><div class="value" style="color: #33e818;">0</div><div class="note">Execution rate -</div></div>
    <div class="summary-card"><div class="label">Cancelled</div><div class="value">0</div><div class="note">Mostly timeout exits</div></div>
    <div class="summary-card"><div class="label">Net Exposure</div><div class="value">0.0%</div><div class="note">Across active bots</div></div>
</div>

<div class="panel">
    <div class="panel-head">
        <div>
            <h3>Order Management</h3>
            <p>Monitor active orders, fill status, and routing state from the dashboard.</p>
        </div>
        <div class="btn-row" style="display:flex; gap:10px;">
            <button class="btn btn-outline">Export CSV</button>
        </div>
    </div>

    <div class="filter-row">
        <div class="filter-chip">All Orders</div>
        <div class="filter-chip">Spot</div>
        <div class="filter-chip">Futures</div>
        <div class="filter-chip">Market</div>
        <div class="filter-chip">Limit</div>
        <div class="filter-chip">Stop Loss</div>
    </div>

    <div class="orders-table">
        <?php if (!empty($orders)): ?>
            <?php foreach($orders as $order): ?>
                <div class="order-row">
                    <div class="order-main">
                        <div class="symbol"><?= esc($order->symbol) ?></div>
                        <div class="meta">Exchange API order #<?= esc($order->exchange_order_id) ?></div>
                    </div>
                    <div class="cell"><span class="badge <?= $order->side === 'buy' ? 'buy' : 'sell' ?>"><?= strtoupper(esc($order->side)) ?></span></div>
                    <div class="cell"><?= number_format($order->amount, 4) ?> lot<br><span class="muted"><?= ucfirst(esc($order->type)) ?> order</span></div>
                    <div class="cell"><?= $order->price ? 'Rp ' . number_format($order->price, 0, ',', '.') : 'Market' ?><br><span class="muted">Entry</span></div>
                    <div class="cell"><span class="badge <?= $order->status === 'filled' ? 'filled' : 'pending' ?>"><?= ucfirst(esc($order->status)) ?></span></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: #9ca3af; padding: 30px; font-size: 13px;">
                No orders executed yet.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="split-grid">
    <div class="panel">
        <div class="panel-head">
            <div>
                <h3>Order Flow Timeline</h3>
                <p>Status update terakhir dari gateway order.</p>
            </div>
        </div>
        <div class="timeline">
            <!-- Dynamic logs can go here. For now it's static structure -->
            <div class="timeline-item">
                <div class="dot" style="background: #9ca3af; box-shadow: 0 0 0 4px rgba(255,255,255,0.05);"></div>
                <div>
                    <div class="title">System is standing by</div>
                    <div class="desc">No recent order flow logs to show.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3>Execution Stats</h3>
                <p>Overview singkat untuk performa order routing.</p>
            </div>
        </div>
        <div class="mini-stat"><span>Avg Fill Time</span><strong>-</strong></div>
        <div class="mini-stat"><span>Reject Rate</span><strong style="color: #33e818;">0.0%</strong></div>
        <div class="mini-stat"><span>Partial Fills</span><strong>0</strong></div>
        <div class="mini-stat"><span>Order Slippage</span><strong>0.00%</strong></div>
    </div>
</div>

<?= $this->endSection() ?>
