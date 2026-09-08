/**
 * ALMAI CEO Dashboard - Main Application Controller
 * Calendar-First Executive Experience
 * 
 * Flow:
 * - Single Hero Calendar as the main dashboard
 * - Daily activity summary directly inside each date cell:
 *   - Green ✓: Notifications & items already approved/viewed
 *   - Blue number: Approvals completed that day
 *   - Red dot/number: Unread notifications & pending decisions
 * - Interactive Bottom Sheet / Modal with full day details & instant 1-click approvals
 * - Empty state if date has no activity
 * - Automatic mark as Done upon opening/approving
 */

let appState = {
  data: JSON.parse(JSON.stringify(CEO_DATA)),
  calendarInstance: null,
  activeTheme: localStorage.getItem('almai_ceo_theme') || 'dark',
  selectedDate: "2026-08-22",
  activeDayModalTab: "all",
  activeInvoiceTab: "all"
};

document.addEventListener('DOMContentLoaded', () => {
  initExecutiveTheme();
  initLiveClock();
  initCalendarDashboard();
  attachEventListeners();
  initCommandPalette();
  updateTopbarBadges();
});

/* -------------------------------------------------------------
   Executive Design System (Obsidian Dark Theme)
   ------------------------------------------------------------- */
function initExecutiveTheme() {
  document.documentElement.setAttribute('data-theme', 'dark');
}

/* -------------------------------------------------------------
   Live Clock (WIB - Jakarta)
   ------------------------------------------------------------- */
function initLiveClock() {
  const clockEl = document.getElementById('liveClockText');
  const dateEl = document.getElementById('liveDateText');

  function update() {
    const now = new Date();
    const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    const dateStr = now.toLocaleDateString('id-ID', optionsDate);
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';

    if (clockEl) clockEl.innerText = timeStr;
    if (dateEl) dateEl.innerText = dateStr;
  }

  update();
  setInterval(update, 1000);
}

/* -------------------------------------------------------------
   Calendar Dashboard Initialization
   ------------------------------------------------------------- */
function initCalendarDashboard() {
  appState.calendarInstance = new CEOCalendar('calendarGridContainer', appState.data.calendarEvents);

  // Month navigation buttons
  const prevBtn = document.getElementById('calPrevBtn');
  const nextBtn = document.getElementById('calNextBtn');
  const todayBtn = document.getElementById('calTodayBtn');

  if (prevBtn) prevBtn.addEventListener('click', () => window.calPrevMonth());
  if (nextBtn) nextBtn.addEventListener('click', () => window.calNextMonth());
  if (todayBtn) todayBtn.addEventListener('click', () => window.calGoToToday());

  // Category filter chips
  const filterBar = document.querySelector('.cal-filter-bar');
  if (filterBar) {
    enableDragToScroll(filterBar);
  }
  const filterChips = document.querySelectorAll('.cal-filter-bar .filter-chip');
  filterChips.forEach(chip => {
    chip.addEventListener('click', () => {
      filterChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      const filter = chip.getAttribute('data-filter') || 'all';
      appState.calendarInstance.setFilter(filter);
    });
  });
}

window.calPrevMonth = function() {
  if (appState.calendarInstance) {
    appState.calendarInstance.prevMonth();
    const curMonth = document.getElementById('calCurrentMonthText');
    if (curMonth && window.showToast) {
      window.showToast('info', `Menampilkan ${curMonth.innerText}`);
    }
  }
};

window.calNextMonth = function() {
  if (appState.calendarInstance) {
    appState.calendarInstance.nextMonth();
    const curMonth = document.getElementById('calCurrentMonthText');
    if (curMonth && window.showToast) {
      window.showToast('info', `Menampilkan ${curMonth.innerText}`);
    }
  }
};

window.calGoToToday = function() {
  if (appState.calendarInstance) {
    appState.calendarInstance.goToToday();
  }
};

/* -------------------------------------------------------------
   Smooth Drag-To-Scroll & Horizontal Scroll Handlers
   ------------------------------------------------------------- */
function enableDragToScroll(element) {
  if (!element) return;
  let isDown = false;
  let startX;
  let scrollLeft;
  let hasMoved = false;

  element.addEventListener('mousedown', (e) => {
    // If clicking directly on a button, still allow drag if mouse moves
    isDown = true;
    hasMoved = false;
    element.classList.add('is-dragging');
    startX = e.pageX - element.offsetLeft;
    scrollLeft = element.scrollLeft;
  });

  element.addEventListener('mouseleave', () => {
    isDown = false;
    element.classList.remove('is-dragging');
  });

  element.addEventListener('mouseup', () => {
    isDown = false;
    element.classList.remove('is-dragging');
  });

  element.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    const x = e.pageX - element.offsetLeft;
    const walk = (x - startX) * 1.5;
    if (Math.abs(walk) > 4) {
      hasMoved = true;
    }
    element.scrollLeft = scrollLeft - walk;
  });

  // Mouse wheel horizontal scroll support
  element.addEventListener('wheel', (e) => {
    if (e.deltaY !== 0) {
      e.preventDefault();
      element.scrollLeft += e.deltaY * 0.9;
    }
  }, { passive: false });
}

