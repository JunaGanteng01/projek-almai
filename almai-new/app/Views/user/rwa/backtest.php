<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('user/dashboard/rwa') ?>" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 18px; }
    .stat { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 16px; color:#fff;}
    .stat .label { font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .stat .value { font-size: 22px; font-weight: 800; }
    .panel { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 18px; margin-bottom: 18px; color:#fff;}
    .panel-head { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 14px; }
    .panel-head h3 { font-size: 15px; font-weight: 700; }
    .panel-head p { font-size: 12px; color: #9ca3af; margin-top: 3px; }
    .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 11px; color: #9ca3af; margin-bottom: 2px; }
    .form-group select, .form-group input { padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); color: inherit; font-size: 13px; outline: none; transition: border-color 0.2s; }
    .form-group select:focus, .form-group input:focus { border-color: #33e818; }
    .form-group select option { background: #171a1d; color: #fff; }
    .button-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
    .btn-primary { background: #33e818; color: #000; padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; }
    .chart-box { height: 320px; border-radius: 14px; border: 1px dashed rgba(255,255,255,0.1); background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)); display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 13px; text-align: center; padding: 24px; }
    .result-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 16px; }
    .results-table { display: grid; gap: 10px; }
    .result-row { display: grid; grid-template-columns: 1fr 90px 110px 100px; gap: 12px; align-items: center; padding: 12px 14px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; background: rgba(255,255,255,0.02); }
    .badge { display: inline-flex; align-items: center; justify-content: center; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .win { background: rgba(124,255,0,0.12); color: #33e818; }
    .loss { background: rgba(255,50,50,0.12); color: #ef4444; }
    .bullet-list { display: grid; gap: 10px; }
    .bullet { display: flex; gap: 10px; align-items: flex-start; font-size: 13px; color: #9ca3af; line-height: 1.5; }
    .bullet .dot { width: 8px; height: 8px; margin-top: 6px; border-radius: 50%; background: #33e818; flex: none; }
    @media (max-width: 1000px) {
        .stats-grid, .form-grid, .result-grid { grid-template-columns: 1fr; }
    }
    
    .loading-overlay { display: none; position: fixed; inset: 0; background: rgba(10, 15, 30, 0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 99999; flex-direction: column; align-items: center; justify-content: center; }
    .spinner-box { position: relative; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center; }
    .spin-ring-1 { position: absolute; width: 120px; height: 120px; border: 4px solid transparent; border-top-color: #33e818; border-bottom-color: #33e818; border-radius: 50%; animation: spin 1.8s linear infinite; }
    .spin-ring-2 { position: absolute; width: 90px; height: 90px; border: 4px solid transparent; border-left-color: #00e5ff; border-right-color: #00e5ff; border-radius: 50%; animation: spin-reverse 1.4s linear infinite; }
    .spin-ring-3 { position: absolute; width: 60px; height: 60px; border: 2px dashed rgba(255, 255, 255, 0.15); border-radius: 50%; }
    .loader-brand { color: #fff; font-size: 16px; font-weight: 900; letter-spacing: 1.5px; text-transform: uppercase; animation: pulse-glow 1.5s ease-in-out infinite; }
    .loader-title { margin-top: 32px; font-weight: 800; color: #fff; font-size: 20px; text-align: center; }
    .loader-subtitle { margin-top: 8px; color: #9ca3af; font-size: 13px; max-width: 400px; text-align: center; height: 20px; font-weight: 500; }
    .progress-bar-container { width: 280px; height: 6px; background: rgba(255,255,255,0.06); border-radius: 10px; margin-top: 24px; overflow: hidden; position: relative; border: 1px solid rgba(255,255,255,0.03); }
    .progress-bar-fill { width: 0%; height: 100%; background: linear-gradient(90deg, #33e818, #00e5ff); border-radius: 10px; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes spin-reverse { 0% { transform: rotate(0deg); } 100% { transform: rotate(-360deg); } }
    @keyframes pulse-glow { 0% { opacity: 0.5; text-shadow: 0 0 2px rgba(124, 255, 0, 0); } 50% { opacity: 1; text-shadow: 0 0 12px rgba(124, 255, 0, 0.8); } 100% { opacity: 0.5; text-shadow: 0 0 2px rgba(124, 255, 0, 0); } }
</style>

<?php if(session()->getFlashdata('success')): ?>
    <div style="padding: 12px; background: rgba(124,255,0,0.1); border: 1px solid #33e818; color: #33e818; border-radius: 8px; margin-bottom: 16px; font-size: 13px; margin-top: 16px;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div style="padding: 12px; background: rgba(255,50,50,0.1); border: 1px solid #ef4444; color: #ef4444; border-radius: 8px; margin-bottom: 16px; font-size: 13px; margin-top: 16px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="stats-grid mt-4">
    <div class="stat">
        <div class="label">Net Profit</div>
        <div class="value" style="color: <?= isset($result['summary']['netProfitPercent']) && $result['summary']['netProfitPercent'] >= 0 ? '#33e818' : '#ef4444' ?>">
            <?= isset($result['summary']['netProfitPercent']) ? ($result['summary']['netProfitPercent'] >= 0 ? '+' : '') . $result['summary']['netProfitPercent'] . '%' : '-' ?>
        </div>
        <div class="label" style="margin-top: 6px; text-transform: none; letter-spacing: 0;">
            <?= isset($result['summary']) ? 'Rp ' . number_format($result['summary']['finalBalance'] - $result['summary']['startingBalance'], 0, ',', '.') : 'Profit/loss return' ?>
        </div>
    </div>
    <div class="stat">
        <div class="label">Win Rate</div>
        <div class="value" style="color: <?= isset($result['summary']['winRate']) && $result['summary']['winRate'] >= 50 ? '#33e818' : 'inherit' ?>">
            <?= isset($result['summary']['winRate']) ? $result['summary']['winRate'] . '%' : '-' ?>
        </div>
        <div class="label" style="margin-top: 6px; text-transform: none; letter-spacing: 0;">
            <?= isset($result['summary']) ? 'Across ' . $result['summary']['tradesCount'] . ' trades' : 'Total completed trades' ?>
        </div>
    </div>
    <div class="stat">
        <div class="label">Max Drawdown</div>
        <div class="value" style="color: #ffc107;"><?= isset($result['summary']['maxDrawdown']) ? '-' . $result['summary']['maxDrawdown'] . '%' : '-' ?></div>
        <div class="label" style="margin-top: 6px; text-transform: none; letter-spacing: 0;">Peak to trough loss</div>
    </div>
    <div class="stat">
        <div class="label">Profit Factor</div>
        <div class="value"><?= isset($result['summary']['profitFactor']) ? $result['summary']['profitFactor'] : '-' ?></div>
        <div class="label" style="margin-top: 6px; text-transform: none; letter-spacing: 0;">Gross profit / gross loss</div>
    </div>
</div>

<form id="backtestForm" action="<?= base_url('user/dashboard/rwa/backtest') ?>" method="POST">
    <?= csrf_field() ?>
    <!-- Hidden fields auto-filled by bot selection -->
    <input type="hidden" name="symbol" id="hidden_symbol" value="<?= esc($params['symbol'] ?? 'XAUT/IDR') ?>">
    <input type="hidden" name="timeframe" id="hidden_timeframe" value="<?= esc($params['timeframe'] ?? '5m') ?>">

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3>Backtest Parameters</h3>
                <p>Pilih strategi bot dan rentang waktu simulasi. Pair & timeframe otomatis mengikuti konfigurasi bot.</p>
            </div>
            <div class="button-row" style="margin-top: 0;">
                <button type="submit" class="btn btn-primary">Run Backtest</button>
            </div>
        </div>

        <div class="form-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            <div class="form-group">
                <label>Select Strategy / Bot</label>
                <select name="bot" id="bot_select">
                    <option value="alpha_gold"
                        data-symbol="SLVON/IDR" data-timeframe="5m"
                        <?= ($params['bot'] ?? 'alpha_gold') === 'alpha_gold' ? 'selected' : '' ?>>
                        Alpha Gold — SLVON/IDR · M5
                    </option>
                    <option value="silver_trend"
                        data-symbol="XAUT/IDR" data-timeframe="15m"
                        <?= ($params['bot'] ?? '') === 'silver_trend' ? 'selected' : '' ?>>
                        Silver Trend — XAUT/IDR · M15
                    </option>
                    <option value="swap_grid"
                        data-symbol="PAXG/IDR" data-timeframe="30m"
                        <?= ($params['bot'] ?? '') === 'swap_grid' ? 'selected' : '' ?>>
                        Swap Grid — PAXG/IDR · M30
                    </option>
                </select>
                <small id="bot_info" style="color:#9ca3af; margin-top:4px; font-size:11px;"></small>
            </div>
            <div class="form-group">
                <label>Historical Period</label>
                <select name="date_range">
                    <option value="last_week" <?= ($params['date_range'] ?? '') === 'last_week' ? 'selected' : '' ?>>Last Week (7 Days)</option>
                    <option value="last_month" <?= ($params['date_range'] ?? '') === 'last_month' || empty($params['date_range']) ? 'selected' : '' ?>>Last Month (30 Days)</option>
                    <option value="last_year" <?= ($params['date_range'] ?? '') === 'last_year' ? 'selected' : '' ?>>Last Year (365 Days)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Starting Balance (IDR)</label>
                <input type="number" name="starting_balance" min="100000" value="<?= intval(str_replace(['Rp', '.', ' ', ','], '', $params['starting_balance'] ?? '1000000')) ?>">
            </div>
            <div class="form-group">
                <label>Capital Allocation (Risk Per Trade)</label>
                <select name="risk_per_trade">
                    <option value="1%"  <?= ($params['risk_per_trade'] ?? '') === '1%'  ? 'selected' : '' ?>>1%</option>
                    <option value="2%"  <?= ($params['risk_per_trade'] ?? '') === '2%'  ? 'selected' : '' ?>>2%</option>
                    <option value="5%"  <?= ($params['risk_per_trade'] ?? '') === '5%'  ? 'selected' : '' ?>>5%</option>
                    <option value="10%" <?= ($params['risk_per_trade'] ?? '') === '10%' ? 'selected' : '' ?>>10%</option>
                    <option value="20%" <?= ($params['risk_per_trade'] ?? '') === '20%' || empty($params['risk_per_trade']) ? 'selected' : '' ?>>20% (Recommended)</option>
                    <option value="50%" <?= ($params['risk_per_trade'] ?? '') === '50%' ? 'selected' : '' ?>>50%</option>
                </select>
            </div>
        </div>
    </div>
</form>
<script>
(function() {
    const botSelect    = document.getElementById('bot_select');
    const hidSymbol    = document.getElementById('hidden_symbol');
    const hidTimeframe = document.getElementById('hidden_timeframe');
    const botInfo      = document.getElementById('bot_info');

    function syncBot() {
        const opt = botSelect.options[botSelect.selectedIndex];
        hidSymbol.value    = opt.dataset.symbol;
        hidTimeframe.value = opt.dataset.timeframe;
        botInfo.textContent = 'Pair: ' + opt.dataset.symbol + ' · Timeframe: ' + opt.dataset.timeframe;
    }
    botSelect.addEventListener('change', syncBot);
    syncBot(); // init on load
})();
</script>

<div class="result-grid">
    <div class="panel">
        <div class="panel-head">
            <div>
                <h3>Equity Curve</h3>
                <p>Grafik pertumbuhan saldo berdasarkan eksekusi bot historis.</p>
            </div>
        </div>
        <div class="chart-box" style="height: auto; border: none; background: transparent; display: block; padding: 0;">
            <?php if(isset($result['equityCurve']) && count($result['equityCurve']) > 0): ?>
                <div style="height: 320px; width: 100%;">
                    <canvas id="equityChart"></canvas>
                </div>
            <?php else: ?>
                <div class="chart-box" style="border: 1px dashed rgba(255,255,255,0.1); border-radius: 14px; background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)); height: 320px; display: flex; align-items: center; justify-content: center;">
                    Grafik ekuitas akan muncul di sini setelah Anda sukses menjalankan simulasi backtest.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 id="notes-title">Backtest Notes &amp; Guidance</h3>
                <p id="notes-subtitle">Tips interpretasi hasil simulasi berdasarkan strategi yang dipilih.</p>
            </div>
        </div>
        <div class="bullet-list" id="notes-list"></div>
    </div>
</div>

<script>
// Strategy notes database — keyed by bot value
const STRATEGY_NOTES = {
    alpha_gold: {
        title: 'Alpha Gold — EMA Cross + VWAP Pullback',
        subtitle: 'Strategi trend-following dengan entri presisi dekat VWAP. Pair: SLVON/IDR · Timeframe: M5.',
        notes: [
            { label: 'EMA 9 & 21 Cross', text: 'Strategi hanya membeli ketika EMA 9 berada di atas EMA 21, memastikan bot hanya masuk saat momentum sedang naik (uptrend). Hindari menggunakan di pasar sideways.' },
            { label: 'VWAP Pullback Entry', text: 'Entri dilakukan ketika harga turun mendekati garis VWAP (Volume Weighted Average Price), bukan mengejar harga di puncak. Ini mengoptimalkan harga beli.' },
            { label: 'Take Profit 1.75x', text: 'TP ditetapkan pada jarak 1.75× dari estimasi pergerakan harga (swing distance), memaksimalkan profit pada setiap trade yang berhasil.' },
            { label: 'Tanpa Stop Loss', text: 'Bot tidak menggunakan stop loss. Posisi ditahan sampai target Take Profit tercapai, cocok untuk aset RWA yang cenderung naik jangka panjang.' },
            { label: 'Capital Allocation', text: 'Alokasi modal 20% per trade memberikan keseimbangan antara pertumbuhan dan perlindungan modal. Alokasi >30% akan memperbesar drawdown secara agresif.' }
        ]
    },
    silver_trend: {
        title: 'Silver Trend — EMA 10/30 Breakout',
        subtitle: 'Strategi trend-following breakout jangka menengah. Pair: XAUT/IDR · Timeframe: M15.',
        notes: [
            { label: 'EMA 10 & EMA 30 Cross', text: 'Sinyal beli muncul ketika EMA 10 memotong ke atas EMA 30 (Golden Cross). Sinyal ini lebih kuat dari EMA jangka pendek dan menghasilkan sinyal palsu yang lebih sedikit.' },
            { label: 'Trend Following', text: 'Strategi ini bekerja sangat baik di pasar yang sedang dalam tren kuat (sustained uptrend). Di pasar choppy, frekuensi false signal akan meningkat.' },
            { label: 'Timeframe M15', text: 'M15 adalah sweet-spot untuk Gold — cukup cepat untuk menangkap momentum, namun cukup lambat untuk menyaring noise harga intraday.' },
            { label: 'Take Profit 2x', text: 'TP menggunakan multiplier 2× dari jarak support EMA 30. Posisi ditahan sampai target profit tercapai tanpa stop loss.' },
            { label: 'Karakteristik Gold (XAUT)', text: 'Emas (XAUT) memiliki volatilitas yang terukur dan cenderung bergerak dalam tren. Cocok untuk strategi EMA berbasis tren menengah.' }
        ]
    },
    swap_grid: {
        title: 'Swap Grid — ATR-Based Grid Trading',
        subtitle: 'Strategi grid otomatis berbasis volatilitas ATR. Pair: PAXG/IDR · Timeframe: M30.',
        notes: [
            { label: 'Grid Trading Logic', text: 'Bot menempatkan serangkaian order beli di bawah harga pasar dan order jual di atasnya secara otomatis, mengambil profit dari pergerakan harga naik-turun (ranging market).' },
            { label: 'ATR (Average True Range)', text: 'Jarak antar level grid ditentukan secara dinamis menggunakan ATR, sehingga grid lebih rapat di pasar tenang dan lebih lebar di pasar volatile. Ini mencegah terlalu banyak order sekaligus.' },
            { label: 'Performa Terbaik di Sideways', text: 'Berbeda dengan strategi trend-following, Swap Grid justru paling menguntungkan ketika harga bergerak sideways dalam range tertentu. Tren kuat bisa menyebabkan kerugian jika harga keluar dari grid.' },
            { label: 'Modal Terdistribusi', text: 'Modal dibagi ke beberapa level grid sekaligus. Pastikan modal awal cukup besar agar grid bisa aktif di beberapa level (disarankan minimal Rp 2.000.000).' },
            { label: 'Pair PAX Gold (PAXG)', text: 'PAXG adalah token berbasis emas fisik dari Paxos yang bergerak mengikuti harga spot emas. Relatif stabil dibanding kripto lain, ideal untuk grid trading.' }
        ]
    }
};

function updateNotes(botValue) {
    const data = STRATEGY_NOTES[botValue] || STRATEGY_NOTES.alpha_gold;
    document.getElementById('notes-title').textContent   = data.title;
    document.getElementById('notes-subtitle').textContent = data.subtitle;
    document.getElementById('notes-list').innerHTML = data.notes.map(n =>
        `<div class="bullet"><div class="dot"></div><div><strong>${n.label}:</strong> ${n.text}</div></div>`
    ).join('');
}

// Hook into the bot selector
const _botSel = document.getElementById('bot_select');
if (_botSel) {
    _botSel.addEventListener('change', () => updateNotes(_botSel.value));
    updateNotes(_botSel.value); // init
}
</script>

<?php if(isset($result['trades']) && count($result['trades']) > 0): ?>
<div class="panel" style="margin-top: 18px;">
    <div class="panel-head">
        <div>
            <h3>Completed Trades Log</h3>
            <p>Daftar lengkap transaksi buy &amp; sell yang disimulasikan — <strong style="color:#33e818;"><?= count($result['trades']) ?> trades</strong> total.</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#9ca3af;">
            <span id="pag-info"></span>
        </div>
    </div>
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table id="trades-table" style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; white-space: nowrap;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #9ca3af;">
                    <th style="padding: 12px 8px;">#</th>
                    <th style="padding: 12px 8px;">Exit Date/Time</th>
                    <th style="padding: 12px 8px;">Trigger Type</th>
                    <th style="padding: 12px 8px;">Entry Price</th>
                    <th style="padding: 12px 8px;">Exit Price</th>
                    <th style="padding: 12px 8px;">Amount</th>
                    <th style="padding: 12px 8px; text-align: right;">PnL (IDR)</th>
                    <th style="padding: 12px 8px; text-align: right;">Return %</th>
                </tr>
            </thead>
            <tbody id="trades-body"></tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:14px 0 0;border-top:1px solid rgba(255,255,255,0.05);margin-top:12px;">
        <div id="pag-info2" style="font-size:12px;color:#9ca3af;"></div>
        <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;" id="pag-buttons"></div>
    </div>
</div>

<script>
(function() {
    const TRADES = <?= json_encode(array_values($result['trades'])) ?>;
    const PER_PAGE = 20;
    let currentPage = 1;
    const totalPages = Math.ceil(TRADES.length / PER_PAGE);

    function fmtDate(unix) {
        const d = new Date(unix * 1000);
        return d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
             + ' ' + d.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
    }
    function fmtNum(n) {
        return Number(n).toLocaleString('id-ID');
    }

    function renderTable(page) {
        const start = (page - 1) * PER_PAGE;
        const end   = Math.min(start + PER_PAGE, TRADES.length);
        const slice = TRADES.slice(start, end);
        const tbody = document.getElementById('trades-body');

        tbody.innerHTML = slice.map((t, idx) => {
            const globalIdx = start + idx + 1;
            const pnlColor   = t.pnl   >= 0 ? '#33e818' : '#ef4444';
            const pctColor   = t.pnlPercent >= 0 ? '#33e818' : '#ef4444';
            const badgeCls   = t.result === 'win' ? 'win' : 'loss';
            const pnlSign    = t.pnl >= 0 ? '+' : '';
            const pctSign    = t.pnlPercent >= 0 ? '+' : '';
            return `<tr style="border-bottom:1px solid rgba(255,255,255,0.03);">
                <td style="padding:12px 8px;color:#9ca3af;">${globalIdx}</td>
                <td style="padding:12px 8px;color:#9ca3af;">${fmtDate(t.exitTime)}</td>
                <td style="padding:12px 8px;"><span class="badge ${badgeCls}">${t.type}</span></td>
                <td style="padding:12px 8px;font-weight:600;">Rp ${fmtNum(t.entryPrice)}</td>
                <td style="padding:12px 8px;font-weight:600;">Rp ${fmtNum(t.exitPrice)}</td>
                <td style="padding:12px 8px;">${Number(t.amount).toFixed(4)}</td>
                <td style="padding:12px 8px;text-align:right;font-weight:700;color:${pnlColor};">${pnlSign}Rp ${fmtNum(t.pnl)}</td>
                <td style="padding:12px 8px;text-align:right;font-weight:700;color:${pctColor};">${pctSign}${t.pnlPercent}%</td>
            </tr>`;
        }).join('');

        // Update info text
        const info = `Menampilkan ${start + 1}–${end} dari ${TRADES.length} trades`;
        document.getElementById('pag-info').textContent  = info;
        document.getElementById('pag-info2').textContent = info;

        renderPagination(page);
    }

    function renderPagination(page) {
        const container = document.getElementById('pag-buttons');
        const btnBase = 'padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.05);color:#fff;';
        const btnActive = 'padding:6px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid #33e818;background:#33e818;color:#000;';

        let html = `<button onclick="goPage(${page - 1})" ${page <= 1 ? 'disabled' : ''} style="${btnBase}opacity:${page <= 1 ? '0.3' : '1'};">‹ Prev</button>`;

        // Window of pages: show up to 5 page buttons
        const maxButtons = 5;
        let startP = Math.max(1, page - Math.floor(maxButtons / 2));
        let endP   = Math.min(totalPages, startP + maxButtons - 1);
        if (endP - startP < maxButtons - 1) startP = Math.max(1, endP - maxButtons + 1);

        if (startP > 1) html += `<span style="color:#9ca3af;padding:6px 4px;">...</span>`;
        for (let p = startP; p <= endP; p++) {
            html += `<button onclick="goPage(${p})" style="${p === page ? btnActive : btnBase}">${p}</button>`;
        }
        if (endP < totalPages) html += `<span style="color:#9ca3af;padding:6px 4px;">...</span>`;

        html += `<button onclick="goPage(${page + 1})" ${page >= totalPages ? 'disabled' : ''} style="${btnBase}opacity:${page >= totalPages ? '0.3' : '1'};">Next ›</button>`;

        container.innerHTML = html;
    }

    window.goPage = function(p) {
        if (p < 1 || p > totalPages) return;
        currentPage = p;
        renderTable(p);
        document.getElementById('trades-table').scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // Initial render
    renderTable(1);
})();
</script>
<?php endif; ?>

<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner-box">
        <div class="spin-ring-1"></div>
        <div class="spin-ring-2"></div>
        <div class="spin-ring-3"></div>
        <div class="loader-brand">AIWE RWA</div>
    </div>
    <h3 class="loader-title">Running Backtest Simulation</h3>
    <p id="loadingStatusText" class="loader-subtitle">Preparing historical market data...</p>
    <div class="progress-bar-container">
        <div id="loadingProgressBar" class="progress-bar-fill"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form        = document.getElementById('backtestForm');
    const overlay     = document.getElementById('loadingOverlay');
    const statusText  = document.getElementById('loadingStatusText');
    const progressBar = document.getElementById('loadingProgressBar');
    const botSelect   = document.getElementById('bot_select');

    // ── Per-strategy loading message sets ──────────────────────────────────
    const STRATEGY_MESSAGES = {
        alpha_gold: [
            { text: 'Menghubungkan ke Trade Engine...', progress: '10%' },
            { text: 'Mengambil data candle historis SLVON/IDR (M5)...', progress: '25%' },
            { text: 'Menghitung EMA 9 & EMA 21...', progress: '45%' },
            { text: 'Menghitung VWAP 20-period untuk titik pullback...', progress: '60%' },
            { text: 'Mendeteksi sinyal entry: EMA Cross + VWAP Pullback...', progress: '75%' },
            { text: 'Mensimulasikan Take Profit (1.75× swing distance)...', progress: '88%' },
            { text: 'Menyusun equity curve & laporan performa...', progress: '97%' },
        ],
        silver_trend: [
            { text: 'Menghubungkan ke Trade Engine...', progress: '10%' },
            { text: 'Mengambil data candle historis XAUT/IDR (M15) dari Binance...', progress: '25%' },
            { text: 'Menghitung EMA 10 & EMA 30...', progress: '45%' },
            { text: 'Mendeteksi Golden Cross (EMA10 × EMA30)...', progress: '62%' },
            { text: 'Mendeteksi Dead Cross untuk exit sinyal...', progress: '76%' },
            { text: 'Mensimulasikan Take Profit (2× support distance)...', progress: '88%' },
            { text: 'Menyusun equity curve & laporan performa...', progress: '97%' },
        ],
        swap_grid: [
            { text: 'Menghubungkan ke Trade Engine...', progress: '10%' },
            { text: 'Mengambil data candle historis PAXG/IDR (M30) dari Binance...', progress: '25%' },
            { text: 'Menghitung ATR-14 untuk menentukan grid spacing...', progress: '42%' },
            { text: 'Membangun level grid di bawah harga pasar...', progress: '58%' },
            { text: 'Mensimulasikan eksekusi buy di setiap level grid...', progress: '72%' },
            { text: 'Mensimulasikan grid sell (+1 step) & grid reset...', progress: '86%' },
            { text: 'Menyusun equity curve & laporan performa...', progress: '97%' },
        ],
    };

    if (form) {
        form.addEventListener('submit', function() {
            overlay.style.display = 'flex';

            const botValue = botSelect ? botSelect.value : 'alpha_gold';
            const messages = STRATEGY_MESSAGES[botValue] || STRATEGY_MESSAGES.alpha_gold;

            let index = 0;
            progressBar.style.width = '5%';

            // Show first message immediately
            statusText.textContent  = messages[0].text;
            progressBar.style.width = messages[0].progress;
            index = 1;

            const interval = setInterval(function() {
                if (index < messages.length) {
                    statusText.textContent  = messages[index].text;
                    progressBar.style.width = messages[index].progress;
                    index++;
                } else {
                    clearInterval(interval);
                }
            }, 1400);
        });
    }
});


<?php if(isset($result['equityCurve']) && count($result['equityCurve']) > 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('equityChart').getContext('2d');
    const data = <?= json_encode($result['equityCurve']) ?>;
    
    const labels = data.map(d => {
        const date = new Date(d.time * 1000);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit' });
    });
    const balances = data.map(d => d.balance);

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(124, 255, 0, 0.15)');
    gradient.addColorStop(1, 'rgba(124, 255, 0, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Account Equity (IDR)',
                data: balances,
                borderColor: '#7CFF00',
                borderWidth: 2,
                backgroundColor: gradient,
                fill: true,
                tension: 0.2,
                pointRadius: 0,
                pointHoverRadius: 4,
                pointBackgroundColor: '#7CFF00'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: 'rgba(255, 255, 255, 0.5)', maxTicksLimit: 8 }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { 
                        color: 'rgba(255, 255, 255, 0.5)',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>
