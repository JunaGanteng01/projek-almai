<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('wpa/dashboard/signal-center') ?>" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Buat Signal Baru</h1>
            <p class="text-gray-400 text-sm">Tambahkan setup trading dengan TradingView & Analisis AI Groq</p>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->has('errors')): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl">
            <ul class="list-disc pl-5">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-6">
        <!-- Kolom Chart & AI (Atas) -->
        <div class="space-y-6">
            <!-- TradingView Widget -->
            <div class="bg-[#111] border border-white/10 rounded-xl p-4 h-[500px]">
                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container" style="height:100%;width:100%">
                  <div id="tradingview_chart" style="height:calc(100% - 32px);width:100%"></div>
                  <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                </div>
                <!-- TradingView Widget END -->
            </div>

        <!-- Kolom Form Setup -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6">
            <form id="signalForm" action="<?= base_url('wpa/dashboard/signal-center/store') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <input type="hidden" name="chart_capture_base64" id="chart_capture_base64" value="">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Pair / Symbol</label>
                        <select name="pair" id="pair" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent">
                            <option value="XAUUSD">XAUUSD (Gold)</option>
                            <option value="EURUSD">EURUSD</option>
                            <option value="GBPUSD">GBPUSD</option>
                            <option value="USDJPY">USDJPY</option>
                            <option value="BTCUSD">BTCUSD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Timeframe</label>
                        <select name="timeframe" id="timeframe" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent">
                            <option value="M5">M5 (5 Menit)</option>
                            <option value="M15">M15 (15 Menit)</option>
                            <option value="H1" selected>H1 (1 Jam)</option>
                            <option value="H4">H4 (4 Jam)</option>
                            <option value="D1">D1 (1 Hari)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Tipe Order</label>
                        <select name="type" id="type" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent">
                            <option value="BUY">BUY</option>
                            <option value="SELL">SELL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Entry Price</label>
                        <input type="number" step="any" name="entry_price" id="entry_price" value="<?= old('entry_price') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Stop Loss (SL)</label>
                        <input type="number" step="any" name="sl" id="sl" value="<?= old('sl') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Take Profit 1 (TP1)</label>
                        <input type="number" step="any" name="tp1" id="tp1" value="<?= old('tp1') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Take Profit 2 (TP2) <span class="text-xs text-gray-500">- Opsional</span></label>
                        <input type="number" step="any" name="tp2" id="tp2" value="<?= old('tp2') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-gray-300">Take Profit 3 (TP3) <span class="text-xs text-gray-500">- Opsional</span></label>
                        <input type="number" step="any" name="tp3" id="tp3" value="<?= old('tp3') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <hr class="border-white/10 my-4">

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-gray-300">Harga (Dalam Poin)</label>
                    <input type="number" name="price_points" value="<?= old('price_points', '50') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-gray-300">Harga (Dalam IDR - Xendit)</label>
                    <input type="number" name="price_idr" value="<?= old('price_idr', '50000') ?>" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-gray-300">Catatan Tambahan (Kata-kata)</label>
                    <textarea name="description" rows="3" class="w-full bg-black border border-white/10 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-accent"><?= old('description') ?></textarea>
                </div>

                <!-- Kolom Form AI Review & CSRF pindah ke bawah tombol Publish Signal ... Oh wait, I will just keep it here -->
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-gray-300">Rekomendasi / AI Review</label>
                    <textarea name="ai_review" id="ai_review" rows="6" class="w-full bg-[#0a0a0a] border border-accent/30 rounded-lg px-4 py-2.5 text-accent font-medium focus:outline-none" placeholder="Klik tombol Generate AI Analis di bawah untuk mengisi otomatis..."><?= old('ai_review') ?></textarea>
                </div>

                <!-- AI Generator Button -->
                <div class="bg-[#111] border border-accent/20 rounded-xl p-6 text-center mt-4">
                    <h3 class="font-bold mb-2 text-accent">AI Analis</h3>
                    <p class="text-sm text-gray-400 mb-4">Gunakan AI untuk menghasilkan ulasan teknikal profesional berdasarkan parameter setup di atas.</p>
                    <button type="button" id="btnGenerateAi" class="px-6 py-3 bg-gradient-to-r from-accent to-green-600 rounded-lg text-white font-medium hover:opacity-90 transition inline-flex items-center gap-2">
                        <i class="fas fa-robot"></i> Generate AI Analis
                    </button>
                </div>

                <div class="pt-4">
                    <button type="button" onclick="submitSignal()" class="w-full py-3.5 bg-accent text-white font-bold rounded-xl hover:bg-accent/90 transition shadow-[0_0_15px_rgba(var(--color-accent-rgb),0.3)]">
                        Publish Signal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script>
    // Fungsi load TradingView Widget
    function loadData() {
        const pair = document.getElementById('pair').value;
        const tf = document.getElementById('timeframe').value;
        
        let interval = '60';
        if(tf === 'M5') interval = '5';
        if(tf === 'M15') interval = '15';
        if(tf === 'H1') interval = '60';
        if(tf === 'H4') interval = '240';
        if(tf === 'D1') interval = 'D';
        
        let tvSymbol = 'BINANCE:BTCUSDT'; // default
        if (pair === 'EURUSD') tvSymbol = 'OANDA:EURUSD';
        if (pair === 'GBPUSD') tvSymbol = 'OANDA:GBPUSD';
        if (pair === 'XAUUSD') tvSymbol = 'OANDA:XAUUSD';
        if (pair === 'USDJPY') tvSymbol = 'OANDA:USDJPY';
        if (pair === 'BTCUSD') tvSymbol = 'BINANCE:BTCUSDT';

        document.getElementById('tradingview_chart').innerHTML = '';

        new TradingView.widget({
          "autosize": true,
          "symbol": tvSymbol,
          "interval": interval,
          "timezone": "Etc/UTC",
          "theme": "dark",
          "style": "1",
          "locale": "en",
          "enable_publishing": false,
          "backgroundColor": "rgba(17, 17, 17, 1)",
          "gridColor": "rgba(255, 255, 255, 0.05)",
          "hide_top_toolbar": false,
          "hide_legend": false,
          "save_image": false,
          "container_id": "tradingview_chart"
        });
    }

    document.getElementById('pair').addEventListener('change', loadData);
    document.getElementById('timeframe').addEventListener('change', loadData);
    
    // Inisialisasi awal
    loadData();

    // Fungsi submit form
    function submitSignal() {
        const entry = document.getElementById('entry_price').value;
        const sl = document.getElementById('sl').value;
        if(!entry || !sl) {
            alert("Entry Price dan SL wajib diisi!");
            return;
        }
        
        // Screenshot iframe tidak didukung secara native tanpa proxy/html2canvas kompleks, 
        // sehingga kita lewatkan saja. Optional bisa diisi URL atau dibiarkan kosong.
        document.getElementById('chart_capture_base64').value = '';
        
        // Submit
        document.getElementById('signalForm').submit();
    }

    document.getElementById('btnGenerateAi').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.innerHTML;
        const reviewBox = document.getElementById('ai_review');
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = document.querySelector(`input[name="${csrfName}"]`).value;

        const data = {
            pair: document.getElementById('pair').value,
            timeframe: document.getElementById('timeframe').value,
            type: document.getElementById('type').value,
            entry_price: document.getElementById('entry_price').value,
            sl: document.getElementById('sl').value,
            tp1: document.getElementById('tp1').value,
            [csrfName]: csrfHash
        };

        if(!data.entry_price || !data.sl) {
            alert('Mohon isi Entry Price dan SL terlebih dahulu sebelum generate AI.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

        try {
            const response = await fetch('<?= base_url('wpa/dashboard/signal-center/generate-ai') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();
            
            if(result.success) {
                reviewBox.value = result.review;
                // Add a little highlight effect
                reviewBox.classList.add('bg-accent/10');
                setTimeout(() => reviewBox.classList.remove('bg-accent/10'), 1000);
                
                // Update CSRF token for next request if it was refreshed
                if (result.csrf_hash) {
                    document.querySelector(`input[name="${csrfName}"]`).value = result.csrf_hash;
                }
            } else {
                alert('Gagal menghasilkan review AI: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            alert('Terjadi kesalahan jaringan.');
            console.error(error);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
</script>
<?= $this->endSection() ?>