window.scrollTabsLeft = function() {
  const bar = document.getElementById('dayTabsBar');
  if (bar) bar.scrollBy({ left: -160, behavior: 'smooth' });
};

window.scrollTabsRight = function() {
  const bar = document.getElementById('dayTabsBar');
  if (bar) bar.scrollBy({ left: 160, behavior: 'smooth' });
};

window.scrollFiltersLeft = function() {
  const bar = document.getElementById('calFilterBar');
  if (bar) bar.scrollBy({ left: -160, behavior: 'smooth' });
};

window.scrollFiltersRight = function() {
  const bar = document.getElementById('calFilterBar');
  if (bar) bar.scrollBy({ left: 160, behavior: 'smooth' });
};

/* -------------------------------------------------------------
   Day Summary Aggregator (Core Reactive Engine)
   ------------------------------------------------------------- */
window.getDaySummary = function(dateStr) {
  const d = appState.data;
  
  const events = (d.calendarEvents || []).filter(e => e.date === dateStr);
  const approvals = (d.approvals || []).filter(a => a.date === dateStr);
  const notifications = (d.notifications || []).filter(n => n.date === dateStr);
  const bills = (d.bills || []).filter(b => b.date === dateStr);

  const completedApprovalsCount = approvals.filter(a => a.status === 'approved').length;
  const pendingApprovalsCount = approvals.filter(a => a.status === 'pending').length;
  
  const doneNotificationsCount = notifications.filter(n => n.status === 'done' || !n.unread).length;
  const unreadNotifsCount = notifications.filter(n => n.unread).length;
  
  const doneEventsCount = events.filter(e => e.status === 'done').length;

  // 1. Green ✓ : notifications & items already approved / viewed
  const doneCount = completedApprovalsCount + doneNotificationsCount + doneEventsCount;

  // 2. Blue number : approvals completed that day
  // (completedApprovalsCount)

  // 3. Red dot/number : unread notifications & pending decisions
  const unreadCount = unreadNotifsCount + pendingApprovalsCount;

  const hasActivity = (events.length + approvals.length + notifications.length + bills.length) > 0;

  return {
    date: dateStr,
    hasActivity,
    doneCount,
    completedApprovalsCount,
    unreadCount,
    events,
    approvals,
    notifications,
    bills
  };
};

/* -------------------------------------------------------------
   Topbar Badges & Pulse Counters
   ------------------------------------------------------------- */
function updateTopbarBadges() {
  const allNotifs = appState.data.notifications || [];
  const allApprovals = appState.data.approvals || [];

  const totalUnreadNotifs = allNotifs.filter(n => n.unread).length;
  const totalPendingApprovals = allApprovals.filter(a => a.status === 'pending').length;
  const totalActionNeeded = totalUnreadNotifs + totalPendingApprovals;

  const notifBadge = document.querySelector('.topbar-btn .btn-badge');
  if (notifBadge) {
    notifBadge.innerText = totalActionNeeded;
    notifBadge.style.display = totalActionNeeded > 0 ? 'flex' : 'none';
  }

  const sidebarBadge = document.getElementById('sidebarApprBadge');
  if (sidebarBadge) {
    sidebarBadge.innerText = totalPendingApprovals;
    sidebarBadge.style.display = totalPendingApprovals > 0 ? 'inline-block' : 'none';
  }

  // Header quick pills
  const pillPending = document.getElementById('headerPillPending');
  if (pillPending) {
    pillPending.innerHTML = `<span class="pulse-dot"></span> ${totalPendingApprovals} Persetujuan`;
  }
  const pillUnread = document.getElementById('headerPillUnread');
  if (pillUnread) {
    pillUnread.innerHTML = `<i class="far fa-bell text-rose"></i> ${totalUnreadNotifs} Belum Dibaca`;
  }
}

/* -------------------------------------------------------------
   Day Details Bottom Sheet / Modal (Opened upon Date Click)
   ------------------------------------------------------------- */
window.openDayDetailSheet = function(dateStr) {
  appState.selectedDate = dateStr;
  appState.activeDayModalTab = 'all';

  const modal = document.getElementById('dayDetailModal');
  if (!modal) return;

  renderDayDetailContent(dateStr, 'all');
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
};

