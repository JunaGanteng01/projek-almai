<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Dashboard'; $pageSubtitle = 'Selamat datang kembali'; ?>

<!-- Welcome Text -->
<div class="mb-4">
    <p class="text-gray-400 text-xs mb-0.5">Selamat datang kembali,</p>
    <h1 class="text-lg font-bold text-white"><?= esc(session()->get('wpaName') ?? session()->get('userName')) ?></h1>
</div>

<!-- Main Wallet Card -->
<div class="relative w-full bg-gradient-to-br from-[#0a3d1f] via-[#0c4a24] to-[#0a3d1f] rounded-2xl p-4 mb-4 overflow-hidden shadow-xl border border-[#1a5c32]">
    <!-- Background Pattern -->
    <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-2 right-2 w-24 h-24 bg-accent/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-accent/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Wallet Illustration -->
    <div class="absolute top-0 right-0 opacity-20 pointer-events-none">
        <img src="<?= base_url('images/dompet.png') ?>" alt="Wallet" class="w-32 h-32 object-contain">
    </div>

    <!-- Total Saldo Section -->
    <div class="relative z-10 mb-4">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-gray-300 text-xs">Total Saldo</span>
            <button onclick="toggleBalanceVisibility()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-eye text-xs" id="balanceIcon"></i>
            </button>
        </div>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-white tracking-tight" id="balanceDisplay">
                Rp <?= number_format($stats['availableBalance'] ?? 0, 0, ',', '.') ?>
            </h2>
            <h2 class="text-2xl font-bold text-white tracking-tight hidden" id="balanceHidden">Rp ••••••</h2>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="relative z-10 grid grid-cols-3 md:flex md:flex-wrap gap-2 mb-4">
        <a href="<?= base_url('wpa/dashboard/withdraw') ?>" class="flex items-center justify-center gap-1.5 bg-accent hover:bg-accent/90 text-black font-bold text-xs py-2 md:px-4 rounded-lg transition shadow-lg">
            <i class="fas fa-arrow-up text-sm"></i>
            <span>Tarik Dana</span>
        </a>
        <button onclick="performCheckin()" id="btnDailyCheckin" <?= !$canCheckin ? 'disabled' : '' ?> class="flex items-center justify-center gap-1.5 <?= $canCheckin ? 'bg-white/10 hover:bg-white/20' : 'bg-white/5 cursor-not-allowed opacity-50' ?> text-white font-bold text-xs py-2 md:px-4 rounded-lg transition border border-white/20">
            <i class="fas fa-plus text-sm"></i>
            <span><?= $canCheckin ? 'Checkin' : 'Sudah Checkin' ?></span>
        </button>
        <a href="<?= base_url('wpa/dashboard/absen') ?>" class="relative flex items-center justify-center gap-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 font-bold text-xs py-2 md:px-4 rounded-lg transition border border-blue-500/30">
            <i class="fas fa-calendar-check text-sm"></i>
            <span>Event</span>
            <?php if (isset($pendingEventAbsenCount) && $pendingEventAbsenCount > 0): ?>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-gray-900"><?= $pendingEventAbsenCount ?></span>
            <?php endif; ?>
        </a>
    </div>

    <!-- Almai Poin Section -->
    <a href="<?= base_url('wpa/dashboard/poin') ?>" class="relative z-10 flex items-center justify-between bg-black/20 hover:bg-black/30 backdrop-blur-sm rounded-xl p-3 transition border border-white/10 group">
        <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 bg-gradient-to-br from-[#FFD700] to-[#FFA500] rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-coins text-white text-base"></i>
            </div>
            <div>
                <p class="text-gray-300 text-xs">Almai Poin</p>
                <p class="text-white text-base font-bold"><?= $stats['totalPoin'] ?? 0 ?></p>
            </div>
        </div>
        <i class="fas fa-chevron-right text-xs text-gray-400 group-hover:text-white transition"></i>
    </a>
</div>

<!-- Quick Actions Grid -->
<div class="grid grid-cols-3 gap-3 mb-6">
    <!-- Sistem Referral -->
    <a href="<?= base_url('wpa/dashboard/referral') ?>" class="bg-[#111] hover:bg-[#1a1a1a] border border-white/10 rounded-xl p-4 transition group flex flex-col items-center text-center">
        <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
            <i class="fas fa-gift text-accent text-xl"></i>
        </div>
        <p class="text-white text-sm font-semibold mb-0.5">Sistem</p>
        <p class="text-gray-400 text-xs">Referral</p>
    </a>

    <!-- Tukar Merchandise -->
    <a href="<?= base_url('wpa/dashboard/poin/merchandise') ?>" class="bg-[#111] hover:bg-[#1a1a1a] border border-white/10 rounded-xl p-4 transition group flex flex-col items-center text-center">
        <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
            <i class="fas fa-exchange-alt text-accent text-xl"></i>
        </div>
        <p class="text-white text-sm font-semibold mb-0.5">Tukar</p>
        <p class="text-gray-400 text-xs">Merchandise</p>
    </a>

    <!-- Bagi Point -->
    <a href="<?= base_url('wpa/dashboard/poin/share') ?>" class="bg-[#111] hover:bg-[#1a1a1a] border border-white/10 rounded-xl p-4 transition group flex flex-col items-center text-center">
        <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
            <i class="fas fa-share-nodes text-accent text-xl"></i>
        </div>
        <p class="text-white text-sm font-semibold mb-0.5">Bagi</p>
        <p class="text-gray-400 text-xs">Point</p>
    </a>
</div>

<!-- Pantau Performa Section -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <div>
            <h3 class="text-base font-bold text-white">Pantau Performa</h3>
            <p class="text-gray-400 text-[10px]">Ringkasan aktivitas layanan Anda</p>
        </div>
        <div class="flex items-center gap-1.5 bg-[#111] border border-white/10 rounded-lg px-3 py-1.5">
            <i class="fas fa-calendar text-accent text-xs"></i>
            <span class="text-white text-xs font-medium">Bulan Ini</span>
            <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-3 mb-4">
        <!-- Layanan Aktif -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-3">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-orange-500 text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white mb-1"><?= number_format($stats['totalKelas'] ?? 0) ?></p>
            <p class="text-gray-400 text-xs mb-0.5">Layanan Aktif</p>
            <p class="text-orange-500 text-[10px] font-medium">0% dari bulan lalu</p>
        </div>

        <!-- User Terdaftar -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-3">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white mb-1"><?= number_format($stats['totalStudents'] ?? 0) ?></p>
            <p class="text-gray-400 text-xs mb-0.5">User Terdaftar</p>
            <p class="text-blue-500 text-[10px] font-medium">0% dari bulan lalu</p>
        </div>
    </div>

    <!-- Stats Grid Row 2 -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Transaksi -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-3">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-green-500 text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white mb-1"><?= count($recentTransactions ?? []) ?></p>
            <p class="text-gray-400 text-xs mb-0.5">Transaksi</p>
            <p class="text-green-500 text-[10px] font-medium">0% dari bulan lalu</p>
        </div>

        <!-- Event Mendatang -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-3">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-purple-500 text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white mb-1">10</p>
            <p class="text-gray-400 text-xs mb-0.5">Event Mendatang</p>
            <p class="text-purple-500 text-[10px] font-medium">↑ 10% dari bulan lalu</p>
        </div>
    </div>
</div>

<!-- Portfolio Saya Card -->
<a href="<?= base_url('wpa/dashboard/portofolio') ?>" class="block bg-gradient-to-br from-[#0a3d1f] to-[#0c4a24] rounded-xl p-4 mb-6 border border-[#1a5c32] hover:border-accent/50 transition group relative overflow-hidden">
    <!-- Background Image -->

    
    <div class="flex items-center justify-between relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
        <img src="<?= base_url('images/porto.png') ?>" alt="Portfolio" class="w-24 h-24 object-contain">
            </div>
            <div>
                <h3 class="text-white text-sm font-bold mb-0.5">Portfolio Saya</h3>
                <p class="text-gray-300 text-xs">Buat rekam jejak performa trading anda</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="text-accent text-xs font-bold group-hover:mr-1 transition-all">Kelola Portfolio</span>
            <i class="fas fa-chevron-right text-accent text-xs"></i>
        </div>
    </div>
</a>

<!-- Event & Calendar Section (Tabbed) -->
<div class="mb-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-base font-bold text-white">Event & Jadwal</h3>
            <p class="text-[10px] text-gray-400">Daftar event dan jadwal rutin Anda</p>
        </div>
    </div>

    <!-- Tab Buttons -->
    <div class="flex border-b border-white/10 mb-4 relative">
        <button onclick="switchTab('calendar')" id="tab-calendar" class="flex-1 pb-2 text-xs font-medium border-b-2 border-accent text-accent transition-all duration-300">
            Kalender & Jadwal
        </button>
        <button onclick="switchTab('list')" id="tab-list" class="flex-1 pb-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-300 transition-all duration-300">
            Event Mendatang
        </button>
    </div>

    <!-- Calendar View (Tab Content) -->
    <div id="content-calendar" class="transition-all duration-300">
        <div class="bg-[#121212] border border-white/5 rounded-xl p-4">
            <div class="flex items-center justify-center gap-4 mb-4">
                <button onclick="changeMonth(-1)" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white transition">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <h4 class="font-bold text-sm text-white tracking-wide uppercase" id="calendarMonth"><?= date('F Y') ?></h4>
                <button onclick="changeMonth(1)" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white transition">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-3">
                <?php foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day): ?>
                    <div class="text-center text-[9px] uppercase tracking-wider text-gray-500 font-medium"><?= $day ?></div>
                <?php endforeach; ?>
            </div>
            <div class="grid grid-cols-7 gap-1" id="calendarGrid"></div>
        </div>
    </div>

    <!-- List View (Tab Content) -->
    <div id="content-list" class="hidden transition-all duration-300">
        <?php
        $eventsByDate = [];
        $now = time();
        $lookAheadDays = 14; // Look ahead 2 weeks

        for ($i = 0; $i < $lookAheadDays; $i++) {
            $currentTime = strtotime("+$i day", $now);
            $dateKey = date('Y-m-d', $currentTime);
            $dayName = date('l', $currentTime);
            $dayOfMonth = (int)date('j', $currentTime);

            foreach ($allEvents ?? [] as $event) {
                $isMatch = false;
                $displayTime = !empty($event['recurring_time']) ? $event['recurring_time'] : (!empty($event['event_date']) ? date('H:i', strtotime($event['event_date'])) : '00:00');

                // Direct match
                if (!empty($event['event_date']) && date('Y-m-d', strtotime($event['event_date'])) === $dateKey) {
                    $isMatch = true;
                } 
                // Recurring match
                elseif (!empty($event['is_recurring']) && $event['is_recurring'] == 1) {
                    if (!empty($event['recurring_frequency'])) {
                        if ($event['recurring_frequency'] === 'daily') {
                            $isMatch = true;
                        } elseif ($event['recurring_frequency'] === 'weekly' && !empty($event['recurring_day'])) {
                            // Convert Indonesian day to English
                            $dayMap = [
                                'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                                'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
                            ];
                            $recurringDay = $dayMap[$event['recurring_day']] ?? $event['recurring_day'];
                            if ($recurringDay === $dayName) {
                                $isMatch = true;
                            }
                        } elseif ($event['recurring_frequency'] === 'monthly' && !empty($event['recurring_day'])) {
                            if ((int)$event['recurring_day'] === $dayOfMonth) {
                                $isMatch = true;
                            }
                        }
                    }
                }

                if ($isMatch) {
                    $eventsByDate[$dateKey]['date_formatted'] = date('d F, Y', $currentTime);
                    $eventsByDate[$dateKey]['day_name'] = [
                        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                    ][$dayName];
                    
                    $eventCopy = $event;
                    $eventCopy['display_time'] = $displayTime;
                    $eventsByDate[$dateKey]['events'][] = $eventCopy;
                }
            }
        }
        ksort($eventsByDate);
        ?>

        <?php if (empty($eventsByDate)): ?>
            <div class="text-center py-8 bg-[#121212] rounded-xl border border-white/5">
                <i class="fas fa-calendar-times text-gray-600 text-2xl mb-2"></i>
                <p class="text-gray-500 text-xs">Belum ada event mendatang.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($eventsByDate as $dateKey => $group): ?>
                    <div class="overflow-hidden rounded-xl border border-white/10 bg-[#121212]" data-date="<?= $dateKey ?>">
                        <div class="bg-accent px-3 py-1.5 flex justify-between items-center">
                            <span class="text-black font-bold text-xs"><?= $group['day_name'] ?></span>
                            <span class="text-black font-medium text-[10px]"><?= $group['date_formatted'] ?></span>
                        </div>
                        <div class="divide-y divide-white/5">
                            <?php foreach ($group['events'] as $event): ?>
                                <div class="p-3 hover:bg-white/5 transition group">
                                    <div class="flex gap-3">
                                        <div class="w-20 shrink-0 text-[9px] text-gray-400 pt-0.5">
                                            <?= $event['display_time'] ?> WIB
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start gap-2 mb-1">
                                                <div class="w-1 h-1 rounded-full bg-accent mt-1.5 shrink-0"></div>
                                                <h4 class="text-xs font-bold text-white leading-tight transition">
                                                    <?= esc($event['title']) ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    #calendarGrid .calendar-cell {
        height: 60px; display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: 4px; cursor: pointer; position: relative; font-size: 10px; transition: all 0.2s;
    }
    #calendarGrid .calendar-cell:hover:not(.empty) { background-color: rgba(255, 255, 255, 0.05); }
    #calendarGrid .calendar-cell.today { background-color: rgba(51, 232, 24, 0.2); color: #33e818; }
    #calendarGrid .calendar-cell.active-event { background-color: #33e818 !important; color: #000 !important; font-weight: bold; }
    #calendarGrid .event-label { font-size: 7px; font-weight: normal; margin-top: 1px; display: none; }
    #calendarGrid .calendar-cell.active-event .event-label { display: block; }
