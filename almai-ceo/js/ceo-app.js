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
  initExecutiveNavigation();
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

/* -------------------------------------------------------------
   Complete Executive Suite Demo Navigation & Views
   ------------------------------------------------------------- */
const EXECUTIVE_PAGE_META = {
  dashboard: ['CEO Executive Dashboard', 'house'],
  briefing: ['CEO Briefing', 'newspaper'],
  calendar: ['Executive Calendar', 'calendar-days'],
  tasks: ['Tugas Eksekutif', 'list-check'],
  approvals: ['Approval Center', 'clipboard-check'],
  reminders: ['Executive Reminder', 'bell'],
  meetings: ['Meeting Center', 'video'],
  'ai-summary': ['AI Executive Summary', 'robot'],
  notifications: ['Notification Center', 'bullhorn'],
  performance: ['Kinerja Perusahaan', 'chart-line'],
  finance: ['Kas, Invoice & Tagihan', 'file-invoice-dollar'],
  crm: ['CRM Snapshot', 'comments'],
  okr: ['OKR & Sasaran Strategis', 'bullseye'],
  audit: ['Audit Trail', 'clock-rotate-left']
};

const DEMO_TASKS = [
  { title: 'Finalisasi roadmap produk Q4', owner: 'Rian · CTO', due: 'Hari ini, 16:00', status: 'Berjalan', progress: 72, color: '#33e818' },
  { title: 'Review draft MOU mitra institusi', owner: 'Dimas · Business', due: '13 Sep 2026', status: 'Review CEO', progress: 88, color: '#3b82f6' },
  { title: 'Rekonsiliasi komisi mentor WPA', owner: 'Anita · CFO', due: '14 Sep 2026', status: 'Menunggu', progress: 54, color: '#f59e0b' },
  { title: 'Audit keamanan Trade Engine', owner: 'Security Squad', due: '16 Sep 2026', status: 'Terjadwal', progress: 35, color: '#8b5cf6' },
  { title: 'Onboarding VP Engineering', owner: 'Sarah · HR', due: '18 Sep 2026', status: 'Siap', progress: 92, color: '#06b6d4' }
];

function initExecutiveNavigation() {
  document.querySelectorAll('#executiveNavigation .nav-link').forEach(link => {
    link.addEventListener('click', event => {
      const view = link.dataset.view;
      const action = link.dataset.action;
      if (view || action) event.preventDefault();
      if (view) showExecutiveView(view);
      if (action === 'profile') window.openModal('ceoProfileModal');
    });
  });

  window.addEventListener('hashchange', () => {
    const view = location.hash.replace('#', '');
    if (EXECUTIVE_PAGE_META[view]) showExecutiveView(view, false);
  });

  const initialView = EXECUTIVE_PAGE_META[location.hash.replace('#', '')]
    ? location.hash.replace('#', '')
    : 'dashboard';
  showExecutiveView(initialView, false);
}