window.closeDayDetailSheet = function() {
  const modal = document.getElementById('dayDetailModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
};

window.setDayModalTab = function(tabName) {
  appState.activeDayModalTab = tabName;
  renderDayDetailContent(appState.selectedDate, tabName);
};

function renderDayDetailContent(dateStr, activeTab = 'all') {
  const summary = window.getDaySummary(dateStr);
  const dateObj = new Date(dateStr);
  const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
  const dateFormatted = dateObj.toLocaleDateString('id-ID', options);
  const isToday = (dateStr === "2026-08-22");

  // Header Elements
  const titleEl = document.getElementById('dayDetailDateTitle');
  if (titleEl) {
    titleEl.innerHTML = `
      <span>${dateFormatted}</span>
      ${isToday ? '<span class="today-tag-pill">HARI INI</span>' : ''}
    `;
  }

  // Header Quick Stats Indicators
  const statsWrap = document.getElementById('dayDetailHeaderStats');
  if (statsWrap) {
    statsWrap.innerHTML = `
      <div class="day-stat-chip chip-done" title="Notifikasi & persetujuan selesai">
        <i class="fas fa-check"></i>
        <span>${summary.doneCount} Selesai</span>
      </div>
      <div class="day-stat-chip chip-appr" title="Persetujuan telah diotorisasi">
        <i class="fas fa-signature"></i>
        <span>${summary.completedApprovalsCount} Disetujui</span>
      </div>
      <div class="day-stat-chip chip-unread" title="Perlu tindakan & belum dibaca">
        <span class="unread-pulse-dot"></span>
        <span>${summary.unreadCount} Perlu Tindakan</span>
      </div>
    `;
  }

  const tabsContainer = document.getElementById('dayDetailTabsContainer');
  const bodyEl = document.getElementById('dayDetailModalBody');
  const footerEl = document.getElementById('dayDetailModalFooter');
  if (!bodyEl) return;

  // 1. EMPTY STATE (If date has no activity)
  if (!summary.hasActivity) {
    if (tabsContainer) {
      tabsContainer.style.display = 'none';
      tabsContainer.innerHTML = '';
    }
    if (footerEl) {
      footerEl.style.display = 'none';
      footerEl.innerHTML = '';
    }
    bodyEl.innerHTML = `
      <div class="day-empty-state">
        <div class="empty-icon-circle">
          <i class="far fa-calendar-check"></i>
        </div>
        <h3>Tidak Ada Aktivitas</h3>
        <p>Tidak ada agenda, notifikasi, tagihan, atau persetujuan yang dijadwalkan pada tanggal ${dateFormatted}.</p>
        <button class="btn-create-agenda" onclick="openAddEventForDate('${dateStr}')">
          <i class="fas fa-plus"></i>
          <span>Tambah Agenda untuk Tanggal Ini</span>
        </button>
      </div>
    `;
    return;
  }

  // 2. HAS ACTIVITY - RENDER TABS, BODY FEED, AND PINNED FOOTER
  const totalItems = summary.approvals.length + summary.notifications.length + summary.events.length + summary.bills.length;

  let tabsHtml = `
    <div class="day-sheet-tabs-wrapper">
      <button class="tabs-scroll-btn prev-tab-btn" onclick="window.scrollTabsLeft()" title="Geser ke kiri" aria-label="Geser tab ke kiri">
        <i class="fas fa-chevron-left"></i>
      </button>
      <div class="day-tabs-bar" id="dayTabsBar">
        <button class="day-tab-btn ${activeTab === 'all' ? 'active' : ''}" onclick="window.setDayModalTab('all')">
          Semua (${totalItems})
        </button>
        <button class="day-tab-btn ${activeTab === 'approvals' ? 'active' : ''}" onclick="window.setDayModalTab('approvals')">
          Persetujuan (${summary.approvals.length})
        </button>
        <button class="day-tab-btn ${activeTab === 'notifications' ? 'active' : ''}" onclick="window.setDayModalTab('notifications')">
          Notifikasi (${summary.notifications.length})
        </button>
        <button class="day-tab-btn ${activeTab === 'events' ? 'active' : ''}" onclick="window.setDayModalTab('events')">
          Agenda & Rapat (${summary.events.length})
        </button>
        ${summary.bills.length > 0 ? `
        <button class="day-tab-btn ${activeTab === 'bills' ? 'active' : ''}" onclick="window.setDayModalTab('bills')">
          Tagihan & Invoice (${summary.bills.length})
        </button>` : ''}
      </div>
      <button class="tabs-scroll-btn next-tab-btn" onclick="window.scrollTabsRight()" title="Geser ke kanan" aria-label="Geser tab ke kanan">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  `;

  if (tabsContainer) {
    tabsContainer.style.display = 'block';
    tabsContainer.innerHTML = tabsHtml;
    enableDragToScroll(document.getElementById('dayTabsBar'));
  }

  let itemsHtml = '<div class="day-items-feed">';
  let renderedCount = 0;

  // A. APPROVALS
  if (activeTab === 'all' || activeTab === 'approvals') {
    summary.approvals.forEach(appr => {
      renderedCount++;
      const isApproved = appr.status === 'approved';
      itemsHtml += `
        <div class="activity-card appr-card ${isApproved ? 'card-completed' : 'card-pending'}">
          <div class="card-top-row">
            <div class="card-badge-left">
              <span class="badge-pill ${isApproved ? 'pill-green' : 'pill-amber'}">
                <i class="${appr.icon || 'fas fa-signature'}"></i>
                <span>${isApproved ? 'Disetujui' : 'Menunggu Persetujuan'}</span>
              </span>
              <span class="card-time-text"><i class="far fa-clock"></i> ${appr.time}</span>
            </div>
            <span class="appr-amount-tag">${appr.amount}</span>
          </div>

          <h4 class="card-item-title">${appr.title}</h4>
          
          <div class="card-meta-line">
            <span><i class="fas fa-user-tie text-accent"></i> ${appr.requester}</span>
            <span>•</span>
            <span><i class="fas fa-building"></i> ${appr.department}</span>
            ${appr.attachment ? `<span>•</span> <span class="attachment-link"><i class="fas fa-paperclip"></i> ${appr.attachment}</span>` : ''}
          </div>

          <p class="card-notes-box">${appr.notes}</p>

          <div class="card-actions-row">
            ${!isApproved ? `
              <button class="btn-instant-approve" onclick="approveItemFromModal('${appr.id}', '${dateStr}')">
                <i class="fas fa-check"></i>
                <span>Setujui Proposal</span>
              </button>
              <button class="btn-reject-sm" onclick="rejectItemFromModal('${appr.id}', '${dateStr}')">
                <i class="fas fa-xmark"></i>
                <span>Tolak</span>
              </button>
            ` : `
              <div class="approved-stamp">
                <i class="fas fa-circle-check text-accent"></i>
                <span>${appr.approvedAt || 'Telah Disetujui'}</span>
              </div>
            `}
          </div>
        </div>
      `;
    });
  }

  // B. NOTIFICATIONS (Auto-mark as Done on open/click)
  if (activeTab === 'all' || activeTab === 'notifications') {
    summary.notifications.forEach(notif => {
      renderedCount++;
      const isDone = notif.status === 'done' || !notif.unread;
      itemsHtml += `
        <div class="activity-card notif-card ${isDone ? 'card-completed' : 'card-unread-glow'}" onclick="markNotificationDone('${notif.id}', '${dateStr}')">
          <div class="card-top-row">
            <div class="card-badge-left">
              <span class="badge-pill ${isDone ? 'pill-green' : 'pill-rose'}">
                <i class="${isDone ? 'fas fa-check' : 'fas fa-bell'}"></i>
                <span>${isDone ? 'Selesai Dibaca' : 'Belum Dibaca'}</span>
              </span>
              <span class="card-time-text"><i class="far fa-clock"></i> ${notif.time}</span>
            </div>
            <span class="notif-cat-tag">${notif.categoryName || 'Notifikasi'}</span>
          </div>

          <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;">
            <h4 class="card-item-title" style="margin-bottom: 0;">${notif.title}</h4>
            ${!isDone ? `
              <button class="btn-mark-done-sm" onclick="event.stopPropagation(); markNotificationDone('${notif.id}', '${dateStr}')" title="Tandai Selesai">
                <i class="fas fa-check"></i>
                <span>Tandai Selesai</span>
              </button>
            ` : `
              <span class="done-check-icon"><i class="fas fa-check"></i></span>
            `}
          </div>
        </div>
      `;
    });
  }

  // C. EVENTS & MEETINGS
  if (activeTab === 'all' || activeTab === 'events') {
    summary.events.forEach(evt => {
      renderedCount++;
      const isDone = evt.status === 'done';
      itemsHtml += `
        <div class="activity-card event-card ${isDone ? 'card-completed' : ''}">
          <div class="card-top-row">
            <div class="card-badge-left">
              <span class="badge-pill event-${evt.category}">
                <i class="fas fa-calendar-day"></i>
                <span>${evt.categoryName}</span>
              </span>
              <span class="card-time-text"><i class="far fa-clock"></i> ${evt.time} WIB</span>
            </div>
            <span class="attendees-tag"><i class="fas fa-users"></i> ${evt.attendees} Peserta</span>
          </div>

          <h4 class="card-item-title">${evt.title}</h4>
          
          <div class="card-meta-line">
            <span><i class="fas fa-location-dot text-accent"></i> ${evt.location}</span>
          </div>

          <p class="card-notes-box">${evt.description}</p>

          <div class="card-actions-row">
            ${evt.meetUrl ? `
              <a href="${evt.meetUrl}" target="_blank" class="btn-join-meet">
                <i class="fas fa-video"></i>
                <span>Masuk Rapat (${evt.meetUrl.includes('google') ? 'Google Meet' : 'Zoom'})</span>
              </a>
            ` : ''}
            <button class="btn-detail-sm" onclick="openEventDetailModalById('${evt.id}')">
              <i class="fas fa-circle-info"></i>
              <span>Detail Agenda</span>
            </button>
          </div>
        </div>
      `;
    });
  }

  // D. BILLS & INVOICES
  if (activeTab === 'all' || activeTab === 'bills') {
    summary.bills.forEach(bill => {
      renderedCount++;
      itemsHtml += `
        <div class="activity-card bill-card">
          <div class="card-top-row">
            <div class="card-badge-left">
              <span class="badge-pill pill-amber">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Jatuh Tempo Tagihan</span>
              </span>
              <span class="card-time-text">Jatuh Tempo: ${bill.dueDate}</span>
            </div>
            <span class="appr-amount-tag" style="color: var(--accent-amber);">${bill.amount}</span>
          </div>

          <h4 class="card-item-title">${bill.serviceName}</h4>
          
          <div class="card-meta-line">
            <span><i class="fas fa-building text-accent"></i> ${bill.vendor}</span>
            <span>•</span>
            <span>No. Invoice: ${bill.invoiceNo}</span>
          </div>

          <div class="card-actions-row">
            <button class="btn-instant-approve" onclick="openOfficialInvoiceModal('${bill.invoiceNo}')">
              <i class="fas fa-file-invoice"></i>
              <span>Buka Dokumen Faktur Resmi</span>
            </button>
          </div>
        </div>
      `;
    });
  }

  if (renderedCount === 0) {
    itemsHtml += `
      <div style="text-align: center; padding: 36px 20px; color: var(--text-muted); font-size: 12.5px;">
        <i class="far fa-folder-open" style="font-size: 28px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
        Tidak ada data untuk kategori ini pada tanggal ${dateFormatted}.
      </div>
    `;
  }

  itemsHtml += '</div>';
  bodyEl.innerHTML = itemsHtml;

  // Bottom action bar inside pinned footer
  if (footerEl) {
    footerEl.style.display = 'flex';
    footerEl.innerHTML = `
      <button class="btn-add-day-agenda" onclick="openAddEventForDate('${dateStr}')">
        <i class="fas fa-plus"></i>
        <span>Tambah Agenda di Tanggal Ini</span>
      </button>

      ${summary.unreadCount > 0 ? `
        <button class="btn-batch-done-day" onclick="markAllDoneForDate('${dateStr}')">
          <i class="fas fa-check-double"></i>
          <span>Tandai Semua Selesai (${summary.unreadCount})</span>
        </button>
      ` : `
        <span style="font-size: 11px; color: #33e818; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
          <i class="fas fa-circle-check"></i> Seluruh Aktivitas Selesai
        </span>
      `}
    `;
  }
}

/* -------------------------------------------------------------
   Reactive Actions: Approve, Mark Done, Batch Mark Done
   ------------------------------------------------------------- */
window.approveItemFromModal = function(apprId, dateStr) {
  const appr = appState.data.approvals.find(a => a.id === apprId);
  if (appr) {
    appr.status = 'approved';
    appr.approvedAt = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB (Disetujui CEO)';

    // Automatically mark associated notification as Done!
    const relatedNotif = appState.data.notifications.find(n => n.relatedId === apprId);
    if (relatedNotif) {
      relatedNotif.unread = false;
      relatedNotif.status = 'done';
    }

    showToast('success', `Proposal "${appr.title}" berhasil disetujui & otomatis ditandai selesai!`);
    
    // Live update Calendar Grid, Topbar, and the open Day Sheet
    appState.calendarInstance.render();
    updateTopbarBadges();
    renderDayDetailContent(dateStr, appState.activeDayModalTab);
  }
};

window.rejectItemFromModal = function(apprId, dateStr) {
  const appr = appState.data.approvals.find(a => a.id === apprId);
  if (appr) {
    appr.status = 'rejected';
    showToast('info', `Proposal "${appr.title}" ditolak.`);
    
    appState.calendarInstance.render();
    updateTopbarBadges();
    renderDayDetailContent(dateStr, appState.activeDayModalTab);
  }
};

window.markNotificationDone = function(notifId, dateStr) {
  const notif = appState.data.notifications.find(n => n.id === notifId);
  if (notif && (notif.unread || notif.status !== 'done')) {
    notif.unread = false;
    notif.status = 'done';
    showToast('success', 'Notifikasi ditandai Selesai (Done)');
    
    appState.calendarInstance.render();
    updateTopbarBadges();
    renderDayDetailContent(dateStr, appState.activeDayModalTab);
  }
};

window.markAllDoneForDate = function(dateStr) {
  // 1. Mark approvals done
  appState.data.approvals.forEach(a => {
    if (a.date === dateStr && a.status === 'pending') {
      a.status = 'approved';
      a.approvedAt = 'Disetujui C-Level (Batch)';
    }
  });

  // 2. Mark notifications done
  appState.data.notifications.forEach(n => {
    if (n.date === dateStr) {
      n.unread = false;
      n.status = 'done';
    }
  });

  showToast('success', `Semua aktivitas pada tanggal ${dateStr} ditandai selesai.`);
  
  appState.calendarInstance.render();
  updateTopbarBadges();
  renderDayDetailContent(dateStr, appState.activeDayModalTab);
};

window.openAddEventForDate = function(dateStr) {
  const dateInput = document.getElementById('newEventDate');
  if (dateInput) {
    dateInput.value = dateStr || '2026-08-22';
  }
  openModal('addEventModal');
};

/* -------------------------------------------------------------
   Add Event Form Handler
   ------------------------------------------------------------- */
window.handleCreateEventSubmit = function(e) {
  if (e && e.preventDefault) {
    e.preventDefault();
  }

  const titleInput = document.getElementById('newEventTitle');
  const dateInput = document.getElementById('newEventDate');
  const timeInput = document.getElementById('newEventTime');
  const categoryInput = document.getElementById('newEventCategory');
  const locationInput = document.getElementById('newEventLocation');
  const descInput = document.getElementById('newEventDesc');

  const title = titleInput ? titleInput.value : 'Agenda Baru';
  const date = dateInput ? dateInput.value : '2026-08-22';
  const time = timeInput && timeInput.value ? timeInput.value : '14:00';
  const category = categoryInput ? categoryInput.value : 'general';
  const location = locationInput && locationInput.value ? locationInput.value : 'Boardroom HQ & Google Meet';
  const desc = descInput && descInput.value ? descInput.value : '-';

  const categoryNames = {
    meeting: 'Rapat Direksi',
    webinar: 'Webinar Akbar',
    bill: 'Jatuh Tempo Tagihan',
    deadline: 'Deadline & MOU',
    general: 'Event Perusahaan'
  };

  const newEvt = {
    id: `evt-${Date.now()}`,
    date: date,
    time: `${time} WIB`,
    title: title,
    category: category,
    categoryName: categoryNames[category] || 'Agenda',
    location: location,
    meetUrl: location.toLowerCase().includes('meet') || location.toLowerCase().includes('zoom') ? 'https://meet.google.com/alm-new-event' : '',
    attendees: 4,
    priority: 'high',
    description: desc,
    status: 'upcoming'
  };

  appState.data.calendarEvents.push(newEvt);
  closeModal('addEventModal');
  showToast('success', `Agenda "${title}" berhasil disimpan ke kalender!`);

  // Reset form
  if (e && e.target && e.target.reset) {
    e.target.reset();
  }

  // Re-render Calendar and open day sheet
  if (appState.calendarInstance) {
    appState.calendarInstance.render();
  }
  if (window.openDayDetailSheet) {
    window.openDayDetailSheet(date);
  }
};

/* -------------------------------------------------------------
   Official Invoice Modal Hub
   ------------------------------------------------------------- */
window.openOfficialInvoiceModal = function(invoiceNo) {
  const inv = appState.data.invoices.find(x => x.invoiceNumber === invoiceNo) || appState.data.invoices[0];
  if (!inv) return;

  const body = document.getElementById('officialInvoiceModalBody');
  const footer = document.getElementById('officialInvoiceModalFooter');

  const rowsHtml = inv.items.map((it, idx) => `
    <tr>
      <td class="col-no">${idx + 1}</td>
      <td class="col-desc">${it.desc}</td>
      <td class="col-qty">${it.qty}</td>
      <td class="col-price">Rp ${it.unitPrice.toLocaleString('id-ID')}</td>
      <td class="col-total">Rp ${it.total.toLocaleString('id-ID')}</td>
    </tr>
  `).join('');

  body.innerHTML = `
    <div class="invoice-card-wrapper">
      <div class="invoice-header-block">
        <div class="invoice-header-left">
          <span style="font-size: 11px; font-weight: 800; color: #33e818; text-transform: uppercase;">${inv.typeText}</span>
          <h3>${inv.invoiceNumber}</h3>
          <p>Penerbit: <strong>${inv.issuer}</strong></p>
        </div>
        <div class="invoice-header-right">
          <span class="invoice-status-tag">
            <i class="fas fa-clock"></i> ${inv.statusText}
          </span>
          <p style="font-size: 12px; color: var(--text-secondary); margin-top: 6px;">Jatuh Tempo: <strong>${inv.dueDate}</strong></p>
        </div>
      </div>

      <div class="invoice-table-responsive">
        <table class="invoice-table">
          <thead>
            <tr>
              <th style="width: 40px; text-align: center;">No</th>
              <th style="text-align: left;">Rincian Layanan</th>
              <th style="width: 55px; text-align: center;">Qty</th>
              <th style="width: 120px; text-align: right;">Harga Satuan</th>
              <th style="width: 130px; text-align: right; padding-right: 14px;">Total</th>
            </tr>
          </thead>
          <tbody>
            ${rowsHtml}
          </tbody>
        </table>
      </div>

      <div class="invoice-summary-container">
        <div class="invoice-summary-box">
          <div class="invoice-summary-row">
            <span>Subtotal:</span>
            <span style="font-weight: 700; color: var(--text-primary);">Rp ${inv.subtotal.toLocaleString('id-ID')}</span>
          </div>
          <div class="invoice-summary-row">
            <span>PPN 11%:</span>
            <span style="font-weight: 700; color: var(--text-primary);">Rp ${inv.tax.toLocaleString('id-ID')}</span>
          </div>
          <div class="invoice-summary-row grand-total">
            <span>Grand Total:</span>
            <span>${inv.formattedTotal}</span>
          </div>
        </div>
      </div>
    </div>
  `;

  footer.innerHTML = `
    <button class="btn-detail" onclick="closeModal('officialInvoiceModal')">Tutup</button>
    <button class="btn-approve" onclick="showToast('success', 'Pembayaran Invoice ${inv.invoiceNumber} telah diotorisasi'); closeModal('officialInvoiceModal');">
      <i class="fas fa-check"></i>
      <span>Otorisasi & Bayar (${inv.formattedTotal})</span>
    </button>
  `;

  openModal('officialInvoiceModal');
};

/* -------------------------------------------------------------
   Batch Approval Modal
   ------------------------------------------------------------- */
window.openBatchApprovalModal = function() {
  const pending = appState.data.approvals.filter(a => a.status === 'pending');
  const body = document.getElementById('batchApprovalModalBody');
  const countEl = document.getElementById('batchApprSelectedCount');

  if (pending.length === 0) {
    body.innerHTML = `
      <div style="text-align: center; padding: 30px; color: var(--text-muted);">
        <i class="fas fa-circle-check text-accent" style="font-size: 36px; margin-bottom: 12px;"></i>
        <h4>Semua Persetujuan Selesai</h4>
        <p style="font-size: 12px; margin-top: 4px;">Tidak ada proposal atau tagihan yang menunggu keputusan Anda.</p>
      </div>
    `;
    if (countEl) countEl.innerText = "0 Terpilih";
    openModal('batchApprovalModal');
    return;
  }

  let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
  pending.forEach(appr => {
    html += `
      <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); cursor: pointer;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <input type="checkbox" checked class="batch-appr-checkbox" value="${appr.id}" style="accent-color: #33e818; width: 16px; height: 16px;">
          <div>
            <div style="font-size: 13px; font-weight: 800; color: #fff;">${appr.title}</div>
            <div style="font-size: 11px; color: var(--text-muted);">${appr.requester} • ${appr.department}</div>
          </div>
        </div>
        <span style="font-size: 13px; font-weight: 800; color: #33e818;">${appr.amount}</span>
      </label>
    `;
  });
  html += '</div>';

  body.innerHTML = html;
  if (countEl) countEl.innerText = `${pending.length} Keputusan Terpilih`;
  openModal('batchApprovalModal');
};

window.executeSelectedBatchApprovals = function() {
  const checkboxes = document.querySelectorAll('.batch-appr-checkbox:checked');
  let count = 0;
  checkboxes.forEach(cb => {
    const appr = appState.data.approvals.find(a => a.id === cb.value);
    if (appr) {
      appr.status = 'approved';
      appr.approvedAt = 'Batch Disetujui';
      
      const relNotif = appState.data.notifications.find(n => n.relatedId === cb.value);
      if (relNotif) {
        relNotif.unread = false;
        relNotif.status = 'done';
      }
      count++;
    }
  });

  closeModal('batchApprovalModal');
  showToast('success', `${count} Proposal berhasil disetujui sekaligus.`);
  
  appState.calendarInstance.render();
  updateTopbarBadges();
};

/* -------------------------------------------------------------
   Event Detail Modal Helper
   ------------------------------------------------------------- */
window.openEventDetailModalById = function(evtId) {
  const evt = appState.data.calendarEvents.find(e => e.id === evtId);
  if (!evt) return;

  const titleEl = document.getElementById('evtModalTitle');
  const bodyEl = document.getElementById('evtModalBody');

  if (titleEl) titleEl.innerText = evt.title;
  if (bodyEl) {
    bodyEl.innerHTML = `
      <div style="display: flex; flex-direction: column; gap: 14px;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--border-subtle);">
          <span class="badge-pill event-${evt.category}">${evt.categoryName}</span>
          <span style="font-size: 12px; font-weight: 800; color: #33e818;"><i class="far fa-clock"></i> ${evt.time}</span>
        </div>

        <div>
          <h4 style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Lokasi / Platform</h4>
          <p style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">
            <i class="fas fa-location-dot text-accent" style="margin-right: 6px;"></i> ${evt.location}
          </p>
        </div>

        <div>
          <h4 style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Poin Pembahasan & Deskripsi</h4>
          <p style="font-size: 12.5px; color: var(--text-secondary); margin-top: 4px; line-height: 1.5;">${evt.description}</p>
        </div>

        ${evt.meetUrl ? `
          <div style="padding-top: 8px;">
            <a href="${evt.meetUrl}" target="_blank" class="btn-join-meet" style="width: 100%; justify-content: center; padding: 10px;">
              <i class="fas fa-video"></i>
              <span>Masuk Tautan Rapat Sekarang</span>
            </a>
          </div>
        ` : ''}
      </div>
    `;
  }

  openModal('eventDetailModal');
};

/* -------------------------------------------------------------
   Executive Report Modal
   ------------------------------------------------------------- */
window.openExecutiveReportModal = function() {
  const body = document.getElementById('executiveReportModalBody');
  if (body) {
    body.innerHTML = `
      <div style="display: flex; flex-direction: column; gap: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px;">
          <div class="day-stat-chip chip-done" style="padding: 12px;">
            <div>
              <p style="font-size: 10.5px; color: var(--text-muted);">Pendapatan Bulanan</p>
              <h4 style="font-size: 14px; color: #33e818; margin-top: 2px;">Rp 1.482.000.000</h4>
            </div>
          </div>
          <div class="day-stat-chip chip-appr" style="padding: 12px;">
            <div>
              <p style="font-size: 10.5px; color: var(--text-muted);">Total Member Aktif</p>
              <h4 style="font-size: 14px; color: var(--accent-blue); margin-top: 2px;">18.420 Member</h4>
            </div>
          </div>
          <div class="day-stat-chip chip-unread" style="padding: 12px;">
            <div>
              <p style="font-size: 10.5px; color: var(--text-muted);">Cash Runway</p>
              <h4 style="font-size: 14px; color: var(--accent-amber); margin-top: 2px;">18.4 Bulan</h4>
            </div>
          </div>
        </div>

        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px;">
          <h4 style="font-size: 13px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px;">Ringkasan Eksekutif Operasional</h4>
          <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5;">
            Seluruh infrastruktur server ALMAI beroperasi dengan SLA 99.98%. Penambahan node cluster AWS telah dijadwalkan untuk mendukung lonjakan 1.000+ peserta Webinar AI Masterclass.
          </p>
        </div>
      </div>
    `;
  }
  openModal('executiveReportModal');
};

window.printExecutiveReportDirect = function() {
  window.print();
};

window.exportExecutiveReportExcel = function() {
  showToast('info', 'File laporan eksekutif (CSV) berhasil diunduh.');
};

/* -------------------------------------------------------------
   Command Palette (Ctrl + K)
   ------------------------------------------------------------- */
function initCommandPalette() {
  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      toggleCommandPalette();
    }
    if (e.key === 'Escape') {
      closeModal('commandPaletteOverlay');
    }
  });

  const input = document.getElementById('commandPaletteInput');
  if (input) {
    input.addEventListener('input', (e) => {
      renderCommandPaletteResults(e.target.value);
    });
  }
}

