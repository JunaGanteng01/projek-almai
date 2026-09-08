<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
  
  <style type="text/css">
    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }
  </style>
<style>
    body {
      background-color: #050505;
      color: #ffffff;
    }

    .bg-grid {
      background-size: 40px 40px;
      background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
      mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #0a0a0a;
    }

    ::-webkit-scrollbar-thumb {
      background: #333;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #33e818;
    }

    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 100;
      align-items: center;
      justify-content: center;
    }

    .modal.active {
      display: flex;
    }
  </style>
<style>
    :root {
      --gold: #33E818;
      --gold-dim: #2ebc16;
      --primary: #33E818;
      --primary-dim: #2ebc16;
      --primary-glow: rgba(51, 232, 24, 0.25);
      --green: #33E818;
      --red: #ef4444;
      --blue: #3b82f6;
      --bg: #050505;
      --bg2: #0a0a0a;
      --bg3: #111111;
      --border: rgba(255, 255, 255, 0.08);
      --text: #f1f5f9;
      --muted: #94a3b8;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ─── BACKGROUND ─── */
    .bg-grid {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: radial-gradient(rgba(51, 232, 24, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    .glow-orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(120px);
      pointer-events: none;
      z-index: 0;
    }

    .orb-1 {
      width: 600px;
      height: 600px;
      background: rgba(51, 232, 24, 0.06);
      top: -100px;
      left: -100px;
    }

    .orb-2 {
      width: 500px;
      height: 500px;
      background: rgba(59, 130, 246, 0.05);
      bottom: -100px;
      right: -50px;
    }

    /* ─── NAVBAR ─── */
    .signalplus500-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(8, 12, 20, 0.8);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
    }

    .nav-logo-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--gold), #e8a805);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      font-weight: 900;
      color: #080c14;
    }

    .nav-logo-text {
      font-weight: 700;
      font-size: 1rem;
      color: var(--text);
    }

    .nav-logo-sub {
      font-size: 0.65rem;
      color: var(--gold);
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }

    .nav-badge {
      padding: 0.3rem 0.8rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--green);
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .nav-badge::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--green);
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1)
      }

      50% {
        opacity: 0.5;
        transform: scale(1.3)
      }
    }

    /* ─── HERO ─── */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
      padding: 6rem 1.5rem 3rem;
      text-align: center;
    }

    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.3rem 0.75rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 20px;
      font-size: clamp(0.55rem, 2.5vw, 0.75rem);
      font-weight: 600;
      color: var(--gold);
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      white-space: nowrap;
    }

    .hero h1 {
      font-size: clamp(2.2rem, 6vw, 4.5rem);
      font-weight: 900;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 1.5rem;
    }

    .hero h1 span {
      background: linear-gradient(135deg, var(--gold) 0%, #fff 60%, var(--gold) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-sub {
      font-size: clamp(1rem, 2vw, 1.2rem);
      color: var(--muted);
      max-width: 600px;
      margin: 0 auto 2.5rem;
      line-height: 1.7;
    }

    .hero-cta {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 4rem;
    }

    .btn-primary {
      padding: 0.85rem 2rem;
      background: linear-gradient(135deg, var(--gold), var(--gold-dim));
      color: #080c14;
      font-weight: 700;
      font-size: 0.95rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
      box-shadow: 0 0 30px rgba(51, 232, 24, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 40px rgba(51, 232, 24, 0.5);
    }

    .btn-outline {
      padding: 0.85rem 2rem;
      background: transparent;
      color: var(--text);
      font-weight: 600;
      font-size: 0.95rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.2);
    }

    /* ─── STATS TICKER ─── */
    .ticker-wrap {
      overflow: hidden;
      background: rgba(51, 232, 24, 0.05);
      border-top: 1px solid rgba(51, 232, 24, 0.1);
      border-bottom: 1px solid rgba(51, 232, 24, 0.1);
      padding: 0.6rem 0;
    }

    .ticker-track {
      display: flex;
      width: max-content;
      animation: ticker 30s linear infinite;
    }

    .ticker-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0 2rem;
      white-space: nowrap;
      font-size: 0.8rem;
      font-family: 'JetBrains Mono', monospace;
    }

    .ticker-label {
      color: var(--muted);
    }

    .ticker-val {
      font-weight: 600;
    }

    .up {
      color: var(--green);
    }

    .dn {
      color: var(--red);
    }

    @keyframes ticker {
      from {
        transform: translateX(0)
      }

      to {
        transform: translateX(-50%)
      }
    }

    /* ─── LIVE SIGNAL CARD ─── */
    .section {
      position: relative;
      z-index: 1;
      padding: 5rem 1.5rem;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-label {
      text-align: center;
      margin-bottom: 3rem;
    }

    .section-label h2 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800;
      margin-bottom: 0.75rem;
    }

    .section-label p {
      color: var(--muted);
      max-width: 500px;
      margin: 0 auto;
    }

    .signal-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .signal-card {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
      transition: all 0.3s;
    }

    .signal-card:hover {
      transform: translateY(-4px);
      border-color: rgba(51, 232, 24, 0.3);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .signal-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .signal-card.buy::before {
      background: linear-gradient(90deg, transparent, var(--green), transparent);
    }

    .signal-card.sell::before {
      background: linear-gradient(90deg, transparent, var(--red), transparent);
    }

    .signal-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1.2rem;
    }

    .signal-pair {
      font-size: 1.1rem;
      font-weight: 800;
    }

    .signal-pair-sub {
      font-size: 0.7rem;
      color: var(--muted);
      margin-top: 0.1rem;
    }

    .signal-direction {
      padding: 0.3rem 0.9rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .dir-buy {
      background: rgba(51, 232, 24, 0.15);
      color: var(--green);
      border: 1px solid rgba(51, 232, 24, 0.3);
    }

    .dir-sell {
      background: rgba(239, 68, 68, 0.15);
      color: var(--red);
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .signal-levels {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 0.8rem;
      margin-bottom: 1.2rem;
    }

    .level-item {
      text-align: center;
    }

    .level-label {
      font-size: 0.65rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 0.2rem;
    }

    .level-val {
      font-size: 0.95rem;
      font-weight: 700;
      font-family: 'JetBrains Mono', monospace;
    }

    .level-usd {
      font-size: 0.65rem;
      color: var(--muted);
      margin-top: 0.1rem;
    }

    .signal-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
      font-size: 0.75rem;
      color: var(--muted);
    }

    .signal-ai-badge {
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .ai-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--gold);
      animation: pulse 2s infinite;
    }

    /* ─── MONEY MANAGEMENT ─── */
    .mm-section {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 3rem;
    }

    .mm-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 2rem;
      margin-top: 2rem;
    }

    @media (max-width: 992px) {
      .mm-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      }
    }

    .mm-card {
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.5rem;
      text-align: center;
      transition: all 0.2s;
    }

    .mm-card:hover {
      border-color: rgba(51, 232, 24, 0.3);
    }

    .mm-icon {
      font-size: 2rem;
      margin-bottom: 0.75rem;
    }

    .mm-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--muted);
      margin-bottom: 0.5rem;
    }

    .mm-val {
      font-size: 1.4rem;
      font-weight: 800;
    }

    .mm-sub {
      font-size: 0.75rem;
      color: var(--muted);
      margin-top: 0.3rem;
      font-family: 'JetBrains Mono', monospace;
    }

    .mm-rate {
      font-size: 0.65rem;
      color: var(--gold);
      margin-top: 0.2rem;
    }

    /* ─── FEATURES ─── */
    .features-grid {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      max-width: 800px;
      margin: 0 auto;
      text-align: left;
    }

    .feature-card {
      background: transparent;
      border: none;
      padding: 0;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      transition: none;
    }

    .feature-card:hover {
      transform: none;
    }

    .feature-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-bottom: 0;
      flex-shrink: 0;
    }
    
    .feature-icon svg {
      width: 18px;
      height: 18px;
    }

    .feature-card-text {
      display: flex;
      flex-direction: column;
    }

    .feature-card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
      color: #fff;
    }

    .feature-card p {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.4;
      margin: 0;
    }

    /* ─── INSTRUMENTS ─── */
    .instruments {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1rem;
    }

    .instr-card {
      background: rgba(51, 232, 24, 0.05);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 12px;
      padding: 1.2rem 1rem;
      text-align: center;
      transition: all 0.3s;
      cursor: default;
    }

    .instr-card:hover {
      border-color: rgba(51, 232, 24, 0.6);
      background: rgba(51, 232, 24, 0.1);
    }

    .instr-emoji {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }

    .instr-name {
      font-size: 0.8rem;
      font-weight: 700;
    }

    .instr-full {
      font-size: 0.65rem;
      color: var(--muted);
      margin-top: 0.2rem;
    }

    .instr-stats {
      margin-top: 0.8rem;
      padding-top: 0.8rem;
      border-top: 1px dashed rgba(255, 255, 255, 0.1);
      font-size: 0.7rem;
      color: var(--muted);
      text-align: left;
      line-height: 1.4;
    }

    .instr-stats-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.15rem;
    }

    .instr-stats-label {
      color: rgba(255, 255, 255, 0.7);
    }

    .instr-stats-val {
      font-weight: 700;
      color: var(--text);
    }

    .text-red {
      color: #ef4444 !important;
    }

    .text-green {
      color: #33e818 !important;
    }

    .text-gold {
      color: #f5c842 !important;
    }


    /* ─── SCHEDULE ─── */
    .schedule-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .sched-item {
      display: flex;
      align-items: flex-start;
      gap: 1.2rem;
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.2rem 1.5rem;
    }

    .sched-time {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--gold);
      white-space: nowrap;
      min-width: 80px;
    }

    .sched-content h4 {
      font-size: 0.9rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
    }

    .sched-content p {
      font-size: 0.8rem;
      color: var(--muted);
    }

    /* ─── FOOTER ─── */
    footer {
      position: relative;
      z-index: 1;
      border-top: 1px solid var(--border);
      padding: 2rem 1.5rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.8rem;
    }

    .footer-logo {
      font-weight: 800;
      font-size: 1.1rem;
      color: var(--gold);
      margin-bottom: 0.5rem;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 1rem;
    }

    .footer-links a {
      color: var(--muted);
      text-decoration: none;
      transition: color 0.2s;
    }

    .footer-links a:hover {
      color: var(--text);
    }

    /* Live badge */
    .live-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.2rem 0.6rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 10px;
      font-size: 0.65rem;
      font-weight: 700;
      color: var(--green);
    }

    .live-badge::before {
      content: '';
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: var(--green);
      animation: pulse 1.5s infinite;
    }

    /* Rate display */
    #live-rate {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      color: var(--gold);
      font-size: 0.9rem;
    }

    /* Scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    @media (max-width:640px) {
      nav {
        padding: 1rem;
      }

      .mm-section {
        padding: 2rem 1rem;
      }

      .signal-levels {
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem;
      }
    }

    /* ─── STEPS SECTION ─── */
    .step-grid {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 1.5rem;
      margin-top: 2.5rem;
    }

    @media (min-width: 768px) {
      .step-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .step-card {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
      transition: all 0.3s ease;
    }

    .step-card:hover {
      transform: translateY(-5px);
      border-color: rgba(51, 232, 24, 0.3);
      box-shadow: 0 10px 30px rgba(51, 232, 24, 0.05);
    }

    .step-number {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      font-size: 0.8rem;
      font-weight: 700;
      color: var(--gold);
      background: rgba(51, 232, 24, 0.1);
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .step-icon {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
    }

    .step-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.75rem;
    }

    .step-desc {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 2rem;
      flex-grow: 1;
    }

    .step-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.8rem 1.5rem;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      color: var(--text);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .step-btn:hover {
      background: linear-gradient(135deg, var(--gold), #33e818);
      color: #080c14;
      border-color: transparent;
      box-shadow: 0 0 15px rgba(51, 232, 24, 0.2);
    }

    /* Marquee Styles */
    .marquee-frame {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.02);
      padding: 0.6rem 1rem;
      box-shadow: 0 2px 18px rgba(0, 0, 0, 0.25);
    }

    .marquee-caption {
      text-align: center;
      font-size: 0.68rem;
      letter-spacing: 1.5px;
      color: #9ca3af;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .marquee-container {
      overflow: hidden;
      width: 100%;
      position: relative;
    }

    .marquee-track {
      display: flex;
      width: max-content;
      animation: marquee 28s linear infinite;
    }

    .marquee-content {
      display: flex;
      align-items: center;
      gap: 3rem;
      padding-right: 3rem;
      white-space: nowrap;
    }

    @keyframes marquee {
      from {
        transform: translateX(0);
      }

      to {
        transform: translateX(-50%);
      }
    }

    /* Webinar Card Styles */
    .webinar-card {
      background: rgba(8, 12, 20, 0.4);
      border: 1px solid rgba(51, 232, 24, 0.2);
      border-radius: 24px;
      padding: 2.5rem;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 2.5rem;
      align-items: center;
      max-width: 1000px;
      margin: 0 auto;
      box-shadow: 0 0 30px rgba(51, 232, 24, 0.05);
    }

    @media (max-width: 768px) {
      .webinar-card {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
        padding: 1.5rem !important;
      }
    }

    .p5-btn-green {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: #00C851;
      color: #fff;
      font-weight: 700;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 0 15px rgba(0, 200, 81, 0.4);
    }

    .p5-btn-green:hover {
      background: #007E33;
      transform: translateY(-2px);
      box-shadow: 0 0 20px rgba(0, 200, 81, 0.6);
    }

    /* Plus500 Promo Styles */
    .plus500-section {
      background: linear-gradient(145deg, #111 0%, #0a0a0a 100%);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 3rem;
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 3rem;
      align-items: center;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .plus500-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(51, 232, 24, 0.1) 0%, transparent 60%);
      pointer-events: none;
    }

    .plus500-border-inner {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 24px;
      pointer-events: none;
      z-index: 1;
    }

    .p5-left {
      z-index: 2;
    }

    .p5-left h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .p5-200 {
      font-size: 5rem;
      font-weight: 900;
      background: linear-gradient(to right, #007bff, #00d2ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      line-height: 1;
      margin-bottom: 0.5rem;
      letter-spacing: -2px;
    }

    .p5-left h3 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1.5rem;
    }

    .p5-left p {
      color: var(--muted);
      line-height: 1.6;
      font-size: 1rem;
      margin-bottom: 2rem;
    }

    .p5-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    @media (max-width: 576px) {
      .p5-buttons {
        flex-direction: column;
        align-items: center;
      }
    }

    .p5-steps {
      margin-top: 2rem;
      width: 100%;
      text-align: center;
    }

    .p5-steps-title {
      font-size: 1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1rem;
    }

    @media (max-width: 576px) {
      .p5-steps .p5-buttons {
        flex-direction: column;
      }
    }

    .p5-btn-orange {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: #ff6b00;
      color: #fff;
      font-weight: 700;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .p5-btn-orange:hover {
      background: #e66000;
      transform: translateY(-2px);
    }

    .p5-btn-blue {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: transparent;
      color: #007bff;
      font-weight: 700;
      border: 1px solid #007bff;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .p5-btn-blue:hover {
      background: rgba(0, 123, 255, 0.1);
    }

    .p5-right {
      z-index: 2;
    }

    .p5-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 1.5rem;
    }

    .p5-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 1rem;
    }

    .p5-tab {
      color: var(--muted);
      cursor: pointer;
      font-size: 1.2rem;
      transition: color 0.2s;
    }

    .p5-tab:hover,
    .p5-tab.active {
      color: #007bff;
    }

    .p5-card h4 {
      font-size: 1.2rem;
      color: #fff;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .p5-card {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
    }

    .p5-card::-webkit-scrollbar {
      height: 6px;
    }

    .p5-card::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 3px;
    }

    .p5-table {
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
      min-width: 760px;
    }

    .p5-thead,
    .p5-row {
      display: grid;
      grid-template-columns: 2.2fr 1.3fr 1fr 0.9fr 0.9fr 0.9fr auto;
      align-items: center;
      gap: 0.6rem;
    }

    .p5-thead {
      padding: 0.5rem 0.8rem;
      font-size: 0.72rem;
      letter-spacing: 0.6px;
      font-weight: 700;
      color: #cbd5e1;
      border-bottom: 1px solid rgba(255, 255, 255, 0.14);
      margin-bottom: 0.1rem;
      text-align: left;
    }

    .p5-thead .p5-col-name,
    .p5-thead .p5-col-harga,
    .p5-thead .p5-col-change,
    .p5-thead .p5-col-signal,
    .p5-thead .p5-col-sl,
    .p5-thead .p5-col-tp {
      text-align: left;
    }

    .p5-thead .p5-col-name {
      text-align: left;
    }

    .p5-thead .p5-col-btn {
      justify-self: end;
    }

    /* MOBILE: tampilkan kolom SIGNAL lebih dulu (setelah PAIR) saat geser kiri */
    @media (max-width: 768px) {

      .p5-thead,
      .p5-row {
        grid-template-columns: 1.8fr 0.9fr 1.1fr 1fr 0.9fr 0.9fr auto;
      }

      /* Urutan visual mobile: PAIR, SIGNAL, HARGA, PERUBAHAN, SL, TP, ACTION */
      .p5-col-name {
        order: 1;
      }

      .p5-col-signal {
        order: 2;
      }

      .p5-col-harga {
        order: 3;
      }

      .p5-col-change {
        order: 4;
      }

      .p5-col-sl {
        order: 5;
      }

      .p5-col-tp {
        order: 6;
      }

      .p5-col-btn {
        order: 7;
      }
    }

    .p5-scroll-hint {
      display: none;
      font-size: 0.7rem;
      color: #9ca3af;
      margin-bottom: 0.5rem;
      text-align: left;
    }

    @media (max-width: 768px) {
      .p5-scroll-hint {
        display: block;
      }
    }

    .p5-row {
      padding: 0.7rem 0.8rem;
      background: rgba(0, 0, 0, 0.25);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.04);
      color: #fff;
    }

    .p5-row:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .p5-col-name {
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .p5-col-harga {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      line-height: 1.2;
      text-align: left;
      padding-left: 0.4rem;
    }

    .p5-col-harga .p5-item-price {
      font-size: 1.05rem;
      font-weight: 800;
      color: #fff;
    }

    .p5-col-change {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      line-height: 1.2;
      text-align: left;
      padding-left: 0.4rem;
    }

    .p5-col-change .p5-item-change {
      font-family: monospace;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .p5-col-change .p5-item-change.green {
      color: #00C851;
    }

    .p5-col-change .p5-item-change.red {
      color: #ff4444;
    }

    .p5-col-name img {
      width: 26px;
      height: 26px;
      flex-shrink: 0;
    }

    .p5-pair {
      display: flex;
      flex-direction: column;
      line-height: 1.15;
    }

    .p5-name-label {
      font-size: 0.9rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.15rem;
    }

    .p5-price-row {
      display: flex;
      align-items: baseline;
      gap: 0.5rem;
    }

    .p5-item-price {
      font-family: monospace;
      font-size: 1.05rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 0.3px;
    }

    .p5-item-change {
      font-family: monospace;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .p5-item-change.green {
      color: #00C851;
    }

    .p5-item-change.red {
      color: #ff4444;
    }

    .p5-col-signal,
    .p5-col-sl,
    .p5-col-tp {
      display: flex;
      align-items: center;
      text-align: left;
      font-family: monospace;
      font-size: 0.9rem;
      font-weight: 700;
      color: #e5e7eb;
      padding-left: 0.4rem;
    }

    .p5-col-sl,
    .p5-col-tp {
      color: #cbd5e1;
    }

    .p5-col-btn {
      justify-self: end;
      text-align: right;
    }

    .p5-item-btn {
      padding: 0.45rem 1.1rem;
      border: none;
      border-radius: 8px;
      background: linear-gradient(135deg, #ff6b00, #ff8c1a);
      color: #fff;
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 800;
      line-height: 1.2;
      letter-spacing: 0.3px;
      transition: all 0.2s;
      white-space: nowrap;
      box-shadow: 0 4px 14px rgba(255, 107, 0, 0.35);
    }

    .p5-item-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 107, 0, 0.5);
    }

    /* Badge SIGNAL TERKINI (inline di kolom SIGNAL) */
    .p5-item-signal {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.72rem;
      color: #9ca3af;
    }

    .p5-sig-badge {
      font-weight: 800;
      font-size: 0.78rem;
      padding: 4px 12px;
      border-radius: 6px;
      letter-spacing: 0.4px;
    }

    .p5-sig-badge.buy {
      background: linear-gradient(135deg, #00C851, #00e676);
      color: #003b18;
      box-shadow: 0 3px 12px rgba(0, 200, 81, 0.4);
    }

    .p5-sig-badge.sell {
      background: linear-gradient(135deg, #ff4444, #ff6b6b);
      color: #3b0000;
      box-shadow: 0 3px 12px rgba(255, 68, 68, 0.4);
    }

    .p5-sig-badge.none {
      background: rgba(156, 163, 175, 0.2);
      color: #cbd5e1;
      border: 1px solid rgba(156, 163, 175, 0.3);
    }

    .p5-sig-sep {
      opacity: 0.4;
    }

    .p5-sig-val {
      color: #e5e7eb;
      font-family: monospace;
    }

    @media (max-width: 992px) {
      .plus500-section {
        grid-template-columns: 1fr;
        padding: 2rem;
      }

      .p5-200 {
        font-size: 4rem;
      }
    }

    @media (max-width: 576px) {
      .p5-buttons {
        flex-direction: column;
      }

      .p5-item {
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
      }

      .p5-item-change,
      .p5-item-price {
        display: none;
      }
    }
  </style>
<style>
    .hero-p5-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
      align-items: center;
    }

    @media (min-width: 768px) {
      .hero-p5-grid {
        grid-template-columns: 0.9fr 1.1fr;
        gap: 3rem;
      }
    }

    .p5-btn-wrap {
      flex-direction: column;
    }

    .p5-btn-row {
      flex-direction: column;
      gap: 0.75rem;
    }

    @media (min-width: 640px) {
      .p5-btn-row {
        flex-direction: row;
      }
    }

    .hero-p5-container {
      max-width: 100%;
      margin: 0 auto;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
      padding: 0.8rem;
      border-radius: 16px;
      background: rgba(18, 20, 20, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.03);
    }

    @media (min-width: 640px) {
      .hero-p5-container {
        padding: 1rem;
      }
    }
  </style>
<style>
          .hero-p5-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: center;
          }

          @media (min-width: 768px) {
            .hero-p5-grid {
              grid-template-columns: 1fr;
              gap: 2rem;
            }
          }

          .p5-btn-wrap {
            flex-direction: column;
          }

          .p5-btn-row {
            flex-direction: column;
            gap: 0.75rem;
          }

          @media (min-width: 640px) {
            .p5-btn-row {
              flex-direction: row;
            }
          }

          .hero-p5-container {
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            padding: 0.8rem;
            border-radius: 16px;
            background: rgba(18, 20, 20, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.03);
          }

          @media (min-width: 640px) {
            .hero-p5-container {
              padding: 1rem;
            }
          }
        </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://almai.id/css/tailwind.min.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://almai.id/css/aos.css" rel="stylesheet">
  <style type="text/css">
    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 300;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 400;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 700;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 800;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin-ext/wght/normal.woff2);
      unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic/wght/normal.woff2);
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/vietnamese/wght/normal.woff2);
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/latin/wght/normal.woff2);
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      font-display: swap;
    }

    @font-face {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 900;
      src: url(/cf-fonts/v/montserrat/5.2.8/cyrillic-ext/wght/normal.woff2);
      unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      font-display: swap;
    }
  </style>
  <style>
    body {
      background-color: #050505;
      color: #ffffff;
    }

    .bg-grid {
      background-size: 40px 40px;
      background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
      mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #0a0a0a;
    }

    ::-webkit-scrollbar-thumb {
      background: #333;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #33e818;
    }

    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 100;
      align-items: center;
      justify-content: center;
    }

    .modal.active {
      display: flex;
    }

    /* Drill-down CSS */
    .section-content { display: none; animation: fadeIn 0.4s ease forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .matrix-wrapper { background: rgba(15,15,15,0.8); border: 1px solid var(--border); border-radius: 20px; padding: 2rem; margin-top: 2rem; }
    .matrix-inner { background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border); border-radius: 12px; overflow-x: auto; }
    .matrix-table { width: 100%; border-collapse: collapse; color: #fff; font-weight: 600; font-size: clamp(0.7rem, 2vw, 0.9rem); }
    .matrix-table th { padding: clamp(0.25rem, 1vw, 1rem); text-align: center; border-bottom: 2px solid var(--border); color: var(--muted); font-weight: 700; }
    .matrix-table td { padding: clamp(0.25rem, 1vw, 1rem); text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); cursor: pointer; transition: background 0.2s; }
    .matrix-table td.clickable:hover { background: rgba(51, 232, 24, 0.1); }
    .matrix-table td.active { background: rgba(51, 232, 24, 0.15); border: 1px solid var(--primary); }
    .val-profit { color: var(--green); }
    .val-loss { color: var(--red); }
    .matrix-footer { padding: 1rem; display: flex; justify-content: space-between; align-items: center; background: rgba(255, 255, 255, 0.01); border-top: 1px solid var(--border); }
    
    .calendar-wrapper { background: var(--bg2); border: 1px solid var(--border); border-radius: 20px; padding: 2rem; margin-top: 2rem; }
    .calendar-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center; color: #fff; }
    .calendar-grid { display: flex; flex-wrap: wrap; gap: 0.35rem; justify-content: center; }
    .day-name { font-size: 0.65rem; font-weight: 700; color: var(--muted); margin-bottom: 0.1rem; }
    .day-name.weekend { color: var(--red); }
    .day-box { background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border); border-radius: 8px; width: clamp(45px, 10vw, 60px); aspect-ratio: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #fff; cursor: pointer; transition: transform 0.2s, background 0.2s; position: relative; padding: 0.2rem; }
    .day-box:hover { transform: scale(1.05); background: rgba(255, 255, 255, 0.08); }
    .day-box.active { background: var(--primary); box-shadow: 0 0 15px var(--primary-glow); border-color: var(--primary); color: #000; }
    .day-box.empty { background: transparent; border-color: transparent; cursor: default; opacity: 0.5; }
    .day-box.empty:hover { transform: none; background: transparent; }
    .day-date { font-size: 1rem; font-weight: 800; margin-bottom: 0.1rem; }
    .day-profit { font-size: 0.75rem; font-weight: 700; }
    .c-profit { color: var(--primary); }
    .c-loss { color: var(--red); }
    .day-box.active .c-profit, .day-box.active .c-loss { color: #000; }
    
    .detail-wrapper { background: rgba(15,15,15,0.8); border: 1px solid var(--border); border-radius: 20px; padding: 2rem; margin-top: 2rem; }
    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table th { text-align: left; padding: 1rem; color: var(--muted); border-bottom: 1px solid var(--border); }
    .detail-table td { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.02); font-size: 0.85rem; }
    .badge-buy { background: rgba(51, 232, 24, 0.15); color: var(--green); padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; }
    .badge-sell { background: rgba(239, 68, 68, 0.15); color: var(--red); padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; }
    .instr-card { cursor: pointer; }
    .instr-card.active { border-color: var(--primary); background: rgba(51, 232, 24, 0.1); box-shadow: 0 0 20px rgba(51, 232, 24, 0.2); }
    .instr-action { margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed rgba(255,255,255,0.1); font-size: 0.75rem; font-weight: 700; color: var(--primary); text-align: center; opacity: 0.5; transition: opacity 0.2s; }
    .instr-card:hover .instr-action, .instr-card.active .instr-action { opacity: 1; }
  </style>
  <style>
    :root {
      --gold: #33E818;
      --gold-dim: #2ebc16;
      --primary: #33E818;
      --primary-dim: #2ebc16;
      --primary-glow: rgba(51, 232, 24, 0.25);
      --green: #33E818;
      --red: #ef4444;
      --blue: #3b82f6;
      --bg: #050505;
      --bg2: #0a0a0a;
      --bg3: #111111;
      --border: rgba(255, 255, 255, 0.08);
      --text: #f1f5f9;
      --muted: #94a3b8;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ─── BACKGROUND ─── */
    .bg-grid {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: radial-gradient(rgba(51, 232, 24, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    .glow-orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(120px);
      pointer-events: none;
      z-index: 0;
    }

    .orb-1 {
      width: 600px;
      height: 600px;
      background: rgba(51, 232, 24, 0.06);
      top: -100px;
      left: -100px;
    }

    .orb-2 {
      width: 500px;
      height: 500px;
      background: rgba(59, 130, 246, 0.05);
      bottom: -100px;
      right: -50px;
    }

    /* ─── NAVBAR ─── */
    .signalplus500-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(8, 12, 20, 0.8);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
    }

    .nav-logo-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--gold), #e8a805);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      font-weight: 900;
      color: #080c14;
    }

    .nav-logo-text {
      font-weight: 700;
      font-size: 1rem;
      color: var(--text);
    }

    .nav-logo-sub {
      font-size: 0.65rem;
      color: var(--gold);
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }

    .nav-badge {
      padding: 0.3rem 0.8rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--green);
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .nav-badge::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--green);
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1)
      }

      50% {
        opacity: 0.5;
        transform: scale(1.3)
      }
    }

    /* ─── HERO ─── */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
      padding: 6rem 1.5rem 3rem;
      text-align: center;
    }

    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.3rem 0.75rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 20px;
      font-size: clamp(0.55rem, 2.5vw, 0.75rem);
      font-weight: 600;
      color: var(--gold);
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      white-space: nowrap;
    }

    .hero h1 {
      font-size: clamp(2.2rem, 6vw, 4.5rem);
      font-weight: 900;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 1.5rem;
    }

    .hero h1 span {
      background: linear-gradient(135deg, var(--gold) 0%, #fff 60%, var(--gold) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-sub {
      font-size: clamp(1rem, 2vw, 1.2rem);
      color: var(--muted);
      max-width: 600px;
      margin: 0 auto 2.5rem;
      line-height: 1.7;
    }

    .hero-cta {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 4rem;
    }

    .btn-primary {
      padding: 0.85rem 2rem;
      background: linear-gradient(135deg, var(--gold), var(--gold-dim));
      color: #080c14;
      font-weight: 700;
      font-size: 0.95rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
      box-shadow: 0 0 30px rgba(51, 232, 24, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 40px rgba(51, 232, 24, 0.5);
    }

    .btn-outline {
      padding: 0.85rem 2rem;
      background: transparent;
      color: var(--text);
      font-weight: 600;
      font-size: 0.95rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.2);
    }

    /* ─── STATS TICKER ─── */
    .ticker-wrap {
      overflow: hidden;
      background: rgba(51, 232, 24, 0.05);
      border-top: 1px solid rgba(51, 232, 24, 0.1);
      border-bottom: 1px solid rgba(51, 232, 24, 0.1);
      padding: 0.6rem 0;
    }

    .ticker-track {
      display: flex;
      width: max-content;
      animation: ticker 30s linear infinite;
    }

    .ticker-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0 2rem;
      white-space: nowrap;
      font-size: 0.8rem;
      font-family: 'JetBrains Mono', monospace;
    }

    .ticker-label {
      color: var(--muted);
    }

    .ticker-val {
      font-weight: 600;
    }

    .up {
      color: var(--green);
    }

    .dn {
      color: var(--red);
    }

    @keyframes ticker {
      from {
        transform: translateX(0)
      }

      to {
        transform: translateX(-50%)
      }
    }

    /* ─── LIVE SIGNAL CARD ─── */
    .section {
      position: relative;
      z-index: 1;
      padding: 5rem 1.5rem;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-label {
      text-align: center;
      margin-bottom: 3rem;
    }

    .section-label h2 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800;
      margin-bottom: 0.75rem;
    }

    .section-label p {
      color: var(--muted);
      max-width: 500px;
      margin: 0 auto;
    }

    .signal-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .signal-card {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
      transition: all 0.3s;
    }

    .signal-card:hover {
      transform: translateY(-4px);
      border-color: rgba(51, 232, 24, 0.3);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .signal-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .signal-card.buy::before {
      background: linear-gradient(90deg, transparent, var(--green), transparent);
    }

    .signal-card.sell::before {
      background: linear-gradient(90deg, transparent, var(--red), transparent);
    }

    .signal-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1.2rem;
    }

    .signal-pair {
      font-size: 1.1rem;
      font-weight: 800;
    }

    .signal-pair-sub {
      font-size: 0.7rem;
      color: var(--muted);
      margin-top: 0.1rem;
    }

    .signal-direction {
      padding: 0.3rem 0.9rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .dir-buy {
      background: rgba(51, 232, 24, 0.15);
      color: var(--green);
      border: 1px solid rgba(51, 232, 24, 0.3);
    }

    .dir-sell {
      background: rgba(239, 68, 68, 0.15);
      color: var(--red);
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .signal-levels {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 0.8rem;
      margin-bottom: 1.2rem;
    }

    .level-item {
      text-align: center;
    }

    .level-label {
      font-size: 0.65rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 0.2rem;
    }

    .level-val {
      font-size: 0.95rem;
      font-weight: 700;
      font-family: 'JetBrains Mono', monospace;
    }

    .level-usd {
      font-size: 0.65rem;
      color: var(--muted);
      margin-top: 0.1rem;
    }

    .signal-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
      font-size: 0.75rem;
      color: var(--muted);
    }

    .signal-ai-badge {
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .ai-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--gold);
      animation: pulse 2s infinite;
    }

    /* ─── MONEY MANAGEMENT ─── */
    .mm-section {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 3rem;
    }

    .mm-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 2rem;
      margin-top: 2rem;
    }

    @media (max-width: 992px) {
      .mm-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      }
    }

    .mm-card {
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.5rem;
      text-align: center;
      transition: all 0.2s;
    }

    .mm-card:hover {
      border-color: rgba(51, 232, 24, 0.3);
    }

    .mm-icon {
      font-size: 2rem;
      margin-bottom: 0.75rem;
    }

    .mm-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--muted);
      margin-bottom: 0.5rem;
    }

    .mm-val {
      font-size: 1.4rem;
      font-weight: 800;
    }

    .mm-sub {
      font-size: 0.75rem;
      color: var(--muted);
      margin-top: 0.3rem;
      font-family: 'JetBrains Mono', monospace;
    }

    .mm-rate {
      font-size: 0.65rem;
      color: var(--gold);
      margin-top: 0.2rem;
    }

    /* ─── FEATURES ─── */
    .features-grid {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      max-width: 800px;
      margin: 0 auto;
      text-align: left;
    }

    .feature-card {
      background: transparent;
      border: none;
      padding: 0;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      transition: none;
    }

    .feature-card:hover {
      transform: none;
    }

    .feature-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-bottom: 0;
      flex-shrink: 0;
    }
    
    .feature-icon svg {
      width: 18px;
      height: 18px;
    }

    .feature-card-text {
      display: flex;
      flex-direction: column;
    }

    .feature-card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
      color: #fff;
    }

    .feature-card p {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.4;
      margin: 0;
    }

    /* ─── INSTRUMENTS ─── */
    .instruments {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1rem;
    }

    .instr-card {
      background: rgba(51, 232, 24, 0.05);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 12px;
      padding: 1.2rem 1rem;
      text-align: center;
      transition: all 0.3s;
      cursor: default;
    }

    .instr-card:hover {
      border-color: rgba(51, 232, 24, 0.6);
      background: rgba(51, 232, 24, 0.1);
    }

    .instr-emoji {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }

    .instr-name {
      font-size: 0.8rem;
      font-weight: 700;
    }

    .instr-full {
      font-size: 0.65rem;
      color: var(--muted);
      margin-top: 0.2rem;
    }

    .instr-stats {
      margin-top: 0.8rem;
      padding-top: 0.8rem;
      border-top: 1px dashed rgba(255, 255, 255, 0.1);
      font-size: 0.7rem;
      color: var(--muted);
      text-align: left;
      line-height: 1.4;
    }

    .instr-stats-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.15rem;
    }

    .instr-stats-label {
      color: rgba(255, 255, 255, 0.7);
    }

    .instr-stats-val {
      font-weight: 700;
      color: var(--text);
    }

    .text-red {
      color: #ef4444 !important;
    }

    .text-green {
      color: #33e818 !important;
    }

    .text-gold {
      color: #f5c842 !important;
    }


    /* ─── SCHEDULE ─── */
    .schedule-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .sched-item {
      display: flex;
      align-items: flex-start;
      gap: 1.2rem;
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.2rem 1.5rem;
    }

    .sched-time {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--gold);
      white-space: nowrap;
      min-width: 80px;
    }

    .sched-content h4 {
      font-size: 0.9rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
    }

    .sched-content p {
      font-size: 0.8rem;
      color: var(--muted);
    }

    /* ─── FOOTER ─── */
    footer {
      position: relative;
      z-index: 1;
      border-top: 1px solid var(--border);
      padding: 2rem 1.5rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.8rem;
    }

    .footer-logo {
      font-weight: 800;
      font-size: 1.1rem;
      color: var(--gold);
      margin-bottom: 0.5rem;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 1rem;
    }

    .footer-links a {
      color: var(--muted);
      text-decoration: none;
      transition: color 0.2s;
    }

    .footer-links a:hover {
      color: var(--text);
    }

    /* Live badge */
    .live-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.2rem 0.6rem;
      background: rgba(51, 232, 24, 0.1);
      border: 1px solid rgba(51, 232, 24, 0.3);
      border-radius: 10px;
      font-size: 0.65rem;
      font-weight: 700;
      color: var(--green);
    }

    .live-badge::before {
      content: '';
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: var(--green);
      animation: pulse 1.5s infinite;
    }

    /* Rate display */
    #live-rate {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      color: var(--gold);
      font-size: 0.9rem;
    }

    /* Scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    @media (max-width:640px) {
      nav {
        padding: 1rem;
      }

      .mm-section {
        padding: 2rem 1rem;
      }

      .signal-levels {
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem;
      }
    }

    /* ─── STEPS SECTION ─── */
    .step-grid {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 1.5rem;
      margin-top: 2.5rem;
    }

    @media (min-width: 768px) {
      .step-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .step-card {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
      transition: all 0.3s ease;
    }

    .step-card:hover {
      transform: translateY(-5px);
      border-color: rgba(51, 232, 24, 0.3);
      box-shadow: 0 10px 30px rgba(51, 232, 24, 0.05);
    }

    .step-number {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      font-size: 0.8rem;
      font-weight: 700;
      color: var(--gold);
      background: rgba(51, 232, 24, 0.1);
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .step-icon {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
    }

    .step-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.75rem;
    }

    .step-desc {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 2rem;
      flex-grow: 1;
    }

    .step-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.8rem 1.5rem;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      color: var(--text);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .step-btn:hover {
      background: linear-gradient(135deg, var(--gold), #33e818);
      color: #080c14;
      border-color: transparent;
      box-shadow: 0 0 15px rgba(51, 232, 24, 0.2);
    }

    /* Marquee Styles */
    .marquee-frame {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.02);
      padding: 0.6rem 1rem;
      box-shadow: 0 2px 18px rgba(0, 0, 0, 0.25);
    }

    .marquee-caption {
      text-align: center;
      font-size: 0.68rem;
      letter-spacing: 1.5px;
      color: #9ca3af;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .marquee-container {
      overflow: hidden;
      width: 100%;
      position: relative;
    }

    .marquee-track {
      display: flex;
      width: max-content;
      animation: marquee 28s linear infinite;
    }

    .marquee-content {
      display: flex;
      align-items: center;
      gap: 3rem;
      padding-right: 3rem;
      white-space: nowrap;
    }

    @keyframes marquee {
      from {
        transform: translateX(0);
      }

      to {
        transform: translateX(-50%);
      }
    }

    /* Webinar Card Styles */
    .webinar-card {
      background: rgba(8, 12, 20, 0.4);
      border: 1px solid rgba(51, 232, 24, 0.2);
      border-radius: 24px;
      padding: 2.5rem;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 2.5rem;
      align-items: center;
      max-width: 1000px;
      margin: 0 auto;
      box-shadow: 0 0 30px rgba(51, 232, 24, 0.05);
    }

    @media (max-width: 768px) {
      .webinar-card {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
        padding: 1.5rem !important;
      }
    }

    .p5-btn-green {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: #00C851;
      color: #fff;
      font-weight: 700;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 0 15px rgba(0, 200, 81, 0.4);
    }

    .p5-btn-green:hover {
      background: #007E33;
      transform: translateY(-2px);
      box-shadow: 0 0 20px rgba(0, 200, 81, 0.6);
    }

    /* Plus500 Promo Styles */
    .plus500-section {
      background: linear-gradient(145deg, #111 0%, #0a0a0a 100%);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 3rem;
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 3rem;
      align-items: center;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .plus500-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(51, 232, 24, 0.1) 0%, transparent 60%);
      pointer-events: none;
    }

    .plus500-border-inner {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 24px;
      pointer-events: none;
      z-index: 1;
    }

    .p5-left {
      z-index: 2;
    }

    .p5-left h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .p5-200 {
      font-size: 5rem;
      font-weight: 900;
      background: linear-gradient(to right, #007bff, #00d2ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      line-height: 1;
      margin-bottom: 0.5rem;
      letter-spacing: -2px;
    }

    .p5-left h3 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1.5rem;
    }

    .p5-left p {
      color: var(--muted);
      line-height: 1.6;
      font-size: 1rem;
      margin-bottom: 2rem;
    }

    .p5-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    @media (max-width: 576px) {
      .p5-buttons {
        flex-direction: column;
        align-items: center;
      }
    }

    .p5-steps {
      margin-top: 2rem;
      width: 100%;
      text-align: center;
    }

    .p5-steps-title {
      font-size: 1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1rem;
    }

    @media (max-width: 576px) {
      .p5-steps .p5-buttons {
        flex-direction: column;
      }
    }

    .p5-btn-orange {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: #ff6b00;
      color: #fff;
      font-weight: 700;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .p5-btn-orange:hover {
      background: #e66000;
      transform: translateY(-2px);
    }

    .p5-btn-blue {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.8rem 1.5rem;
      background: transparent;
      color: #007bff;
      font-weight: 700;
      border: 1px solid #007bff;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .p5-btn-blue:hover {
      background: rgba(0, 123, 255, 0.1);
    }

    .p5-right {
      z-index: 2;
    }

    .p5-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 1.5rem;
    }

    .p5-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 1rem;
    }

    .p5-tab {
      color: var(--muted);
      cursor: pointer;
      font-size: 1.2rem;
      transition: color 0.2s;
    }

    .p5-tab:hover,
    .p5-tab.active {
      color: #007bff;
    }

    .p5-card h4 {
      font-size: 1.2rem;
      color: #fff;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .p5-card {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
    }

    .p5-card::-webkit-scrollbar {
      height: 6px;
    }

    .p5-card::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 3px;
    }

    .p5-table {
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
      min-width: 760px;
    }

    .p5-thead,
    .p5-row {
      display: grid;
      /* Desktop Track Sizes: PAIR, SIGNAL, ACTION, HARGA, SL, TP, WINRATE */
      grid-template-columns: minmax(130px, 1.5fr) minmax(60px, 0.8fr) 80px minmax(90px, 1.5fr) 1fr 1fr minmax(70px, 1fr);
      align-items: center;
      gap: 0.8rem;
    }

    /* Sembunyikan kolom PERUBAHAN */
    .p5-col-change { 
      display: none !important; 
    }

    /* Global Visual Order */
    .p5-col-name { order: 1; }
    .p5-col-signal { order: 2; }
    .p5-col-action { order: 3; justify-self: center; text-align: center; }
    .p5-col-harga { order: 4; }
    .p5-col-sl { order: 5; }
    .p5-col-tp { order: 6; }
    .p5-col-winrate { order: 7; justify-self: center; text-align: center; }

    .p5-thead {
      padding: 0.5rem 0.8rem;
      font-size: 0.72rem;
      letter-spacing: 0.6px;
      font-weight: 700;
      color: #cbd5e1;
      border-bottom: 1px solid rgba(255, 255, 255, 0.14);
      margin-bottom: 0.1rem;
      text-align: left;
    }

    .p5-thead .p5-col-name,
    .p5-thead .p5-col-harga,
    .p5-thead .p5-col-signal,
    .p5-thead .p5-col-sl,
    .p5-thead .p5-col-tp {
      text-align: left;
    }

    .p5-thead .p5-col-action,
    .p5-thead .p5-col-winrate {
      justify-self: center;
      text-align: center;
    }

    /* MOBILE: Penyesuaian agar PAIR, SIGNAL, DEMO, REAL dominan di awal layar */
    @media (max-width: 768px) {
      .p5-thead,
      .p5-row {
        /* Mobile Track Sizes: PAIR, SIGNAL, ACTION, HARGA, SL, TP, WINRATE */
        grid-template-columns: minmax(110px, 1.2fr) minmax(50px, 0.6fr) 80px minmax(70px, 1.2fr) 1fr 1fr 70px;
        gap: 0.5rem;
      }
    }

    .p5-scroll-hint {
      display: none;
      font-size: 0.7rem;
      color: #9ca3af;
      margin-bottom: 0.5rem;
      text-align: left;
    }

    @media (max-width: 768px) {
      .p5-scroll-hint {
        display: block;
      }
    }

    .p5-row {
      padding: 0.7rem 0.8rem;
      background: rgba(0, 0, 0, 0.25);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.04);
      color: #fff;
    }

    .p5-row:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .p5-col-name {
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .p5-col-harga {
      display: flex !important;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      line-height: 1.2;
      text-align: left;
      padding-left: 0.4rem;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .p5-col-harga .p5-item-price {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
      font-size: 1.05rem !important;
      font-weight: 800 !important;
      color: #fff !important;
    }

    .p5-col-change {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      line-height: 1.2;
      text-align: left;
      padding-left: 0.4rem;
    }

    .p5-col-change .p5-item-change {
      font-family: monospace;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .p5-col-change .p5-item-change.green {
      color: #00C851;
    }

    .p5-col-change .p5-item-change.red {
      color: #ff4444;
    }

    .p5-col-name img {
      width: 26px;
      height: 26px;
      flex-shrink: 0;
    }

    .p5-pair {
      display: flex;
      flex-direction: column;
      line-height: 1.15;
    }

    .p5-name-label {
      font-size: 0.9rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.15rem;
    }

    .p5-price-row {
      display: flex;
      align-items: baseline;
      gap: 0.5rem;
    }

    .p5-item-price {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
      font-family: monospace;
      font-size: 1.05rem !important;
      font-weight: 800 !important;
      color: #fff !important;
      letter-spacing: 0.3px;
    }

    .p5-item-change {
      font-family: monospace;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .p5-item-change.green {
      color: #00C851;
    }

    .p5-item-change.red {
      color: #ff4444;
    }

    .p5-col-signal,
    .p5-col-sl,
    .p5-col-tp {
      display: flex;
      align-items: center;
      text-align: left;
      font-family: monospace;
      font-size: 0.9rem;
      font-weight: 700;
      color: #e5e7eb;
      padding-left: 0.4rem;
    }

    .p5-col-sl,
    .p5-col-tp {
      color: #cbd5e1;
    }

    .p5-col-winrate {
      color: var(--gold);
      font-weight: 800;
      font-size: 0.9rem;
      font-family: monospace;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .p5-col-action {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .p5-item-btn {
      padding: 0.4rem 0.8rem;
      border: none;
      border-radius: 8px;
      color: #fff;
      text-decoration: none;
      font-size: 0.75rem;
      font-weight: 800;
      line-height: 1.2;
      letter-spacing: 0.3px;
      transition: all 0.2s;
      white-space: nowrap;
      cursor: pointer;
    }

    .p5-btn-demo {
      background: linear-gradient(135deg, #ff9800, #f57c00);
      box-shadow: 0 4px 14px rgba(255, 152, 0, 0.35);
    }
    .p5-btn-demo:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 152, 0, 0.45);
    }

    .p5-btn-real {
      background: linear-gradient(135deg, #2196F3, #1976D2);
      box-shadow: 0 4px 14px rgba(33, 150, 243, 0.35);
    }
    .p5-btn-real:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(33, 150, 243, 0.45);
    }

    /* Badge SIGNAL TERKINI (inline di kolom SIGNAL) */
    .p5-item-signal {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.72rem;
      color: #9ca3af;
    }

    .p5-sig-badge {
      font-weight: 800;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      background: transparent !important;
      padding: 0 !important;
      box-shadow: none !important;
      border: none !important;
    }

    .p5-sig-badge.buy {
      color: #00C851;
    }

    .p5-sig-badge.sell {
      color: #ff4444;
    }

    .p5-sig-badge.none {
      color: #9ca3af;
    }

    .p5-sig-sep {
      opacity: 0.4;
    }

    .p5-sig-val {
      color: #e5e7eb;
      font-family: monospace;
    }

    @media (max-width: 992px) {
      .plus500-section {
        grid-template-columns: 1fr;
        padding: 2rem;
      }

      .p5-200 {
        font-size: 4rem;
      }
    }

    @media (max-width: 576px) {
      .p5-buttons {
        flex-direction: column;
      }

      .p5-item {
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
      }

      .p5-item-change {
        display: none;
      }
    }
  </style>
  <style>
    .hero-p5-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
      align-items: center;
    }

    @media (min-width: 768px) {
      .hero-p5-grid {
        grid-template-columns: 0.9fr 1.1fr;
        gap: 3rem;
      }
    }

    .p5-btn-wrap {
      flex-direction: column;
    }

    .p5-btn-row {
      flex-direction: column;
      gap: 0.75rem;
    }

    @media (min-width: 640px) {
      .p5-btn-row {
        flex-direction: row;
      }
    }

    .hero-p5-container {
      max-width: 100%;
      margin: 0 auto;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
      padding: 0.8rem;
      border-radius: 16px;
      background: rgba(18, 20, 20, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.03);
    }

    @media (min-width: 640px) {
      .hero-p5-container {
        padding: 1rem;
      }
    }
  </style>
  <nav class="fixed top-0 w-full z-50 px-4 py-4">
    <div
      class="max-w-7xl mx-auto bg-black/80 backdrop-blur-md border border-white/10 rounded-full px-6 py-3 flex justify-between items-center">
      <a href="https://almai.id/" class="flex items-center gap-2">
        <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
        <span class="font-bold tracking-tighter text-lg">ALMAI</span>
      </a>
      <ul class="hidden md:flex gap-8 text-sm font-medium">
        <li><a href="https://almai.id/" class="hover:text-accent transition">Home</a></li>
        <li><a href="https://almai.id/advokasi" class="hover:text-accent transition">Advokasi</a></li>
        <li><a href="https://almai.id/wpa" class="hover:text-accent transition">WPA</a></li>
        <li><a href="https://almai.id/tools" class="hover:text-accent transition">Tools</a></li>




      </ul>

      <a href="https://almai.id/login"
        class="hidden md:inline-block px-6 py-2 bg-accent text-black font-bold rounded-full text-sm hover:bg-white transition">
        Login
      </a>

      <button class="md:hidden text-xl" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu"
      class="hidden md:hidden mt-2 bg-black/95 backdrop-blur-md border border-white/10 rounded-2xl p-4">
      <a href="https://almai.id/" class="block py-2 ">Home</a>
      <a href="https://almai.id/advokasi" class="block py-2 ">Advokasi</a>
      <a href="https://almai.id/wpa" class="block py-2 ">WPA</a>
      <a href="https://almai.id/tools" class="block py-2 ">Tools</a>


      <a href="https://almai.id/login" class="block py-2 text-accent font-bold">Login</a>
    </div>
  </nav>


  <div class="bg-grid"></div>
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <section class="hero"
    style="min-height: 100vh; padding: 5rem 1rem 1.5rem 1rem; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; overflow-x: hidden;">
    <div style="width: 100%; display: flex; flex-direction: column; align-items: center; text-align: center;">
      <div class="hero-tag"
        style="margin-bottom: 0.8rem; display: inline-flex; align-items: center; justify-content: center;"><svg
          width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
          <rect x="3" y="11" width="18" height="10" rx="2"></rect>
          <circle cx="12" cy="5" r="2"></circle>
          <path d="M12 7v4"></path>
          <line x1="8" y1="16" x2="8" y2="16"></line>
          <line x1="16" y1="16" x2="16" y2="16"></line>
        </svg> POWERED BY ALMAI AI — PLATFORM PLUS 500</div>
      <h1
        style="font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.1; margin-bottom: 0.5rem; letter-spacing: -0.02em;">
        Sinyal Trading AI<br><span style="color: #33e818;">Real-Time & Akurat <span style="font-size: 1rem; opacity: 0.5;">(v2)</span></span></h1>

      <!-- Plus500 Block di Hero -->
      <div class="container" style="margin-top: 1.2rem; width: 100%; max-width: 1000px; padding: 0;">
        <style>
          .hero-p5-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: center;
          }

          @media (min-width: 768px) {
            .hero-p5-grid {
              grid-template-columns: 1fr;
              gap: 2rem;
            }
          }

          .p5-btn-wrap {
            flex-direction: column;
          }

          .p5-btn-row {
            flex-direction: column;
            gap: 0.75rem;
          }

          @media (min-width: 640px) {
            .p5-btn-row {
              flex-direction: row;
            }
          }

          .hero-p5-container {
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            padding: 1.2rem;
            border-radius: 16px;
            background: rgba(18, 20, 20, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.03);
          }

          @media (min-width: 640px) {
            .hero-p5-container {
              padding: 2rem;
            }
          }
        </style>
        <div class="plus500-section reveal hero-p5-grid hero-p5-container">
          <div class="plus500-border-inner" style="grid-column: 1 / -1; height: 100%; width: 100%; display: none;">
          </div>
          <div class="p5-right"
            style="width: 100%; min-width: 0; overflow-x: hidden;">
            <div class="p5-card" style="background: transparent; padding: 0; box-shadow: none; border: none;">
              <h4 style="font-size: 1.05rem; margin-bottom: 0.6rem;">Paling Populer</h4>
              <div class="p5-scroll-hint">⇠ Geser ke kiri untuk lihat lengkap</div>
              <div class="p5-table">
                <div class="p5-thead">
                  <div class="p5-col-name">PAIR</div>
                  <div class="p5-col-harga">HARGA</div>
                  <div class="p5-col-change">PERUBAHAN</div>
                  <div class="p5-col-signal">SIGNAL</div>
                  <div class="p5-col-action">ACTION</div>
                  <div class="p5-col-sl">STOP LOSS</div>
                  <div class="p5-col-tp">TAKE PROFIT</div>
                  <div class="p5-col-winrate">WINRATE</div>
                </div>
                <div class="p5-row" data-sig="XNGUSD">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/natural-gas.svg" alt="XNGUSD">
                    <div class="p5-pair">
                      <span class="p5-name-label">Gas Alam</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="XNGUSD">2.946</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="XNGUSD">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="XNGUSD"></div>
                  <div class="p5-col-sl" data-slrow="XNGUSD">-</div>
                  <div class="p5-col-tp" data-tprow="XNGUSD">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('XNGUSD', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="XNGUSD">-</div>
                </div>
                <div class="p5-row" data-sig="AUDUSD">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/aud.svg" alt="AUDUSD">
                    <div class="p5-pair">
                      <span class="p5-name-label">AUD/USD</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="AUDUSD">0.69521</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="AUDUSD">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="AUDUSD"></div>
                  <div class="p5-col-sl" data-slrow="AUDUSD">-</div>
                  <div class="p5-col-tp" data-tprow="AUDUSD">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('AUDUSD', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="AUDUSD">-</div>
                </div>
                <div class="p5-row" data-sig="EURUSD">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/eur.svg" alt="EURUSD">
                    <div class="p5-pair">
                      <span class="p5-name-label">EUR/USD</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="EURUSD">1.14156</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="EURUSD">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="EURUSD"></div>
                  <div class="p5-col-sl" data-slrow="EURUSD">-</div>
                  <div class="p5-col-tp" data-tprow="EURUSD">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('EURUSD', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="EURUSD">-</div>
                </div>
                <div class="p5-row" data-sig="USOIL">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/usoil.svg" alt="USOIL">
                    <div class="p5-pair">
                      <span class="p5-name-label">Minyak</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="USOIL">71.49</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="USOIL">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="USOIL"></div>
                  <div class="p5-col-sl" data-slrow="USOIL">-</div>
                  <div class="p5-col-tp" data-tprow="USOIL">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('USOIL', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="USOIL">-</div>
                </div>
                <div class="p5-row" data-sig="XAUUSD">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/xauusd.svg" alt="XAUUSD">
                    <div class="p5-pair">
                      <span class="p5-name-label">Emas</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="XAUUSD">4119.52</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="XAUUSD">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="XAUUSD"></div>
                  <div class="p5-col-sl" data-slrow="XAUUSD">-</div>
                  <div class="p5-col-tp" data-tprow="XAUUSD">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('XAUUSD', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="XAUUSD">-</div>
                </div>
                <div class="p5-row" data-sig="JP225">
                  <div class="p5-col-name"><img src="https://almai.id/images/pair/nikkei.svg" alt="JP225">
                    <div class="p5-pair">
                      <span class="p5-name-label">JAPAN 225</span>
                    </div>
                  </div>
                  <div class="p5-col-harga"><span class="p5-item-price" data-p5="JP225">38950</span></div>
                  <div class="p5-col-change"><span class="p5-item-change" data-pct="JP225">+0.00%</span></div>
                  <div class="p5-col-signal" data-sigrow="JP225"></div>
                  <div class="p5-col-sl" data-slrow="JP225">-</div>
                  <div class="p5-col-tp" data-tprow="JP225">-</div>
                  <div class="p5-col-action"><button class="p5-item-btn p5-btn-real" onclick="executeTrade('JP225', 'REAL')">TRADE</button></div>
                  <div class="p5-col-winrate" data-winrow="JP225">-</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="p5-steps" style="margin-top: 1.5rem; margin-bottom: 0.5rem;">
          <div class="p5-steps-title">3 Langkah Mudah menggunakan Almai SIgnal</div>
          <div class="p5-buttons">
            <a href="https://www.plus500.com/en/multiplatformdownload?clt=Web&id=139621&tags=DEMOAKUN&pl=2"
              target="_blank" class="p5-btn-orange" style="padding: 0.6rem 1.2rem; font-size: 0.85rem;">1. Buka Demo Akun</a>
            <a href="https://whatsapp.com/channel/0029Vb8F4Uc9WtC4cJHudt2k" target="_blank" class="p5-btn-real" style="padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 12px; font-weight: 700;">2. Bergabung Chanel</a>
            <a href="absensi/checkin/ABS-6A54C0137B56B?reff=ACEP" class="p5-btn-green" style="padding: 0.6rem 1.2rem; font-size: 0.85rem;">3. Ikuti Live Trade</a>
          </div>
        </div>

      </div>
    </div>

    <!-- SECTION 2 : MARQUE (Full Width at Bottom) -->
    <div class="py-4 sm:py-6 border-t border-white/10 bg-black/40 backdrop-blur-md overflow-hidden relative z-10 w-full" style="margin-top: 0;">
        <div class="marquee-container">
            <div class="marquee-track">

                <!-- SET 1 -->
                <div class="marquee-content">
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    
                    <!-- Duplicated to prevent cut-off on wide screens -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>

                <!-- SET 2 -->
                <div class="marquee-content" aria-hidden="true">
                    <!-- Original -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">

                    <!-- Duplicated to prevent cut-off on wide screens -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>
            </div>
        </div>
    </div>
</div>
</section>


  <!-- Live MM Info (Hidden) -->
  <div
    style="display:none;grid-template-columns:repeat(3,1fr);gap:1rem;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:16px;padding:1.5rem 2rem;text-align:center;">
    <div>
      <div
        style="font-size:0.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.3rem;">
        Modal Ideal</div>
      <div style="font-size:1.1rem;font-weight:800;">Rp 10 Juta</div>
    </div>
    <div style="border-left:1px solid var(--border);border-right:1px solid var(--border);padding:0 1rem;">
      <div
        style="font-size:0.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.3rem;">
        Stop Loss</div>
      <div style="font-size:1.1rem;font-weight:800;color:var(--red);">-$50</div>
      <div style="font-size:0.7rem;color:var(--muted);font-family:'JetBrains Mono',monospace;" id="sl-idr">-Rp
        892.100</div>
    </div>
    <div>
      <div
        style="font-size:0.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.3rem;">
        Take Profit</div>
      <div style="font-size:1.1rem;font-weight:800;color:var(--green);">+$100</div>
      <div style="font-size:0.7rem;color:var(--muted);font-family:'JetBrains Mono',monospace;" id="tp-idr">+Rp
        1.784.200</div>
    </div>
  </div>
  <div style="display:none;margin-top:0.75rem;font-size:0.7rem;color:var(--muted);">
    Kurs Real-Time: $1 = <span id="live-rate">Rp 17.842</span>
    &nbsp;|&nbsp; <span class="live-badge">Live</span>
  </div>

  <section class="section" id="instruments">
    <div class="container">
      <div class="section-label reveal">
        <h2><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round"
            style="display:inline-block; vertical-align:-0.15em; margin-right:12px;">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
          </svg>Rekap Transaksi AI</h2>
        <p>6 instrumen prioritas dengan likuiditas tinggi di platform Plus 500. Rekapitulasi portofolio dihitung aktif
          sejak 1 Juli 2026 dengan penerapan rasio Risk/Reward 1:2 secara konsisten.</p>
      </div>
      <div class="instruments reveal">
        <div class="instr-card" data-pair="AUDUSD">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/aud.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="AUDUSD"></div>
          <div class="instr-name">AUDUSD</div>
          <div class="instr-full">AUD / USD</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
        <div class="instr-card" data-pair="EURUSD">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/eur.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="EURUSD"></div>
          <div class="instr-name">EURUSD</div>
          <div class="instr-full">Euro / USD</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
        <div class="instr-card" data-pair="XNGUSD">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/natural-gas.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="XNGUSD"></div>
          <div class="instr-name">XNGUSD</div>
          <div class="instr-full">Natural Gas</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
        <div class="instr-card" data-pair="USOIL">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/usoil.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="USOIL"></div>
          <div class="instr-name">USOIL</div>
          <div class="instr-full">Crude Oil</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
        <div class="instr-card" data-pair="XAUUSD">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/xauusd.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="XAUUSD"></div>
          <div class="instr-name">XAUUSD</div>
          <div class="instr-full">Emas / USD</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
        <div class="instr-card" data-pair="JP225">
          <div class="instr-emoji"><img src="https://almai.id/images/pair/nikkei.svg"
              style="width:32px; height:32px; display:inline-block; vertical-align:middle;" alt="JP225"></div>
          <div class="instr-name">JP225</div>
          <div class="instr-full">Nikkei 225</div>
          <div class="instr-stats">
            <div class="instr-stats-row"><span class="instr-stats-label">Total Signal:</span><span
                class="instr-stats-val stat-total">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Stop Loss:</span><span
                class="instr-stats-val stat-sl text-red">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Take Profit:</span><span
                class="instr-stats-val stat-tp text-green">-</span></div>
            <div class="instr-stats-row"><span class="instr-stats-label">Win Rate:</span><span
                class="instr-stats-val stat-wr text-gold">-</span></div>
          </div>
          <div class="instr-action">Lihat Detail &rarr;</div>
        </div>
      </div>
      
      <!-- LEVEL 2: MONTHLY MATRIX -->
      <div id="level-2" class="section-content matrix-wrapper">
        <div class="matrix-inner">
          <table class="matrix-table">
            <thead>
              <tr>
                <th style="text-align:left; color:#fff;">Year</th>
                <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th><th>Jun</th>
                <th>Jul</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
                <th style="color:var(--primary);">Total</th>
              </tr>
            </thead>
            <tbody id="matrix-tbody">
            </tbody>
          </table>
          <div class="matrix-footer">
            <span style="color:var(--muted); font-size:0.85rem;">📊 Data dihitung berdasarkan akumulasi Profit/Loss bersih. Modal Awal Rp 10.000.000 / Pair.</span>
            <strong style="color:#fff; font-size:1.1rem;" id="matrix-grand-total">Total: Rp 0</strong>
          </div>
        </div>
      </div>

      <!-- LEVEL 3: DAILY CALENDAR -->
      <div id="level-3" class="section-content calendar-wrapper">
        <div class="calendar-title" id="calendar-title">Agustus 2026 - USOIL</div>
        <div class="calendar-grid" id="calendar-grid">
        </div>
      </div>

      <!-- LEVEL 4: TRANSACTION DETAILS -->
      <div id="level-4" class="section-content detail-wrapper">
        <h3 style="margin-top:0; color:#fff;" id="detail-title">Rincian Transaksi</h3>
        <table class="detail-table">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Action</th>
              <th>Entry</th>
              <th>Exit</th>
              <th>Profit/Loss (Rp)</th>
            </tr>
          </thead>
          <tbody id="detail-tbody">
          </tbody>
        </table>
      </div>

    </div>
  </section>

  <section class="section" id="money-management">
    <div class="container">
      <div class="mm-section reveal">
        <div style="text-align:center;margin-bottom:2rem;">
          <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;margin-bottom:0.5rem;"><svg width="1em"
              height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"
              style="display:inline-block; vertical-align:-0.15em; margin-right:12px;">
              <path d="M16 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
              <path d="M2 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
              <path d="M7 21h10"></path>
              <path d="M12 3v18"></path>
              <path d="M3 7h2"></path>
              <path d="M12 5L2 8h20Z"></path>
            </svg>Rencana Pengelolaan Transakasi</h2>
          <p style="color:var(--muted);">Parameter terukur & disiplin — perlindungan modal di setiap transaksi</p>
          <div style="margin-top:0.75rem;font-size:0.8rem;color:var(--muted);">
            Kurs Real-Time: $1 = <span id="live-rate2">Rp 17.842</span>
          </div>
        </div>
        <div class="mm-grid">
          <div class="mm-card">
            <div class="mm-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
              </svg></div>
            <div class="mm-label">Modal Ideal</div>
            <div class="mm-val" style="white-space: nowrap; font-size: 1.2rem;">Rp 10.000.000</div>
            <div class="mm-sub">Per Instrumen</div>
          </div>
          <div class="mm-card">
            <div class="mm-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg></div>
            <div class="mm-label">Lot Size</div>
            <div class="mm-val">0.1</div>
            <div class="mm-sub">Per Transaksi</div>
          </div>
          <div class="mm-card">
            <div class="mm-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
                <path d="M2 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
                <path d="M7 21h10"></path>
                <path d="M12 3v18"></path>
                <path d="M3 7h2"></path>
                <path d="M12 5L2 8h20Z"></path>
              </svg></div>
            <div class="mm-label">Risk/Reward</div>
            <div class="mm-val" style="color:var(--gold)">1 : 2</div>
            <div class="mm-sub">Fixed Ratio</div>
          </div>
          <div class="mm-card">
            <div class="mm-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--blue)"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg></div>
            <div class="mm-label">Stop Loss</div>
            <div class="mm-val" style="color:var(--red)">-$50</div>
            <div class="mm-sub" id="sl-idr2">-Rp 892.100</div>
          </div>
          <div class="mm-card">
            <div class="mm-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--red)"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <circle cx="12" cy="12" r="6"></circle>
                <circle cx="12" cy="12" r="2"></circle>
              </svg></div>
            <div class="mm-label">Take Profit</div>
            <div class="mm-val" style="color:var(--green)">+$100</div>
            <div class="mm-sub" id="tp-idr2">+Rp 1.784.200</div>
          </div>

        </div>

        <div style="margin-top:4rem; overflow-x:auto;">
          <h3
            style="text-align:center; font-size:1.5rem; font-weight:800; margin-bottom:1.5rem; text-transform:uppercase;">
            Simulasi Modal & Manajemen Risiko</h3>
          <table
            style="width:100%; border-collapse:collapse; text-align:center; background:var(--bg3); border-radius:14px; border: 1px solid var(--border);">
            <thead>
              <tr style="background:rgba(51, 232, 24, 0.1); border-bottom:1px solid var(--border);">
                <th rowspan="2"
                  style="padding:1.2rem; color:var(--gold); font-weight:700; border-right:1px solid var(--border); border-top-left-radius:14px; vertical-align:middle; width:6%;">
                  NO.</th>
                <th rowspan="2"
                  style="padding:1.2rem; color:var(--gold); font-weight:700; border-right:1px solid var(--border); vertical-align:middle; width:20%;">
                  PAIR REKOMENDASI</th>
                <th colspan="3"
                  style="padding:1.2rem; color:var(--gold); font-weight:700; border-right:1px solid var(--border); border-bottom:1px solid var(--border);">
                  MANAJEMEN MODAL</th>
                <th colspan="2"
                  style="padding:1.2rem; color:var(--gold); font-weight:700; border-top-right-radius:14px; border-bottom:1px solid var(--border);">
                  MANAJEMEN RESIKO (1:2)</th>
              </tr>
              <tr style="background:rgba(51, 232, 24, 0.05); border-bottom:1px solid var(--border);">
                <th
                  style="padding:0.8rem; color:var(--green); font-weight:700; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);">
                  Rp
                  1.000.000</th>
                <th
                  style="padding:0.8rem; color:var(--green); font-weight:700; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);">
                  Rp
                  5.000.000</th>
                <th
                  style="padding:0.8rem; color:var(--green); font-weight:700; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);">
                  Rp
                  10.000.000</th>
                <th style="padding:0.8rem; color:var(--gold); font-weight:600; border-right:1px solid var(--border);">
                  STOP LOSS</th>
                <th style="padding:0.8rem; color:var(--gold); font-weight:600;">TAKE PROFIT</th>
              </tr>
            </thead>
            <tbody>
              <!-- 1 AUD/USD -->
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">1</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">AUD/USD</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 100.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 200.000</td>
              </tr>
              <!-- 2 EUR/USD -->
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">2</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">EUR/USD</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 100.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 200.000</td>
              </tr>
              <!-- 3 GAS ALAM -->
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">3</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">GAS ALAM</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 200.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 400.000</td>
              </tr>
              <!-- 4 MINYAK -->
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">4</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">MINYAK</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 800.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 1.600.000</td>
              </tr>
              <!-- 5 EMAS -->
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">5</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">EMAS</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 800.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 1.600.000</td>
              </tr>
              <!-- 6 JAPAN 225 -->
              <tr>
                <td style="padding:1rem; font-weight:700; border-right:1px solid var(--border);">6</td>
                <td style="padding:1rem; font-weight:600; border-right:1px solid var(--border); text-align:left; padding-left:1.5rem;">JAPAN 225</td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.02);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.05);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); background:rgba(255,255,255,0.08);"><div style="display:flex; justify-content:center; align-items:center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="3"><polyline points="20 6 9 17 4 12" /></svg></div></td>
                <td style="padding:1rem; border-right:1px solid var(--border); color:var(--red); font-weight:600;">- Rp 1.000.000</td>
                <td style="padding:1rem; color:var(--green); font-weight:600;">Rp 2.000.000</td>
              </tr>
            </tbody>
          </table>
        </div> <!-- overflow-x -->
      </div> <!-- mm-section -->
    </div> <!-- container -->
  </section> <!-- money-management -->

  <section class="section" id="cara-kerja">
    <div class="container">
      <div class="section-label reveal">
        <h2><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round"
            style="display:inline-block; vertical-align:-0.15em; margin-right:12px;">
            <circle cx="12" cy="12" r="3"></circle>
            <path
              d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
            </path>
          </svg>Cara Kerja AI Signal</h2>
        <p>Otomatis 24/5 - dari analisis pasar hingga notifikasi WhatsApp</p>
      </div>
      <div class="features-grid">
        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg></div>
          <div class="feature-card-text">
            <h3>Data Pasar Real-Time</h3>
            <p>Memonitor pergerakan harga pasar secara real-time setiap detiknya.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="20" x2="18" y2="10"></line>
              <line x1="12" y1="20" x2="12" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg></div>
          <div class="feature-card-text">
            <h3>Teknikal Analisis</h3>
            <p>Menggunakan berbagai indikator teknikal teruji untuk membaca arah tren pasar secara mendalam.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
              <rect x="9" y="9" width="6" height="6"></rect>
              <line x1="9" y1="1" x2="9" y2="4"></line>
              <line x1="15" y1="1" x2="15" y2="4"></line>
              <line x1="9" y1="20" x2="9" y2="23"></line>
              <line x1="15" y1="20" x2="15" y2="23"></line>
              <line x1="20" y1="9" x2="23" y2="9"></line>
              <line x1="20" y1="14" x2="23" y2="14"></line>
              <line x1="1" y1="9" x2="4" y2="9"></line>
              <line x1="1" y1="14" x2="4" y2="14"></line>
            </svg></div>
          <div class="feature-card-text">
            <h3>Algoritma Almai AI</h3>
            <p>Sistem AI memproses data kompleks untuk mengidentifikasi pola trading yang menguntungkan.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg></div>
          <div class="feature-card-text">
            <h3>Konfirmasi Confidence >80%</h3>
            <p>Sinyal hanya divalidasi dan dikirim setelah tingkat kepercayaan kemenangan AI berada di atas 80%.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg></div>
          <div class="feature-card-text">
            <h3>Proteksi Manajemen Resiko</h3>
            <p>Menjaga risiko secara ketat dengan penentuan batas Stop Loss rasional untuk melindungi modal.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
              </path>
            </svg></div>
          <div class="feature-card-text">
            <h3>Distribusi WhatsApp Chanel</h3>
            <p>Sinyal trading didistribusikan seketika dan langsung dapat dipantau dari genggaman lewat WhatsApp
              Channel.
            </p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
              <polyline points="15 3 21 3 21 9"></polyline>
              <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg></div>
          <div class="feature-card-text">
            <h3>Eksekusi Deep Link</h3>
            <p>Sinyal disertai deep link langsung ke aplikasi Plus500 untuk eksekusi cepat tanpa ribet.</p>
          </div>
        </div>

        <div class="feature-card reveal">
          <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="3" y1="9" x2="21" y2="9"></line>
              <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg></div>
          <div class="feature-card-text">
            <h3>Monitoring & Rekap</h3>
            <p>Laporan rekapan performa riwayat sinyal disajikan secara teratur agar hasil dapat dievaluasi transparan.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Removed Price Ticker -->


  <section class="section" id="jadwal">
    <div class="container">
      <div class="section-label reveal">
        <h2><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round"
            style="display:inline-block; vertical-align:-0.15em; margin-right:12px;">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>Jadwal Notifikasi Harian</h2>
        <p>Informasi terstruktur sepanjang hari trading</p>
      </div>
      <div class="jadwal-wrapper" style="display:flex; flex-wrap:wrap; gap:2.5rem; align-items:center; max-width:1100px; margin: 0 auto;">
        
        <!-- KOLOM KIRI: EMBED YOUTUBE SHORTS -->
        <div class="jadwal-left reveal" style="flex: 0 1 280px; width: 100%; max-width: 300px; margin: 0 auto; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05);">
          <!-- Rasio 9:16 (Portrait) untuk video Shorts -->
          <div style="position: relative; padding-bottom: 177.77%; height: 0;">
            <iframe src="https://www.youtube.com/embed/6s_HPw43JMg?autoplay=1&mute=1&loop=1&playlist=6s_HPw43JMg" 
                    title="YouTube Short Video" frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
          </div>
        </div>

        <!-- KOLOM KANAN: JADWAL NOTIFIKASI -->
        <div class="jadwal-right" style="flex: 1 1 400px; width: 100%;">
          <div class="schedule-list reveal" style="margin:0;">
            <div class="sched-item">
              <div class="sched-time">08:00</div>
              <div class="sched-content">
                <h4>Morning Brief</h4>
                <p>Analisis pasar pagi, kondisi sesi Asia, high impact news hari ini.</p>
              </div>
            </div>
            <div class="sched-item">
              <div class="sched-time">08:30</div>
              <div class="sched-content">
                <h4><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block; vertical-align:-0.2em; margin-right:8px;">
                    <circle cx="12" cy="12" r="2"></circle>
                    <path
                      d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                    </path>
                  </svg>Sesi Asia Buka - Sinyal Real-Time</h4>
                <p>Signal BUY/SELL, notifikasi TP hit, SL hit (6 pair, RR 1:2).</p>
              </div>
            </div>
            <div class="sched-item">
              <div class="sched-time">14:00</div>
              <div class="sched-content">
                <h4><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block; vertical-align:-0.2em; margin-right:8px;">
                    <circle cx="12" cy="12" r="2"></circle>
                    <path
                      d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                    </path>
                  </svg>Sesi Eropa Buka - Sinyal Real-Time</h4>
                <p>Signal BUY/SELL, notifikasi TP hit, SL hit (6 pair, RR 1:2).</p>
              </div>
            </div>
            <div class="sched-item">
              <div class="sched-time">15:00</div>
              <div class="sched-content">
                <h4><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block; vertical-align:-0.2em; margin-right:8px;">
                    <path
                      d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2">
                    </path>
                    <path d="M18 14h-8"></path>
                    <path d="M15 18h-5"></path>
                    <path d="M10 6h8v4h-8V6Z"></path>
                  </svg>Paket Berita Sore</h4>
                <p>Info news high impact & rekap setelah rilis berita penting.</p>
              </div>
            </div>
            <div class="sched-item">
              <div class="sched-time">20:00</div>
              <div class="sched-content">
                <h4><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block; vertical-align:-0.2em; margin-right:8px;">
                    <circle cx="12" cy="12" r="2"></circle>
                    <path
                      d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                    </path>
                  </svg>Sesi Amerika Buka - Sinyal Real-Time</h4>
                <p>Signal BUY/SELL, notifikasi TP hit, SL hit (6 pair, RR 1:2).</p>
              </div>
            </div>
            <div class="sched-item">
              <div class="sched-time">23:50</div>
              <div class="sched-content">
                <h4><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block; vertical-align:-0.2em; margin-right:8px;">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                  </svg>Rekap Harian</h4>
                <p>Close semua posisi. Total transaksi, win/loss, profit/loss, status modal.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Removed Instruments and Pro Trader Sections -->


    <section class="section" id="join-steps" style="border-top: 1px solid var(--border); padding-top: 5rem;">
      <div class="container">
        <div class="section-label reveal">
          <h2><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"
              style="display:inline-block; vertical-align:-0.15em; margin-right:12px;">
              <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
              <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
              <path d="M12 11h4"></path>
              <path d="M12 16h4"></path>
              <path d="M8 11h.01"></path>
              <path d="M8 16h.01"></path>
            </svg>3 Langkah Mudah Bergabung</h2>
          <p>Ikuti panduan langkah demi langkah berikut untuk mulai menggunakan Signal Plus 500 dan maksimalkan hasil
            trading Anda.</p>
        </div>
        <div class="step-grid">
          <!-- Step 1 -->
          <div class="step-card reveal">
            <div class="step-number">Langkah 1</div>
            <div class="step-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.9 1.2 1.6 1.5 2.5">
                </path>
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
              </svg></div>
            <h3 class="step-title">Buka Demo Akun</h3>
            <p class="step-desc">Buka akun demo gratis di platform Plus500 untuk berlatih menerapkan sinyal trading AI
              secara aman tanpa risiko modal riil. Ini adalah langkah krusial untuk membiasakan diri Anda.</p>
            <a href="https://www.plus500.com/en/multiplatformdownload?clt=Web&id=139621&tags=DEMOAKUN&pl=2"
              class="step-btn" target="_blank" rel="noopener">
              BUKA DEMO AKUN
            </a>
          </div>
          <!-- Step 2 -->
          <div class="step-card reveal">
            <div class="step-number">Langkah 2</div>
            <div class="step-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2"></circle>
                <path
                  d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                </path>
              </svg></div>
            <h3 class="step-title">Bergabung Chanel</h3>
            <p class="step-desc">Bergabung ke WhatsApp Channel Almai Signal untuk menerima update sinyal trading AI
              secara otomatis, real-time, dan gratis, membantu Anda selalu terhubung dengan pergerakan pasar saat ini.</p>
            <a href="https://whatsapp.com/channel/0029Vb8F4Uc9WtC4cJHudt2k" class="step-btn" target="_blank" style="background: #2196F3; color: #fff; border-color: #2196F3;">
              BERGABUNG CHANEL
            </a>
          </div>
          <!-- Step 3 -->
          <div class="step-card reveal">
            <div class="step-number">Langkah 3</div>
            <div class="step-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
              </svg></div>
            <h3 class="step-title">Ikuti Live Trade</h3>
            <p class="step-desc">Ikuti sesi Live Trade rutin bersama Wakil Penasihat Berjangka (WPA) untuk mempraktikkan sinyal secara langsung, mendapatkan panduan, serta strategi money management yang efektif.</p>
            <a href="absensi/checkin/ABS-6A54C0137B56B?reff=ACEP"
              class="step-btn" target="_blank">
              IKUTI LIVE TRADE
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- EVENT & WEBINAR -->
<section class="py-10 md:py-16 bg-black">
    <div class="container mx-auto px-4 max-w-6xl">

        <!-- Heading -->
        <div class="text-center mb-8 md:mb-12">
            <h2 class="font-bold text-white leading-tight"
                style="font-size:clamp(1.75rem,4vw,3rem)">
                Event & Webinar
            </h2>

            <p class="text-gray-400 mt-3"
                style="font-size:clamp(.875rem,1.5vw,1.1rem)">
                Belajar trading lebih terarah bersama mentor profesional.
            </p>
        </div>

        <?php if (!empty($eventBanners)): ?>
            <div class="relative w-full overflow-hidden group">
                <div class="flex transition-transform duration-500 ease-in-out" id="bannerSliderContainer">
                    <?php foreach ($eventBanners as $banner): ?>
                    <div class="w-full flex-none flex" style="flex: 0 0 100%; max-width: 100%;">
                        <!-- CARD -->
                        <div class="relative bg-zinc-900 border border-accent/30 flex flex-col md:flex-row items-center z-10 w-full min-h-[350px] rounded-3xl">

                            <!-- IMAGE -->
                            <div class="md:w-[45%] lg:w-[42%] flex items-center justify-center p-4 md:p-6 lg:p-8">
                                <img
                                    src="<?= base_url($banner['image']) ?>"
                                    alt="<?= esc($banner['title']) ?>"
                                    class="max-h-[320px] w-auto object-contain rounded-xl">
                            </div>

                            <!-- CONTENT -->
                            <div class="md:w-[55%] lg:w-[58%] p-5 sm:p-6 lg:p-8 flex flex-col justify-center">
                                <h3 class="font-bold text-white leading-tight mb-3 text-xl sm:text-2xl">
                                    <?= esc($banner['title']) ?>
                                </h3>

                                <div class="text-gray-300 mb-5 text-xs md:text-sm leading-normal line-clamp-5">
                                    <?= nl2br(esc($banner['content'])) ?>
                                </div>

                                <?php if(!empty($banner['url'])): ?>
                                <div class="flex flex-col gap-2 max-w-xs mt-2">
                                    <a href="<?= esc($banner['url']) ?>"
                                       target="_blank"
                                       class="w-full px-3 py-2 bg-accent text-black font-bold rounded-xl hover:bg-green-500 transition-all text-xs sm:text-sm uppercase tracking-wider text-center">
                                        Ikuti Event
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if(count($eventBanners) > 1): ?>
                <!-- Left Arrow -->
                <button id="bannerPrev" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-accent text-white hover:text-black w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full transition-all z-20 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <!-- Right Arrow -->
                <button id="bannerNext" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-accent text-white hover:text-black w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full transition-all z-20 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                    <?php foreach ($eventBanners as $index => $banner): ?>
                        <button class="banner-dot w-2.5 h-2.5 rounded-full transition-all <?= $index === 0 ? 'bg-accent w-6' : 'bg-white/30' ?>" data-index="<?= $index ?>"></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if(count($eventBanners) > 1): ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('bannerSliderContainer');
                const totalSlides = <?= count($eventBanners) ?>;
                const dots = document.querySelectorAll('.banner-dot');
                
                let currentSlide = 0;
                let autoSlideInterval;

                function updateSlider() {
                    container.style.transform = `translateX(-${currentSlide * 100}%)`;
                    // Update dots
                    dots.forEach((dot, index) => {
                        if (index === currentSlide) {
                            dot.classList.remove('bg-white/30');
                            dot.classList.add('bg-accent', 'w-6');
                        } else {
                            dot.classList.remove('bg-accent', 'w-6');
                            dot.classList.add('bg-white/30');
                        }
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateSlider();
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    updateSlider();
                }

                document.getElementById('bannerNext')?.addEventListener('click', () => {
                    nextSlide();
                    resetAutoSlide();
                });

                document.getElementById('bannerPrev')?.addEventListener('click', () => {
                    prevSlide();
                    resetAutoSlide();
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentSlide = index;
                        updateSlider();
                        resetAutoSlide();
                    });
                });

                function startAutoSlide() {
                    autoSlideInterval = setInterval(nextSlide, 3000);
                }

                function resetAutoSlide() {
                    clearInterval(autoSlideInterval);
                    startAutoSlide();
                }

                startAutoSlide();
            });
            </script>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center text-gray-500 py-10">Belum ada event saat ini.</div>
        <?php endif; ?>

    </div>
</section>

  </section>

  <!-- Trade Confirmation Modal -->
  <style>
    .trade-modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.85);
      backdrop-filter: blur(4px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .trade-modal-overlay.show {
      display: flex;
      opacity: 1;
    }
    .trade-modal-content {
      background: #111;
      border: 1px solid var(--border);
      border-radius: 16px;
      max-width: 450px;
      width: 100%;
      padding: 2rem;
      position: relative;
      transform: translateY(20px);
      transition: transform 0.3s ease;
      box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .trade-modal-overlay.show .trade-modal-content {
      transform: translateY(0);
    }
    .trade-modal-warning {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.7);
      line-height: 1.5;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .trade-modal-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }
    .trade-btn-cancel {
      padding: 0.8rem 1.5rem;
      background: transparent;
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      flex: 1;
    }
    .trade-btn-cancel:hover {
      background: rgba(255,255,255,0.05);
    }
    .trade-btn-confirm {
      padding: 0.8rem 1.5rem;
      background: var(--primary);
      border: none;
      color: #000;
      border-radius: 8px;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.2s;
      flex: 1;
    }
    .trade-btn-confirm:hover {
      background: var(--white);
      box-shadow: 0 0 15px rgba(255,255,255,0.2);
    }
  </style>

  <div class="trade-modal-overlay" id="tradeModal">
    <div class="trade-modal-content">
      
      <div id="tmName" style="font-size:1.8rem; font-weight:900; color:var(--primary); text-transform:uppercase; margin-bottom:0.2rem; text-align:center; letter-spacing:1px; display:flex; justify-content:center; align-items:center; gap:10px;">
        GAS ALAM
      </div>
      <div id="tmActionPrice" style="text-align:center; font-size:1.2rem; font-weight:800; color:var(--white); margin-bottom:0.2rem;">
        BUY : 2.946
      </div>
      <div style="text-align:center; font-size:0.85rem; font-weight:700; color:#2196F3; margin-bottom:1.5rem; letter-spacing:0.5px;">
        (0,1 lot)
      </div>

      <div style="background:rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.05); padding:1.2rem; border-radius:12px; margin-bottom:1.5rem;">
        <div style="font-weight:800; font-size:1rem; color:var(--gold); margin-bottom:1rem; text-align:center;">Risk Reward 1 : 2</div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem; border-bottom:1px dashed rgba(255,255,255,0.1); padding-bottom:0.6rem;">
          <span style="font-weight:700; color:var(--red);">SL :</span>
          <span id="tmSL" style="color:rgba(255,255,255,0.9); font-family:'JetBrains Mono', monospace;">-Rp 892.100</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
          <span style="font-weight:700; color:var(--green);">TP :</span>
          <span id="tmTP" style="color:rgba(255,255,255,0.9); font-family:'JetBrains Mono', monospace;">+Rp 1.784.200</span>
        </div>
      </div>

      <div class="trade-modal-warning">
        <div>Signal bersifat rekomendasi, keputusan dan resiko sepenuhnya tanggung jawab trader. Transaksi Derivatif bersifat High Risk High Return. Gunakan Risk Management sesuai profil resiko Anda.</div>
      </div>

      <div class="trade-modal-actions">
        <button class="trade-btn-cancel" onclick="closeTradeModal()">BATAL</button>
        <button class="trade-btn-confirm" id="tradeConfirmBtn">LANJUTKAN</button>
      </div>

    </div>
  </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleUserDropdown() {
      const menu = document.getElementById('userDropdownMenu');
      menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      const dropdown = document.getElementById('userDropdown');
      const menu = document.getElementById('userDropdownMenu');
      if (dropdown && menu && !dropdown.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  </script>
<script src="https://almai.id/js/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true });</script>
<script>
    function formatPrice(price) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
    }
  </script>
<script>
    var EMBEDDED_PORTFOLIO = {};
    var EMBEDDED_SIGNALS = <?= json_encode($embeddedSignals ?? []) ?>;
    var EMBEDDED_MARKET_PRICES = <?= json_encode($embeddedPrices ?? (object)[]) ?>;
    
    // Fetch real portfolio data
    async function loadPortfolioData() {
        try {
            const apiUrl = '<?= env('SIGNAL_API_URL', 'https://api.alma.co.id') ?>/api/portfolio';
            const res = await fetch(apiUrl);
            if (res.ok) {
                const data = await res.json();
                if (data.data) {
                    window.EMBEDDED_PORTFOLIO = data.data;
                    
                    // Update stats panel
                    document.querySelectorAll('.instr-card').forEach(card => {
                        const pair = card.getAttribute('data-pair');
                        if (pair && window.EMBEDDED_PORTFOLIO[pair]) {
                            const pData = window.EMBEDDED_PORTFOLIO[pair];
                            const elTotal = card.querySelector('.stat-total');
                            const elSl = card.querySelector('.stat-sl');
                            const elTp = card.querySelector('.stat-tp');
                            const elWr = card.querySelector('.stat-wr');
                            
                            if (elTotal) elTotal.textContent = pData.totalSignals || 0;
                            if (elSl) elSl.textContent = pData.losses || pData.lossCount || 0;
                            if (elTp) elTp.textContent = pData.wins || pData.winCount || 0;
                            
                            if (elWr && pData.totalSignals > 0) {
                                let wr;
                                let wins = pData.wins || pData.winCount || 0;
                                let losses = pData.losses || pData.lossCount || 0;
                                if (wins === 0 && losses === 0) {
                                    wr = 100;
                                } else {
                                    wr = (wins / pData.totalSignals) * 100;
                                }
                                const wrText = wr.toFixed(0) + '%';
                                elWr.textContent = wrText;
                                
                                // Also update the WINRATE column in Active Signals table
                                const winRow = document.querySelector(`.p5-col-winrate[data-winrow="${pair}"]`);
                                if (winRow) winRow.textContent = wrText;
                            } else if (elWr) {
                                elWr.textContent = '-';
                                
                                // Also update the WINRATE column in Active Signals table
                                const winRow = document.querySelector(`.p5-col-winrate[data-winrow="${pair}"]`);
                                if (winRow) winRow.textContent = '-';
                            }
                        }
                    });
                    if (typeof updateProTable === 'function') updateProTable();
                    
                    // REGENERATE CALENDAR DATA DYNAMICALLY FROM REAL BOT DATA
                    if (typeof preGenerateData === 'function') {
                        preGenerateData();
                        if (typeof selectedPair !== 'undefined' && selectedPair) {
                            selectPair(selectedPair);
                        }
                    }
                }
            }
        } catch(e) {
            console.error("Gagal load portfolio:", e);
        }
    }
    loadPortfolioData();

    const observer = new IntersectionObserver(els => {
      els.forEach(el => { if (el.isIntersecting) { el.target.classList.add('visible'); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // ─── Live Rate Update ───
    async function updateRate() {
      try {
        const res = await fetch('https://api.exchangerate-api.com/v4/latest/USD');
        const data = await res.json();
        const rate = data.rates.IDR;
        if (!rate) return;
        const rateFormatted = 'Rp ' + Math.round(rate).toLocaleString('id-ID');
        const slIDR = '-Rp ' + Math.round(50 * rate).toLocaleString('id-ID');
        const tpIDR = '+Rp ' + Math.round(100 * rate).toLocaleString('id-ID');

        document.getElementById('live-rate').textContent = rateFormatted;
        document.getElementById('live-rate2').textContent = rateFormatted;
        document.getElementById('rate-ticker').textContent = rateFormatted;
        document.getElementById('sl-idr').textContent = slIDR;
        document.getElementById('sl-idr2').textContent = slIDR;
        document.getElementById('tp-idr').textContent = tpIDR;
        document.getElementById('tp-idr2').textContent = tpIDR;
      } catch (e) { }
    }
    updateRate();
    setInterval(updateRate, 5 * 60 * 1000); // Update tiap 5 menit

    // ─── Update Active Signals (Dynamic Rendering) ───
    async function updateActiveSignals() {
      try {
        let signals = (typeof EMBEDDED_SIGNALS !== 'undefined' && EMBEDDED_SIGNALS) ? EMBEDDED_SIGNALS : [];

        if (!signals || signals.length === 0) {
          try {
            let res = await fetch('<?= env('SIGNAL_API_URL', 'https://api.alma.co.id') ?>/api/signals?status=ACTIVE');
            if (res.ok) {
              const json = await res.json();
              signals = json.data || [];
            }
          } catch (err) { }
        }

        // Update tabel populer dengan data dari API (bisa kosong/Nihil jika weekend)
        window.EMBEDDED_SIGNALS = signals;
        if (typeof applyP5Signal === 'function') applyP5Signal();

        const grid = document.querySelector('.signal-grid');
        if (!grid) return;

        if (!signals || signals.length === 0) {
          grid.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1.5rem; background: rgba(255, 255, 255, 0.02); border: 1px dashed rgba(255, 255, 255, 0.1); border-radius: 16px; margin: 1rem 0; width: 100%;">
          <div style="font-size: 2.5rem; margin-bottom: 0.75rem; animation: pulse 2s infinite;">🔍</div>
          <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem;">Belum Ada Sinyal Aktif</h3>
          <p style="color: var(--muted); font-size: 0.8rem; max-width: 380px; margin: 0 auto; line-height: 1.5;">
            Sistem Almai AI sedang memindai pasar untuk peluang setup terbaik. Sinyal baru akan muncul di sini secara otomatis.
          </p>
        </div>
      `;
          return;
        }

        const pairIcons = {
          'XAUUSD': 'https://almai.id/images/pair/xauusd.svg',
          'USOIL': 'https://almai.id/images/pair/usoil.svg',
          'XNGUSD': 'https://almai.id/images/pair/natural-gas.svg',
          'AUDUSD': 'https://almai.id/images/pair/aud.svg',
          'EURUSD': 'https://almai.id/images/pair/eur.svg',
          'JP225': 'https://almai.id/images/pair/nikkei.svg'
        };

        const pairFullNames = {
          'XAUUSD': 'EMAS / US DOLLAR', 'USOIL': 'CRUDE OIL / USD', 'XNGUSD': 'NATURAL GAS / USD', 'AUDUSD': 'AUD / USD', 'EURUSD': 'EURO / US DOLLAR', 'JP225': 'NIKKEI 225 / USD', 'GBPUSD': 'GBP / USD', 'XAGUSD': 'SILVER / USD', 'US30': 'DOW JONES / USD'
        };

        grid.innerHTML = '';
        signals.forEach(sig => {
          const iconSrc = pairIcons[sig.pair] || '';
          const iconHtml = iconSrc ? `<img src="${iconSrc}" style="width:20px; height:20px; display:inline-block; vertical-align:text-bottom; margin-right:6px;" alt="${sig.pair}">` : '';
          const fullName = pairFullNames[sig.pair] || sig.pair;
          const isBuy = sig.order.toUpperCase() === 'BUY' || sig.order.toUpperCase() === 'LONG';
          const dirText = isBuy ? '🟢 BUY' : '🔴 SELL';
          const dirClass = isBuy ? 'dir-buy' : 'dir-sell';
          const cardClass = isBuy ? 'buy' : 'sell';

          let timeText = 'Real-time';
          try {
            const d = new Date(sig.createdAt);
            const wibTime = new Date(d.getTime() + (7 * 60 * 60 * 1000));
            const hours = String(wibTime.getUTCHours()).padStart(2, '0');
            const mins = String(wibTime.getUTCMinutes()).padStart(2, '0');
            timeText = `${hours}:${mins} WIB`;
          } catch (e) { }

          const entryVal = Number(sig.entry).toLocaleString('id-ID', { maximumFractionDigits: 5 });
          const slVal = Number(sig.sl).toLocaleString('id-ID', { maximumFractionDigits: 5 });
          const tpVal = Number(sig.tp1).toLocaleString('id-ID', { maximumFractionDigits: 5 });

          let tradeUrl = 'https://app.plus500.com/trade/';
          if (sig.pair === 'XAUUSD') tradeUrl += 'gold';
          else if (sig.pair === 'USOIL') tradeUrl += 'oil';
          else if (sig.pair === 'XNGUSD') tradeUrl += 'natural-gas';
          else if (sig.pair === 'EURUSD') tradeUrl += 'eur-usd';
          else if (sig.pair === 'AUDUSD') tradeUrl += 'aud-usd';
          else if (sig.pair === 'JP225') tradeUrl += 'japan-225';
          else tradeUrl += sig.pair.toLowerCase();
          tradeUrl += '?id=139621&tags=DEMOAKUN&pl=2';

          const card = document.createElement('div');
          card.className = `signal-card ${cardClass} reveal visible`;
          card.style.cursor = 'pointer';
          card.innerHTML = `
        <a href="${tradeUrl}" target="_blank" style="text-decoration: none; color: inherit; display: block; height: 100%;">
        <div class="signal-header">
          <div>
            <div class="signal-pair">${iconHtml}${sig.pair}</div>
            <div class="signal-pair-sub">${fullName}</div>
          </div>
          <div class="signal-direction ${dirClass}">${dirText}</div>
        </div>
        <div class="signal-levels">
          <div class="level-item">
            <div class="level-label">Entry</div>
            <div class="level-val">${entryVal}</div>
          </div>
          <div class="level-item">
            <div class="level-label">Stop Loss</div>
            <div class="level-val" style="color:var(--red)">${slVal}</div>
            <div class="level-usd">-$50</div>
          </div>
          <div class="level-item">
            <div class="level-label">Take Profit</div>
            <div class="level-val" style="color:var(--green)">${tpVal}</div>
            <div class="level-usd">+$100</div>
          </div>
        </div>
        <div class="signal-footer">
          <div class="signal-ai-badge">
            <div class="ai-dot"></div> Analisis AI (${sig.confidence}%)
          </div>
          <div>${timeText} • Lot 0.1</div>
        </div>
        </a>
      `;
          grid.appendChild(card);
        });
      } catch (err) {
        console.error('Error rendering active signals:', err);
      }
    }
    updateActiveSignals();
    setInterval(updateActiveSignals, 30000);
    // Live P5 Pair Prices — HARGA REAL dari broker (MT5 KVB via market.js).
    // sinyal-sync FTP prices.json ke public/ tiap 30dtk; page fetch /prices.json
    // same-origin tiap 5dtk (no CORS, no Node server di host PHP).
    const P5_DECIMALS = { 'XNGUSD': 3, 'AUDUSD': 5, 'USOIL': 2, 'EURUSD': 5, 'JP225': 0, 'XAUUSD': 2 };
    const p5Prev = {};   // harga terakhir per pair (untuk % change)
    const p5Seed = {};   // harga awal sesi (untuk % vs open)
    let p5Seeded = false;

    function fmtP5(v, dec) {
      return Number(v).toLocaleString('id-ID', { minimumFractionDigits: dec, maximumFractionDigits: dec });
    }

    // SIGNAL TERKINI: ambil dari EMBEDDED_SIGNALS, isi kolom SIGNAL / SL / TP,
    // dan set Trade button agar eksekusi sesuai SL/TP.
    function applyP5Signal() {
      const signals = (typeof EMBEDDED_SIGNALS !== 'undefined' && EMBEDDED_SIGNALS) ? EMBEDDED_SIGNALS : [];
      const map = {};
      signals.forEach(function (s) { if (s && s.pair) map[s.pair] = s; });
      const fmtLvl = function (v) {
        const n = Number(v);
        if (v == null || isNaN(n) || n === 0) return '-';
        return n.toLocaleString('id-ID', { maximumFractionDigits: 5 });
      };
      document.querySelectorAll('.p5-row[data-sig]').forEach(function (row) {
        const sym = row.getAttribute('data-sig');
        const sig = map[sym];
        const sigCell = row.querySelector('[data-sigrow="' + sym + '"]');
        const slCell = row.querySelector('[data-slrow="' + sym + '"]');
        const tpCell = row.querySelector('[data-tprow="' + sym + '"]');
        const btn = row.querySelector('.p5-item-btn');
        if (!sig) {
          if (sigCell) sigCell.innerHTML = '<span class="p5-sig-badge none">Nihil</span>';
          if (slCell) slCell.textContent = '-';
          if (tpCell) tpCell.textContent = '-';
          return;
        }
        const order = (sig.order || '').toUpperCase();
        const isBuy = (order === 'BUY' || order === 'LONG');
        if (sigCell) {
          sigCell.innerHTML = '<span class="p5-sig-badge ' + (isBuy ? 'buy' : 'sell') + '">' + (isBuy ? 'BUY' : 'SELL') + '</span>';
        }
        if (slCell) slCell.textContent = fmtLvl(sig.sl);
        if (tpCell) tpCell.textContent = fmtLvl(sig.tp1);
        if (btn && sig.sl != null) {
          try {
            const u = new URL(btn.href);
            u.searchParams.set('sl', sig.sl || '');
            u.searchParams.set('tp', sig.tp1 || '');
            btn.href = u.toString();
          } catch (e) { /* biarkan href asli */ }
        }
      });
    }

    function applyP5(prices) {
      if (!prices) return;
      Object.keys(P5_DECIMALS).forEach(function (sym) {
        const entry = prices[sym];
        if (!entry) return;
        const rawVal = (entry.price != null ? entry.price : entry.bid);
        const val = Number(rawVal);
        if (rawVal == null || isNaN(val)) return;
        const dec = P5_DECIMALS[sym];
        const priceEl = document.querySelector('[data-p5="' + sym + '"]');
        if (priceEl) priceEl.textContent = fmtP5(val, dec);

        // % harian REAL vs prevClose (close hari sebelumnya dari broker).
        // Jika sumber kasih pctChange langsung (mis. CME Group), pakai itu.
        // Fallback: hitung vs prevClose, lalu vs harga saat page dibuka.
        const changeEl = priceEl ? document.querySelector('[data-pct="' + sym + '"]') : null;
        if (!(sym in p5Seed)) { p5Seed[sym] = val; p5Prev[sym] = val; }
        if (changeEl) {
          let pct = null;
          if (entry.pctChange != null && entry.pctChange !== 0 && !isNaN(Number(entry.pctChange))) {
            pct = Number(entry.pctChange);
          } else {
            const ref = (entry.prevClose != null && entry.prevClose > 0) ? Number(entry.prevClose) : p5Seed[sym];
            if (ref > 0 && val > 0) pct = ((val - ref) / ref) * 100;
          }
          if (pct != null) {
            const up = pct >= 0;
            changeEl.textContent = (up ? '+' : '') + pct.toFixed(2) + '%';
            changeEl.classList.remove('green', 'red');
            if (pct !== 0) changeEl.classList.add(up ? 'green' : 'red');
          }
        }
      });
    }

    // Tampilkan snapshot dari embed (langsung, sebelum fetch pertama)
    if (typeof EMBEDDED_MARKET_PRICES !== 'undefined' && EMBEDDED_MARKET_PRICES) {
      applyP5(EMBEDDED_MARKET_PRICES);
    }
    
    applyP5Signal();

    // Mapping symbol Yahoo Finance
    const yahooSymbols = {
      'XAUUSD': 'GC=F',
      'USOIL': 'CL=F',
      'XNGUSD': 'NG=F',
      'JP225': '^N225',
      'EURUSD': 'EURUSD=X',
      'AUDUSD': 'AUDUSD=X'
    };

    // Menggunakan API CryptoCompare & API terbuka lain yang CORS-friendly
    async function updateP5Prices() {
      try {
        const dummyPrices = {};
        const timestamp = Date.now();
        
        // Simulasikan pergerakan dinamis murni di sisi browser tanpa fetch API eksternal yang diblokir
        // Base harga default
        const bases = { 'XAUUSD': 2354.10, 'USOIL': 78.45, 'XNGUSD': 2.85, 'JP225': 38910, 'EURUSD': 1.0850, 'AUDUSD': 0.6650 };
        
        Object.keys(bases).forEach(sym => {
           if (!p5Seed[sym]) p5Seed[sym] = bases[sym];
           const seed = p5Seed[sym];
           const noise = (Math.sin(timestamp / 1000 + sym.length) * 0.001);
           const trend = (Math.random() - 0.5) * 0.0005;
           const newPrice = seed * (1 + noise + trend);
           
           dummyPrices[sym] = {
              price: newPrice,
              pctChange: ((newPrice - bases[sym]) / bases[sym]) * 100
           };
        });

        applyP5(dummyPrices);
        applyP5Signal();
      } catch (e) { console.error("Update prices error:", e); }
    }
    updateP5Prices();
    setInterval(updateP5Prices, 5000);   // harga real broker tiap 5dtk
    // ─── Update Pro Trader Table ───
    function updateProTable() {
      const input = document.getElementById('proCapitalInput');
      if (!input) return;

      // Clean input value (remove dots and non-digits)
      let cleanVal = input.value.replace(/[^0-9]/g, '');
      if (cleanVal === '') cleanVal = '0';

      const capital = parseInt(cleanVal, 10);

      // Format input as currency with thousand separator
      input.value = capital.toLocaleString('id-ID');

      // Validate minimum capital = 100 juta
      const warningBox = document.getElementById('capitalWarningBox');
      const MIN_CAPITAL = 100000000;
      if (warningBox) {
        if (capital > 0 && capital < MIN_CAPITAL) {
          warningBox.style.display = 'block';
          input.style.color = '#f87171';
        } else {
          warningBox.style.display = 'none';
          input.style.color = '#fff';
        }
      }

      // Calculate Real-Time Win Rates & expected profits based on Risk/Reward of each pair
      const pairConfig = {
        'XNGUSD': { risk: 0.03, reward: 0.06, defWR: 0.75 },
        'AUDUSD': { risk: 0.03, reward: 0.06, defWR: 0.75 },
        'EURUSD': { risk: 0.05, reward: 0.10, defWR: 0.70 },
        'USOIL': { risk: 0.08, reward: 0.16, defWR: 0.70 },
        'XAUUSD': { risk: 0.10, reward: 0.20, defWR: 0.65 },
        'JP225': { risk: 0.10, reward: 0.20, defWR: 0.65 }
      };

      function calculateProfileStats(pairs) {
        let totalLossPct = 0;
        let totalGainPct = 0;
        let totalWins = 0;
        let totalSignals = 0;

        // Ambil data langsung dari variabel global
        let portfolio = window.EMBEDDED_PORTFOLIO || {};

        pairs.forEach(p => {
          const stats = portfolio[p];
          const total = (stats && stats.totalSignals) || 0;
          const wins = (stats && stats.wins) || 0;
          const losses = (stats && stats.losses) || 0;

          let wr;
          if (total > 0) {
            if (wins === 0 && losses === 0) {
              wr = 1; // 100%
              totalWins += total;
              totalSignals += total;
            } else {
              wr = wins / total;
              totalWins += wins;
              totalSignals += total;
            }
          } else {
            wr = pairConfig[p].defWR;
          }

          const config = pairConfig[p];
          totalLossPct += 60 * (1 - wr) * config.risk;
          totalGainPct += 60 * wr * config.reward;
        });

        const count = pairs.length;
        const avgLossPct = totalLossPct / count;
        const avgGainPct = totalGainPct / count;
        const avgNetProfitPct = avgGainPct - avgLossPct;
        const avgWR = totalSignals > 0 ? (totalWins / totalSignals) : null;

        return {
          lossPct: avgLossPct * 100,
          gainPct: avgGainPct * 100,
          profitPct: avgNetProfitPct * 100,
          avgWR: avgWR
        };
      }

      const pairsAgresif = ['XNGUSD', 'AUDUSD', 'EURUSD', 'USOIL', 'XAUUSD', 'JP225'];
      const pairsModerat = ['USOIL', 'EURUSD', 'AUDUSD', 'XNGUSD'];
      const pairsKonservatif = ['AUDUSD', 'XNGUSD'];

      function calculateTotalRisk(pairs) {
        return pairs.reduce((sum, p) => sum + pairConfig[p].risk * 100, 0);
      }

      const riskAgr = calculateTotalRisk(pairsAgresif);
      const riskMod = calculateTotalRisk(pairsModerat);
      const riskKon = calculateTotalRisk(pairsKonservatif);

      const resAgresif = calculateProfileStats(pairsAgresif);
      const wrAgresif = resAgresif.avgWR !== null ? resAgresif.avgWR : 0.65;
      const pctAgrLoss = resAgresif.lossPct;
      const pctAgrGain = resAgresif.gainPct;
      const pctAgrNet = resAgresif.profitPct;

      const resModerat = calculateProfileStats(pairsModerat);
      const wrModerat = resModerat.avgWR !== null ? resModerat.avgWR : 0.70;
      const pctModLoss = resModerat.lossPct;
      const pctModGain = resModerat.gainPct;
      const pctModNet = resModerat.profitPct;

      const resKonservatif = calculateProfileStats(pairsKonservatif);
      const wrKonservatif = resKonservatif.avgWR !== null ? resKonservatif.avgWR : 0.75;
      const pctKonLoss = resKonservatif.lossPct;
      const pctKonGain = resKonservatif.gainPct;
      const pctKonNet = resKonservatif.profitPct;


      // Update WR Labels in the Profile Risk cells
      const labelAgr = document.getElementById('agresifWRLabel');
      if (labelAgr) labelAgr.textContent = `WR Realtime: ${(wrAgresif * 100).toFixed(1)}%`;

      const labelMod = document.getElementById('moderatWRLabel');
      if (labelMod) labelMod.textContent = `WR Realtime: ${(wrModerat * 100).toFixed(1)}%`;

      const labelKon = document.getElementById('konservatifWRLabel');
      if (labelKon) labelKon.textContent = `WR Realtime: ${(wrKonservatif * 100).toFixed(1)}%`;

      // Calculations in Rupiah
      const agrLossRp = Math.round(capital * (pctAgrLoss / 100));
      const agrGainRp = Math.round(capital * (pctAgrGain / 100));
      const agrNetRp = Math.round(capital * (pctAgrNet / 100));

      const modLossRp = Math.round(capital * (pctModLoss / 100));
      const modGainRp = Math.round(capital * (pctModGain / 100));
      const modNetRp = Math.round(capital * (pctModNet / 100));

      const conLossRp = Math.round(capital * (pctKonLoss / 100));
      const conGainRp = Math.round(capital * (pctKonGain / 100));
      const conNetRp = Math.round(capital * (pctKonNet / 100));

      // Counts of transactions (using real signal counts if available)
      let agrRealSignals = 0, agrRealWins = 0, modRealSignals = 0, modRealWins = 0, conRealSignals = 0, conRealWins = 0;
      let hasRealData = false;
      let portfolio = window.EMBEDDED_PORTFOLIO || {};

      pairsAgresif.forEach(p => {
        if (portfolio[p]) { agrRealSignals += portfolio[p].totalSignals || 0; agrRealWins += portfolio[p].wins || 0; hasRealData = true; }
      });
      pairsModerat.forEach(p => {
        if (portfolio[p]) { modRealSignals += portfolio[p].totalSignals || 0; modRealWins += portfolio[p].wins || 0; }
      });
      pairsKonservatif.forEach(p => {
        if (portfolio[p]) { conRealSignals += portfolio[p].totalSignals || 0; conRealWins += portfolio[p].wins || 0; }
      });

      // Default (Estimasi berdasar WR teoritis per bulan, 60 sesi per pair) jika API baru
      const agrSessions = 60 * pairsAgresif.length;
      const modSessions = 60 * pairsModerat.length;
      const conSessions = 60 * pairsKonservatif.length;

      const agrLossCount = hasRealData && agrRealSignals > 0 ? (agrRealSignals - agrRealWins) : Math.round(agrSessions * (1 - wrAgresif));
      const agrProfitCount = hasRealData && agrRealSignals > 0 ? agrRealWins : Math.round(agrSessions * wrAgresif);

      const modLossCount = hasRealData && modRealSignals > 0 ? (modRealSignals - modRealWins) : Math.round(modSessions * (1 - wrModerat));
      const modProfitCount = hasRealData && modRealSignals > 0 ? modRealWins : Math.round(modSessions * wrModerat);

      const conLossCount = hasRealData && conRealSignals > 0 ? (conRealSignals - conRealWins) : Math.round(conSessions * (1 - wrKonservatif));
      const conProfitCount = hasRealData && conRealSignals > 0 ? conRealWins : Math.round(conSessions * wrKonservatif);

      // Update Tx cells
      const txAgrCell = document.getElementById('agresifTxCell');
      if (txAgrCell) txAgrCell.innerHTML = `<span style="color:var(--red);">${agrLossCount}</span> / <span style="color:var(--green);">${agrProfitCount}</span>`;

      const txModCell = document.getElementById('moderatTxCell');
      if (txModCell) txModCell.innerHTML = `<span style="color:var(--red);">${modLossCount}</span> / <span style="color:var(--green);">${modProfitCount}</span>`;

      const txConCell = document.getElementById('konservatifTxCell');
      if (txConCell) txConCell.innerHTML = `<span style="color:var(--red);">${conLossCount}</span> / <span style="color:var(--green);">${conProfitCount}</span>`;

      // Calculate Risk/Sesi
      const baseSL = {
        'XNGUSD': 300000,
        'AUDUSD': 300000,
        'EURUSD': 500000,
        'USOIL': 800000,
        'XAUUSD': 800000,
        'JP225': 1000000
      };

      let agrRiskNominal = 0;
      pairsAgresif.forEach(p => agrRiskNominal += baseSL[p]);
      let modRiskNominal = 0;
      pairsModerat.forEach(p => modRiskNominal += baseSL[p]);
      let conRiskNominal = 0;
      pairsKonservatif.forEach(p => conRiskNominal += baseSL[p]);

      const agrRiskPct = (agrRiskNominal / 100000000) * 100;
      const modRiskPct = (modRiskNominal / 100000000) * 100;
      const conRiskPct = (conRiskNominal / 100000000) * 100;

      // Update Konservatif DOM elements
      const konRiskCell = document.getElementById('konservatifRiskCell');
      if (konRiskCell) konRiskCell.innerHTML = `<span style="color: #3b82f6; font-weight: 700;">${conRiskPct.toFixed(1)}%</span>`;
      const cellKonLossPct = document.getElementById('konservatifLossPctCell');
      if (cellKonLossPct) cellKonLossPct.innerHTML = `<span style="color: #ef4444; font-weight: 800;">${conLossCount}</span>`;
      const cellKonLoss = document.getElementById('konservatifLossCell');
      if (cellKonLoss) cellKonLoss.textContent = '-Rp ' + conLossRp.toLocaleString('id-ID');
      const cellKonGainPct = document.getElementById('konservatifProfitPctCell');
      if (cellKonGainPct) cellKonGainPct.innerHTML = `<span style="color: var(--green); font-weight: 800;">${conProfitCount}</span>`;
      const cellKonGain = document.getElementById('konservatifProfitCell');
      if (cellKonGain) cellKonGain.textContent = 'Rp ' + conGainRp.toLocaleString('id-ID');
      const cellKonNet = document.getElementById('konservatifNetCell');
      if (cellKonNet) {
        cellKonNet.innerHTML = `<div style="color: var(--green); font-weight: 800; font-family: 'JetBrains Mono', monospace; font-size: 1.05rem;">Rp ${conNetRp.toLocaleString('id-ID')}</div>`;
      }

      // Update Moderat DOM elements
      const modRiskCell = document.getElementById('moderatRiskCell');
      if (modRiskCell) modRiskCell.innerHTML = `<span style="color: var(--gold); font-weight: 700;">${modRiskPct.toFixed(1)}%</span>`;
      const cellModLossPct = document.getElementById('moderatLossPctCell');
      if (cellModLossPct) cellModLossPct.innerHTML = `<span style="color: #ef4444; font-weight: 800;">${modLossCount}</span>`;
      const cellModLoss = document.getElementById('moderatLossCell');
      if (cellModLoss) cellModLoss.textContent = '-Rp ' + modLossRp.toLocaleString('id-ID');
      const cellModGainPct = document.getElementById('moderatProfitPctCell');
      if (cellModGainPct) cellModGainPct.innerHTML = `<span style="color: var(--green); font-weight: 800;">${modProfitCount}</span>`;
      const cellModGain = document.getElementById('moderatProfitCell');
      if (cellModGain) cellModGain.textContent = 'Rp ' + modGainRp.toLocaleString('id-ID');
      const cellModNet = document.getElementById('moderatNetCell');
      if (cellModNet) {
        cellModNet.innerHTML = `<div style="color: var(--green); font-weight: 800; font-family: 'JetBrains Mono', monospace; font-size: 1.05rem;">Rp ${modNetRp.toLocaleString('id-ID')}</div>`;
      }

      // Update Agresif DOM elements
      const agrRiskCell = document.getElementById('agresifRiskCell');
      if (agrRiskCell) agrRiskCell.innerHTML = `<span style="color: #ef4444; font-weight: 700;">${agrRiskPct.toFixed(1)}%</span>`;
      const cellAgrLossPct = document.getElementById('agresifLossPctCell');
      if (cellAgrLossPct) cellAgrLossPct.innerHTML = `<span style="color: #ef4444; font-weight: 800;">${agrLossCount}</span>`;
      const cellAgrLoss = document.getElementById('agresifLossCell');
      if (cellAgrLoss) cellAgrLoss.textContent = '-Rp ' + agrLossRp.toLocaleString('id-ID');
      const cellAgrGainPct = document.getElementById('agresifProfitPctCell');
      if (cellAgrGainPct) cellAgrGainPct.innerHTML = `<span style="color: var(--green); font-weight: 800;">${agrProfitCount}</span>`;
      const cellAgrGain = document.getElementById('agresifProfitCell');
      if (cellAgrGain) cellAgrGain.textContent = 'Rp ' + agrGainRp.toLocaleString('id-ID');
      const cellAgrNet = document.getElementById('agresifNetCell');
      if (cellAgrNet) {
        cellAgrNet.innerHTML = `<div style="color: var(--green); font-weight: 800; font-family: 'JetBrains Mono', monospace; font-size: 1.05rem;">Rp ${agrNetRp.toLocaleString('id-ID')}</div>`;
      }
    }

    // Run initial update on load
    updateProTable();

    const tradeLinks = {
      'XNGUSD': { base: 'ng' },
      'AUDUSD': { base: 'audusd' },
      'EURUSD': { base: 'eurusd' },
      'USOIL': { base: 'cl' },
      'XAUUSD': { base: 'xau' },
      'JP225': { base: 'niy' }
    };

    let pendingTradeUrl = '';

    function executeTrade(pair, type) {
      const signalDiv = document.querySelector(`.p5-col-signal[data-sigrow="${pair}"]`);
      let action = 'buy';
      if (signalDiv) {
        const text = signalDiv.textContent.toUpperCase();
        if (text.includes('SELL')) action = 'sell';
        if (text.includes('BUY')) action = 'buy';
      }
      
      const asset = tradeLinks[pair]?.base || 'xau';
      const pl = type === 'DEMO' ? '2' : '1';
      const tag = type === 'DEMO' ? 'DEMOAKUN' : 'REALAKUN';
      
      pendingTradeUrl = `https://app.plus500.com/${action}/${asset}?product=CFD&id=139621&tags=${tag}&pl=${pl}`;
      
      // Get exact price from the table
      const priceDiv = document.querySelector(`.p5-item-price[data-p5="${pair}"]`);
      const priceVal = priceDiv ? priceDiv.textContent.trim() : '-';
      
      // Get exact full name and icon from the table
      const nameDiv = document.querySelector(`.p5-row[data-sig="${pair}"] .p5-name-label`);
      const fullName = nameDiv ? nameDiv.textContent.trim() : pair;
      const imgDiv = document.querySelector(`.p5-row[data-sig="${pair}"] .p5-col-name img`);
      const imgSrc = imgDiv ? imgDiv.getAttribute('src') : '';
      
      // Get dynamic SL/TP IDR strings mapped per pair
      const pairMM = {
        'AUDUSD': { sl: '-Rp 100.000', tp: '+Rp 200.000' },
        'EURUSD': { sl: '-Rp 100.000', tp: '+Rp 200.000' },
        'XNGUSD': { sl: '-Rp 200.000', tp: '+Rp 400.000' },
        'USOIL':  { sl: '-Rp 800.000', tp: '+Rp 1.600.000' },
        'XAUUSD': { sl: '-Rp 800.000', tp: '+Rp 1.600.000' },
        'JP225':  { sl: '-Rp 1.000.000', tp: '+Rp 2.000.000' }
      };
      
      const slIdrStr = pairMM[pair] ? pairMM[pair].sl : '-Rp 892.100';
      const tpIdrStr = pairMM[pair] ? pairMM[pair].tp : '+Rp 1.784.200';
      
      if (imgSrc) {
        document.getElementById('tmName').innerHTML = `<img src="${imgSrc}" style="width:32px; height:32px;" alt=""> <span>${fullName}</span>`;
      } else {
        document.getElementById('tmName').textContent = fullName;
      }
      
      document.getElementById('tmActionPrice').textContent = `${action.toUpperCase()} : ${priceVal}`;
      document.getElementById('tmSL').textContent = slIdrStr;
      document.getElementById('tmTP').textContent = tpIdrStr;
      
      const modal = document.getElementById('tradeModal');
      modal.style.display = 'flex';
      // Slight delay to allow display:flex to apply before adding opacity class
      setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeTradeModal() {
      const modal = document.getElementById('tradeModal');
      modal.classList.remove('show');
      setTimeout(() => modal.style.display = 'none', 300);
    }

    document.getElementById('tradeConfirmBtn')?.addEventListener('click', function() {
      if (pendingTradeUrl) {
        window.open(pendingTradeUrl, '_blank');
        closeTradeModal();
      }
    });

    // === DRILL-DOWN LOGIC ===
    const PAIRS_DATA = [
      { id: 'AUDUSD', winrate: 44, fixedSL: -100000, fixedTP: 200000 },
      { id: 'EURUSD', winrate: 30, fixedSL: -100000, fixedTP: 200000 },
      { id: 'XNGUSD', winrate: 45, fixedSL: -200000, fixedTP: 400000 },
      { id: 'USOIL', winrate: 71, fixedSL: -800000, fixedTP: 1600000 },
      { id: 'XAUUSD', winrate: 43, fixedSL: -800000, fixedTP: 1600000 },
      { id: 'JP225', winrate: 41, fixedSL: -1000000, fixedTP: 2000000 }
    ];
    
    let selectedPair = null;
    let selectedYear = null;
    let selectedMonth = null;
    let selectedDate = null;
    const drillMonthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function formatRp(angka, showPlus = false) {
      if (angka === null || angka === undefined) return '';
      let format = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Math.abs(angka));
      let sign = angka > 0 ? (showPlus ? '+' : '') : (angka < 0 ? '-' : '');
      return sign + format;
    }

    let generatedData = {};

    function preGenerateData() {
      // 1. Get real data if available from API
      let pData = window.EMBEDDED_PORTFOLIO || null;

      PAIRS_DATA.forEach(pair => {
        let pairId = pair.id;
        generatedData[pairId] = {
          totalSignals: 0, wins: 0, losses: 0,
          matrixData: { 2026: Array(12).fill(null) },
          dailyData: { 2026: {} }
        };

        let pairApiData = pData ? pData[pairId] : null;
        let totalSimulatedTrades = pairApiData ? (pairApiData.totalSignals || pairApiData.totalCount || 0) : 0;
        
        // Buat pool hasil transaksi (Win/Loss) berdasarkan jumlah dari API
        let exactWins = pairApiData ? (pairApiData.wins || pairApiData.winCount || 0) : Math.round(totalSimulatedTrades * (pair.winrate / 100));
        let exactLosses = pairApiData ? (pairApiData.losses || pairApiData.lossCount || 0) : (totalSimulatedTrades - exactWins);
        if (exactLosses < 0) exactLosses = 0;
        
        let outcomes = Array(exactWins).fill(true).concat(Array(exactLosses).fill(false));
        // Acak urutan hasil
        for (let i = outcomes.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [outcomes[i], outcomes[j]] = [outcomes[j], outcomes[i]];
        }

        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();
        
        // Iterasi secara terbalik dari bulan ini mundur ke bulan Juli
        let monthsToGenerate = [];
        for (let y = currentYear; y >= 2026; y--) {
             let mStart = (y === 2026) ? 6 : 0;
             let mEnd = (y === currentYear) ? currentMonth : 11;
             for (let m = mEnd; m >= mStart; m--) {
                 monthsToGenerate.push({year: y, monthIdx: m});
             }
        }
        
        let tradesToDistribute = totalSimulatedTrades;

        monthsToGenerate.forEach(item => {
          let year = item.year;
          let monthIdx = item.monthIdx;
          
          if (!generatedData[pairId].matrixData[year]) {
              generatedData[pairId].matrixData[year] = Array(12).fill(null);
              generatedData[pairId].dailyData[year] = {};
          }
          
          const daysInMonth = new Date(year, monthIdx + 1, 0).getDate();
          const startDayOfWeek = new Date(year, monthIdx, 1).getDay();
          
          let monthProfit = 0;
          let days = [];

          // Iterasi hari dari belakang ke depan (hari ini mundur ke tanggal 1)
          let loopEnd = (year === currentYear && monthIdx === currentMonth) ? currentDate.getDate() : daysInMonth;
          
          for(let i=daysInMonth; i>=1; i--) {
            let currentDay = new Date(year, monthIdx, i).getDay();
            let isFutureDate = (year === currentYear && monthIdx === currentMonth && i > currentDate.getDate());

            if (currentDay === 0 || currentDay === 6 || tradesToDistribute <= 0 || isFutureDate) { 
              days.unshift({ date: i, pips: 0, trades: [] });
            } else {
              let dailyTradesCount = Math.min(3, tradesToDistribute);
              tradesToDistribute -= dailyTradesCount;
              
              let dailyPips = 0;
              let dayTrades = [];
              let sessions = ["08:30 (Sesi Asia)", "15:45 (Sesi Eropa)", "21:15 (Sesi Amerika)"];
              
              for(let t=0; t<dailyTradesCount; t++) {
                generatedData[pairId].totalSignals++;
                
                let isWin = outcomes.pop();
                // Cegah undefined jika tradesToDistribute meleset
                if (isWin === undefined) isWin = false; 
                let tradeProfit = isWin ? pair.fixedTP : pair.fixedSL;
                
                if(isWin) generatedData[pairId].wins++;
                else generatedData[pairId].losses++;
                
                dailyPips += tradeProfit;
                let action = Math.random() > 0.5 ? "BUY" : "SELL";
                dayTrades.push({ time: sessions[t], action: action, entry: "0.000", exit: "0.000", pips: tradeProfit });
              }
              days.unshift({ date: i, pips: dailyPips, trades: dayTrades });
              monthProfit += dailyPips;
            }
          }
          
          generatedData[pairId].dailyData[year][monthIdx] = { startDayOfWeek, days };
          generatedData[pairId].matrixData[year][monthIdx] = monthProfit;
        });
      });
    }
    
    // Jalankan sekali saat script diload
    preGenerateData();

    function generateMatrixData() {
      return generatedData[selectedPair].matrixData;
    }

    function generateDailyData(year, monthIdx) {
      return generatedData[selectedPair].dailyData[year][monthIdx];
    }

    function selectPair(pairId) {
      selectedPair = pairId;
      document.querySelectorAll('.instr-card').forEach(el => el.classList.remove('active'));
      let card = document.querySelector(`.instr-card[data-pair="${pairId}"]`);
      if(card) card.classList.add('active');
      
      document.getElementById('level-3').style.display = 'none';
      document.getElementById('level-4').style.display = 'none';
      
      const data = generateMatrixData();
      const tbody = document.getElementById('matrix-tbody');
      let html = '';
      let grandTotal = 0;

      for (const [yr, months] of Object.entries(data)) {
        let rowHtml = `<td style="text-align:left; font-weight:800; color:#fff;">${yr}</td>`;
        let yrTotal = 0;
        months.forEach((val, mIdx) => {
          if (val === null) {
            rowHtml += `<td></td>`;
          } else {
            yrTotal += val;
            let colorCls = val > 0 ? 'val-profit' : (val < 0 ? 'val-loss' : '');
            rowHtml += `<td class="clickable ${colorCls}" onclick="selectMonth(${yr}, ${mIdx}, this)">${formatRp(val, true)}</td>`;
          }
        });
        grandTotal += yrTotal;
        let yColor = yrTotal > 0 ? 'val-profit' : 'val-loss';
        rowHtml += `<td style="font-weight:800;" class="${yColor}">${formatRp(yrTotal, true)}</td>`;
        html += `<tr>${rowHtml}</tr>`;
      }
      tbody.innerHTML = html;
      
      // Override matrix grand total with actual LIVE Total Profit from database
      let liveDbData = window.EMBEDDED_PORTFOLIO ? window.EMBEDDED_PORTFOLIO[pairId] : null;
      let finalGrandTotal = (liveDbData && liveDbData.totalProfit !== undefined) ? liveDbData.totalProfit : grandTotal;
      
      document.getElementById('matrix-grand-total').innerText = `Total: ${formatRp(finalGrandTotal, true)}`;
      document.getElementById('level-2').style.display = 'block';
      setTimeout(() => { document.getElementById('level-2').scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
    }

    function selectMonth(year, monthIdx, tdEl) {
      selectedYear = year;
      selectedMonth = monthIdx;
      document.querySelectorAll('.matrix-table td').forEach(el => el.classList.remove('active'));
      tdEl.classList.add('active');
      document.getElementById('level-4').style.display = 'none';
      document.getElementById('calendar-title').innerText = `${drillMonthNames[monthIdx]} ${year} — ${selectedPair}`;
      
      const calData = generateDailyData(year, monthIdx);
      const container = document.getElementById('calendar-grid');
      let html = '';

      // Hari dari 0=Min, 1=Sen, ...
      const headers = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
      let currentDayOfWeek = calData.startDayOfWeek;

      calData.days.forEach(d => {
        let dayStr = headers[currentDayOfWeek];
        let isWeekend = (currentDayOfWeek === 0 || currentDayOfWeek === 6);
        let dayNameHtml = `<div class="day-name ${isWeekend ? 'weekend' : ''}">${dayStr}</div>`;
        
        if (d.trades.length === 0) {
          html += `<div class="day-box empty">${dayNameHtml}<div class="day-date">${d.date}</div><div class="day-profit">-</div></div>`;
        } else {
          let isProfit = d.pips >= 0;
          let pColor = isProfit ? 'c-profit' : 'c-loss';
          let tradesJson = encodeURIComponent(JSON.stringify(d.trades));
          html += `<div class="day-box clickable-day" onclick="selectDay(${d.date}, '${tradesJson}', this)">${dayNameHtml}<div class="day-date">${d.date}</div><div class="day-profit ${pColor}">${formatRp(d.pips, true)}</div></div>`;
        }
        currentDayOfWeek = (currentDayOfWeek + 1) % 7;
      });
      container.innerHTML = html;
      document.getElementById('level-3').style.display = 'block';
      setTimeout(() => { document.getElementById('level-3').scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
    }

    function selectDay(date, tradesJsonStr, boxEl) {
      selectedDate = date;
      document.querySelectorAll('.day-box').forEach(el => el.classList.remove('active'));
      boxEl.classList.add('active');
      document.getElementById('detail-title').innerText = `Rincian Transaksi: ${date} ${drillMonthNames[selectedMonth]} ${selectedYear}`;
      
      const trades = JSON.parse(decodeURIComponent(tradesJsonStr));
      const tbody = document.getElementById('detail-tbody');
      let html = '';

      trades.forEach(t => {
        let isProfit = t.pips > 0;
        let badgeCls = t.action === 'BUY' ? 'badge-buy' : 'badge-sell';
        html += `<tr><td>${t.time}</td><td><span class="${badgeCls}">${t.action}</span></td><td>${t.entry}</td><td>${t.exit}</td><td style="font-weight:700;" class="${isProfit ? 'val-profit' : 'val-loss'}">${formatRp(t.pips, true)}</td></tr>`;
      });
      tbody.innerHTML = html;
      document.getElementById('level-4').style.display = 'block';
      setTimeout(() => { document.getElementById('level-4').scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
    }

    // Attach click listeners to existing instr-card
    document.addEventListener('DOMContentLoaded', () => {
      let highestWinratePairId = null;
      let highestWinrate = -1;

      document.querySelectorAll('.instr-card').forEach(card => {
        let pairId = card.getAttribute('data-pair');
        
        if (pairId) {
          let pairData = PAIRS_DATA.find(p => p.id === pairId);
          if (pairData && generatedData[pairId]) {
            let elTotal = card.querySelector('.stat-total');
            let elSl = card.querySelector('.stat-sl');
            let elTp = card.querySelector('.stat-tp');
            let elWr = card.querySelector('.stat-wr');
            
            let stats = generatedData[pairId];
            if (elTotal) elTotal.innerText = stats.totalSignals;
            if (elSl) elSl.innerText = stats.losses;
            if (elTp) elTp.innerText = stats.wins;
            if (elWr) elWr.innerText = pairData.winrate + '%';

            // Cek winrate tertinggi
            if (pairData.winrate > highestWinrate) {
              highestWinrate = pairData.winrate;
              highestWinratePairId = pairId;
            }
          }
        }

        card.addEventListener('click', function() {
          if (pairId) selectPair(pairId);
        });
      });

      // Pilih otomatis pair dengan winrate tertinggi
      if (highestWinratePairId) {
        selectPair(highestWinratePairId);
      }
    });

  </script>
<?= $this->endSection() ?>
