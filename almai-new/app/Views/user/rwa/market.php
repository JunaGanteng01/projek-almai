<?php
/**
 * @var string $tradeMode
 * @var array $marketPairs
 * @var array $activePair
 */
?>
<?= $this->extend('user/partials/layout') ?>
<?= $this->section('styles') ?>
<style>
    :root { --primary: #33e818; --danger: #ef4444; --success: #33e818; }
    .market-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .market-page-header h4 { font-size:16px; font-weight:600; margin:0; color:#fff; }
    .mode-badge { font-size:10px; padding:3px 8px; border-radius:4px; font-weight:700; text-transform:uppercase; }
    .mode-badge.demo { background:rgba(124,255,0,0.12); color:var(--primary); }
    .mode-badge.live { background:rgba(255,50,50,0.12); color:var(--danger); }

    .pair-list { display:flex; gap:8px; overflow-x:auto; padding-bottom:8px; margin-bottom:16px; }
    .pair-card { display:flex; align-items:center; gap:10px; padding:12px 14px; background:#111; border:1px solid rgba(255,255,255,0.1); border-radius:10px; cursor:pointer; transition:all 0.2s; min-width:150px; flex-shrink:0; }
    .pair-card:hover, .pair-card.active { border-color:var(--primary); background:rgba(124,255,0,0.02); }
    .pair-card .pair-icon { width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,0.05); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .pair-card .pair-icon img { width:20px; height:20px; border-radius:50%; }
    .pair-card .pair-name { font-weight:600; font-size:12px; color:#fff; }
    .pair-card .pair-price-val { font-size:11px; color:#9ca3af; margin-top:2px; }
    .pair-card .pair-change { font-size:10px; font-weight:600; margin-top:1px; }
    .pair-card .pair-change.up { color:var(--primary); }
    .pair-card .pair-change.down { color:var(--danger); }
    .pair-unavailable { opacity:0.5; }

    .chart-container { background:#111; border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:1px; margin-bottom:20px; height:320px; display:flex; flex-direction:column; position:relative; overflow:hidden; }
    .chart-header { padding:12px 16px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; justify-content:space-between; align-items:center; z-index:2; position:relative; }
    .chart-header .chart-title { font-size:14px; font-weight:700; color:#fff; }
    .chart-header .tf-btn { cursor:pointer; padding:3px 8px; border-radius:4px; border:1px solid transparent; font-size:11px; color:#9ca3af; font-weight:600; }
    .chart-header .tf-btn.active-tf { color:var(--primary); border-color:rgba(124,255,0,0.3); font-weight:700; }
    .chart-area { flex:1; background:#0b0e14; border-radius:0 0 13px 13px; position:relative; }
    .chart-status { font-size:10px; color:#9ca3af; margin-left:8px; }

    .order-section { background:#111; border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:16px; }
    .order-section h5 { font-size:14px; font-weight:600; color:#fff; margin-bottom:12px; }
    .order-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px; }
    .order-input { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:10px 12px; color:#fff; font-size:13px; width:100%; outline:none; }
    .order-input:focus { border-color:var(--primary); }
    .order-input::placeholder { color:#6b7280; }
    .order-label { font-size:11px; color:#9ca3af; margin-bottom:4px; display:block; }
    .btn-buy { background:var(--primary); color:#000; border:none; border-radius:8px; padding:12px; font-weight:700; font-size:13px; cursor:pointer; transition:all 0.2s; width:100%; }
    .btn-buy:hover { opacity:0.9; }
    .btn-sell { background:var(--danger); color:#fff; border:none; border-radius:8px; padding:12px; font-weight:700; font-size:13px; cursor:pointer; transition:all 0.2s; width:100%; }
    .btn-sell:hover { opacity:0.9; }
    .btn-disabled { opacity:0.4; cursor:not-allowed; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .spin { animation: spin 1s linear infinite; }
    
    .watermark-wrapper { position:absolute; top:55%; left:50%; transform:translate(-50%, -50%); z-index:3; pointer-events:none; opacity:0.04; filter:grayscale(100%); width: 200px; }
    .watermark-wrapper img { width: 100%; height: auto; }
    
    @media (max-width: 768px) {
        .watermark-wrapper { width: 150px; }
        .chart-header { flex-direction: column; align-items: flex-start !important; gap: 12px; }
        .tf-container { width: 100%; overflow-x: auto; padding-bottom: 4px; }
    }
    @media (max-width: 480px) {
        .watermark-wrapper { width: 120px; }
    }
    
    .tf-container::-webkit-scrollbar { display: none; }
    .tf-container { -ms-overflow-style: none; scrollbar-width: none; max-width: 100%; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Back to Dashboard -->
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('user/dashboard/rwa') ?>" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<div class="market-page-header">
    <h4>Market</h4>
    <span class="mode-badge <?= $tradeMode ?>"><?= strtoupper($tradeMode) ?></span>
</div>

<!-- Pair Tabs -->
<div class="pair-list">
    <?php foreach($marketPairs as $i => $pair): ?>
        <?php
            $iconMap = ['PAXG/IDR' => 'PAXG', 'XAUT/IDR' => 'XAUT', 'SLVON/IDR' => 'SLVON'];
            $coin = $iconMap[$pair['symbol']] ?? 'COIN';
            $isActive = ($pair['symbol'] === ($activePair['symbol'] ?? ''));
            $isAvailable = $pair['available'] ?? false;
            $changeClass = ($pair['change_percent'] ?? 0) >= 0 ? 'up' : 'down';
            $changeSign = ($pair['change_percent'] ?? 0) >= 0 ? '+' : '';
            $apiSymbol = str_replace('/', '', $pair['symbol']);
        ?>
        <div class="pair-card <?= $isActive ? 'active' : '' ?> <?= !$isAvailable ? 'pair-unavailable' : '' ?>"
             data-symbol="<?= esc($pair['symbol']) ?>"
             data-api-symbol="<?= esc($apiSymbol) ?>"
             onclick="switchPair('<?= esc($pair['symbol']) ?>', '<?= esc($apiSymbol) ?>', this)">
            <div class="pair-icon">
                <img src="<?= base_url('images/crypto_icons/'.strtoupper($coin).'.png') ?>" alt="<?= $coin ?>" onerror="this.onerror=null; this.alt='<?= $coin ?>'; this.src='';">
            </div>
            <div>
                <div class="pair-name"><?= esc($pair['symbol']) ?></div>
                <?php if($isAvailable): ?>
                    <div class="pair-price-val" id="price-<?= $apiSymbol ?>">Rp <?= number_format($pair['price'], 0, ',', '.') ?></div>
                    <div class="pair-change <?= $changeClass ?>"><?= $changeSign ?><?= number_format($pair['change_percent'], 2) ?>%</div>
                <?php else: ?>
                    <div class="pair-price-val" style="color:#6b7280;">Unavailable</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Chart -->
<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title" id="chartTitle"><?= esc($activePair['symbol'] ?? 'PAXG/IDR') ?></div>
        <div class="tf-container" style="display:flex; align-items:center; gap:6px;">
            <span class="tf-btn" id="tf-M1" onclick="switchTimeframe('M1', this)">1M</span>
            <span class="tf-btn" id="tf-M5" onclick="switchTimeframe('M5', this)">5M</span>
            <span class="tf-btn" id="tf-M15" onclick="switchTimeframe('M15', this)">15M</span>
            <span class="tf-btn" id="tf-M30" onclick="switchTimeframe('M30', this)">30M</span>
            <span class="tf-btn active-tf" id="tf-H1" onclick="switchTimeframe('H1', this)">1H</span>
            <span class="tf-btn" id="tf-H4" onclick="switchTimeframe('H4', this)">4H</span>
            <span class="tf-btn" id="tf-D1" onclick="switchTimeframe('D1', this)">1D</span>
            <span class="chart-status" id="chartStatus" style="white-space:nowrap;">Loading...</span>
        </div>
    </div>
    <div class="chart-area" id="tv_chart_container" style="z-index:2; position:relative;"></div>
    <div class="watermark-wrapper">
        <img src="<?= base_url('images/almai-full.png') ?>" alt="ALMAI.ID">
    </div>
</div>

<!-- Market Analysis Insight -->
<div class="insight-section" style="background:#111; border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h5 style="font-size:14px; font-weight:600; color:#fff; margin:0; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Market Analysis AI - <span id="insightPairLabel"><?= esc($activePair['symbol'] ?? '-') ?></span>
        </h5>
        <div id="insightStatus" style="font-size:11px; color:#9ca3af; display:flex; align-items:center; gap:6px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-9-9"></path></svg> Analyzing...
        </div>
    </div>
    
    <div id="insightContent" style="font-size:13px; color:#9ca3af; line-height:1.6; min-height:80px; display:flex; align-items:center; justify-content:center;">
        Memproses data pasar...
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/lightweight-charts@4.1.1/dist/lightweight-charts.standalone.production.js"></script>
<script>
let chart = null;
let candlestickSeries = null;
let priceUpdateInterval = null;
let currentSymbol = '<?= esc($activePair['symbol'] ?? 'PAXG/IDR') ?>';
let currentApiSymbol = '<?= str_replace('/', '', $activePair['symbol'] ?? 'PAXGIDR') ?>';
let currentTf = 'H1';

function initChart() {
    const container = document.getElementById('tv_chart_container');
    if (!container) return;
    container.innerHTML = '';

    chart = LightweightCharts.createChart(container, {
        width: container.clientWidth,
        height: container.clientHeight,
        layout: {
            background: { type: 'solid', color: 'transparent' },
            textColor: '#9CA3AF',
            fontFamily: 'Inter, system-ui, sans-serif',
        },
        grid: {
            vertLines: { color: 'rgba(255,255,255,0.04)' },
            horzLines: { color: 'rgba(255,255,255,0.04)' },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
            vertLine: { color: 'rgba(124,255,0,0.4)', labelBackgroundColor: '#33e818' },
            horzLine: { color: 'rgba(124,255,0,0.4)', labelBackgroundColor: '#33e818' },
        },
        rightPriceScale: {
            borderColor: 'rgba(255,255,255,0.08)',
            scaleMargins: { top: 0.05, bottom: 0.05 },
        },
        timeScale: {
            borderColor: 'rgba(255,255,255,0.08)',
            timeVisible: true,
            secondsVisible: false,
        },
    });

    candlestickSeries = chart.addCandlestickSeries({
        upColor: '#00e676',
        downColor: '#ff3d71',
        borderDownColor: '#ff3d71',
        borderUpColor: '#00e676',
        wickDownColor: '#ff3d71',
        wickUpColor: '#00e676',
    });

    window.addEventListener('resize', () => {
        if (chart && container) chart.resize(container.clientWidth, container.clientHeight);
    });
}

function setStatus(msg) {
    const el = document.getElementById('chartStatus');
    if (el) el.innerText = msg;
}

function loadChartData(apiSymbol, tf) {
    setStatus('Loading...');
    if (candlestickSeries) candlestickSeries.setData([]);

    fetch('<?= base_url('user/dashboard/rwa/market/candles/') ?>' + apiSymbol + '?tf=' + tf + '&limit=200')
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                candlestickSeries.setData(data);
                chart.timeScale().fitContent();
                setStatus(data.length + ' candles');
            } else {
                setStatus('No data');
            }
            // Automatically generate insight after data loads (even if empty, to clear the loading state)
            generateInsight();
        })
        .catch(() => setStatus('Failed'));

    // Start polling live price updates every 5s
    if (priceUpdateInterval) clearInterval(priceUpdateInterval);
    priceUpdateInterval = setInterval(() => updateLivePrice(apiSymbol), 5000);
}

function updateLivePrice(apiSymbol) {
    fetch('<?= base_url('user/dashboard/rwa/market/price/') ?>' + apiSymbol)
        .then(res => res.json())
        .then(data => {
            if (!data || !data.price) return;
            const newPrice = parseFloat(data.price);
            if (!newPrice || isNaN(newPrice)) return;

            // Update the candle chart
            const now = Math.floor(Date.now() / 1000);
            const intervalSec = currentTf === 'H1' ? 3600 : (currentTf === 'H4' ? 14400 : 86400);
            const slotTime = now - (now % intervalSec);

            const dataList = candlestickSeries.data();
            if (dataList.length > 0) {
                const last = { ...dataList[dataList.length - 1] };
                if (last.time === slotTime) {
                    last.high = Math.max(last.high, newPrice);
                    last.low = Math.min(last.low, newPrice);
                    last.close = newPrice;
                    candlestickSeries.update(last);
                } else if (slotTime > last.time) {
                    candlestickSeries.update({
                        time: slotTime,
                        open: last.close,
                        high: Math.max(last.close, newPrice),
                        low: Math.min(last.close, newPrice),
                        close: newPrice,
                    });
                }
            } else {
                candlestickSeries.update({
                    time: slotTime, open: newPrice,
                    high: newPrice, low: newPrice, close: newPrice,
                });
            }

            // Update price card
            const priceEl = document.getElementById('price-' + apiSymbol);
            if (priceEl) {
                priceEl.innerText = 'Rp ' + newPrice.toLocaleString('id-ID', { maximumFractionDigits: 0 });
            }
        }).catch(() => {});
}

function switchPair(displaySymbol, apiSymbol, element) {
    currentSymbol = displaySymbol;
    currentApiSymbol = apiSymbol;
    document.getElementById('chartTitle').innerText = displaySymbol;
    const insightLabel = document.getElementById('insightPairLabel');
    if(insightLabel) insightLabel.innerText = displaySymbol;
    document.querySelectorAll('.pair-card').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    loadChartData(apiSymbol, currentTf);
}

function switchTimeframe(tf, element) {
    currentTf = tf;
    document.querySelectorAll('.tf-btn').forEach(el => el.classList.remove('active-tf'));
    element.classList.add('active-tf');
    loadChartData(currentApiSymbol, tf);
}

function generateInsight() {
    const status = document.getElementById('insightStatus');
    const content = document.getElementById('insightContent');
    
    if(status) {
        status.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-9-9"></path></svg> Analyzing...';
        status.style.display = 'flex';
    }
    content.innerHTML = '<div style="display:flex; justify-content:center; padding:20px; color:var(--primary);">Processing AI market analysis...</div>';
    
    const candles = candlestickSeries ? candlestickSeries.data().slice(-20) : [];
    
    if (candles.length === 0) {
        if(status) status.style.display = 'none';
        content.innerHTML = '<span style="color:var(--text-muted)">Data pasar belum memadai untuk melakukan analisis AI pada koin ini.</span>';
        return;
    }
    
    fetch('<?= base_url('user/dashboard/rwa/market/insight') ?>', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-Requested-With': 'XMLHttpRequest',
            '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({ symbol: currentSymbol, tf: currentTf, candles: candles })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            content.innerHTML = '<div style="display:block; text-align:left; width:100%;">' + (data.insight || '').replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<b style="color:var(--text-light)">$1</b>') + '</div>';
        } else {
            let errorMsg = data.message || (data.messages && data.messages.error) || JSON.stringify(data);
            content.innerHTML = '<span style="color:var(--danger)">Failed to generate insight: ' + errorMsg + '</span>';
        }
    })
    .catch(err => {
        content.innerHTML = '<span style="color:var(--danger)">Network error while generating insight.</span>';
    })
    .finally(() => {
        if(status) {
            status.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> <span style="color:var(--primary)">Updated</span>';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initChart();
    loadChartData(currentApiSymbol, currentTf);
});
</script>
<?= $this->endSection() ?>