window.toggleCommandPalette = function() {
  const overlay = document.getElementById('commandPaletteOverlay');
  if (!overlay) return;

  if (overlay.classList.contains('active')) {
    overlay.classList.remove('active');
  } else {
    overlay.classList.add('active');
    const input = document.getElementById('commandPaletteInput');
    if (input) {
      input.value = '';
      input.focus();
      renderCommandPaletteResults('');
    }
  }
};

function renderCommandPaletteResults(query) {
  const resContainer = document.getElementById('commandPaletteResults');
  if (!resContainer) return;

  const q = query.toLowerCase().trim();
  let matches = [];

  // Search events
  appState.data.calendarEvents.forEach(e => {
    if (e.title.toLowerCase().includes(q) || e.categoryName.toLowerCase().includes(q)) {
      matches.push({ type: 'Agenda', title: e.title, sub: `${e.date} • ${e.time}`, action: () => { window.openDayDetailSheet(e.date); closeModal('commandPaletteOverlay'); } });
    }
  });

  // Search approvals
  appState.data.approvals.forEach(a => {
    if (a.title.toLowerCase().includes(q) || a.requester.toLowerCase().includes(q)) {
      matches.push({ type: 'Approval', title: a.title, sub: `${a.requester} • ${a.amount}`, action: () => { window.openDayDetailSheet(a.date); closeModal('commandPaletteOverlay'); } });
    }
  });

  // Search invoices
  appState.data.invoices.forEach(inv => {
    if (inv.invoiceNumber.toLowerCase().includes(q) || inv.title.toLowerCase().includes(q)) {
      matches.push({ type: 'Invoice', title: inv.invoiceNumber, sub: `${inv.issuer} • ${inv.formattedTotal}`, action: () => { openOfficialInvoiceModal(inv.invoiceNumber); closeModal('commandPaletteOverlay'); } });
    }
  });

  if (matches.length === 0) {
    resContainer.innerHTML = `<div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12px;">Tidak ditemukan hasil untuk "${query}"</div>`;
    return;
  }

  resContainer.innerHTML = matches.slice(0, 6).map((m, idx) => `
    <div class="cmd-result-item" onclick="matches[${idx}].action()">
      <span class="badge-pill pill-green">${m.type}</span>
      <div style="flex: 1; min-width: 0;">
        <div style="font-size: 13px; font-weight: 800; color: #fff;">${m.title}</div>
        <div style="font-size: 11px; color: var(--text-muted);">${m.sub}</div>
      </div>
      <i class="fas fa-arrow-right" style="font-size: 11px; color: var(--text-muted);"></i>
    </div>
  `).join('');

  // Attach actions dynamically
  const items = resContainer.querySelectorAll('.cmd-result-item');
  items.forEach((it, idx) => {
    it.onclick = matches[idx].action;
  });
}