window.showExecutiveView = function(view, updateHash = true) {
  if (!EXECUTIVE_PAGE_META[view]) view = 'dashboard';
  const container = document.getElementById('demoViewContainer');
  const calendar = document.getElementById('calendar-dashboard');
  const pageTitle = document.getElementById('pageTitle');
  const quickBar = document.getElementById('quickActionBar');

  document.querySelectorAll('#executiveNavigation .nav-link').forEach(link => {
    link.classList.toggle('active', link.dataset.view === view);
  });

  if (pageTitle) pageTitle.textContent = EXECUTIVE_PAGE_META[view][0];
  if (calendar) calendar.classList.toggle('demo-hidden', view !== 'calendar');
  if (container) {
    container.classList.toggle('demo-hidden', view === 'calendar');
    if (view !== 'calendar') container.innerHTML = renderExecutivePage(view);
  }
  if (quickBar) quickBar.style.display = ['dashboard', 'calendar'].includes(view) ? '' : 'none';

  if (updateHash && location.hash !== `#${view}`) history.pushState(null, '', `#${view}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });

  const sidebar = document.getElementById('ceoSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  if (sidebar) sidebar.classList.remove('open');
  if (backdrop) backdrop.classList.remove('open');
};

function renderExecutivePage(view) {
  const renderers = {
    dashboard: renderDemoDashboard,
    briefing: renderDemoBriefing,
    tasks: renderDemoTasks,
    approvals: renderDemoApprovals,
    reminders: renderDemoReminders,
    meetings: renderDemoMeetings,
    'ai-summary': renderDemoAiSummary,
    notifications: renderDemoNotifications,
    performance: renderDemoPerformance,
    finance: renderDemoFinance,
    crm: renderDemoCrm,
    okr: renderDemoOkr,
    audit: renderDemoAudit
  };
  return (renderers[view] || renderDemoDashboard)();
}

function demoHero(eyebrow, title, description, actions = '') {
  return `<div class="demo-hero">
    <div class="demo-status-row"><span class="demo-status live">Data aktual</span><span class="demo-status">Bulan berjalan · 01 Sep–12 Sep 2026</span></div>
    <div class="demo-hero-top">
      <div><div class="demo-eyebrow">${eyebrow}</div><h2>${title}</h2><p>${description}</p></div>
      <div class="demo-actions">${actions}</div>
    </div>
  </div>`;
}

function demoButton(icon, label, action, primary = false) {
  return `<button class="demo-button${primary ? ' primary' : ''}" onclick="${action}"><i class="fas fa-${icon}"></i>${label}</button>`;
}

function metricCard(label, value, detail, icon, color = '#33e818', tone = '') {
  return `<article class="demo-metric" style="--metric-color:${color}">
    <i class="fas fa-${icon} demo-metric-icon"></i><div class="demo-metric-label">${label}</div>
    <div class="demo-metric-value">${value}</div><div class="demo-metric-detail ${tone}">${detail}</div>
  </article>`;
}

function sectionHeading(kicker, title, note = '') {
  return `<div class="demo-section-heading"><div><div class="demo-eyebrow">${kicker}</div><h3>${title}</h3></div><span class="demo-section-note">${note}</span></div>`;
}

function renderDemoDashboard() {
  return `<div class="executive-page">
    ${demoHero('Executive Command Center', 'Keputusan penting terlihat jelas, tanpa tenggelam dalam data operasional.', 'Diperbarui 12 Sep 2026, 13:24 · Seluruh angka pada demo ini adalah data simulasi.',
      demoButton('magnifying-glass', 'Cari', 'toggleCommandPalette()') + demoButton('calendar-days', 'Kalender', "showExecutiveView('calendar')") + demoButton('file-excel', 'Ekspor Excel', 'exportExecutiveReportExcel()', true))}
    <section class="demo-section">${sectionHeading('Ringkasan 10 Detik', 'Executive Pulse', 'Arahkan kursor ke kartu untuk melihat detail')}
      <div class="demo-metric-grid">
        ${metricCard('Pendapatan', 'Rp 2,84 M', '↑ 18,4% dari bulan lalu', 'arrow-trend-up', '#33e818', 'good')}
        ${metricCard('Kas & Bank', 'Rp 5,33 M', 'Saldo buku jurnal saat ini', 'building-columns', '#60a5fa')}
        ${metricCard('Butuh Keputusan', '3 approval', 'Rp 136,5 jt · 1 overdue', 'signature', '#f59e0b', 'warn')}
        ${metricCard('Risiko Aktif', '3 alert', '1 SLA CRM · 2 invoice overdue', 'triangle-exclamation', '#fb7185', 'bad')}
        ${metricCard('Pengguna Baru', '1.284', '↑ 24,8% · conversion sehat', 'users', '#06b6d4', 'good')}
        ${metricCard('Konversi', '8,7%', 'Target bulan ini 10%', 'bullseye', '#8b5cf6')}
        ${metricCard('Event Mendatang', '6 event', '1.420 peserta terdaftar', 'microphone', '#d946ef')}
        ${metricCard('Pencairan', '12 pending', 'Rp 218,4 jt dalam antrean', 'money-bill-transfer', '#fb923c', 'warn')}
      </div>
    </section>
    <section class="demo-section demo-grid-main">
      <div class="demo-card"><div class="demo-card-header"><div><h3>Tren Pendapatan & Aktivitas</h3><p class="demo-card-subtitle">Performa enam bulan terakhir</p></div><span class="demo-badge green">+18,4%</span></div>
        <div class="demo-chart">${[48,62,57,73,69,91,83,96].map((h,i)=>`<div class="demo-chart-bar" style="--height:${h}%;--bar-color:${i===7?'#33e818':'#1f8f18'}"><span>${['Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep'][i]}</span></div>`).join('')}</div>
      </div>
      <div class="demo-card"><div class="demo-card-header"><div><h3>Keputusan Prioritas</h3><p class="demo-card-subtitle">Membutuhkan perhatian CEO</p></div>${demoButton('arrow-right','Lihat Semua',"showExecutiveView('approvals')")}</div>
        <div class="demo-list">
          ${appState.data.approvals.filter(a=>a.status==='pending').slice(0,3).map(a=>`<div class="demo-list-item"><div class="demo-list-icon" style="--item-color:${a.iconColor}"><i class="fas ${a.icon}"></i></div><div class="demo-list-copy"><strong>${a.title}</strong><span>${a.requester} · ${a.amount}</span></div><span class="demo-badge amber">${a.urgency}</span></div>`).join('')}
        </div>
      </div>
    </section>
    <section class="demo-section demo-grid-3">
      <div class="demo-card"><h3>Agenda Hari Ini</h3><p class="demo-card-subtitle">2 agenda utama · 1 hybrid</p><div class="demo-list"><div class="demo-list-item"><div class="demo-list-icon"><i class="fas fa-video"></i></div><div class="demo-list-copy"><strong>Rapat Pleno Direksi Q3</strong><span>14:00 · Boardroom HQ & Google Meet</span></div><span class="demo-badge green">90 mnt</span></div><div class="demo-list-item"><div class="demo-list-icon" style="--item-color:#f43f5e"><i class="fas fa-file-signature"></i></div><div class="demo-list-copy"><strong>Penandatanganan MOU</strong><span>16:30 · Menara Mandiri Jakarta</span></div></div></div></div>
      <div class="demo-card"><h3>Kesehatan Operasional</h3><p class="demo-card-subtitle">Status unit bisnis hari ini</p><div class="demo-list">${[['Payment Gateway','99,98%','green'],['Trade Engine','98,72%','green'],['WhatsApp SLA','91,20%','amber'],['CRM Response','87,40%','red']].map(x=>`<div class="demo-row-between"><span style="font-size:10px;color:var(--text-secondary)">${x[0]}</span><span class="demo-badge ${x[2]}">${x[1]}</span></div>`).join('')}</div></div>
      <div class="demo-card"><div class="demo-callout ai"><strong><i class="fas fa-wand-magic-sparkles text-accent"></i> AI Executive Insight</strong><p>Pertumbuhan pendaftaran meningkat 24,8%, tetapi SLA respons CRM turun. Prioritaskan penambahan shift CS sebelum kampanye webinar berikutnya.</p></div><div class="demo-list"><div class="demo-list-item"><div class="demo-list-icon" style="--item-color:#8b5cf6"><i class="fas fa-lightbulb"></i></div><div class="demo-list-copy"><strong>Rekomendasi hari ini</strong><span>Alihkan 2 agen ke antrean high-value leads.</span></div></div></div></div>
    </section>
  </div>`;
}

function renderDemoBriefing() {
  return `<div class="executive-page">${demoHero('Morning Intelligence', 'Briefing ringkas sebelum keputusan pertama.', 'Prioritas, perubahan penting, dan risiko disusun otomatis untuk CEO.', demoButton('volume-high','Putar Briefing',"demoAction('Briefing suara diputar dalam mode demo')",true))}
    <div class="demo-grid-main"><div class="demo-card"><h3>Briefing · Sabtu, 12 September 2026</h3><div class="demo-list">
      ${[['Pertumbuhan di atas target','Akuisisi pengguna mencapai 124% dari target mingguan.','#33e818','arrow-trend-up'],['Keputusan mendesak','Tiga pengajuan senilai Rp 136,5 juta menunggu otorisasi.','#f59e0b','signature'],['Risiko layanan CRM','SLA respons melewati 15 menit pada jam sibuk.','#f43f5e','triangle-exclamation'],['Peluang kemitraan','Draft MOU institusi siap masuk tahap legal review.','#3b82f6','handshake']].map(x=>`<div class="demo-list-item"><div class="demo-list-icon" style="--item-color:${x[2]}"><i class="fas fa-${x[3]}"></i></div><div class="demo-list-copy"><strong>${x[0]}</strong><span>${x[1]}</span></div></div>`).join('')}
    </div></div><div class="demo-card"><h3>Fokus CEO Hari Ini</h3><div class="demo-timeline"><div class="demo-timeline-item"><strong>09:30 · Review arus kas</strong><p>Validasi runway dan pencairan vendor.</p></div><div class="demo-timeline-item" style="--item-color:#3b82f6"><strong>14:00 · Rapat Pleno Direksi</strong><p>Roadmap Q4 dan target pertumbuhan.</p></div><div class="demo-timeline-item" style="--item-color:#f43f5e"><strong>16:30 · Penandatanganan MOU</strong><p>Finalisasi kerja sama institusi.</p></div></div></div></div>
  </div>`;
}

function renderDemoTasks() {
  const columns = [
    ['Prioritas', DEMO_TASKS.slice(0,2), '#f43f5e'],
    ['Sedang Berjalan', DEMO_TASKS.slice(2,4), '#3b82f6'],
    ['Siap Ditinjau', DEMO_TASKS.slice(4), '#33e818']
  ];
  return `<div class="executive-page">${demoHero('Execution Control', 'Tugas strategis terpantau dari satu tempat.', 'Delegasikan, tinjau progres, dan tuntaskan hambatan lintas divisi.', demoButton('plus','Tambah Tugas',"demoAction('Form tugas baru dibuka')",true))}<div class="demo-kanban">${columns.map(col=>`<div class="demo-kanban-column"><div class="demo-kanban-title"><span>${col[0]}</span><span class="demo-badge">${col[1].length}</span></div>${col[1].map(t=>`<div class="demo-task-card" onclick="demoAction('Detail tugas: ${t.title}')"><strong>${t.title}</strong><p>${t.owner} · ${t.due}</p><div class="demo-progress" style="--progress:${t.progress}%;--bar-color:${t.color}"><span></span></div><div class="demo-progress-meta"><span>${t.status}</span><b>${t.progress}%</b></div></div>`).join('')}</div>`).join('')}</div></div>`;
}

function renderDemoApprovals() {
  const rows = appState.data.approvals.map(a=>`<tr><td><strong>${a.title}</strong><br><span>${a.requester}</span></td><td>${a.department}</td><td><strong>${a.amount}</strong></td><td><span class="demo-badge ${a.status==='pending'?'amber':'green'}">${a.status==='pending'?'Menunggu':'Disetujui'}</span></td><td>${a.status==='pending'?`<button class="demo-button primary" onclick="demoApprovalDecision('${a.id}')">Setujui</button>`:'—'}</td></tr>`).join('');
  return `<div class="executive-page">${demoHero('Decision Queue', 'Approval Center', 'Setiap keputusan memiliki pemilik, nilai, urgensi, dan jejak audit.', demoButton('layer-group','Batch Approval','openBatchApprovalModal()',true))}<div class="demo-metric-grid">${metricCard('Menunggu','3','1 melewati SLA','hourglass-half','#f59e0b','warn')}${metricCard('Nilai Antrean','Rp 136,5 jt','Butuh keputusan CEO','money-bill-wave','#60a5fa')}${metricCard('Disetujui Bulan Ini','28','Median 3 jam 14 menit','circle-check','#33e818','good')}${metricCard('Ditolak','2','Dokumen tidak lengkap','circle-xmark','#fb7185')}</div><section class="demo-section demo-card">${sectionHeading('Daftar Keputusan','Pengajuan Terbaru','Klik Setujui untuk simulasi')}<div class="demo-table-wrap"><table class="demo-table"><thead><tr><th>Pengajuan</th><th>Divisi</th><th>Nilai</th><th>Status</th><th>Aksi</th></tr></thead><tbody>${rows}</tbody></table></div></section></div>`;
}

function renderDemoReminders() {
  return `<div class="executive-page">${demoHero('Personal Control', 'Tidak ada tenggat penting yang terlewat.', 'Reminder pribadi dan otomatis dari seluruh modul eksekutif.', demoButton('plus','Tambah Reminder',"demoAction('Form reminder baru dibuka')",true))}<div class="demo-grid-3">${[['Hari ini','Review laporan kas harian','09:30','#f43f5e'],['Hari ini','Konfirmasi kehadiran rapat pleno','13:30','#f59e0b'],['Besok','Tinjau MOU kemitraan korporasi','10:00','#3b82f6'],['14 Sep','Persetujuan payroll & komisi','08:30','#33e818'],['16 Sep','Audit keamanan triwulanan','14:00','#8b5cf6'],['18 Sep','Evaluasi OKR tengah bulan','15:30','#06b6d4']].map(x=>`<div class="demo-card"><div class="demo-row-between"><span class="demo-badge">${x[0]}</span><i class="fas fa-bell" style="color:${x[3]}"></i></div><h3 style="margin-top:20px">${x[1]}</h3><p class="demo-card-subtitle">${x[2]} WIB · Pengingat otomatis</p><div class="demo-actions" style="margin-top:18px">${demoButton('check','Tandai Selesai',"demoAction('Reminder ditandai selesai')")}</div></div>`).join('')}</div></div>`;
}

function renderDemoMeetings() {
  return `<div class="executive-page">${demoHero('Executive Collaboration', 'Meeting Center', 'Agenda rapat, peserta, materi, dan tindak lanjut tersimpan dalam satu alur.', demoButton('plus','Buat Meeting',"demoAction('Form meeting baru dibuka')",true))}<div class="demo-grid-main"><div class="demo-card"><h3>Meeting Berikutnya</h3><div class="demo-callout" style="margin-top:15px"><span class="demo-badge green">Mulai 38 menit lagi</span><strong style="margin-top:14px;font-size:18px">Rapat Pleno Direksi Q3</strong><p>14:00–15:30 WIB · Boardroom HQ & Google Meet Hybrid</p><div class="demo-actions" style="margin-top:16px">${demoButton('video','Masuk Rapat',"demoAction('Ruang rapat demo dibuka')",true)}${demoButton('folder-open','Buka Materi',"demoAction('Materi rapat ditampilkan')")}</div></div></div><div class="demo-card"><h3>Peserta</h3><div class="demo-avatar-stack" style="margin-top:18px"><div class="demo-avatar">HP</div><div class="demo-avatar">RS</div><div class="demo-avatar">AW</div><div class="demo-avatar">DK</div><div class="demo-avatar">+3</div></div><div class="demo-list"><div class="demo-list-item"><div class="demo-list-copy"><strong>7 peserta dikonfirmasi</strong><span>5 hadir langsung · 2 melalui Google Meet</span></div></div></div></div></div><section class="demo-section demo-card">${sectionHeading('Jadwal','Meeting Mendatang')}<div class="demo-table-wrap"><table class="demo-table"><thead><tr><th>Waktu</th><th>Meeting</th><th>Pemilik</th><th>Peserta</th><th>Status</th></tr></thead><tbody><tr><td>Hari ini · 14:00</td><td><strong>Rapat Pleno Direksi Q3</strong></td><td>CEO Office</td><td>7 orang</td><td><span class="demo-badge green">Confirmed</span></td></tr><tr><td>14 Sep · 10:30</td><td><strong>Weekly Product Review</strong></td><td>CTO</td><td>9 orang</td><td><span class="demo-badge blue">Online</span></td></tr><tr><td>16 Sep · 15:00</td><td><strong>Finance Steering Committee</strong></td><td>CFO</td><td>5 orang</td><td><span class="demo-badge amber">Tentative</span></td></tr></tbody></table></div></section></div>`;
}

function renderDemoAiSummary() {
  return `<div class="executive-page">${demoHero('ALMAI Intelligence', 'AI merangkum sinyal terpenting untuk keputusan Anda.', 'Analisis lintas keuangan, pengguna, layanan, CRM, dan agenda eksekutif.', demoButton('arrows-rotate','Buat Ulang Summary',"demoAction('AI Summary diperbarui dari data demo')",true))}<div class="demo-grid-main"><div class="demo-card"><div class="demo-callout ai"><strong><i class="fas fa-robot"></i> Ringkasan Eksekutif</strong><p>ALMAI mencatat momentum pertumbuhan positif dengan pendapatan naik 18,4% dan 1.284 pengguna baru. Risiko utama berada pada penurunan SLA CRM serta dua invoice yang mendekati jatuh tempo. Sistem menyarankan persetujuan tambahan kapasitas server sebelum webinar akbar untuk menjaga reliabilitas layanan.</p></div><div class="demo-list">${[['Peluang','Konversi kampanye WPA naik 2,1 poin.','#33e818'],['Risiko','Respons lead premium melambat pada pukul 19:00–21:00.','#f43f5e'],['Rekomendasi','Setujui capacity scaling dan tambah dua agen malam.','#3b82f6']].map(x=>`<div class="demo-list-item"><div class="demo-list-icon" style="--item-color:${x[2]}"><i class="fas fa-wand-magic-sparkles"></i></div><div class="demo-list-copy"><strong>${x[0]}</strong><span>${x[1]}</span></div></div>`).join('')}</div></div><div class="demo-card"><h3>Confidence Score</h3><div style="font-size:52px;font-weight:900;color:#33e818;margin:22px 0 4px">92%</div><p class="demo-card-subtitle">Berdasarkan kelengkapan 14 sumber data</p><div class="demo-progress" style="--progress:92%"><span></span></div><div class="demo-list"><div class="demo-row-between"><span class="demo-section-note">Keuangan</span><span class="demo-badge green">Lengkap</span></div><div class="demo-row-between"><span class="demo-section-note">CRM</span><span class="demo-badge green">Lengkap</span></div><div class="demo-row-between"><span class="demo-section-note">Market eksternal</span><span class="demo-badge amber">Terlambat 8m</span></div></div></div></div></div>`;
}

function renderDemoNotifications() {
  const items = appState.data.notifications.slice(0,6);
  return `<div class="executive-page">${demoHero('Signal & Alerts', 'Notification Center', 'Pemberitahuan diprioritaskan berdasarkan dampak, urgensi, dan kewenangan.', demoButton('check-double','Tandai Semua Dibaca',"demoAction('Semua notifikasi ditandai dibaca')",true))}<div class="demo-card"><div class="demo-list">${items.map(n=>`<div class="demo-list-item"><div class="demo-list-icon" style="--item-color:${n.color||'#33e818'}"><i class="fas ${n.icon||'fa-bell'}"></i></div><div class="demo-list-copy"><strong>${n.title}</strong><span>${n.time} · ${n.categoryName}</span></div><span class="demo-badge ${n.unread?'red':'green'}">${n.unread?'Baru':'Dibaca'}</span></div>`).join('')}</div></div></div>`;
}

function renderDemoPerformance() {
  return `<div class="executive-page">${demoHero('Performance Intelligence', 'Kinerja perusahaan terhadap target strategis.', 'Pantau pertumbuhan, profitabilitas, kualitas layanan, dan produktivitas.', demoButton('download','Unduh Laporan',"demoAction('Laporan kinerja disiapkan')",true))}<div class="demo-metric-grid">${metricCard('Revenue Growth','18,4%','Target 15%','arrow-trend-up','#33e818','good')}${metricCard('Gross Margin','64,2%','↑ 3,1 poin','chart-pie','#60a5fa','good')}${metricCard('Customer NPS','71','Kategori excellent','face-smile','#8b5cf6','good')}${metricCard('Team Productivity','87%','Target 85%','people-group','#06b6d4','good')}</div><section class="demo-section demo-grid-2"><div class="demo-card"><h3>Target vs Realisasi</h3><div class="demo-list">${[['Pendapatan Bulanan',84],['Akuisisi Pengguna',92],['Retensi Member',78],['Kemitraan Institusi',68],['SLA Layanan',87]].map(x=>`<div><div class="demo-row-between"><span style="font-size:10px">${x[0]}</span><b style="font-size:10px">${x[1]}%</b></div><div class="demo-progress" style="--progress:${x[1]}%"><span></span></div></div>`).join('')}</div></div><div class="demo-card"><h3>Performa Unit Bisnis</h3><div class="demo-chart">${[72,88,61,93,79,84].map((h,i)=>`<div class="demo-chart-bar" style="--height:${h}%;--bar-color:${['#33e818','#3b82f6','#f59e0b','#8b5cf6','#06b6d4','#fb7185'][i]}"><span>${['Edu','WPA','AIWE','CRM','Event','Ops'][i]}</span></div>`).join('')}</div></div></section></div>`;
}

function renderDemoFinance() {
  const bills = (appState.data.bills || []).slice(0,5);
  return `<div class="executive-page">${demoHero('Financial Control', 'Kas, invoice, dan kewajiban dalam satu pandangan.', 'Angka terhubung dengan jurnal, pembayaran, dan approval.', demoButton('file-invoice','Lihat Faktur',"openOfficialInvoiceModal('INV-AWS-2026-08-882')")+demoButton('file-excel','Ekspor',"demoAction('Laporan keuangan diekspor')",true))}<div class="demo-metric-grid">${metricCard('Saldo Kas','Rp 5,33 M','Across 6 rekening','building-columns','#33e818')}${metricCard('Arus Kas Masuk','Rp 1,28 M','Bulan berjalan','arrow-down','#60a5fa','good')}${metricCard('Arus Kas Keluar','Rp 742 jt','58% dari inflow','arrow-up','#f59e0b')}${metricCard('Tagihan Terbuka','Rp 218 jt','2 melewati jatuh tempo','file-circle-exclamation','#fb7185','bad')}</div><section class="demo-section demo-grid-main"><div class="demo-card"><h3>Arus Kas 8 Minggu</h3><div class="demo-chart">${[56,70,62,82,68,91,76,88].map((h,i)=>`<div class="demo-chart-bar" style="--height:${h}%;--bar-color:${i%2?'#33e818':'#176d16'}"><span>W${i+1}</span></div>`).join('')}</div></div><div class="demo-card"><h3>Tagihan Prioritas</h3><div class="demo-list">${(bills.length?bills:[{vendor:'AWS Cloud',amount:'Rp 18.750.000',dueDate:'13 Sep'},{vendor:'Biznet',amount:'Rp 4.300.000',dueDate:'14 Sep'},{vendor:'Cloudflare',amount:'Rp 7.850.000',dueDate:'18 Sep'}]).map(b=>`<div class="demo-list-item"><div class="demo-list-icon" style="--item-color:#f59e0b"><i class="fas fa-receipt"></i></div><div class="demo-list-copy"><strong>${b.vendor||b.title||'Vendor'}</strong><span>${b.amount||b.total||'Rp 0'} · ${b.dueDate||b.date||'Segera'}</span></div></div>`).join('')}</div></div></section></div>`;
}

function renderDemoCrm() {
  return `<div class="executive-page">${demoHero('Customer Intelligence', 'CRM Snapshot', 'Pantau pipeline, respons tim, sentimen, dan peluang bernilai tinggi.', demoButton('filter','Atur Segment',"demoAction('Filter CRM dibuka')",true))}<div class="demo-metric-grid">${metricCard('Lead Aktif','3.482','↑ 16% bulan ini','address-book','#33e818','good')}${metricCard('High-value Lead','184','Potensi Rp 920 jt','gem','#8b5cf6')}${metricCard('Avg. Response','12m 48s','Target di bawah 10m','stopwatch','#f59e0b','warn')}${metricCard('Closing Rate','22,6%','↑ 2,4 poin','handshake','#60a5fa','good')}</div><section class="demo-section demo-grid-main"><div class="demo-card"><h3>Pipeline Penjualan</h3><div class="demo-list">${[['New Leads',3482,100,'#3b82f6'],['Qualified',1720,71,'#06b6d4'],['Consultation',864,48,'#8b5cf6'],['Proposal',421,30,'#f59e0b'],['Won',189,18,'#33e818']].map(x=>`<div><div class="demo-row-between"><span style="font-size:10px">${x[0]}</span><b style="font-size:10px">${x[1].toLocaleString('id-ID')}</b></div><div class="demo-progress" style="--progress:${x[2]}%;--bar-color:${x[3]}"><span></span></div></div>`).join('')}</div></div><div class="demo-card"><h3>Alert SLA</h3><div class="demo-list"><div class="demo-list-item"><div class="demo-list-icon" style="--item-color:#f43f5e"><i class="fas fa-user-clock"></i></div><div class="demo-list-copy"><strong>18 lead belum direspons</strong><span>6 lead premium · antrean malam</span></div><span class="demo-badge red">Overdue</span></div><div class="demo-list-item"><div class="demo-list-icon" style="--item-color:#f59e0b"><i class="fas fa-face-meh"></i></div><div class="demo-list-copy"><strong>Sentimen turun 4%</strong><span>Keluhan dominan: waktu respons</span></div></div></div></div></section></div>`;
}

function renderDemoOkr() {
  return `<div class="executive-page">${demoHero('Strategy Execution', 'OKR & Sasaran Strategis', 'Hubungkan target perusahaan dengan realisasi setiap unit.', demoButton('plus','Tambah Objective',"demoAction('Form objective baru dibuka')",true))}<div class="demo-grid-3">${[
    ['O1 · Pertumbuhan Berkelanjutan','Mencapai 25.000 pengguna aktif dan revenue Rp 12 miliar.',84,'#33e818',['Pengguna aktif 21.420','Revenue YTD Rp 9,8 M','Retention 82%']],
    ['O2 · Operational Excellence','Menjaga SLA layanan dan reliabilitas platform di atas target.',76,'#3b82f6',['Uptime 99,94%','Response time 12m','NPS 71']],
    ['O3 · Institutional Expansion','Membangun kemitraan strategis berskala nasional.',68,'#8b5cf6',['4 MOU aktif','2 pilot institusi','Pipeline Rp 3,4 M']]
  ].map(o=>`<div class="demo-card"><span class="demo-badge" style="color:${o[3]}">${o[0].split(' · ')[0]}</span><h3 style="margin-top:15px">${o[0].split(' · ')[1]}</h3><p class="demo-card-subtitle" style="min-height:42px">${o[1]}</p><div class="demo-progress" style="--progress:${o[2]}%;--bar-color:${o[3]}"><span></span></div><div class="demo-progress-meta"><span>Progress</span><b>${o[2]}%</b></div><div class="demo-list">${o[4].map(k=>`<div class="demo-row-between"><span style="font-size:9px;color:var(--text-secondary)">${k}</span><i class="fas fa-circle-check" style="color:${o[3]};font-size:9px"></i></div>`).join('')}</div></div>`).join('')}</div></div>`;
}

function renderDemoAudit() {
  const logs = [
    ['13:24:08','CEO','Membuka Executive Dashboard','Dashboard','Berhasil','#33e818'],
    ['13:18:42','Anita W.','Mengirim approval pencairan komisi','Finance','Menunggu','#f59e0b'],
    ['12:55:17','Rian S.','Memperbarui proposal server cluster','Technology','Tercatat','#3b82f6'],
    ['11:31:02','CEO','Menyetujui MOU kemitraan institusi','Approval','Berhasil','#33e818'],
    ['10:44:33','System','Sinkronisasi CRM dan billing selesai','Integration','Berhasil','#06b6d4'],
    ['09:12:16','Security Bot','Login CEO terverifikasi dengan MFA','Security','Aman','#8b5cf6']
  ];
  return `<div class="executive-page">${demoHero('Governance & Control', 'Audit Trail', 'Jejak aktivitas penting transparan, terurut, dan siap ditinjau.', demoButton('download','Ekspor Audit',"demoAction('Audit trail diekspor')",true))}<div class="demo-card"><div class="demo-table-wrap"><table class="demo-table"><thead><tr><th>Waktu</th><th>Pelaku</th><th>Aktivitas</th><th>Modul</th><th>Status</th></tr></thead><tbody>${logs.map(l=>`<tr><td>${l[0]}</td><td><strong>${l[1]}</strong></td><td>${l[2]}</td><td>${l[3]}</td><td><span class="demo-badge" style="color:${l[5]}">${l[4]}</span></td></tr>`).join('')}</tbody></table></div></div></div>`;
}

window.demoAction = function(message) {
  window.showToast('success', `${message} — simulasi berhasil`);
};

window.demoApprovalDecision = function(approvalId) {
  const approval = appState.data.approvals.find(item => item.id === approvalId);
  if (!approval || approval.status !== 'pending') return;
  approval.status = 'approved';
  approval.approvedAt = 'Baru saja · Demo CEO';
  updateTopbarBadges();
  document.getElementById('demoViewContainer').innerHTML = renderDemoApprovals();
  window.showToast('success', 'Pengajuan berhasil disetujui dalam mode demo');
};
