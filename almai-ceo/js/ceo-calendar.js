/**
 * ALMAI CEO Dashboard - Calendar-First Executive Engine
 * Harmonized with ALMAI Platform Design Tokens (#33e818)
 * 
 * Features:
 * - Direct aggregation inside date cells:
 *   - Green ✓: Notifications & items already approved / viewed
 *   - Blue number: Approvals completed that day
 *   - Red dot/number: Unread notifications & pending actions
 * - Interactive click on any date opens bottom sheet / modal with full day details
 * - Category filter support
 * - Clean status legend
 */

class CEOCalendar {
  constructor(containerId, events) {
    this.container = document.getElementById(containerId);
    this.events = events || [];
    this.currentDate = new Date(2026, 7, 22); // Aug 22, 2026 (Today)
    this.viewDate = new Date(2026, 7, 1);
    this.selectedDateStr = "2026-08-22";
    this.activeFilter = "all";

    this.monthNames = [
      "Januari", "Februari", "Maret", "April", "Mei", "Juni",
      "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    this.dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

    this.init();
  }

  init() {
    this.render();
  }

  setFilter(filter) {
    this.activeFilter = filter;
    this.render();
  }

  prevMonth() {
    this.viewDate.setMonth(this.viewDate.getMonth() - 1);
    this.render();
  }

  nextMonth() {
    this.viewDate.setMonth(this.viewDate.getMonth() + 1);
    this.render();
  }

  goToToday() {
    this.viewDate = new Date(2026, 7, 1);
    this.selectedDateStr = "2026-08-22";
    this.render();
    if (window.showToast) {
      window.showToast('info', 'Menampilkan Kalender Bulan Ini (Agustus 2026)');
    }
  }

  formatDateKey(year, month, day) {
    const m = String(month + 1).padStart(2, '0');
    const d = String(day).padStart(2, '0');
    return `${year}-${m}-${d}`;
  }

  render() {
    if (!this.container) return;

    const year = this.viewDate.getFullYear();
    const month = this.viewDate.getMonth();
    const monthTitle = `${this.monthNames[month]} ${year}`;

    // Update Header Month Title if exists
    const titleEl = document.getElementById('calCurrentMonthText');
    if (titleEl) {
      titleEl.innerText = monthTitle;
    }

    // Calendar Math
    const firstDayIndex = new Date(year, month, 1).getDay();
    const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
    const totalDaysPrevMonth = new Date(year, month, 0).getDate();

    let html = `
      <div class="calendar-wrapper">
        <div class="calendar-grid">
          ${this.dayNames.map(d => `<div class="calendar-header-day">${d}</div>`).join('')}
    `;

    // Previous month filler days
    for (let i = firstDayIndex - 1; i >= 0; i--) {
      const prevDayNum = totalDaysPrevMonth - i;
      const prevDateKey = this.formatDateKey(year, month - 1, prevDayNum);
      html += this.renderDayCell(prevDayNum, prevDateKey, true);
    }

    // Current month days
    for (let day = 1; day <= totalDaysInMonth; day++) {
      const dateKey = this.formatDateKey(year, month, day);
      const isToday = (year === 2026 && month === 7 && day === 22);
      const isSelected = (dateKey === this.selectedDateStr);
      html += this.renderDayCell(day, dateKey, false, isToday, isSelected);
    }

    // Next month filler days (to fill 35 or 42 grid slots)
    const totalRendered = firstDayIndex + totalDaysInMonth;
    const remainingSlots = (totalRendered > 35 ? 42 : 35) - totalRendered;
    for (let nextDay = 1; nextDay <= remainingSlots; nextDay++) {
      const nextDateKey = this.formatDateKey(year, month + 1, nextDay);
      html += this.renderDayCell(nextDay, nextDateKey, true);
    }

    html += `
        </div>
        
        <!-- Bottom Legend Strip for Status Indicators -->
        <div class="calendar-legend-bar" aria-label="Keterangan Indikator Kalender">
          <div class="legend-title">
            <i class="fas fa-info-circle text-accent"></i>
            <span>Indikator Status Harian:</span>
          </div>
          <div class="legend-items-wrap">
            <div class="legend-pill done">
              <span class="legend-badge"><i class="fas fa-check"></i></span>
              <span class="legend-text"><strong>Green ✓</strong> : Notifikasi & aktivitas telah disetujui / dilihat (Done)</span>
            </div>
            <div class="legend-pill approval">
              <span class="legend-badge"><i class="fas fa-signature"></i> #</span>
              <span class="legend-text"><strong>Blue number</strong> : Persetujuan selesai pada hari tersebut</span>
            </div>
            <div class="legend-pill unread">
              <span class="legend-badge"><span class="legend-pulse-dot"></span> #</span>
              <span class="legend-text"><strong>Red dot/number</strong> : Notifikasi & persetujuan belum dibaca / pending (Unread)</span>
            </div>
          </div>
        </div>
      </div>
    `;

    this.container.innerHTML = html;
    this.attachCellListeners();
  }

  renderDayCell(dayNum, dateKey, isOtherMonth = false, isToday = false, isSelected = false) {
    let classes = ['calendar-cell'];
    if (isOtherMonth) classes.push('other-month');
    if (isToday) classes.push('today');
    if (isSelected) classes.push('selected');

    // Get aggregated summary for this day
    const summary = window.getDaySummary ? window.getDaySummary(dateKey) : {
      hasActivity: false,
      doneCount: 0,
      completedApprovalsCount: 0,
      unreadCount: 0,
      events: []
    };

    // Filter events if activeFilter is set
    let visibleEvents = summary.events || [];
    if (this.activeFilter !== 'all') {
      visibleEvents = visibleEvents.filter(e => e.category === this.activeFilter);
    }

    // Build Status Indicators HTML
    let indicatorsHtml = '';
    const hasIndicators = summary.doneCount > 0 || summary.completedApprovalsCount > 0 || summary.unreadCount > 0;

    if (hasIndicators) {
      indicatorsHtml += '<div class="cell-indicators-row">';

      // 1. Green ✓ = notifications & items already approved / viewed
      if (summary.doneCount > 0) {
        indicatorsHtml += `
          <span class="ind-badge ind-done" title="${summary.doneCount} Disetujui / Selesai (Done)">
            <i class="fas fa-check"></i>
            ${summary.doneCount > 1 ? `<span class="ind-num">${summary.doneCount}</span>` : ''}
          </span>
        `;
      }

      // 2. Blue number = approvals completed that day
      if (summary.completedApprovalsCount > 0) {
        indicatorsHtml += `
          <span class="ind-badge ind-approval" title="${summary.completedApprovalsCount} Persetujuan Selesai">
            <i class="fas fa-signature"></i>
            <span class="ind-num">${summary.completedApprovalsCount}</span>
          </span>
        `;
      }

      // 3. Red dot / number = unread notifications & pending actions
      if (summary.unreadCount > 0) {
        indicatorsHtml += `
          <span class="ind-badge ind-unread" title="${summary.unreadCount} Notifikasi Belum Dibaca / Pending">
            <span class="unread-pulse-dot"></span>
            <span class="ind-num">${summary.unreadCount}</span>
          </span>
        `;
      }

      indicatorsHtml += '</div>';
    }

    // Build Activity Preview Bars
    let eventBarsHtml = '';
    if (visibleEvents.length > 0) {
      eventBarsHtml = visibleEvents.slice(0, 2).map(evt => {
        const catClass = `event-${evt.category || 'general'}`;
        const timeShort = evt.time ? evt.time.split(' - ')[0].trim() : '';
        return `
          <div class="cell-event-chip ${catClass}" title="${evt.time} • ${evt.title}">
            ${timeShort ? `<span class="chip-time">${timeShort}</span>` : ''}
            <span class="chip-title">${evt.title}</span>
          </div>
        `;
      }).join('');

      if (visibleEvents.length > 2) {
        eventBarsHtml += `
          <div class="cell-more-tag">
            +${visibleEvents.length - 2} kegiatan lainnya
          </div>
        `;
      }
    } else if (!hasIndicators && !isOtherMonth) {
      // Subtle hint of free/empty day
      eventBarsHtml = `<div class="cell-empty-hint">Kosong</div>`;
    }

    return `
      <div class="${classes.join(' ')}" data-date="${dateKey}" tabindex="0" role="button" aria-label="Tanggal ${dateKey}, klik untuk rincian harian">
        <div class="cell-header">
          <span class="cell-day-number ${isToday ? 'today-circle' : ''}">${dayNum}</span>
          ${isToday ? '<span class="cell-today-pill">HARI INI</span>' : ''}
          ${indicatorsHtml}
        </div>
        
        <div class="cell-body-events">
          ${eventBarsHtml}
        </div>
      </div>
    `;
  }

  attachCellListeners() {
    const cells = this.container.querySelectorAll('.calendar-cell');
    cells.forEach(cell => {
      cell.addEventListener('click', (e) => {
        const dateKey = cell.getAttribute('data-date');
        this.selectedDateStr = dateKey;
        
        // Highlight active cell without full rebuild
        cells.forEach(c => c.classList.remove('selected'));
        cell.classList.add('selected');

        if (window.openDayDetailSheet) {
          window.openDayDetailSheet(dateKey);
        }
      });

      // Keyboard Accessibility (Enter / Space)
      cell.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          cell.click();
        }
      });
    });
  }
}