/* -------------------------------------------------------------
   Modal Utilities & Toast Notifications
   ------------------------------------------------------------- */
window.openModal = function(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
};

window.closeModal = function(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
};

window.showToast = function(type, message) {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast-message toast-${type}`;
  toast.innerHTML = `
    <i class="${type === 'success' ? 'fas fa-circle-check' : 'fas fa-info-circle'}"></i>
    <span>${message}</span>
  `;

  container.appendChild(toast);
  setTimeout(() => {
    toast.classList.add('fade-out');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
};

/* -------------------------------------------------------------
   Sidebar Toggle for Mobile Responsive
   ------------------------------------------------------------- */
function attachEventListeners() {
  const toggleBtn = document.getElementById('sidebarToggleBtn');
  const closeBtn = document.getElementById('sidebarCloseMobileBtn');
  const backdrop = document.getElementById('sidebarBackdrop');
  const sidebar = document.getElementById('ceoSidebar');

  if (toggleBtn && sidebar && backdrop) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.add('open');
      backdrop.classList.add('open');
    });
  }

  if (closeBtn && sidebar && backdrop) {
    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('open');
      backdrop.classList.remove('open');
    });
  }

  if (backdrop && sidebar) {
    backdrop.addEventListener('click', () => {
      sidebar.classList.remove('open');
      backdrop.classList.remove('open');
    });
  }
}
