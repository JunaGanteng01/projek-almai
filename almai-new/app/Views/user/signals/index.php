<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Signal Marketplace</h1>
        <p class="text-gray-400 text-sm">Temukan setup trading terbaik dari WPA (Wakil Pialang) berlisensi.</p>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($signals)): ?>
            <div class="col-span-full bg-[#111] border border-white/10 rounded-xl p-8 text-center text-gray-500">
                Belum ada signal yang tersedia saat ini.
            </div>
        <?php else: ?>
            <?php foreach($signals as $signal): 
                $isUnlocked = in_array($signal['id'], $unlockedIds);
            ?>
                <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden flex flex-col relative group">
                    <!-- Header Card -->
                    <div class="p-5 border-b border-white/5 relative overflow-hidden">
                        <!-- Abstract background based on type -->
                        <div class="absolute inset-0 opacity-10 <?php echo $signal['type'] === 'BUY' ? 'bg-gradient-to-br from-green-500 to-transparent' : 'bg-gradient-to-br from-red-500 to-transparent' ?>"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-xl text-white"><?= esc($signal['pair']) ?></h3>
                                <p class="text-xs text-gray-400">TF: <?= esc($signal['timeframe']) ?> • by <?= esc($signal['wpa_name']) ?></p>
                            </div>
                            <?php if($signal['type'] === 'BUY'): ?>
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 font-bold rounded-lg shadow-[0_0_10px_rgba(34,197,94,0.2)]">BUY</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-red-500/20 text-red-400 font-bold rounded-lg shadow-[0_0_10px_rgba(239,68,68,0.2)]">SELL</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- AI Review / Description (Teaser) -->
                    <div class="p-5 flex-grow space-y-4">
                        <?php if(!empty($signal['ai_review'])): ?>
                            <div class="bg-[#1a1a1a] rounded-lg p-3 text-sm text-gray-300 border border-white/5 relative">
                                <div class="absolute -top-3 -left-3 bg-accent text-white w-6 h-6 flex items-center justify-center rounded-full text-xs shadow-lg">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <?= nl2br(esc($signal['ai_review'])) ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($signal['description']) && $isUnlocked): ?>
                            <div class="text-sm text-gray-400 italic">
                                "<?= esc($signal['description']) ?>"
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Area -->
                    <div class="p-5 border-t border-white/5 bg-[#0a0a0a]">
                        <?php if($isUnlocked): ?>
                            <!-- Unlocked Content -->
                            <div class="grid grid-cols-2 gap-3 text-sm text-center mb-3">
                                <div class="bg-white/5 rounded-lg py-2">
                                    <div class="text-gray-500 text-xs mb-1">Entry Price</div>
                                    <div class="font-bold text-white"><?= (float)$signal['entry_price'] ?></div>
                                </div>
                                <div class="bg-red-500/10 rounded-lg py-2">
                                    <div class="text-red-400/70 text-xs mb-1">Stop Loss</div>
                                    <div class="font-bold text-red-400"><?= (float)$signal['sl'] ?></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2 text-xs text-center">
                                <?php if($signal['tp1']): ?>
                                    <div class="bg-green-500/10 rounded-md py-1.5">
                                        <span class="text-green-500/70 block mb-0.5">TP 1</span>
                                        <span class="font-bold text-green-400"><?= (float)$signal['tp1'] ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if($signal['tp2']): ?>
                                    <div class="bg-green-500/10 rounded-md py-1.5">
                                        <span class="text-green-500/70 block mb-0.5">TP 2</span>
                                        <span class="font-bold text-green-400"><?= (float)$signal['tp2'] ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if($signal['tp3']): ?>
                                    <div class="bg-green-500/10 rounded-md py-1.5">
                                        <span class="text-green-500/70 block mb-0.5">TP 3</span>
                                        <span class="font-bold text-green-400"><?= (float)$signal['tp3'] ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <span class="text-xs font-medium text-accent"><i class="fas fa-check-circle"></i> Terbuka</span>
                            </div>

                            <?php if(!empty($signal['chart_capture'])): ?>
                            <div class="mt-4 border-t border-white/5 pt-4">
                                <div class="text-xs text-gray-400 mb-2 font-medium">Chart Capture:</div>
                                <img src="<?= base_url($signal['chart_capture']) ?>" alt="Chart" class="w-full h-auto rounded-lg border border-white/10 hover:scale-105 transition duration-300 cursor-pointer" onclick="window.open(this.src, '_blank')">
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Locked Content -->
                            <div class="relative py-4 px-2">
                                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm rounded-lg flex flex-col items-center justify-center z-10">
                                    <i class="fas fa-lock text-gray-400 mb-2 text-xl"></i>
                                    <span class="text-xs text-gray-300 font-medium">Beli untuk melihat Entry, SL & TP</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm text-center opacity-30">
                                    <div class="bg-white/5 rounded-lg py-2">
                                        <div class="text-gray-500 text-xs mb-1">Entry Price</div>
                                        <div class="font-bold text-white">***.***</div>
                                    </div>
                                    <div class="bg-red-500/10 rounded-lg py-2">
                                        <div class="text-red-400/70 text-xs mb-1">Stop Loss</div>
                                        <div class="font-bold text-red-400">***.***</div>
                                    </div>
                                </div>
                            </div>

                            <form action="<?= base_url('user/dashboard/signals/buy/'.$signal['id']) ?>" method="POST" class="mt-4 flex gap-2">
                                <?= csrf_field() ?>
                                <button type="submit" name="payment_method" value="points" class="flex-1 py-2.5 bg-accent/20 text-accent hover:bg-accent hover:text-white transition rounded-lg text-sm font-bold flex items-center justify-center gap-2" onclick="return confirm('Gunakan <?= $signal['price_points'] ?> Poin untuk signal ini?')">
                                    <i class="fas fa-coins"></i> <?= number_format($signal['price_points'], 0, ',', '.') ?> Poin
                                </button>
                                <button type="submit" name="payment_method" value="xendit" class="flex-1 py-2.5 bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white transition rounded-lg text-sm font-bold flex items-center justify-center gap-2">
                                    Rp <?= number_format($signal['price_idr'], 0, ',', '.') ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
