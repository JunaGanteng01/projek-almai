<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>

<!-- FullCalendar CSS/JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<!-- FullCalendar Indonesian locale -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<style>
    /* === CALENDAR WRAPPER === */
    .cal-wrapper {
        background: #111;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 24px;
    }

    /* === FULLCALENDAR OVERRIDES === */
    .fc-theme-standard td, .fc-theme-standard th { border: none !important; background: transparent !important; }
    .fc-scrollgrid { border: none !important; background: transparent !important; }

    /* Day headers - Indonesian */
    .fc-col-header-cell-cushion {
        color: #6b7280;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 8px 0 !important;
        text-decoration: none !important;
    }
    /* Sunday header - red */
    .fc-day-sun .fc-col-header-cell-cushion { color: #ef4444; }

    /* Date numbers - FORCE override locale styles */
    .fc-daygrid-day-number,
    a.fc-daygrid-day-number {
        color: #6b7280 !important;
        text-decoration: none !important;
        padding: 6px 8px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        width: 100%;
        text-align: right;
        display: block;
    }
    /* Sunday dates red */
    .fc-day-sun .fc-daygrid-day-number,
    .fc-day-sun a.fc-daygrid-day-number { color: #ef4444 !important; }
    /* Other-month dates barely visible */
    .fc-day-other .fc-daygrid-day-number,
    .fc-day-other a.fc-daygrid-day-number { color: #282828 !important; }
    .fc-day-other.fc-day-sun .fc-daygrid-day-number,
    .fc-day-other.fc-day-sun a.fc-daygrid-day-number { color: rgba(239,68,68,0.15) !important; }
    /* Today date green */
    .fc-day-today .fc-daygrid-day-number,
    .fc-day-today a.fc-daygrid-day-number { color: #33e818 !important; font-weight: 700 !important; }

    .fc-daygrid-day-frame {
        background-color: #161616;
        border: 1px solid rgba(255,255,255,0.03);
        border-radius: 12px;
        margin: 2px;
        min-height: 76px !important;
        display: flex;
        flex-direction: column;
        transition: background 0.15s ease;
        cursor: pointer;
    }
    .fc-daygrid-day-frame:hover { background-color: rgba(255,255,255,0.06); }
    
    /* Remove box for dates in previous/next months */
    .fc-day-other .fc-daygrid-day-frame {
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
        cursor: default;
    }
    .fc-day-other .fc-daygrid-day-frame:hover {
        background-color: transparent !important;
    }

    .fc-day-today .fc-daygrid-day-frame {
        background-color: rgba(51, 232, 24, 0.05) !important;
        border: 1px solid rgba(51, 232, 24, 0.3) !important;
        box-shadow: 0 0 10px rgba(51, 232, 24, 0.05) inset;
    }
    .fc-day-today .fc-daygrid-day-number {
        color: #33e818 !important;
        font-weight: 700;
    }
    .fc-day-today.fc-day-sun .fc-daygrid-day-number { color: #33e818 !important; }

    /* Custom toolbar used */

    /* Dot events - list-item style */
    .fc-daygrid-dot-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .fc-daygrid-dot-event:hover { background: transparent !important; }
    .fc-event-title, .fc-event-time, .fc-daygrid-dot-event .fc-event-title { display: none !important; }
    .fc-daygrid-event-dot {
        border-width: 5px !important;
        border-radius: 50% !important;
        margin: 0 2px !important;
        display: inline-block !important;
    }
    .fc-daygrid-event-harness {
        display: inline-block !important;
        margin: 0 1px !important;
    }
    .fc-daygrid-day-events {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2px;
        margin: auto 2px 6px 2px;
        min-height: 16px;
    }

    /* === EVENT CARD === */
    .event-card {
        background: #1a1a1a;
        border-radius: 14px;
        padding: 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .event-card:hover { background: #1f1f1f; }
    .event-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    /* === QUICK ADD BAR === */
    .quick-add-bar {
        background: #111;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 12px;
        width: 100%;
    }
    .quick-add-info { flex-shrink: 0; min-width: 100px; }
    .quick-add-buttons { display: flex; gap: 8px; flex: 1; flex-wrap: wrap; }
    .quick-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
        white-space: nowrap;
        flex: 1;
        justify-content: center;
    }
    .quick-add-btn:hover { opacity: 0.85; }

    /* === SCHEDULE PANEL === */
    .schedule-panel {
        background: #111;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 24px;
        height: fit-content;
    }
    .filter-chip {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
    }
    .filter-chip.active { background: #33e818; color: #fff; }
    .filter-chip:not(.active) { background: rgba(255,255,255,0.06); color: #9ca3af; }
    .filter-chip:not(.active):hover { background: rgba(255,255,255,0.1); color: #fff; }
    .tambah-btn {
        width: 100%;
        background: rgba(51, 232, 24, 0.08);
        border: 1px solid rgba(51, 232, 24, 0.3);
        border-radius: 14px;
        padding: 13px;
        color: #33e818;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
    }
    .tambah-btn:hover { background: rgba(51, 232, 24, 0.15); border-color: rgba(51, 232, 24, 0.5); }
</style>

<!-- Page Header is handled by layout -->

<div class="w-full flex flex-col gap-6">
    <!-- Quick Add Bar (full width top) -->
    <div class="quick-add-bar">
        <div class="quick-add-info">
            <div class="flex items-center gap-1.5 mb-0.5">
                <i class="fas fa-bolt text-yellow-400 text-xs"></i>
                <span class="text-white font-semibold text-sm">Quick Add</span>
            </div>
            <p class="text-gray-500 text-xs leading-tight">Buat acara baru dengan cepat</p>
        </div>
        <div class="quick-add-buttons">
            <a href="<?= site_url('ceo/quick-actions/create-meeting') ?>" class="quick-add-btn" style="background:rgba(59,130,246,0.15);color:#60a5fa;">
                <i class="fas fa-users text-xs"></i> Meeting
            </a>
            <a href="<?= site_url('ceo/quick-actions/create-task') ?>" class="quick-add-btn" style="background:rgba(16,185,129,0.15);color:#34d399;">
                <i class="fas fa-clipboard-check text-xs"></i> Task/Deadline
            </a>
            <a href="<?= site_url('ceo/quick-actions/create-reminder') ?>" class="quick-add-btn" style="background:rgba(245,158,11,0.15);color:#fbbf24;">
                <i class="fas fa-bell text-xs"></i> Reminder
            </a>
            <a href="<?= site_url('ceo/dashboard#finance') ?>" class="quick-add-btn" style="background:rgba(168,85,247,0.15);color:#c084fc;">
                <i class="fas fa-file-invoice-dollar text-xs"></i> Tagihan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Kalender -->
        <div class="md:col-span-2 flex flex-col gap-6">
            <div class="cal-wrapper">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex-1">Jadwal Hari Ini & Kalender</h3>
                    <div class="flex gap-2">
                        <button id="prevBtn" class="w-8 h-8 rounded bg-white/5 hover:bg-white/10 text-white flex items-center justify-center border border-white/10 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                        <div id="calTitle" class="px-4 py-1.5 rounded bg-white/5 text-white font-semibold text-sm border border-white/10 flex items-center">Juli 2026</div>
                        <button id="nextBtn" class="w-8 h-8 rounded bg-white/5 hover:bg-white/10 text-white flex items-center justify-center border border-white/10 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
                        <button id="todayBtn" class="px-4 py-1.5 rounded bg-[#33e818] text-white font-semibold text-sm border border-[#33e818] transition-colors hover:bg-[#2ebd15]">Today</button>
                    </div>
                </div>
                <div id='calendar'></div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-5 justify-center border-t border-white/5 pt-5 mt-1">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#3b82f6"></span>
                    <span class="text-xs text-gray-600">Meeting</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#33e818"></span>
                    <span class="text-xs text-gray-600">Task/Deadline</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#f59e0b"></span>
                    <span class="text-xs text-gray-600">Reminder</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#a855f7"></span>
                    <span class="text-xs text-gray-600">Webinar</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#f97316"></span>
                    <span class="text-xs text-gray-600">Invoice/Tagihan</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#ef4444"></span>
                    <span class="text-xs text-gray-600">Approval</span>
                </div>
            </div>
        </div>

        </div>

        <!-- Kolom Kanan: Upcoming Schedule -->
        <div class="schedule-panel">
        <!-- Header -->
        <div class="flex items-center gap-2 mb-4">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(51, 232, 24, 0.2);">
                <i class="fas fa-calendar-alt text-xs" style="color: #33e818;"></i>
            </div>
            <h3 class="font-bold text-white text-base">Upcoming Schedule</h3>
        </div>

        <!-- Filter chips -->
        <div class="flex items-center gap-2 mb-4">
            <button class="filter-chip active" onclick="filterEvents('all', this)">All</button>
            <button class="filter-chip" onclick="filterEvents('today', this)">Today</button>
            <button class="filter-chip" onclick="filterEvents('week', this)">This Week</button>
            <a href="<?= site_url('ceo/calendar') ?>" class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 text-gray-500 flex items-center justify-center ml-auto transition flex-shrink-0" title="Reset filter">
                <i class="fas fa-sync-alt text-xs"></i>
            </a>
        </div>

        <!-- Event List -->
        <div class="space-y-3" id="event-list">
            <?php 
                $hasEvents = false;
                foreach($events as $evt): 
                    $date = date('Y-m-d', strtotime($evt['start']));
                    $time = date('H:i', strtotime($evt['start']));
                    $endTime = isset($evt['end']) && $evt['end'] ? date('H:i', strtotime($evt['end'])) : '';

                    if ($time == '00:00' && strpos($evt['start'], '00:00:00') !== false) {
                        $time = '00:00'; $endTime = '';
                    }

                    $eventType = strtolower((string) ($evt['type'] ?? 'event'));
                    $color = '#6b7280'; $bg = 'rgba(107,114,128,0.15)'; $icon = 'fas fa-calendar';
                    if($eventType === 'task')        { $color='#33e818'; $bg='rgba(51,232,24,0.12)';   $icon='fas fa-clipboard-check'; }
                    elseif($eventType === 'meeting') { $color='#3b82f6'; $bg='rgba(59,130,246,0.12)';  $icon='fas fa-briefcase'; }
                    elseif($eventType === 'reminder'){ $color='#f59e0b'; $bg='rgba(245,158,11,0.12)';  $icon='fas fa-bell'; }
                    elseif($eventType === 'webinar') { $color='#a855f7'; $bg='rgba(168,85,247,0.12)';  $icon='fas fa-video'; }
                    elseif(in_array($eventType, ['invoice', 'bill'], true)) { $color='#f97316'; $bg='rgba(249,115,22,0.12)'; $icon='fas fa-file-invoice-dollar'; }
                    elseif($eventType === 'approval'){ $color='#ef4444'; $bg='rgba(239,68,68,0.12)';   $icon='fas fa-user-check'; }
            ?>
                <div class="event-card" data-date="<?= $date ?>" style="border-left-color:<?= $color ?>;">
                    <div class="event-icon" style="background:<?= $bg ?>; color:<?= $color ?>;">
                        <i class="<?= $icon ?>"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-xs mb-1" style="color:<?= $color ?>"><?= $time ?><?= $endTime ? ' - ' . $endTime : '' ?></div>
                        <h4 class="font-bold text-sm text-white leading-snug mb-1 truncate"><?= esc($evt['title']) ?></h4>
                        <div class="flex items-center text-gray-700 text-xs mb-3">
                            <i class="fas fa-map-marker-alt mr-1 text-gray-700 text-xs"></i>
                            <span class="truncate"><?= esc($evt['location'] ?? 'System') ?></span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                        <?php if (!empty($evt['url'])): ?>
                            <a href="<?= esc($evt['url'], 'attr') ?>" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-blue-400 hover:text-blue-300 text-xs px-2.5 py-1.5 rounded-lg border border-blue-500/20 bg-blue-500/5 hover:bg-blue-500/10 transition">
                                <i class="fas fa-external-link-alt"></i> Buka
                            </a>
                        <?php endif; ?>
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=<?= urlencode($evt['title']) ?>&dates=<?= date('Ymd\THis', strtotime($evt['start'])) ?>/<?= date('Ymd\THis', strtotime($evt['end'] ?? $evt['start'])) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 text-gray-500 hover:text-white text-xs px-2.5 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 transition">
                            <img src="<?= base_url('images/google-calender.png') ?>" alt="G" class="w-3 h-3 object-contain">
                            Add to GCal
                        </a>
                        </div>
                    </div>
                </div>
            <?php 
                $hasEvents = true;
                endforeach; 
            ?>

            <?php if(!$hasEvents): ?>
                <div class="text-gray-700 text-center text-sm italic py-8">
                    <i class="fas fa-calendar-times text-2xl mb-3 block text-gray-800"></i>
                    Tidak ada jadwal
                </div>
            <?php endif; ?>
        </div>

        <!-- Tambah Acara -->
        <a href="<?= site_url('ceo/quick-actions') ?>" class="tambah-btn mt-2">
            <i class="fas fa-plus text-xs"></i> Tambah Acara
        </a>
    </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var rawEvents  = <?= $eventsJson ?>;
        var todayDate  = new Date().toISOString().split('T')[0];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            buttonText: { today: 'Today' },
            headerToolbar: false,
            height: 'auto',
            /* Render events as dots (list-item = dot + title) */
            events: rawEvents.map(function(e) {
                return {
                    id: e.id, title: e.title, start: e.start, end: e.end,
                    display: 'list-item',
                    color: e.colorCode,
                    extendedProps: { type: e.type, location: e.location }
                };
            }),
            /* Custom day header: Indonesian, Sunday red */
            dayHeaderContent: function(arg) {
                var days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
                var idx   = arg.date.getDay();
                var color = (idx === 0) ? '#ef4444' : '#6b7280';
                return { html: '<span style="color:' + color + ';font-size:0.7rem;font-weight:600;letter-spacing:0.05em;">' + days[idx] + '</span>' };
            },
            /* After each render/navigate: color dates + style title */
            datesSet: function(info) {
                applyDateColors();
                var calTitleElem = document.getElementById('calTitle');
                if (calTitleElem) {
                    calTitleElem.innerHTML = info.view.title.replace(/(\d{4})/, '<span style="color:#33e818;font-weight:700;">$1</span>');
                }
            },
            dateClick: function(info) {
                highlightCards(info.dateStr);
            }
        });

        calendar.render();

        /* Attach custom button handlers */
        document.getElementById('prevBtn').addEventListener('click', function() { calendar.prev(); });
        document.getElementById('nextBtn').addEventListener('click', function() { calendar.next(); });
        var todayBtnElem = document.getElementById('todayBtn');
        if (todayBtnElem) {
            todayBtnElem.addEventListener('click', function() { calendar.today(); });
        }

        /* Force date number colors via JS (overrides locale styles) */
        function applyDateColors() {
            document.querySelectorAll('.fc-daygrid-day').forEach(function(cell) {
                var dateStr = cell.getAttribute('data-date');
                if (!dateStr) return;
                var isSun    = (new Date(dateStr + 'T12:00:00').getDay() === 0);
                var isOther  = cell.classList.contains('fc-day-other');
                var isToday  = cell.classList.contains('fc-day-today');
                var num = cell.querySelector('a.fc-daygrid-day-number, .fc-daygrid-day-number');
                if (!num) return;
                num.style.textDecoration = 'none';
                if (isToday) {
                    num.style.color = '#33e818';
                    num.style.fontWeight = '700';
                } else if (isOther) {
                    num.style.color = isSun ? 'rgba(239,68,68,0.15)' : '#282828';
                } else {
                    num.style.color = isSun ? '#ef4444' : '#6b7280';
                    num.style.fontWeight = '500';
                }
            });
        }

        /* Removed applyTitleStyle because we now use custom title in datesSet */

        /* Default: show today's events */
        highlightCards(todayDate);

        function highlightCards(date) {
            var cards = document.querySelectorAll('.event-card');
            var found = false;
            cards.forEach(function(card) {
                if (card.getAttribute('data-date') === date) { card.style.display = 'flex'; found = true; }
                else { card.style.display = 'none'; }
            });
            if (!found) cards.forEach(function(c) { c.style.display = 'flex'; });
        }
    });

    function filterEvents(type, btn) {
        document.querySelectorAll('.filter-chip').forEach(function(c) { c.classList.remove('active'); });
        btn.classList.add('active');
        var today = new Date().toISOString().split('T')[0];
        var cards = document.querySelectorAll('.event-card');
        cards.forEach(function(card) {
            var date = card.getAttribute('data-date');
            if (type === 'all') {
                card.style.display = 'flex';
            } else if (type === 'today') {
                card.style.display = (date === today) ? 'flex' : 'none';
            } else if (type === 'week') {
                var d = new Date(date + 'T00:00:00');
                var now = new Date(); now.setHours(0,0,0,0);
                var end = new Date(now); end.setDate(now.getDate() + 7);
                card.style.display = (d >= now && d <= end) ? 'flex' : 'none';
            }
        });
    }
</script>

<?= $this->endSection() ?>