</style>

<script>
    let isBalanceVisible = true;
    function toggleBalanceVisibility() {
        const display = document.getElementById('balanceDisplay');
        const hidden = document.getElementById('balanceHidden');
        const icon = document.getElementById('balanceIcon');
        isBalanceVisible = !isBalanceVisible;
        if (isBalanceVisible) {
            display.classList.remove('hidden');
            hidden.classList.add('hidden');
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            display.classList.add('hidden');
            hidden.classList.remove('hidden');
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }

    // Checkin Function
    function doCheckin() {
        const btn = document.getElementById('checkinBtn');
        if (btn.disabled) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i> <span>Processing...</span>';

        fetch('<?= base_url('wpa/dashboard/poin/checkin') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Checkin Berhasil!',
                    text: data.message || 'Anda mendapat <?= $poinDailyCheckin ?> poin!',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#ef4444'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus text-sm"></i> <span>Checkin</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan koneksi',
                confirmButtonColor: '#ef4444'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus text-sm"></i> <span>Checkin</span>';
        });
    }

    // Calendar & Event Functions
    let currentCalendarMonth = <?= date('n') ?>;
    let currentCalendarYear = <?= date('Y') ?>;
    const eventDates = <?= json_encode($allEvents ?? []) ?> || [];
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function changeMonth(delta) {
        currentCalendarMonth += delta;
        if (currentCalendarMonth > 12) { currentCalendarMonth = 1; currentCalendarYear++; }
        else if (currentCalendarMonth < 1) { currentCalendarMonth = 12; currentCalendarYear--; }
        renderCalendar();
    }

    function switchTab(tab) {
        const calContent = document.getElementById('content-calendar');
        const listContent = document.getElementById('content-list');
        const calTab = document.getElementById('tab-calendar');
        const listTab = document.getElementById('tab-list');
        if (tab === 'calendar') {
            calContent.classList.remove('hidden'); listContent.classList.add('hidden');
            calTab.classList.replace('border-transparent', 'border-accent'); calTab.classList.replace('text-gray-500', 'text-accent');
            listTab.classList.replace('border-accent', 'border-transparent'); listTab.classList.replace('text-accent', 'text-gray-500');
        } else {
            calContent.classList.add('hidden'); listContent.classList.remove('hidden');
            calTab.classList.replace('border-accent', 'border-transparent'); calTab.classList.replace('text-accent', 'text-gray-500');
            listTab.classList.replace('border-transparent', 'border-accent'); listTab.classList.replace('text-gray-500', 'text-accent');
        }
    }

    function renderCalendar() {
        const grid = document.getElementById('calendarGrid');
        const monthLabel = document.getElementById('calendarMonth');
        monthLabel.textContent = monthNames[currentCalendarMonth - 1] + ' ' + currentCalendarYear;

        const firstDay = new Date(currentCalendarYear, currentCalendarMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentCalendarYear, currentCalendarMonth, 0).getDate();
        const today = new Date();
        const isCurrentMonth = today.getMonth() + 1 === currentCalendarMonth && today.getFullYear() === currentCalendarYear;

        // Day mapping for Indonesian recurring events
        const dayMap = {
            'Senin': 'Monday', 'Selasa': 'Tuesday', 'Rabu': 'Wednesday',
            'Kamis': 'Thursday', 'Jumat': 'Friday', 'Sabtu': 'Saturday', 'Minggu': 'Sunday'
        };

        grid.innerHTML = '';
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'calendar-cell empty';
            grid.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const cell = document.createElement('div');
            cell.className = 'calendar-cell';
            cell.textContent = day;

            const dateStr = `${currentCalendarYear}-${String(currentCalendarMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayOfWeek = new Date(currentCalendarYear, currentCalendarMonth - 1, day).toLocaleDateString('en-US', { weekday: 'long' });
            
            const hasEvent = eventDates.some(e => {
                // Direct date match
                if (e.event_date && e.event_date.startsWith(dateStr)) return true;
                
                // Recurring event match
                if (e.is_recurring == 1 && e.recurring_frequency) {
                    if (e.recurring_frequency === 'daily') return true;
                    
                    if (e.recurring_frequency === 'weekly' && e.recurring_day) {
                        // Convert Indonesian day to English if needed
                        const recurringDay = dayMap[e.recurring_day] || e.recurring_day;
                        if (recurringDay === dayOfWeek) return true;
                    }
                    
                    if (e.recurring_frequency === 'monthly' && e.recurring_day) {
                        if (parseInt(e.recurring_day) === day) return true;
                    }
                }
                return false;
            });

            if (hasEvent) {
                cell.classList.add('active-event');
                const label = document.createElement('div');
                label.className = 'event-label';
                label.textContent = 'Event';
                cell.appendChild(label);
            }

            if (isCurrentMonth && day === today.getDate()) {
                cell.classList.add('today');
            }

            grid.appendChild(cell);
        }
    }

    // Initialize calendar on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderCalendar();
    });
</script>
<?= $this->endSection() ?>
