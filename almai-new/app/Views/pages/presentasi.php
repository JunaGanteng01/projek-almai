<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
/* -------------------------------------------------------------
   ALMAI presentation design system & styles
   Aesthetics: Sleek Dark Mode, Neon Green (#33E818), Glassmorphism
------------------------------------------------------------- */

/* Custom resets & variables */
:root {
    --color-bg: #0A0A0A;
    --color-card: #111111;
    --color-card-glass: rgba(17, 17, 17, 0.7);
    --color-primary: #33E818;
    --color-primary-glow: rgba(51, 232, 24, 0.3);
    --color-primary-dark: #26AD12;
    --color-text-main: #FFFFFF;
    --color-text-sub: #A0A0A2;
    --color-border: #222222;
    --color-border-glow: rgba(51, 232, 24, 0.25);
    
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Public Sans', sans-serif;
    
    --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    --transition-fast: all 0.2s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    width: 100%;
    overflow: hidden;
    margin: 0;
    padding: 0;
    background-color: var(--color-bg);
    color: var(--color-text-main);
    font-family: var(--font-body);
    position: relative;
}

/* Background canvas setup */
#bg-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    opacity: 0.35;
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: var(--color-bg);
}
::-webkit-scrollbar-thumb {
    background: var(--color-border);
    border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
    background: var(--color-primary);
}

/* Text Selection */
::selection {
    background: var(--color-primary);
    color: #000000;
}

/* Typography elements */
h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-heading);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

p {
    font-weight: 300;
    line-height: 1.6;
    color: var(--color-text-sub);
}

/* Highlight / Accent typography styles */
.accent-text {
    color: var(--color-primary);
    text-shadow: 0 0 15px var(--color-primary-glow);
}

.slide-tag {
    font-family: var(--font-heading);
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--color-primary);
    letter-spacing: 0.15em;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.5rem;
}

.slide-heading {
    font-size: 2.2rem;
    font-weight: 900;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: -0.01em;
}

.slide-desc {
    font-size: 1.1rem;
    max-width: 800px;
    margin-bottom: 2rem;
    color: var(--color-text-sub);
}

/* Branding Bar Layout */
.branding-bar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 3rem;
    z-index: 10;
    border-bottom: 1px solid var(--color-border);
    background: rgba(10, 10, 10, 0.85);
    backdrop-filter: blur(10px);
}

.brand-logo-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.almai-logo {
    width: 32px;
    height: 32px;
    filter: drop-shadow(0 0 5px var(--color-primary-glow));
}

.brand-text {
    font-family: var(--font-heading);
    font-weight: 900;
    font-size: 1.4rem;
    letter-spacing: 0.05em;
    color: var(--color-text-main);
}

.brand-badge {
    background: rgba(51, 232, 24, 0.08);
    border: 1px solid var(--color-primary-glow);
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
}

.brand-badge span {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    color: var(--color-primary);
}

/* Glassmorphism Panel styles */
.glass-panel {
    background: var(--color-card-glass);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    transition: var(--transition-smooth);
}

.glass-panel:hover {
    border-color: var(--color-border-glow);
    box-shadow: 0 8px 30px rgba(51, 232, 24, 0.06);
}

.card-panel {
    background: var(--color-card);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 2rem;
    transition: var(--transition-smooth);
}

.card-panel:hover {
    border-color: var(--color-border-glow);
}

/* Slides deck / transition engine layout */
.slide-deck {
    position: relative;
    width: 100%;
    height: calc(100vh - 100px);
    margin-top: 100px;
    z-index: 5;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 3rem 6rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transform: translate3d(50px, 0, 0);
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide.active {
    opacity: 1;
    pointer-events: auto;
    transform: translate3d(0, 0, 0);
}

.slide.past {
    opacity: 0;
    pointer-events: none;
    transform: translate3d(-50px, 0, 0);
}

.slide-content {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* -------------------------------------------------------------
   SLIDE 1: Cover Page Layout
------------------------------------------------------------- */
.cover-slide {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 2rem 0;
}

.cover-hero-wrap {
    display: flex;
    align-items: center;
    gap: 4rem;
    flex-grow: 1;
}

.hero-graphics {
    position: relative;
    width: 180px;
    height: 180px;
    flex-shrink: 0;
}

.hero-logo-large {
    width: 100%;
    height: 100%;
    filter: drop-shadow(0 0 25px var(--color-primary-glow));
    animation: floatingLogo 6s ease-in-out infinite;
}

.pulsing-glow {
    position: absolute;
    top: 10%;
    left: 10%;
    width: 80%;
    height: 80%;
    background: var(--color-primary-glow);
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.5;
    animation: pulsingBackground 4s ease-in-out infinite alternate;
}

.hero-text {
    flex-grow: 1;
}

.main-title {
    font-size: 3.5rem;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    letter-spacing: -0.03em;
}

.subtitle {
    font-size: 1.4rem;
    font-weight: 300;
    margin-bottom: 2rem;
    color: var(--color-text-sub);
}

.meta-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.95rem;
    color: var(--color-text-sub);
}

.company-name {
    font-weight: 600;
    color: var(--color-text-main);
}

.license {
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.divider {
    color: var(--color-border);
}

.objectives-container {
    margin-top: 2rem;
    padding: 1.5rem 2rem;
}

.panel-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--color-text-sub);
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.5rem;
}

.objectives-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.objective-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.obj-num {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--color-primary);
    line-height: 1;
}

.objective-card p {
    font-size: 0.95rem;
    color: var(--color-text-sub);
}

@keyframes floatingLogo {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes pulsingBackground {
    0% { transform: scale(0.9); opacity: 0.3; }
    100% { transform: scale(1.1); opacity: 0.6; }
}

/* -------------------------------------------------------------
   SLIDE 2: Siapa Kami? Layout
------------------------------------------------------------- */
.flex-layout {
    display: flex;
    gap: 4rem;
    height: 100%;
    align-items: center;
}

.left-col {
    flex: 1.2;
}

.right-col {
    flex: 0.8;
}

.feature-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-top: 1.5rem;
}

.feature-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.feature-bullet {
    width: 6px;
    height: 6px;
    background-color: var(--color-primary);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--color-primary);
    margin-top: 0.6rem;
    flex-shrink: 0;
}

.feature-text h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--color-text-main);
    margin-bottom: 0.25rem;
}

.feature-text p {
    font-size: 0.9rem;
    color: var(--color-text-sub);
}

.quote-col {
    display: flex;
    justify-content: center;
    align-items: center;
}

.philosophy-card {
    padding: 3rem 2.5rem;
    position: relative;
    text-align: center;
    border-left: 3px solid var(--color-primary);
}

.quote-mark {
    position: absolute;
    top: -15px;
    left: 20px;
    font-size: 6rem;
    font-family: var(--font-heading);
    color: rgba(51, 232, 24, 0.08);
    line-height: 1;
}

.philosophy-card blockquote {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.4;
    color: var(--color-text-main);
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
}

.philosophy-card cite {
    font-size: 0.85rem;
    font-weight: 600;
    font-style: normal;
    color: var(--color-primary);
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

/* -------------------------------------------------------------
   SLIDE 3: Peran Penasihat Berjangka Layout
------------------------------------------------------------- */
.grid-layout-3 {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.full-width-header {
    margin-bottom: 1.5rem;
}

.role-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.role-card {
    padding: 1.8rem 1.25rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    cursor: pointer;
}

.role-card:hover, .role-card.active {
    transform: translateY(-5px);
    border-color: var(--color-primary);
    box-shadow: 0 8px 30px rgba(51, 232, 24, 0.15);
}

.role-card.active {
    background: rgba(51, 232, 24, 0.04);
}

.role-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.1));
}

.role-card h3 {
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    color: var(--color-text-main);
}

.role-card p {
    font-size: 0.8rem;
    color: var(--color-text-sub);
    line-height: 1.5;
}

.alert-panel {
    background: rgba(51, 232, 24, 0.05);
    border: 1px dashed rgba(51, 232, 24, 0.2);
    border-radius: 6px;
    padding: 1.2rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.alert-icon {
    font-size: 1.4rem;
}

.alert-text {
    font-size: 0.85rem;
    color: var(--color-text-main);
    line-height: 1.5;
}

.alert-text strong {
    color: var(--color-primary);
}

/* -------------------------------------------------------------
   SLIDE 4: Ekosistem Industri Keuangan Layout (Interactive Hub)
------------------------------------------------------------- */
.central-diagram-slide {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.diagram-intro {
    margin-bottom: 1rem;
}

.diagram-container {
    position: relative;
    height: 340px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.interactive-diagram {
    position: relative;
    width: 800px;
    height: 340px;
}

/* Connector SVG styling */
.connector-lines {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.connector-lines line {
    stroke: var(--color-border);
    stroke-width: 2.5;
    transition: var(--transition-smooth);
}

.connector-lines line.line-active {
    stroke: var(--color-primary-dark);
}

.connector-lines line.highlighted {
    stroke: var(--color-primary);
    stroke-width: 3.5;
    filter: drop-shadow(0 0 5px var(--color-primary-glow));
}

.pulse-dot {
    filter: drop-shadow(0 0 4px var(--color-primary));
}

/* Node styles */
.node {
    position: absolute;
    z-index: 2;
    background: var(--color-card);
    border: 2px solid var(--color-border);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: var(--transition-smooth);
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
}

.node:hover {
    border-color: var(--color-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(51, 232, 24, 0.15);
}

.node.active {
    border-color: var(--color-primary);
    box-shadow: 0 4px 25px rgba(51, 232, 24, 0.2);
}

/* Nodes coordinates mapping (Mockup Wireframe) */
.trader-node {
    top: 15px;
    left: 100px;
    width: 240px;
    height: 80px;
    border-width: 2px;
}

.advisor-node {
    top: 15px;
    left: 460px;
    width: 240px;
    height: 80px;
    border-width: 2px;
    background: rgba(17, 17, 17, 0.9);
}

.branch-node {
    top: 170px;
    width: 300px;
    height: 130px;
}

#node-derivatif-btn {
    left: 70px;
}

#node-digital-btn {
    left: 430px;
}

/* Visual Node Styling with Split Image and Text */
.visual-node {
    display: flex !important;
    flex-direction: row !important;
    padding: 0 !important;
    overflow: hidden;
}

.visual-node-img {
    width: 40%;
    height: 100%;
    background-size: cover;
    background-position: center;
    border-right: 1px solid var(--color-border);
    transition: var(--transition-smooth);
}

.visual-node:hover .visual-node-img, .visual-node.active .visual-node-img {
    filter: brightness(1.15);
    border-right-color: var(--color-primary-glow);
}

.visual-node-text-wrap {
    width: 60%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
    background: var(--color-card);
    transition: var(--transition-smooth);
}

.visual-node:hover .visual-node-text-wrap, .visual-node.active .visual-node-text-wrap {
    background: rgba(51, 232, 24, 0.02);
}

.node-title-visual {
    font-family: var(--font-heading);
    font-size: 0.8rem;
    font-weight: 900;
    letter-spacing: 0.05em;
    color: var(--color-text-main);
    text-align: center;
    text-transform: uppercase;
    transition: var(--transition-fast);
    line-height: 1.3;
}

.visual-node:hover .node-title-visual, .visual-node.active .node-title-visual {
    color: var(--color-primary);
}

/* Node detail fonts */
.node-icon {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

.node-title {
    font-family: var(--font-heading);
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: var(--color-text-main);
    text-align: center;
}

.node-subtitle {
    font-size: 0.65rem;
    color: var(--color-primary);
    font-weight: 500;
    margin-top: 0.15rem;
}

/* Interactive Info Panel */
.interactive-info-panel {
    padding: 1.25rem 2rem;
    min-height: 90px;
    margin-top: 1rem;
}

.info-content {
    display: none;
    animation: fadeIn 0.4s ease;
}

.info-content.active {
    display: block;
}

.info-content h4 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.4rem;
    color: var(--color-text-main);
}

.info-content p {
    font-size: 0.85rem;
    line-height: 1.5;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* -------------------------------------------------------------
   SLIDES 5 & 6: Ekosistem Flows layout
------------------------------------------------------------- */
.flow-slide {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.flow-interactive-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin: 1rem 0;
}

.flow-map-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    width: 100%;
}

.flow-step {
    flex: 1;
    background: var(--color-card);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 1.2rem 0.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-smooth);
    min-width: 120px;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.flow-step:hover, .flow-step.active {
    border-color: var(--color-primary);
    box-shadow: 0 4px 15px var(--color-primary-glow);
}

.flow-step.active {
    background: rgba(51, 232, 24, 0.04);
}

.flow-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--color-border);
    color: var(--color-text-sub);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-size: 0.75rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    transition: var(--transition-fast);
}

.flow-step:hover .flow-num, .flow-step.active .flow-num {
    background: var(--color-primary);
    color: #000000;
}

.flow-label {
    font-size: 0.75rem;
    font-family: var(--font-heading);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    color: var(--color-text-main);
}

.flow-arrow {
    font-size: 1.2rem;
    color: var(--color-border);
    font-weight: bold;
    padding: 0 0.5rem;
    user-select: none;
}

/* Flow Detail Description */
.flow-description-box {
    padding: 1.25rem 2rem;
    min-height: 80px;
}

.flow-description-box h4 {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    text-transform: uppercase;
}

.flow-description-box p {
    font-size: 0.85rem;
    line-height: 1.5;
}

/* Ecosystem Cards Grid (Slide 5 Concept) */
.ecosystem-cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin: 0.5rem 0;
    width: 100%;
}

.regulator-bar {
    width: 100%;
    padding: 0.85rem;
    text-align: center;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 0.95rem;
    letter-spacing: 0.05em;
    color: var(--color-text-main);
    background: var(--color-card);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition-smooth);
}

.regulator-bar:hover, .regulator-bar.active {
    border-color: var(--color-primary);
    box-shadow: 0 0 15px var(--color-primary-glow);
    background: rgba(51, 232, 24, 0.04);
}

.eco-col {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.eco-card {
    flex: 1;
    background: var(--color-card);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    cursor: pointer;
    transition: var(--transition-smooth);
    min-height: 85px;
}

.eco-card.tall {
    min-height: 186px;
    height: 100%;
}

.eco-card:hover, .eco-card.active {
    border-color: var(--color-primary);
    box-shadow: 0 0 20px var(--color-primary-glow);
    transform: translateY(-2px);
    background: rgba(51, 232, 24, 0.04);
}

.eco-card-title {
    font-family: var(--font-heading);
    font-weight: 900;
    font-size: 1rem;
    color: var(--color-text-main);
    letter-spacing: 0.02em;
}

.eco-card-subtitle {
    font-family: var(--font-body);
    font-weight: 500;
    font-size: 0.75rem;
    color: var(--color-text-sub);
    margin-top: 0.25rem;
}

/* Products and Features Split Row */
.products-features-grid {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr 1.5fr;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.products-box, .functions-box {
    padding: 1.5rem;
}

.products-box h5, .functions-box h5 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    color: var(--color-text-main);
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.5rem;
}

.tag-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.product-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--color-border);
    color: var(--color-text-main);
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    transition: var(--transition-fast);
}

.product-tag:hover {
    border-color: var(--color-primary-glow);
    background: rgba(51, 232, 24, 0.05);
    color: var(--color-primary);
}

.functions-box ul {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}



.functions-box li {
    font-size: 0.75rem;
    line-height: 1.4;
    color: var(--color-text-sub);
    position: relative;
    padding-left: 0.75rem;
}

.functions-box li::before {
    content: "▪";
    position: absolute;
    left: 0;
    color: var(--color-primary);
}

.functions-box li strong {
    color: var(--color-text-main);
    display: block;
    font-size: 0.8rem;
}

/* -------------------------------------------------------------
   SLIDE 7: Comparison Slide Layout
------------------------------------------------------------- */
.comparison-slide {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.comparison-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.25rem;
}

.comp-card {
    padding: 1.5rem 2rem;
}

.comp-card:hover {
    border-color: var(--color-primary-glow);
    box-shadow: 0 4px 20px rgba(51, 232, 24, 0.04);
}

.comp-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.75rem;
}

.comp-icon {
    font-size: 1.5rem;
}

.comp-header h3 {
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.comp-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.comp-list li {
    font-size: 0.85rem;
    color: var(--color-text-sub);
    border-bottom: 1px solid rgba(255, 255, 255, 0.02);
    padding-bottom: 0.5rem;
}

.comp-label {
    color: var(--color-text-main);
    font-weight: 600;
    margin-right: 0.5rem;
}

.similarities-panel {
    padding: 1.25rem 2rem;
}

.similarities-panel h4 {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.75rem;
    color: var(--color-primary);
}

.sim-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}

.sim-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--color-text-main);
    background: rgba(255,255,255,0.02);
    border: 1px solid var(--color-border);
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    transition: var(--transition-fast);
}

.sim-item:hover {
    border-color: var(--color-primary-glow);
    background: rgba(51,232,24,0.03);
}

.sim-check {
    color: var(--color-primary);
    font-weight: bold;
}

/* -------------------------------------------------------------
   SLIDE 8: Closing slide layout
------------------------------------------------------------- */
.penutup-slide {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.closing-quote-container {
    text-align: center;
    margin-bottom: 1.5rem;
}

.closing-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--color-primary);
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}

.closing-quote {
    font-family: var(--font-heading);
    font-size: 2.2rem;
    font-weight: 900;
    line-height: 1.2;
    color: var(--color-text-main);
    max-width: 1000px;
    margin: 0 auto;
    letter-spacing: -0.02em;
}

.closing-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
    align-items: center;
    flex-grow: 1;
    margin-bottom: 1rem;
}

.closing-intro-box {
    padding: 2rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-left: 3px solid var(--color-primary);
}

.closing-intro-box h4 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.closing-intro-box p {
    font-size: 0.95rem;
}

.closing-points-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.closing-point {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}

.cp-bullet {
    font-size: 1.5rem;
    line-height: 1;
}

.cp-text strong {
    font-size: 0.9rem;
    color: var(--color-text-main);
    display: block;
    margin-bottom: 0.2rem;
}

.cp-text p {
    font-size: 0.8rem;
    line-height: 1.4;
}

.closing-footer {
    border-top: 1px solid var(--color-border);
    padding-top: 0.75rem;
    text-align: center;
}

.closing-footer p {
    font-size: 0.65rem;
    color: rgba(255,255,255,0.3);
}

/* -------------------------------------------------------------
   Overlay Navigation Controls UI
------------------------------------------------------------- */
.nav-icon-btn {
    position: absolute;
    z-index: 15;
    background: var(--color-card);
    border: 1px solid var(--color-border);
    color: var(--color-text-sub);
    width: 42px;
    height: 42px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
}

.nav-icon-btn:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
    box-shadow: 0 0 10px var(--color-primary-glow);
}

.nav-icon-btn .icon {
    width: 18px;
    height: 18px;
}

#drawer-toggle {
    top: 80px;
    right: 25px;
}

#fullscreen-toggle {
    top: 130px;
    right: 25px;
}

/* Slide Navigation Drawer */
.slide-drawer {
    position: absolute;
    top: 70px;
    right: -320px;
    width: 300px;
    height: calc(100vh - 120px);
    z-index: 20;
    background: rgba(17, 17, 17, 0.95);
    border-left: 1px solid var(--color-border);
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(15px);
    display: flex;
    flex-direction: column;
    transition: right 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-drawer.open {
    right: 0;
}

.drawer-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.drawer-header h3 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.close-btn {
    background: none;
    border: none;
    color: var(--color-text-sub);
    font-size: 1.5rem;
    cursor: pointer;
    transition: var(--transition-fast);
}

.close-btn:hover {
    color: var(--color-primary);
}

.drawer-menu {
    list-style: none;
    overflow-y: auto;
    flex-grow: 1;
    padding: 1rem 0;
}

.drawer-menu li {
    padding: 0.9rem 1.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--color-text-sub);
    cursor: pointer;
    border-left: 3px solid transparent;
    display: flex;
    gap: 0.75rem;
    transition: var(--transition-fast);
}

.drawer-menu li span {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-border);
    transition: var(--transition-fast);
}

.drawer-menu li:hover {
    background: rgba(255,255,255,0.02);
    color: var(--color-text-main);
}

.drawer-menu li.active {
    background: rgba(51, 232, 24, 0.04);
    color: var(--color-primary);
    border-left-color: var(--color-primary);
}

.drawer-menu li.active span {
    color: var(--color-primary);
}

/* Slide Bottom Controls Bar */
.slide-controls-footer {
    position: absolute;
    bottom: 10px;
    left: 0;
    width: 100%;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 3rem;
    z-index: 10;
}

.controls-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.control-btn {
    background: var(--color-card);
    border: 1px solid var(--color-border);
    color: var(--color-text-main);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
}

.control-btn:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
    box-shadow: 0 0 10px var(--color-primary-glow);
}

.control-btn svg {
    width: 14px;
    height: 14px;
}

.slide-progress-text {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-text-sub);
    letter-spacing: 0.05em;
}

#current-slide-num {
    color: var(--color-primary);
}

.controls-center {
    display: flex;
    justify-content: center;
}

.slide-indicators {
    display: flex;
    gap: 0.5rem;
}

.indicator {
    width: 8px;
    height: 8px;
    background: var(--color-border);
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-smooth);
}

.indicator:hover {
    background: var(--color-primary-dark);
}

.indicator.active {
    width: 24px;
    border-radius: 4px;
    background: var(--color-primary);
    box-shadow: 0 0 8px var(--color-primary-glow);
}

.controls-right {
    display: flex;
    align-items: center;
}

.keyboard-tip {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.02);
    border: 1px solid var(--color-border);
    padding: 0.35rem 0.65rem;
    border-radius: 4px;
}

/* Bottom Progress Bar container */
.progress-bar-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--color-border);
    z-index: 10;
}

.progress-bar {
    height: 100%;
    width: 12.5%; /* Default slide 1 progress */
    background: var(--color-primary);
    box-shadow: 0 0 10px var(--color-primary);
    transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* -------------------------------------------------------------
   Responsive adjustments for smaller viewports
------------------------------------------------------------- */
@media (max-width: 1024px) {
    .slide {
        padding: 2rem 3rem;
    }
    .slide-heading {
        font-size: 1.8rem;
    }
    .main-title {
        font-size: 2.5rem;
    }
    .hero-graphics {
        width: 130px;
        height: 130px;
    }
    .role-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .sim-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .closing-quote {
        font-size: 1.7rem;
    }
    .closing-grid {
        grid-template-columns: 1fr;
    }
    .closing-intro-box {
        padding: 1.25rem;
    }
}

@media (max-width: 768px) {
    .branding-bar {
        padding: 0 1.5rem;
    }
    .brand-badge {
        display: none;
    }
    .slide-controls-footer {
        padding: 0 1.5rem;
    }
    .keyboard-tip {
        display: none;
    }
    .flex-layout {
        flex-direction: column;
        gap: 1.5rem;
        align-items: stretch;
    }
    .role-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .products-features-grid {
        grid-template-columns: 1fr;
    }
    .comparison-grid {
        grid-template-columns: 1fr;
    }
    .sim-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .interactive-diagram {
        transform: scale(0.7);
        transform-origin: center;
    }
    .flow-map-container {
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
    .flow-arrow {
        transform: rotate(90deg);
        padding: 0.25rem 0;
    }
}

/* Vertical Height Responsiveness (Crucial for presentation slides) */
@media (max-height: 820px) {
    .branding-bar {
        height: 55px;
    }
    .slide-deck {
        margin-top: 55px;
        height: calc(100vh - 125px);
    }
    #drawer-toggle {
        top: 65px;
    }
    #fullscreen-toggle {
        top: 115px;
    }
    .slide-drawer {
        top: 55px;
        height: calc(100vh - 105px);
    }
    .slide {
        padding: 1.5rem 4rem;
    }
    .slide-heading {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
    }
    .slide-desc {
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    .main-title {
        font-size: 2.2rem;
        margin-bottom: 0.75rem;
    }
    .subtitle {
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
    }
    .cover-hero-wrap {
        gap: 2rem;
    }
    .hero-graphics {
        width: 110px;
        height: 110px;
    }
    .objectives-container {
        margin-top: 1rem;
        padding: 1rem;
    }
    .objectives-grid {
        gap: 1rem;
    }
    .feature-list {
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .philosophy-card {
        padding: 1.5rem;
    }
    .philosophy-card blockquote {
        font-size: 1.15rem;
        margin-bottom: 0.75rem;
    }
    .role-grid {
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .role-card {
        padding: 1rem 0.5rem;
    }
    .role-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .role-card h3 {
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }
    .role-card p {
        font-size: 0.75rem;
    }
    .disclaimer-banner {
        padding: 0.75rem 1.5rem;
    }
    .diagram-container {
        height: 245px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .interactive-diagram {
        transform: scale(0.72);
        transform-origin: center top;
        height: 340px;
        margin: 0 auto;
    }
    .interactive-info-panel {
        min-height: 70px;
        padding: 0.75rem 1.5rem;
        margin-top: 0.5rem;
    }
    .flow-interactive-wrapper {
        gap: 0.75rem;
        margin: 0.5rem 0;
    }
    .flow-step {
        padding: 0.75rem 0.25rem;
    }
    .flow-description-box {
        padding: 0.75rem 1.5rem;
        min-height: 65px;
    }
    .ecosystem-cards-grid {
        gap: 0.75rem;
        margin: 0.5rem 0;
    }
    .regulator-bar {
        padding: 0.65rem;
        font-size: 0.85rem;
    }
    .eco-col {
        gap: 0.75rem;
    }
    .eco-card {
        padding: 0.75rem 0.5rem;
        min-height: 65px;
    }
    .eco-card.tall {
        min-height: 142px;
    }
    .eco-card-title {
        font-size: 0.85rem;
    }
    .eco-card-subtitle {
        font-size: 0.7rem;
    }
    .products-features-grid {
        gap: 1rem;
        margin-top: 0.25rem;
    }
    .products-box, .functions-box {
        padding: 1rem;
    }
    .product-tag {
        padding: 0.3rem 0.6rem;
        font-size: 0.7rem;
    }
    .comparison-grid {
        gap: 1rem;
        margin-bottom: 0.75rem;
    }
    .comp-card {
        padding: 1rem 1.5rem;
    }
    .comp-header {
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
    }
    .comp-header h3 {
        font-size: 0.95rem;
    }
    .comp-list {
        gap: 0.5rem;
    }
    .comp-list li {
        font-size: 0.8rem;
        padding-bottom: 0.35rem;
    }
    .similarities-panel {
        padding: 0.75rem 1.5rem;
    }
    .sim-grid {
        gap: 0.5rem;
    }
    .sim-item {
        padding: 0.4rem 0.6rem;
        font-size: 0.7rem;
    }
    .closing-title {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .closing-quote {
        font-size: 1.6rem;
    }
    .closing-grid {
        gap: 1rem;
    }
    .closing-intro-box {
        padding: 1.25rem;
    }
    .closing-intro-box h4 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .closing-intro-box p {
        font-size: 0.8rem;
    }
    .closing-points-container {
        gap: 0.75rem;
    }
    .closing-point {
        gap: 0.5rem;
    }
    .cp-bullet {
        font-size: 1.2rem;
    }
    .cp-text strong {
        font-size: 0.8rem;
    }
    .cp-text p {
        font-size: 0.75rem;
    }
}

@media (max-height: 680px) {
    .slide {
        padding: 0.4rem 3rem;
    }
    .slide-heading {
        font-size: 1.2rem;
        margin-bottom: 0.2rem;
    }
    .slide-desc {
        font-size: 0.8rem;
        margin-bottom: 0.35rem;
    }
    .hero-graphics {
        width: 80px;
        height: 80px;
    }
    .main-title {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }
    .subtitle {
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }
    .objectives-container {
        display: none; /* Hide objectives at extremely small height */
    }
    .feature-list {
        gap: 0.4rem;
    }
    .feature-text h4 {
        font-size: 0.9rem;
    }
    .feature-text p {
        font-size: 0.75rem;
    }
    .philosophy-card {
        padding: 1rem;
    }
    .philosophy-card blockquote {
        font-size: 1rem;
    }
    .role-grid {
        gap: 0.4rem;
        margin-bottom: 0.5rem;
    }
    .role-card {
        padding: 0.6rem 0.4rem;
    }
    .role-icon {
        font-size: 1.2rem;
        margin-bottom: 0.25rem;
    }
    .role-card h3 {
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }
    .role-card p {
        font-size: 0.7rem;
        line-height: 1.3;
    }
    .disclaimer-banner {
        padding: 0.5rem 1rem;
    }
    .diagram-container {
        height: 180px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .interactive-diagram {
        transform: scale(0.53);
        transform-origin: center top;
        height: 340px;
    }
    .interactive-info-panel {
        min-height: 55px;
        padding: 0.5rem 1.25rem;
        margin-top: 0.25rem;
    }
    .ecosystem-cards-grid {
        gap: 0.5rem;
        margin: 0.25rem 0;
    }
    .regulator-bar {
        padding: 0.5rem;
        font-size: 0.75rem;
    }
    .eco-col {
        gap: 0.5rem;
    }
    .eco-card {
        padding: 0.5rem 0.25rem;
        min-height: 50px;
    }
    .eco-card.tall {
        min-height: 105px;
    }
    .eco-card-title {
        font-size: 0.75rem;
    }
    .eco-card-subtitle {
        font-size: 0.65rem;
    }
    .flow-map-container {
        gap: 0.25rem;
    }
    .flow-step {
        padding: 0.5rem 0.2rem;
        min-width: 90px;
    }
    .flow-num {
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
        margin-bottom: 0.25rem;
    }
    .flow-label {
        font-size: 0.65rem;
    }
    .flow-arrow {
        font-size: 0.9rem;
        padding: 0 0.2rem;
    }
    .products-features-grid {
        display: none; /* Hide supplementary lists at very small height */
    }
    .sim-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .similarities-panel {
        display: none;
    }
    .closing-quote {
        font-size: 1.3rem;
    }
    .closing-grid {
        display: none;
    }
}

/* -------------------------------------------------------------
   MODAL STYLES
------------------------------------------------------------- */
.modal-overlay {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.modal-content {
    background: var(--color-card);
    border: 1px solid var(--color-primary);
    border-radius: 12px;
    padding: 2rem;
    width: 90%;
    max-width: 600px;
    position: relative;
    box-shadow: 0 10px 40px rgba(51, 232, 24, 0.2);
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: translateY(0);
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}

.modal-close:hover {
    color: var(--color-primary);
}

/* -------------------------------------------------------------
   WPA ROADMAP (8 STAGES)
------------------------------------------------------------- */
.roadmap-container {
    background: rgba(20, 20, 20, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2.5rem 1rem;
    position: relative;
    width: 100%;
}

.roadmap-line-wrapper {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

/* The connecting green line */
.roadmap-line-wrapper::before {
    content: '';
    position: absolute;
    top: 22px; /* Center of the 44px circle */
    left: 4%;
    right: 4%;
    height: 2px;
    background: var(--color-primary);
    z-index: 0;
}

.roadmap-step {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 12.5%; /* 100% / 8 steps */
    text-align: center;
}

.roadmap-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #0f1012;
    border: 2px solid var(--color-primary);
    box-shadow: 0 0 12px rgba(51, 232, 24, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 800;
    font-size: 1rem;
    color: var(--color-primary);
    margin-bottom: 1rem;
}

.roadmap-text {
    font-size: 0.75rem;
    line-height: 1.3;
    color: #ccc;
    padding: 0 0.2rem;
}

.roadmap-info-box {
    margin-top: 1rem;
    background: rgba(10, 25, 10, 0.6);
    border: 1px solid rgba(51, 232, 24, 0.3);
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    color: var(--color-primary);
    font-weight: 600;
    font-size: 0.95rem;
}

.roadmap-info-box svg {
    fill: currentColor;
    width: 18px;
    height: 18px;
}

/* ===== TIMELINE CONTAINER ===== */
.phase-timeline-wpa {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

/* ===== ITEM ===== */
.phase-item-wpa {
    flex: 1;
    min-width: 90px;
    text-align: center;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.phase-item-wpa:hover {
    transform: translateY(-5px);
}

.phase-item-wpa:hover .phase-circle-wpa {
    background-image: url('<?= base_url("images/lari.gif") ?>');
    background-size: 85%;
    background-position: center;
    background-repeat: no-repeat;
    color: transparent !important;
    border-color: #33e818;
    box-shadow: 0 0 20px rgba(51,232,24,0.8);
    background-color: #000;
}

/* ===== CONNECTOR ===== */
.phase-connector-wpa {
    position: absolute;
    top: 18px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #33e818;
    z-index: 0;
}

/* ===== CIRCLE ===== */
.phase-circle-wpa {
    width: 38px;
    height: 38px;
    border-radius: 999px;
    background: #000;
    border: 2px solid #33e818;
    box-shadow: 0 0 10px rgba(51,232,24,0.4);

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: bold;
    font-size: 12px;
    color: #33e818;

    margin: 0 auto;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

/* ===== TITLE ===== */
.phase-title-wpa {
    font-size: 11px;
    line-height: 1.3;
    margin-top: 8px;
    color: #ccc;
    transition: color 0.3s ease;
}

.phase-item-wpa:hover .phase-title-wpa {
    color: #fff;
}

/* ===== DESKTOP (FULL WIDTH, NO SCROLL) ===== */
@media (min-width: 1024px) {
    .phase-timeline-wpa {
        justify-content: space-between;
        overflow: visible;
    }

    .phase-item-wpa {
        min-width: auto;
    }
}

/* ===== MOBILE (RESPONSIVE SCROLLING) ===== */
@media (max-width: 768px) {
    /* Base slide layout for scrolling */
    .slide-deck { 
        height: calc(100vh - 60px) !important; 
        margin-top: 60px !important; 
        overflow-y: auto !important; 
        overflow-x: hidden !important; 
    }
    
    /* Disable 100% fixed heights and complex transforms */
    .slide { 
        padding: 1.5rem 1rem 6rem 1rem !important; 
        position: absolute !important; 
        height: auto !important; 
        min-height: 100% !important; 
        overflow-y: visible !important; 
        transform: none !important; 
        opacity: 1 !important; 
        display: none !important; 
    }
    
    .slide.active { 
        display: flex !important; 
        flex-direction: column !important; 
    }
    
    .slide.past { 
        display: none !important; 
    }
    
    .slide-content { 
        height: auto !important; 
        gap: 1.5rem !important;
    }
    
    /* Force all flex rows to column */
    .slide-content > div[style*="display: flex"],
    .slide-content > div[style*="display:flex"],
    .slide-content > div > div[style*="display: flex"],
    .cover-hero-wrap,
    .closing-grid,
    .closing-points-container { 
        flex-direction: column !important; 
        gap: 1.5rem !important; 
    }
    
    /* Typography adjustments */
    h1, .main-title { font-size: 2rem !important; }
    h2, .slide-heading { font-size: 1.6rem !important; }
    .slide-desc, p { font-size: 0.95rem !important; }
    
    /* Grids & Complex Layouts */
    .role-grid, .ecosystem-cards-grid, .products-features-grid, .sim-grid, .objectives-grid { 
        grid-template-columns: 1fr !important; 
    }
    
    .roadmap-container-wpa .phase-timeline-wpa { 
        flex-direction: column !important; 
        align-items: center !important; 
        gap: 1.5rem !important; 
    }
    
    .phase-connector-wpa { 
        width: 2px !important; 
        height: 1.5rem !important; 
        top: 100% !important; 
        left: 50% !important; 
        transform: translateX(-50%) !important; 
    }
    
    .interactive-diagram {
        transform: scale(0.65) !important;
        height: auto !important;
        margin: -20px auto !important;
    }
    
    /* Footer Controls */
    .slide-controls-footer { 
        padding: 0.5rem 1rem !important; 
        height: auto !important; 
        flex-wrap: wrap !important; 
        justify-content: space-between !important;
    }
    
    .keyboard-tip, .controls-center { 
        display: none !important; 
    }
    
    /* Modals */
    .modal-content { 
        width: 95% !important; 
        padding: 1rem !important; 
        margin: auto !important;
    }
    
    iframe#modal-iframe {
        height: 60vh !important;
    }

    .connector-arrows-desktop {
        display: none !important;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Background Canvas for Interactive Network Animation -->
    <canvas id="bg-canvas"></canvas>

    <!-- Top Branding Bar -->
    

    <!-- Navigation Drawer Toggle Button -->
    <button id="drawer-toggle" class="nav-icon-btn" aria-label="Open Slide Navigation">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Fullscreen Toggle Button -->
    <button id="fullscreen-toggle" class="nav-icon-btn" aria-label="Toggle Fullscreen">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
            </path>
        </svg>
    </button>

    <!-- Slide Navigation Drawer (Side Index) -->
    <nav id="slide-drawer" class="slide-drawer">
        <div class="drawer-header">
            <h3>Daftar Slide</h3>
            <button id="drawer-close" class="close-btn">&times;</button>
        </div>
        <ul class="drawer-menu">
            <li data-slide="0" class="active"><span>01</span> Cover</li>
            <li data-slide="1"><span>02</span> Penasihat Berjangka (TRUSTED)</li>
            <li data-slide="2"><span>03</span> WPA (SPECIALIST)</li>
            <li data-slide="3"><span>04</span> Expert Advisor (EFFICIENT)</li>
            <li data-slide="4"><span>05</span> Ekosistem Industri Keuangan</li>
            <li data-slide="5"><span>06</span> Ekosistem Derivatif Keuangan</li>
            <li data-slide="6"><span>07</span> Ekosistem Aset Keuangan Digital</li>
            <li data-slide="7"><span>08</span> Penutup & Materi Inti</li>
        </ul>
    </nav>

    <!-- Slides Wrapper -->
    <main class="slide-deck">

        <!-- Slide 1: Cover -->
        <section class="slide active" id="slide-1">
            <div class="slide-content cover-slide">
                <div class="cover-hero-wrap">
                    <div class="hero-graphics">
                        <div class="pulsing-glow"></div>
                        <img src="<?= base_url('images/alma.gif') ?>" class="hero-logo-large" alt="ALMAI Logo" style="object-fit: contain;">
                    </div>
                    <div class="hero-text">
                        <h1 class="main-title">MENGENAL PERUSAHAAN<br><span class="accent-text">PENASIHAT
                                BERJANGKA</span></h1>
                        <p class="subtitle">Gerbang Edukasi Menuju Ekosistem Derivatif & Aset Keuangan Digital</p>

                        <div class="meta-info">
                            <span class="company-name"> PT. ALMA INDONESIA RAYA</span>
                            <span class="divider">|</span>
                            <span class="license">Perusahaan Penasihat Berjangka</span>
                        </div>
                    </div>
                </div>

                <div class="objectives-container card-panel">
                    <h3 class="panel-title">Tujuan Presentasi</h3>
                    <div class="objectives-grid">
                        <div class="objective-card">
                            <div class="obj-num">1</div>
                            <p>Memahami peran strategis Penasihat Berjangka.</p>
                        </div>
                        <div class="objective-card">
                            <div class="obj-num">2</div>
                            <p>Mengenal ekosistem Derivatif & Aset Keuangan Digital.</p>
                        </div>
                        <div class="objective-card">
                            <div class="obj-num">3</div>
                            <p>Mengetahui posisi trader dalam industri finansial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Slide 2: Mengenal Perusahaan -->
        <section class="slide" id="slide-2">
            <div class="slide-content"
                style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%; padding-top: 1rem;">

                <!-- Top Section: Intro & Trusted -->
                <div style="display: flex; gap: 2rem; align-items: stretch; flex: 1;">

                    <!-- Top Left: Intro -->
                    <div style="flex: 1.5; display: flex; flex-direction: column; justify-content: center;">
                        <h2
                            style="font-size: 2.3rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.2; color: white;">
                            PENASIHAT DERIVATIF &<br>ASET KEUANGAN DIGITAL</h2>
                        <h3
                            style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--color-primary);">
                            PT. ALMA INDONESIA RAYA | ALMAI</h3>
                        <div
                            style="background: rgba(0,0,0,0.3); border-left: 4px solid var(--color-primary); padding: 1.5rem; border-radius: 4px;">
                            <p style="font-size: 1.2rem; color: #eee; line-height: 1.6; margin: 0;"><strong>Adalah
                                    :</strong> Perusahaan penasihat berjangka resmi yang berdedikasi untuk memberikan
                                edukasi, rekomendasi strategi algoritmik, dan perlindungan bagi nasabah dalam
                                bertransaksi di ekosistem derivatif maupun aset keuangan digital secara transparan dan
                                independen.</p>
                        </div>
                    </div>

                    <!-- Top Right: Trusted -->
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <h2
                            style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem; color: white; text-align: right;">
                            TRUSTED</h2>
                        <div
                            style="border: 2px solid var(--color-primary); border-radius: 8px; padding: 1.2rem; background: rgba(0,0,0,0.4); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600; color: white;">
                                Legalitas PT ALMAI</h4>
                            <ul
                                style="font-size: 0.85rem; line-height: 1.4; color: #ccc; padding-left: 1.2rem; margin-bottom: 1rem; list-style-type: disc;">
                                <li class="cert-link" data-cert="bi"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">Bank Indonesia : Persetujuan Pelaku Derivatif
                                    (PUVA)</li>
                                <li class="cert-link" data-cert="ojk"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">OJK : Prinsip Pelaku Derivatif Penasihat
                                    Investasi</li>
                                <li class="cert-link" data-cert="bappebti"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">Bappebti : Penasihat Berjangka <em>Expert
                                        Advisor</em> & Reguler</li>
                                <li class="cert-link" data-cert="komdigi"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">Komdigi : Penyelenggara Sistem Elektronik (PSE)
                                </li>
                            </ul>

                            <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600; color: white;">
                                Rekomendasi Bursa</h4>
                            <ul
                                style="font-size: 0.85rem; line-height: 1.4; color: #ccc; padding-left: 1.2rem; list-style-type: disc;">
                                <li class="cert-link" data-cert="jfx"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">JFX untuk EA Metatrader 4</li>
                                <li class="cert-link" data-cert="cfx"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">CFX untuk EA Kripto</li>
                                <li class="cert-link" data-cert="icdx"
                                    style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--color-primary)'"
                                    onmouseout="this.style.color='#ccc'">ICDX untuk EA Metatrader 5 - Segera</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Peran Strategis (Full width) -->
                <div
                    style="flex: 0 0 auto; display: flex; flex-direction: column; background: #0f1011; padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                    <h4
                        style="font-size: 1.2rem; margin-bottom: 1.5rem; text-transform: uppercase; color: white; font-weight: 700; text-align: center;">
                        PERAN STRATEGIS ALMAI</h4>

                    <div class="role-grid"
                        style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                        <!-- Cards with onclick handler -->
                        <div class="role-card glass-panel interactive-role" data-target="info-peran-1"
                            style="padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="role-icon" style="font-size: 2rem; margin-bottom: 0.8rem; color: #3b82f6;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M16.5 9.4 7.5 4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
                            </div>
                            <h3 style="font-size: 0.9rem; color: white; margin: 0; font-weight: bold;">MEMAHAMI PRODUK
                            </h3>
                        </div>
                        <div class="role-card glass-panel interactive-role" data-target="info-peran-2"
                            style="padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="role-icon" style="font-size: 2rem; margin-bottom: 0.8rem; color: #60a5fa;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            </div>
                            <h3 style="font-size: 0.9rem; color: white; margin: 0; font-weight: bold;">ANALISIS PELUANG
                            </h3>
                        </div>
                        <div class="role-card glass-panel interactive-role" data-target="info-peran-3"
                            style="padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="role-icon" style="font-size: 2rem; margin-bottom: 0.8rem; color: #eab308;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <h3 style="font-size: 0.9rem; color: white; margin: 0; font-weight: bold;">MANAJEMEN RISIKO
                            </h3>
                        </div>
                        <div class="role-card glass-panel interactive-role" data-target="info-peran-4"
                            style="padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="role-icon" style="font-size: 2rem; margin-bottom: 0.8rem; color: #ec4899;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            </div>
                            <h3 style="font-size: 0.9rem; color: white; margin: 0; font-weight: bold;">STRATEGI &
                                TEKNOLOGI</h3>
                        </div>
                        <div class="role-card glass-panel interactive-role" data-target="info-peran-5"
                            style="padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="role-icon" style="font-size: 2rem; margin-bottom: 0.8rem; color: #3b82f6;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2-1 4-3 6-3s4 2 6 3a1 1 0 0 1 1 1z"/></svg>
                            </div>
                            <h3 style="font-size: 0.9rem; color: white; margin: 0; font-weight: bold;">PERLINDUNGAN</h3>
                        </div>
                    </div>

                    <!-- Interactive Description Box -->
                    <div
                        style="background: rgba(0,0,0,0.5); border: 1px solid var(--color-primary); border-radius: 8px; padding: 1.5rem; text-align: center; min-height: 80px; display: flex; align-items: center; justify-content: center; position: relative;">

                        <div id="info-peran-default" class="peran-info-content active">
                            <p style="color: #aaa; font-size: 1.1rem; margin: 0; font-style: italic;">Klik salah satu
                                peran strategis di atas untuk melihat detail penjelasannya.</p>
                        </div>

                        <div id="info-peran-1" class="peran-info-content" style="display: none;">
                            <p style="color: white; font-size: 1.1rem; margin: 0;"><strong>MEMAHAMI PRODUK:</strong>
                                Mengupas secara mendalam instrumen derivatif maupun kripto agar trader benar-benar paham
                                struktur transaksi dan karakter dasar produk investasi.</p>
                        </div>

                        <div id="info-peran-2" class="peran-info-content" style="display: none;">
                            <p style="color: white; font-size: 1.1rem; margin: 0;"><strong>ANALISIS PELUANG:</strong>
                                Mengidentifikasi momentum pasar yang prospektif berdasar riset mendalam, perpaduan
                                analisis teknikal, fundamental, serta sentimen pasar terkini.</p>
                        </div>

                        <div id="info-peran-3" class="peran-info-content" style="display: none;">
                            <p style="color: white; font-size: 1.1rem; margin: 0;"><strong>MANAJEMEN RISIKO:</strong>
                                Memetakan rasio untung-rugi yang objektif dan menerapkan parameter pelindung untuk
                                menjaga keberlangsungan portofolio dalam jangka panjang.</p>
                        </div>

                        <div id="info-peran-4" class="peran-info-content" style="display: none;">
                            <p style="color: white; font-size: 1.1rem; margin: 0;"><strong>STRATEGI &
                                    TEKNOLOGI:</strong> Eksekusi via algoritma canggih (Expert Advisor) yang mampu
                                beradaptasi secara objektif, bebas emosi, dan merespon pasar dalam hitungan detik.</p>
                        </div>

                        <div id="info-peran-5" class="peran-info-content" style="display: none;">
                            <p style="color: white; font-size: 1.1rem; margin: 0;"><strong>PERLINDUNGAN:</strong>
                                Memastikan kepatuhan terhadap regulasi ekosistem keuangan resmi, memberikan transparansi
                                penuh tanpa janji profit fiktif (bebas <em>scam</em>).</p>
                        </div>

                    </div>
                </div>

            </div>
        </section>

        <!-- Slide 3: Wakil Penasihat Berjangka (WPA) -->
        <section class="slide" id="slide-3">
            <div class="slide-content" style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%; padding-top: 1rem;">

                <!-- Top Section -->
                <div style="display: flex; gap: 2rem; align-items: stretch; flex: 1;">
                    
                    <!-- Top Left: Title & Desc -->
                    <div style="flex: 1.5; display: flex; flex-direction: column; justify-content: center;">
                        <h2 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.2; color: white;">WAKIL PENASIHAT<br>BERJANGKA (WPA)</h2>
                        <div style="background: rgba(0,0,0,0.3); border-left: 4px solid #8B5CF6; padding: 1.5rem; border-radius: 4px; margin-top: 1rem;">
                            <p style="font-size: 1.2rem; color: #eee; line-height: 1.6; margin: 0;"><strong>Adalah :</strong> Profesional tersertifikasi yang berwenang memberikan edukasi, analisis, dan rekomendasi strategi perdagangan algoritmik di pasar derivatif maupun aset keuangan digital secara transparan.</p>
                        </div>
                    </div>

                    <!-- Top Right: Daftar WPA -->
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <h2 style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem; color: white; text-align: right;">SPECIALIST</h2>
                        <div style="border: 2px solid var(--color-primary); border-radius: 8px; padding: 1.2rem; background: rgba(0,0,0,0.4); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600; color: white;">Daftar Nama WPA :</h4>
                            <ul style="font-size: 0.85rem; line-height: 1.4; color: #ccc; padding-left: 1.2rem; margin-bottom: 0; list-style-type: decimal;">
                                <li class="wpa-link" data-wpa="Alit Widiastika, S.E.,M.H.,CFP" data-slug="alit-widiastika-semhcfp" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">Alit Widiastika, S.E.,M.H.,CFP (0005)</li>
                                <li class="wpa-link" data-wpa="Sri Ardiansyah, S.Kom." data-slug="sri-ardiansyah-skom" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">Sri Ardiansyah, S.Kom. (0004)</li>
                                <li class="wpa-link" data-wpa="I Made Dwi Wijaya, ST." data-slug="i-made-dwi-wijaya-st" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">I Made Dwi Wijaya, ST. (0006) - CUTI</li>
                                <li class="wpa-link" data-wpa="I Putu Agi Sumara Jaya, S.T" data-slug="i-putu-agi-sumara-jaya-st" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">I Putu Agi Sumara Jaya, S.T (0019)</li>
                                <li class="wpa-link" data-wpa="I Gusti Rai Bayu Pramana, S.E." data-slug="i-gusti-rai-bayu-pramana-se" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">I Gusti Rai Bayu Pramana, S.E. (0016)</li>
                                <li class="wpa-link" data-wpa="I Dewa Gede Sugiarta Putra, S.P" data-slug="i-dewa-gede-sugiarta-putra-sp" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">I Dewa Gede Sugiarta Putra, S.P (0017)</li>
                                <li class="wpa-link" data-wpa="I Gusti Bagus Aditya, S.P" data-slug="i-gusti-bagus-aditya-sp" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">I Gusti Bagus Aditya, S.P (0018)</li>
                                <li class="wpa-link" data-wpa="Aries Yuangga, S.Si" data-slug="aries-yuangga-ssi" style="margin-bottom: 0.2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='#ccc'">Aries Yuangga, S.Si (0015)</li>
                                <li onclick="window.open('/wpa', '_blank')" style="margin-top: 0.5rem; cursor: pointer; transition: color 0.2s; color: var(--color-primary); font-weight: bold; list-style: none;" onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--color-primary)'">+ 7 Spesialis Lainnya...</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Roadmap Full Wide -->
                <div style="flex: 0 0 auto; margin-top: auto;">
                    <!-- Optional inner title to match the design perfectly -->
                    <div style="text-align: center; margin-bottom: 0.5rem;">
                        <h4 style="color: white; font-size: 1rem; margin: 0; font-weight: 500;">Roadmaps</h4>
                        <h2 style="color: var(--color-primary); font-size: 1.5rem; margin: 0.2rem 0; font-weight: 800;">8 Tahap WPA</h2>
                    </div>

                    <div class="roadmap-container-wpa" style="padding: 1.5rem 0rem; width: 100%;">
                        <div class="phase-timeline-wpa">
                            <?php
                            $phases = [
                                [
                                    'number' => '01', 
                                    'title' => 'Almai | Pendampingan', 
                                    'desc' => 'Program persiapan dan pendampingan terpadu hingga kompeten sebagai WPA',
                                    'img' => 'images/1.jpg'
                                ],
                                [
                                    'number' => '02', 
                                    'title' => 'Bursa ICDX | Sertifikasi Multilateral', 
                                    'desc' => 'Bergabung WGA ICDX, Pelatihan & sertifikasi Bursa Berjangka (multilateral), Mengerjakan tugas quesioner. (Online)',
                                    'img' => 'images/2.jpg'
                                ],
                                [
                                    'number' => '03', 
                                    'title' => 'LPK | Pelatihan & Sertifikasi PBK', 
                                    'desc' => 'Bergabung WAG LPK, Pelatihan & sertifikasi Perdagangan Berjangka secara online selama 5 hari, Quesioner dan presentasi. (Online)',
                                    'img' => 'images/3.jpg'
                                ],
                                [
                                    'number' => '04', 
                                    'title' => 'BNSP | Sertifikasi Kompetensi PBK', 
                                    'desc' => 'Bergabung WAG LPK, Wawancara Sertifikasi kompetensi Nasional Perdagangan Berjangka - tatap muka sesuai jadwal',
                                    'img' => 'images/4.jpg'
                                ],
                                [
                                    'number' => '05', 
                                    'title' => 'BAPPEBTI | Sertifikasi Profesi-TLUP', 
                                    'desc' => 'Sertifikasi profesi, Tanda Lulus Uji Profesi - tatap muka sesuai jadwal.',
                                    'img' => 'images/5.jpg'
                                ],
                                [
                                    'number' => '06', 
                                    'title' => 'Almai | BAPPEBTI | Izin WPA', 
                                    'desc' => 'Pengesahan resmi sebagai WPA diajukan oleh perusahaan → Almai',
                                    'img' => 'images/6.jpg'
                                ],
                                [
                                    'number' => '07', 
                                    'title' => 'Almai | OJK | Penasihat Investasi', 
                                    'desc' => 'Pengesahan resmi sebagai Penasihat Derivatif dan Aset Keuangan DIgital diajukan oleh perusahaan → Almai',
                                    'img' => 'images/7.jpg'
                                ],
                                [
                                    'number' => '08', 
                                    'title' => 'Almai | BI | Penasihat Derivatif PUVA', 
                                    'desc' => 'Pengesahan resmi sebagai Penasihat Derivatif Pasar Uang dan Valuta Asing diajukan oleh perusahaan → Almai',
                                    'img' => 'images/8.jpg'
                                ],
                            ];

                            foreach ($phases as $index => $phase):
                            ?>
                                <div class="phase-item-wpa" onclick="openPhaseModal('<?= esc($phase['title']) ?>', '<?= esc($phase['desc']) ?>', '<?= esc($phase['number']) ?>', '<?= base_url($phase['img']) ?>')">
                                    <?php if ($index < count($phases) - 1): ?>
                                        <div class="phase-connector-wpa"></div>
                                    <?php endif; ?>
                                    <div class="phase-circle-wpa">
                                        <?= esc($phase['number']) ?>
                                    </div>
                                    <div class="phase-title-wpa">
                                        <?= esc($phase['title']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(51, 232, 24, 0.1); border: 1px solid rgba(51, 232, 24, 0.3); border-radius: 8px; text-align: center; display: flex; justify-content: center; align-items: center;">
                        <p style="color: #33e818; font-size: 0.85rem; font-weight: bold; margin: 0; display: flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; margin-right: 8px;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Alur pendaftaran hingga pengesahan resmi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Slide 4: Penasihat Expert Advisor (EFFICIENT) -->
        <section class="slide" id="slide-4">
            <div class="slide-content"
                style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; gap: 4rem; align-items: flex-start; height: 100%; padding-top: 2rem;">

                <!-- Left Column -->
                <div
                    style="flex: 1.1; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <div>
                        <div
                            style="border: 2px solid #8B5CF6; padding: 1rem 1.5rem; display: inline-block; margin-bottom: 2rem;">
                            <h2
                                style="font-size: 2.2rem; font-weight: 700; line-height: 1.3; color: white; text-transform: uppercase; margin: 0;">
                                PENASIHAT BERJANGKA<br>EXPERT ADVISOR</h2>
                        </div>
                        <p style="font-size: 1.3rem; color: white; line-height: 1.6; font-weight: 300;">Sebuah sistem
                            perangkat lunak terprogram yang memberikan nasihat dan eksekusi strategi perdagangan secara
                            otomatis di pasar derivatif maupun aset keuangan digital.</p>

                        <div style="margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                            <div
                                style="display: flex; gap: 1.2rem; align-items: flex-start; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 8px; border-left: 4px solid #8B5CF6;">
                                <div style="font-size: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg></div>
                                <div>
                                    <h4
                                        style="color: #8B5CF6; font-size: 1.2rem; margin-bottom: 0.5rem; font-weight: bold;">
                                        Eksekusi Otomatis (Algoritmik)</h4>
                                    <p style="color: #ccc; font-size: 0.95rem; line-height: 1.5;">Menjalankan instruksi
                                        transaksi secara presisi berdasarkan parameter logis dan historis yang telah
                                        diuji ekstensif (backtesting).</p>
                                </div>
                            </div>

                            <div
                                style="display: flex; gap: 1.2rem; align-items: flex-start; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 8px; border-left: 4px solid #8B5CF6;">
                                <div style="font-size: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg></div>
                                <div>
                                    <h4
                                        style="color: #8B5CF6; font-size: 1.2rem; margin-bottom: 0.5rem; font-weight: bold;">
                                        Respon Pasar Kecepatan Tinggi</h4>
                                    <p style="color: #ccc; font-size: 0.95rem; line-height: 1.5;">Menganalisis
                                        pergerakan harga seketika dan menangkap peluang profit sepersekian detik yang
                                        tak terjangkau oleh trader manual.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div style="flex: 1; display: flex; flex-direction: column; height: 100%;">
                    <h2 style="font-size: 3rem; font-weight: 800; margin-bottom: 1rem; color: white;">EFFICIENT</h2>
                    <div
                        style="border: 2px solid var(--color-primary); border-radius: 12px; padding: 2.5rem 2rem; background: rgba(0,0,0,0.4); flex-grow: 1; margin-bottom: 2rem; display: flex; flex-direction: column; justify-content: center;">
                        <ul
                            style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 2rem;">
                            <li
                                style="display: flex; align-items: flex-start; gap: 1.2rem; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 1.5rem;">
                                <span
                                    style="color: var(--color-primary); font-size: 1.8rem; font-weight: 900; line-height: 1;">01</span>
                                <div>
                                    <strong
                                        style="color: white; font-size: 1.2rem; display: block; margin-bottom: 0.4rem; text-transform: uppercase;">Pengawasan
                                        24/7</strong>
                                    <span style="color: #aaa; font-size: 0.95rem; line-height: 1.4;">Sistem bekerja
                                        tanpa lelah menjaga portofolio melewati pergantian sesi pasar dunia.</span>
                                </div>
                            </li>
                            <li
                                style="display: flex; align-items: flex-start; gap: 1.2rem; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 1.5rem;">
                                <span
                                    style="color: var(--color-primary); font-size: 1.8rem; font-weight: 900; line-height: 1;">02</span>
                                <div>
                                    <strong
                                        style="color: white; font-size: 1.2rem; display: block; margin-bottom: 0.4rem; text-transform: uppercase;">Bebas
                                        Bias Emosional</strong>
                                    <span style="color: #aaa; font-size: 0.95rem; line-height: 1.4;">Mengeliminasi
                                        keserakahan (greed) & ketakutan (fear) demi menjaga konsistensi strategi.</span>
                                </div>
                            </li>
                            <li
                                style="display: flex; align-items: flex-start; gap: 1.2rem; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 1.5rem;">
                                <span
                                    style="color: var(--color-primary); font-size: 1.8rem; font-weight: 900; line-height: 1;">03</span>
                                <div>
                                    <strong
                                        style="color: white; font-size: 1.2rem; display: block; margin-bottom: 0.4rem; text-transform: uppercase;">Manajemen
                                        Risiko Disiplin</strong>
                                    <span style="color: #aaa; font-size: 0.95rem; line-height: 1.4;">Eksekusi proteksi
                                        kerugian (Stop Loss) yang matematis dan kaku tanpa ragu-ragu.</span>
                                </div>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 1.2rem;">
                                <span
                                    style="color: var(--color-primary); font-size: 1.8rem; font-weight: 900; line-height: 1;">04</span>
                                <div>
                                    <strong
                                        style="color: white; font-size: 1.2rem; display: block; margin-bottom: 0.4rem; text-transform: uppercase;">Efisiensi
                                        Waktu Optimal</strong>
                                    <span style="color: #aaa; font-size: 0.95rem; line-height: 1.4;">Nasabah mendapatkan
                                        hasil investasi maksimal tanpa harus terpaku menatap layar grafik
                                        berjam-jam.</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Slide 5: Ekosistem Industri Keuangan -->
        <section class="slide" id="slide-5">
            <div class="slide-content"
                style="display: flex; flex-direction: column; justify-content: center; height: 100%; gap: 1rem; zoom: 0.8;">

                <div class="diagram-intro" style="margin-bottom: 0; text-align: left;">
                    <span class="slide-tag" style="margin-bottom: 0.5rem; display: inline-block;">STRUKTUR INDUSTRI</span>
                    <h2 class="slide-heading" style="text-align: left; margin-bottom: 0.5rem; font-size: 2.3rem; font-weight: 800; line-height: 1.2; color: white;">
                        EKOSISTEM INDUSTRI KEUANGAN</h2>
                    <div style="background: rgba(0,0,0,0.3); border-left: 4px solid #8B5CF6; padding: 1.5rem; border-radius: 4px; margin-top: 1rem;">
                        <p style="font-size: 1.2rem; color: #eee; line-height: 1.6; margin: 0;">Bagaimana posisi Penasihat
                            Berjangka di antara trader dan pasar? Gunakan visual hub di bawah untuk melihat bagaimana kami
                            menjembatani kedua ekosistem utama.</p>
                    </div>
                </div>

                <div class="flow-interactive-wrapper" style="display: flex; gap: 2rem; flex: 1; flex-direction: column;">
                    <div style="display: flex; gap: 2rem; flex: 1;">
                    <!-- Left: Hub Nodes with Arrows -->
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; position: relative;">
                        <div style="display: flex; gap: 0; width: 100%; max-width: 650px;">
                            <!-- Left Boxes -->
                            <div style="display: flex; flex-direction: column; justify-content: center; flex: 1;">
                                <div class="eco-card" id="slide5-card-trader" data-title="TRADER" data-desc="Trader adalah individu atau entitas yang melakukan transaksi di pasar (baik derivatif maupun kripto) dengan tujuan mendapatkan keuntungan." style="background: #222; border-radius: 12px; padding: 0; text-align: center; font-size: 1.5rem; font-weight: bold; color: white; display: flex; align-items: center; justify-content: center; height: 120px; z-index: 2; position: relative; cursor: pointer;">TRADER</div>
                                
                                <!-- Connector Gap (2rem = 32px) -->
                                <div style="height: 2rem; position: relative; z-index: 1;">
                                    <!-- Vertical line from Trader bottom to center -->
                                    <div style="position: absolute; top: 0; bottom: 50%; left: 50%; width: 4px; background: #33e818; transform: translateX(-50%);"></div>
                                    <!-- Vertical line from Penasihat top to center -->
                                    <div style="position: absolute; top: 50%; bottom: 0; left: 50%; width: 4px; background: #33e818; transform: translateX(-50%);"></div>
                                    <!-- Horizontal line from center to right edge -->
                                    <div style="position: absolute; top: 50%; left: 50%; right: 0; height: 4px; background: #33e818; transform: translateY(-50%);"></div>
                                </div>

                                <div class="eco-card" id="slide5-card-penasihat" data-title="PENASIHAT" data-desc="Wakil Penasihat Berjangka yang bersertifikat resmi, bertugas memberikan saran, analisis, dan rekomendasi transaksi yang aman dan menguntungkan." style="background: #222; border-radius: 12px; padding: 0; text-align: center; font-size: 1.5rem; font-weight: bold; color: white; display: flex; align-items: center; justify-content: center; height: 120px; z-index: 2; position: relative; cursor: pointer;">PENASIHAT</div>
                            </div>
                            
                            <!-- Connector Arrows -->
                            <div class="connector-arrows-desktop" style="width: 40px; position: relative; z-index: 1;">
                                <!-- Middle Horizontal Line -->
                                <div style="position: absolute; top: 50%; left: 0; right: 5px; height: 4px; background: #33e818; transform: translateY(-50%);"></div>

                                <!-- Right Vertical Spine -->
                                <div style="position: absolute; top: 60px; bottom: 60px; right: 5px; width: 4px; background: #33e818;"></div>

                                <!-- Top Arrow -->
                                <div style="position: absolute; top: 60px; right: -5px; height: 4px; width: 10px; background: #33e818; transform: translateY(-50%);">
                                    <div style="position: absolute; right: -8px; top: -4px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-left: 8px solid #33e818;"></div>
                                </div>

                                <!-- Bottom Arrow -->
                                <div style="position: absolute; bottom: 60px; right: -5px; height: 4px; width: 10px; background: #33e818; transform: translateY(50%);">
                                    <div style="position: absolute; right: -8px; top: -4px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-left: 8px solid #33e818;"></div>
                                </div>
                            </div>
                            
                            <!-- Right Boxes -->
                            <div style="display: flex; flex-direction: column; gap: 2rem; justify-content: center; flex: 1.2;">
                                <div class="eco-card" id="slide5-card-derivatif" data-title="EKOSISTEM DERIVATIF KEUANGAN" data-desc="Pasar berjangka dan derivatif yang diawasi penuh oleh Bappebti. Memiliki kliring penyelesaian dana dan menggunakan sistem margin (leverage)." style="background: #222; border-radius: 12px; padding: 1rem; text-align: center; font-size: 1.3rem; font-weight: bold; color: white; display: flex; align-items: center; justify-content: center; height: 120px; z-index: 2; cursor: pointer;">EKOSISTEM DERIVATIF<br>KEUANGAN</div>
                                <div class="eco-card" id="slide5-card-digital" data-title="EKOSISTEM ASET KEUANGAN DIGITAL" data-desc="Pasar aset kripto (AKD) yang terdesentralisasi, namun perdagangannya tetap diawasi. Menggunakan teknologi blockchain dan aset fisik tokenisasi." style="background: #222; border-radius: 12px; padding: 1rem; text-align: center; font-size: 1.3rem; font-weight: bold; color: white; display: flex; align-items: center; justify-content: center; height: 120px; z-index: 2; cursor: pointer;">EKOSISTEM ASET<br>KEUANGAN DIGITAL</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Comparison Grid -->
                    <div
                        style="flex: 1.2; display: flex; flex-direction: column; gap: 1rem; justify-content: space-between;">
                        <!-- Derivatif -->
                        <div class="comp-card glass-panel" style="margin:0; padding: 1.5rem;">
                            <div class="comp-header" style="margin-bottom: 0.5rem;">
                                <div class="comp-icon"
                                    style="font-size: 1.2rem; width: 30px; height: 30px; display:flex; align-items:center; justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></div>
                                <h3 style="font-size: 1.2rem;">EKOSISTEM DERIVATIF</h3>
                            </div>
                            <ul class="comp-list" style="gap: 0.5rem; font-size: 0.9rem;">
                                <li><span class="comp-label">Bentuk Aset:</span> Kontrak Berjangka / Derivatif</li>
                                <li><span class="comp-label">Sistem Margin:</span> Transaksi Leverage (hanya jaminan
                                    margin)</li>
                                <li><span class="comp-label">Fokus Utama:</span> Hedging (Lindung Nilai) & Spekulasi
                                </li>
                                <li><span class="comp-label">Mekanisme:</span> Likuiditas melalui Pialang & Bursa</li>
                                <li><span class="comp-label">Infrastruktur:</span> Bursa Berjangka & Kliring
                                    Konvensional</li>
                            </ul>
                        </div>
                        <!-- Digital -->
                        <div class="comp-card glass-panel" style="margin:0; padding: 1.5rem;">
                            <div class="comp-header" style="margin-bottom: 0.5rem;">
                                <div class="comp-icon"
                                    style="font-size: 1.2rem; width: 30px; height: 30px; background: #EAB308; color: black; display:flex; align-items:center; justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="M7 6h1v4"/><path d="m16.71 13.88.7.71-2.82 2.82"/></svg></div>
                                <h3 style="font-size: 1.2rem;">ASET KEUANGAN DIGITAL</h3>
                            </div>
                            <ul class="comp-list" style="gap: 0.5rem; font-size: 0.9rem;">
                                <li><span class="comp-label">Bentuk Aset:</span> Aset Digital Fisik / Kepemilikan Token
                                </li>
                                <li><span class="comp-label">Sistem Margin:</span> Kepemilikan Penuh (Spot) & Tokenisasi
                                </li>
                                <li><span class="comp-label">Fokus Utama:</span> Investasi Jangka Panjang & Utilitas
                                </li>
                                <li><span class="comp-label">Mekanisme:</span> Ledger Terdistribusi (Blockchain)</li>
                                <li><span class="comp-label">Infrastruktur:</span> Bursa AKD & Kustodian Digital</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom: Explanation & Similarities -->
                <div>
                    <div class="flow-description-box glass-panel" style="background: #222; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.1);">
                        <h4 id="slide5-desc-title" style="font-size: 1.2rem; font-weight: bold; color: var(--color-primary); margin-bottom: 0.5rem; text-transform: uppercase;">Penjelasan</h4>
                        <p id="slide5-desc-text" style="font-size: 1rem; color: #ccc; line-height: 1.5; margin: 0;">Arahkan kursor atau klik pada salah satu komponen kartu di atas untuk melihat detail perannya.</p>
                    </div>

                    <div class="similarities-panel glass-panel" style="margin:0; padding: 1.5rem;">
                        <h4
                            style="font-size: 1rem; color: var(--color-primary); margin-bottom: 1rem; text-transform: uppercase;">
                            PERSAMAAN ESENSIAL KEDUA EKOSISTEM</h4>
                        <div class="sim-grid" style="grid-template-columns: repeat(5, 1fr); gap: 0.5rem;">
                            <div class="sim-item"
                                style="padding: 0.5rem; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); border-radius: 4px; justify-content: flex-start; gap: 0.5rem; line-height:1.2;">
                                <span class="sim-check" style="color: var(--color-primary);">✓</span>
                                <span>Diawasi penuh oleh regulator</span>
                            </div>
                            <div class="sim-item"
                                style="padding: 0.5rem; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); border-radius: 4px; justify-content: flex-start; gap: 0.5rem; line-height:1.2;">
                                <span class="sim-check" style="color: var(--color-primary);">✓</span>
                                <span>Menyediakan bursa transaksi teratur</span>
                            </div>
                            <div class="sim-item"
                                style="padding: 0.5rem; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); border-radius: 4px; justify-content: flex-start; gap: 0.5rem; line-height:1.2;">
                                <span class="sim-check" style="color: var(--color-primary);">✓</span>
                                <span>Menjamin kliring penyelesaian dana</span>
                            </div>
                            <div class="sim-item"
                                style="padding: 0.5rem; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); border-radius: 4px; justify-content: flex-start; gap: 0.5rem; line-height:1.2;">
                                <span class="sim-check" style="color: var(--color-primary);">✓</span>
                                <span>Sangat memerlukan edukasi yang benar</span>
                            </div>
                            <div class="sim-item"
                                style="padding: 0.5rem; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); border-radius: 4px; justify-content: flex-start; gap: 0.5rem; line-height:1.2;">
                                <span class="sim-check" style="color: var(--color-primary);">✓</span>
                                <span>Membutuhkan manajemen risiko ketat</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Slide 6: Ekosistem Derivatif -->
        <section class="slide" id="slide-6">
            <div class="slide-content flow-slide" style="zoom: 0.8;">
                <div class="slide-header">
                    <span class="slide-tag">Ekosistem 1</span>
                    <h2 class="slide-heading">EKOSISTEM DERIVATIF</h2>
                    <p class="slide-desc">Struktur regulasi dan alur transaksi perdagangan berjangka komoditi.</p>
                </div>

                <div class="flow-interactive-wrapper">
                    <!-- Regulator Full Width Card -->
                    <div class="regulator-bar glass-panel" id="card-regulator" data-title="Regulator PBK & Keuangan"
                        data-desc="Otoritas pengawas perdagangan berjangka komoditi dan sektor keuangan di Indonesia. Terdiri dari Bappebti (Kementerian Perdagangan) sebagai regulator utama PBK, OJK untuk perlindungan konsumen jasa keuangan, dan Bank Indonesia untuk sistem pembayaran.">
                        <span>Regulator : BAPPEBTI, OJK, BANK INDONESIA</span>
                    </div>

                    <!-- Ecosystem Grid Columns -->
                    <div class="ecosystem-cards-grid">
                        <!-- Col 1 -->
                        <div class="eco-col">
                            <div class="eco-card glass-panel" id="card-trader" data-title="Trader (Trader)"
                                data-desc="Trader (Nasabah) adalah individu atau korporasi yang menyetor dana margin dan melakukan transaksi jual/beli kontrak derivatif, baik untuk spekulasi profit maupun lindung nilai (hedging).">
                                <div class="eco-card-title">TRADER</div>
                            </div>
                            <div class="eco-card glass-panel" id="card-penasihat" data-title="Penasihat Berjangka"
                                data-desc="Penasihat Berjangka (seperti PT ALMA Indonesia Raya) bertindak sebagai konsultan profesional yang membimbing nasabah melalui edukasi, riset pasar, dan mitigasi risiko sebelum transaksi dilakukan.">
                                <div class="eco-card-title">PENASIHAT</div>
                            </div>
                        </div>

                        <!-- Col 2 -->
                        <div class="eco-col">
                            <div class="eco-card tall glass-panel" id="card-pialang"
                                data-title="Pialang & Margin Trading"
                                data-desc="Pialang Berjangka (Broker) adalah perantara transaksi yang menyelenggarakan sistem margin trading nasabah, mengelola rekening terpisah (segregated account), dan mengeksekusi order nasabah ke bursa.">
                                <div class="eco-card-title">PIALANG</div>
                                <div class="eco-card-subtitle">MARGIN TRADING</div>
                            </div>
                        </div>

                        <!-- Col 3 -->
                        <div class="eco-col">
                            <div class="eco-card glass-panel" id="card-bursa" data-title="Bursa Berjangka"
                                data-desc="Bursa Berjangka adalah lembaga resmi penyelenggara perdagangan kontrak berjangka secara teratur, wajar, efisien, dan transparan (misalnya Jakarta Futures Exchange / JFX dan ICDX).">
                                <div class="eco-card-title">BURSA</div>
                            </div>
                            <div class="eco-card glass-panel" id="card-spa"
                                data-title="SPA (Sistem Perdagangan Alternatif)"
                                data-desc="Sistem Perdagangan Alternatif (SPA) adalah sarana transaksi derivatif bilateral di luar bursa (OTC) yang teratur dan diawasi ketat, di mana transaksi diselesaikan dengan Pedagang Berjangka.">
                                <div class="eco-card-title">SPA</div>
                            </div>
                        </div>

                        <!-- Col 4 -->
                        <div class="eco-col">
                            <div class="eco-card tall glass-panel" id="card-kliring" data-title="Lembaga Kliring"
                                data-desc="Lembaga Kliring Berjangka melakukan pendaftaran, penjaminan, kliring penyelesaian dana transaksi berjangka, serta memegang kendali penarikan dana margin jaminan dari pialang.">
                                <div class="eco-card-title">KLIRING</div>
                            </div>
                        </div>
                    </div>

                    <!-- Explanation Panel -->
                    <div class="flow-description-box glass-panel" id="slide6-desc-box">
                        <h4 id="slide6-desc-title" class="accent-text">Penjelasan</h4>
                        <p id="slide6-desc-text">Arahkan kursor atau klik pada salah satu komponen kartu di atas
                            untuk melihat detail perannya dalam struktur ekosistem derivatif Indonesia.</p>
                    </div>
                </div>

                <div class="products-features-grid">
                    <!-- Perlindungan Trader Box (Slide 5) -->
                    <div class="glass-panel"
                        style="border-color: var(--color-primary); box-shadow: 0 0 15px rgba(51,232,24,0.1); padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem; text-transform: uppercase;">PERLINDUNGAN
                            TRADER</h4>
                        <p style="font-size: 0.85rem; color: var(--color-text-sub); line-height: 1.5;">Di pasar
                            derivatif yang menggunakan sistem margin, perlindungan berfokus pada transparansi harga di
                            Bursa, penjaminan transaksi oleh Kliring, serta pemisahan dana nasabah (segregated account).
                        </p>
                    </div>

                    <div class="products-box glass-panel">
                        <h5>Produk Derivatif Utama</h5>
                        <div class="tag-cloud">
                            <span class="product-tag">Forex (Mata Uang)</span>
                            <span class="product-tag">Emas (Gold)</span>
                            <span class="product-tag">Komoditas Utama</span>
                            <span class="product-tag">Indeks Saham</span>
                            <span class="product-tag">Kontrak Futures</span>
                            <span class="product-tag">Opsi (Options)</span>
                        </div>
                    </div>
                    <div class="functions-box glass-panel">
                        <h5>Fungsi Utama Industri</h5>
                        <ul>
                            <li><strong>Hedging (Lindung Nilai):</strong> Melindungi risiko perubahan harga fisik.</li>
                            <li><strong>Price Discovery:</strong> Pembentukan harga pasar yang transparan di bursa.</li>
                            <li><strong>Trading & Spekulasi:</strong> Memanfaatkan volatilitas harga global.</li>
                            <li><strong>Manajemen Risiko:</strong> Diversifikasi alokasi aset.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Slide 7: Ekosistem Aset Keuangan Digital -->
        <section class="slide" id="slide-7">
            <div class="slide-content flow-slide">
                <div class="slide-header">
                    <span class="slide-tag">Ekosistem 2</span>
                    <h2 class="slide-heading">EKOSISTEM ASET KEUANGAN DIGITAL (AKD)</h2>
                    <p class="slide-desc">Infrastruktur baru berbasis blockchain untuk transaksi instrumen digital.</p>
                </div>

                <div class="flow-interactive-wrapper">
                    <!-- Regulator Bar (Full-Width Card) -->
                    <div class="regulator-bar glass-panel" id="digital-regulator"
                        data-title="Regulator : OJK, BANK INDONESIA"
                        data-desc="OJK & Bank Indonesia selaku otoritas pengawas tertinggi sektor keuangan dan sistem pembayaran digital untuk melindungi nasabah dan memitigasi risiko sistemik.">
                        <span>Regulator : OJK, BANK INDONESIA</span>
                    </div>

                    <!-- Ecosystem Cards Grid -->
                    <div class="ecosystem-cards-grid">
                        <!-- Col 1 -->
                        <div class="eco-col">
                            <div class="eco-card glass-panel" id="digital-card-trader" data-title="Trader"
                                data-desc="Trader melakukan transaksi jual-beli aset keuangan digital di pasar teratur demi meraih potensi keuntungan.">
                                <div class="eco-card-title">TRADER</div>
                            </div>
                            <div class="eco-card glass-panel" id="digital-card-penasihat" data-title="Penasihat"
                                data-desc="Penasihat memberikan jasa edukasi, nasihat investasi, dan analisis manajemen risiko transaksi aset digital bagi trader.">
                                <div class="eco-card-title">PENASIHAT</div>
                            </div>
                        </div>

                        <!-- Col 2 -->
                        <div class="eco-col">
                            <div class="eco-card tall glass-panel" id="digital-card-pedagang"
                                data-title="Pedagang AKD & Deposit Modal"
                                data-desc="Pedagang Aset Keuangan Digital memfasilitasi transaksi perdagangan dan mewajibkan penyetoran deposit modal awal demi keamanan finansial dan likuiditas pasar.">
                                <div class="eco-card-title" style="margin-bottom: 0.5rem;">PEDAGANG AKD</div>
                                <div class="eco-card-subtitle"
                                    style="font-size: 0.75rem; opacity: 0.8; font-weight: 500;">DEPOSIT MODAL</div>
                            </div>
                        </div>

                        <!-- Col 3 -->
                        <div class="eco-col">
                            <div class="eco-card glass-panel" id="digital-card-bursa" data-title="Bursa"
                                data-desc="Bursa Aset Keuangan Digital menyelenggarakan platform transaksi terpusat yang aman, tertib, dan transparan bagi para pelaku pasar.">
                                <div class="eco-card-title">BURSA</div>
                            </div>
                            <div class="eco-card glass-panel" id="digital-card-kliring" data-title="Kliring"
                                data-desc="Lembaga Kliring menjamin penyelesaian hak dan kewajiban atas seluruh transaksi aset keuangan digital secara real-time dan terverifikasi.">
                                <div class="eco-card-title">KLIRING</div>
                            </div>
                        </div>

                        <!-- Col 4 -->
                        <div class="eco-col">
                            <div class="eco-card tall glass-panel" id="digital-card-kustodian" data-title="Kustodian"
                                data-desc="Lembaga Kustodian menyediakan sarana penyimpanan cold/hot storage aset digital secara aman, terproteksi, serta mengelola private key milik pengguna.">
                                <div class="eco-card-title">KUSTODIAN</div>
                            </div>
                        </div>
                    </div>

                    <!-- Explanation Panel -->
                    <div class="flow-description-box glass-panel" id="slide7-desc-box">
                        <h4 id="slide7-desc-title" class="accent-text">Penjelasan</h4>
                        <p id="slide7-desc-text">Arahkan kursor atau klik pada salah satu komponen kartu di atas untuk
                            melihat detail perannya dalam ekosistem aset keuangan digital.</p>
                    </div>
                </div>

                <div class="products-features-grid">
                    <!-- Perlindungan Trader Box (Slide 6) -->
                    <div class="glass-panel"
                        style="border-color: var(--color-primary); box-shadow: 0 0 15px rgba(51,232,24,0.1); padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem; text-transform: uppercase;">PERLINDUNGAN
                            TRADER</h4>
                        <p style="font-size: 0.85rem; color: var(--color-text-sub); line-height: 1.5;">Di pasar kripto
                            dengan volatilitas tinggi, perlindungan berfokus pada keamanan dompet digital (wallet) oleh
                            Kustodian dan kepastian transaksi hanya pada instrumen digital yang terdaftar resmi.</p>
                    </div>

                    <div class="products-box glass-panel">
                        <h5>Produk AKD</h5>
                        <div class="tag-cloud">
                            <span class="product-tag">Crypto Asset (BTC, ETH, dll)</span>
                            <span class="product-tag">Stablecoin (USDT, USDC)</span>
                            <span class="product-tag">Tokenisasi Aset RWA</span>
                            <span class="product-tag">Instrumen Digital Regulatif</span>
                        </div>
                    </div>
                    <div class="functions-box glass-panel">
                        <h5>Karakteristik</h5>
                        <ul>
                            <li><strong>Teknologi Blockchain:</strong> Catatan transaksi immutable (tidak dapat diubah).
                            </li>
                            <li><strong>Transparansi Mutlak:</strong> Audit ledger terdistribusi yang terbuka secara
                                publik.</li>
                            <li><strong>Digital Settlement:</strong> Penyelesaian transaksi instan, efisien tanpa
                                birokrasi.</li>
                            <li><strong>Akses & Likuiditas Global:</strong> Berjalan 24/7 menjangkau pasar tanpa batas
                                negara.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>



        <!-- Slide 8: Penutup -->
        <section class="slide" id="slide-8">
            <div class="slide-content penutup-slide">
                <div class="closing-quote-container">
                    <h2 class="closing-title">INVESTASI CERDAS MULAI DARI PEMAHAMAN</h2>
                    <blockquote class="closing-quote">
                        "Edukasi yang tepat membangun trader yang cerdas, mandiri, dan berkelanjutan."
                    </blockquote>
                </div>

                <div class="closing-grid">
                    <div class="closing-intro-box card-panel">
                        <h4>Mengapa ALMAI Hadir untuk Anda?</h4>
                        <p>Kami percaya perlindungan dan keuntungan trader berjangka panjang hanya bisa dicapai bila
                            dilandasi oleh kecakapan pemahaman yang matang.</p>
                    </div>

                    <div class="closing-points-container">
                        <div class="closing-point">
                            <div class="cp-bullet"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                            <div class="cp-text">
                                <strong>Meningkatkan Literasi Keuangan</strong>
                                <p>Mengubah ketidaktahuan menjadi pemahaman investasi komprehensif.</p>
                            </div>
                        </div>
                        <div class="closing-point">
                            <div class="cp-bullet"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                            <div class="cp-text">
                                <strong>Menganalisis Peluang Pasar</strong>
                                <p>Menyaring kebisingan pasar menjadi wawasan data objektif terpercaya.</p>
                            </div>
                        </div>
                        <div class="closing-point">
                            <div class="cp-bullet"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></svg></div>
                            <div class="cp-text">
                                <strong>Menyajikan Risiko Objektif</strong>
                                <p>Menghadirkan pemahaman resiko tanpa menyembunyikan sisi kerugian.</p>
                            </div>
                        </div>
                        <div class="closing-point">
                            <div class="cp-bullet"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                            <div class="cp-text">
                                <strong>Mendorong Keputusan Bertanggung Jawab</strong>
                                <p>Melatih kemandirian keputusan transaksi trader jangka panjang.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembuka Materi Inti -->
                <div
                    style="text-align: center; margin-top: 3rem; background: rgba(51, 232, 24, 0.1); border: 1px solid var(--color-primary); padding: 1.5rem; border-radius: 12px;">
                    <h3
                        style="color: var(--color-primary); font-size: 1.8rem; font-weight: bold; text-transform: uppercase;">
                        MARI MASUK KE MATERI INTI PRESENTASI</h3>
                    <p style="color: white; margin-top: 0.5rem; font-size: 1.1rem;">Memahami pasar untuk meraih peluang
                        secara bijak dan komprehensif.</p>
                </div>

 
            </div>
        </section>

    </main>

    <!-- Bottom Navigation Bar controls -->
    <footer class="slide-controls-footer">
        <div class="controls-left">
            <span class="keyboard-tip">Gunakan Tombol Arah / Spacebar</span>
        </div>

        <!-- Interactive Slide Indicator dots -->
        <div class="controls-center">
            <div class="slide-indicators">
                <span class="indicator active" data-slide="0"></span>
                <span class="indicator" data-slide="1"></span>
                <span class="indicator" data-slide="2"></span>
                <span class="indicator" data-slide="3"></span>
                <span class="indicator" data-slide="4"></span>
                <span class="indicator" data-slide="5"></span>
                <span class="indicator" data-slide="6"></span>
                <span class="indicator" data-slide="7"></span>
            </div>
        </div>

        <div class="controls-right" style="display: flex; align-items: center; gap: 1rem;">
            <button id="prev-btn" class="control-btn" aria-label="Previous Slide">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <span class="slide-progress-text"><span id="current-slide-num">1</span> / 8</span>
            <button id="next-btn" class="control-btn" aria-label="Next Slide">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </footer>

    <!-- Progress Bar at the very bottom -->
    <div class="progress-bar-container">
        <div id="progress-bar" class="progress-bar"></div>
    </div>

    <!-- Global Modal Pop Up for Certifications -->
    <div id="cert-modal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px; padding: 2rem; text-align: center;">
            <button class="modal-close" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: white; font-size: 2rem; cursor: pointer;">&times;</button>
            <h3 id="modal-title" style="color: white; margin-bottom: 1.5rem; text-align: center; font-size: 1.5rem; font-weight: bold;">Sertifikasi</h3>
            <div id="modal-body" style="text-align: center; width: 100%;">
                <img id="modal-cert-img" src="" style="max-width: 100%; max-height: 70vh; border-radius: 8px; display: none; margin: 0 auto; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                
                <iframe id="modal-iframe" src="" style="width: 100%; height: 75vh; border: none; border-radius: 8px; display: none; background: white;"></iframe>

                <div id="modal-cert-placeholder" style="border: 1px dashed rgba(255,255,255,0.2); padding: 3rem; background: rgba(0,0,0,0.3); border-radius: 8px; display: none;">
                    <span style="font-size: 3rem; display: block; margin-bottom: 1rem;">📄</span>
                    <p style="color: #999; margin: 0; font-size: 1rem;">[Tempat Menampilkan Dokumen/Logo <span id="modal-cert-name" style="color: var(--color-primary); font-weight: bold;"></span>]</p>
                    <p id="modal-cert-desc" style="color: #666; font-size: 0.8rem; margin-top: 0.5rem;">Sertifikat / Dokumen belum tersedia.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include AI Chatbot/WhatsApp Floating Button but adjusted for presentation footer -->
    <style>
        #aiChatbot {
            bottom: 90px !important;
            right: 48px !important; /* Align with the 3rem padding of the footer */
            z-index: 99999 !important;
            display: block !important;
        }
        @media (min-width: 768px) {
            #aiChatbot {
                bottom: 100px !important; 
                right: 48px !important;
            }
        }
    </style>
    <?= $this->include('partials/whatsapp_button') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------
    // SLIDE DECK NAV LOGIC
    // -------------------------------------------------------------
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    const drawerItems = document.querySelectorAll('.drawer-menu li');
    const currentSlideNum = document.getElementById('current-slide-num');
    const progressBar = document.getElementById('progress-bar');
    
    let currentIdx = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
        // Bounds checking
        if (index < 0) index = 0;
        if (index >= totalSlides) index = totalSlides - 1;
        
        currentIdx = index;

        // Update slides classes for transition animations
        slides.forEach((slide, idx) => {
            slide.classList.remove('active', 'past');
            if (idx === currentIdx) {
                slide.classList.add('active');
            } else if (idx < currentIdx) {
                slide.classList.add('past');
            }
        });

        // Update indicators
        indicators.forEach((indicator, idx) => {
            indicator.classList.toggle('active', idx === currentIdx);
        });

        // Update drawer menu selection
        drawerItems.forEach((item, idx) => {
            item.classList.toggle('active', idx === currentIdx);
        });

        // Update numbers and progress bar
        currentSlideNum.textContent = currentIdx + 1;
        const progressPercent = ((currentIdx + 1) / totalSlides) * 100;
        progressBar.style.width = `${progressPercent}%`;

        // Sync local storage or memory state if needed
        closeDrawer();
    }

    // Previous slide function
    function prevSlide() {
        if (currentIdx > 0) {
            showSlide(currentIdx - 1);
        }
    }

    // Next slide function
    function nextSlide() {
        if (currentIdx < totalSlides - 1) {
            showSlide(currentIdx + 1);
        }
    }

    // Control buttons event listeners
    document.getElementById('prev-btn').addEventListener('click', prevSlide);
    document.getElementById('next-btn').addEventListener('click', nextSlide);

    // Indicator dots event listeners
    indicators.forEach(indicator => {
        indicator.addEventListener('click', (e) => {
            const targetSlide = parseInt(e.target.getAttribute('data-slide'));
            showSlide(targetSlide);
        });
    });

    // Drawer menu list items event listeners
    drawerItems.forEach(item => {
        item.addEventListener('click', (e) => {
            const targetItem = e.currentTarget;
            const targetSlide = parseInt(targetItem.getAttribute('data-slide'));
            showSlide(targetSlide);
        });
    });

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
            e.preventDefault();
            nextSlide();
        } else if (e.key === 'ArrowLeft' || e.key === 'Backspace' || e.key === 'PageUp') {
            e.preventDefault();
            prevSlide();
        } else if (e.key === 'Escape') {
            closeDrawer();
        }
    });

    // Mobile touch swipe gestures
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    document.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const threshold = 50; // swipe delta threshold
        if (touchStartX - touchEndX > threshold) {
            // Swipe Left -> Next Slide
            nextSlide();
        } else if (touchEndX - touchStartX > threshold) {
            // Swipe Right -> Prev Slide
            prevSlide();
        }
    }

    // -------------------------------------------------------------
    // SLIDE NAVIGATION DRAWER (SIDE LIST)
    // -------------------------------------------------------------
    const slideDrawer = document.getElementById('slide-drawer');
    const drawerToggle = document.getElementById('drawer-toggle');
    const drawerClose = document.getElementById('drawer-close');

    function openDrawer() {
        slideDrawer.classList.add('open');
    }

    function closeDrawer() {
        slideDrawer.classList.remove('open');
    }

    drawerToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        if (slideDrawer.classList.contains('open')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    drawerClose.addEventListener('click', closeDrawer);

    // Close drawer when clicking outside
    document.addEventListener('click', (e) => {
        if (slideDrawer.classList.contains('open') && !slideDrawer.contains(e.target) && e.target !== drawerToggle) {
            closeDrawer();
        }
    });

    // -------------------------------------------------------------
    // FULLSCREEN TOGGLE
    // -------------------------------------------------------------
    const fullscreenToggleBtn = document.getElementById('fullscreen-toggle');
    
    fullscreenToggleBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error(`Gagal masuk mode layar penuh: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });

    // Sync fullscreen button state/icon if wanted
    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            fullscreenToggleBtn.querySelector('svg').innerHTML = `
                <path d="M4 14h6v6m10-6h-6v6M4 10h6V4m10 6h-6V4" stroke="currentColor" stroke-width="2" fill="none"></path>
            `;
        } else {
            fullscreenToggleBtn.querySelector('svg').innerHTML = `
                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="2" fill="none"></path>
            `;
        }
    });

    // -------------------------------------------------------------
    // SLIDE 2: INTERACTIVE ROLE CARDS
    // -------------------------------------------------------------
    const roleCards = document.querySelectorAll('.interactive-role');
    const allRoleInfos = document.querySelectorAll('.peran-info-content');

    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            const targetId = card.getAttribute('data-target');
            const isActive = card.classList.contains('active');
            
            // Clear active state on all cards and hide all info panels
            roleCards.forEach(c => {
                c.classList.remove('active');
                c.style.borderColor = 'rgba(255,255,255,0.1)';
                c.style.background = 'rgba(255,255,255,0.02)';
            });
            allRoleInfos.forEach(info => {
                info.style.display = 'none';
                info.classList.remove('active');
            });
            
            if (isActive) {
                // If clicked card was already active, reset to default
                document.getElementById('info-peran-default').style.display = 'block';
            } else {
                // Set this card to active
                card.classList.add('active');
                card.style.borderColor = 'var(--color-primary)';
                card.style.background = 'rgba(51, 232, 24, 0.1)';
                
                // Show target info
                const targetInfo = document.getElementById(targetId);
                if (targetInfo) {
                    targetInfo.style.display = 'block';
                }
            }
        });
    });

    // -------------------------------------------------------------
    // SLIDE 4: INTERACTIVE HUB DIAGRAM (Removed due to redesign)
    // -------------------------------------------------------------



    // -------------------------------------------------------------
    // SLIDE 5 & 6: INTERACTIVE FLOW SYSTEMS
    // -------------------------------------------------------------
    function setupFlowController(containerId, titleId, descId) {
        const container = document.getElementById(containerId);
        const titleEl = document.getElementById(titleId);
        const descEl = document.getElementById(descId);
        
        if (!container) return;
        
        const steps = container.querySelectorAll('.flow-step');
        
        steps.forEach(step => {
            const handleInteraction = () => {
                // Clear active
                steps.forEach(s => s.classList.remove('active'));
                
                // Set current active
                step.classList.add('active');
                
                // Update text
                const labelText = step.querySelector('.flow-label').textContent;
                const descText = step.getAttribute('data-desc');
                
                titleEl.textContent = labelText;
                descEl.textContent = descText;
            };

            step.addEventListener('mouseenter', handleInteraction);
            step.addEventListener('click', handleInteraction);
        });
    }

    // Slide 5 Ecosystem Grid Event Listeners
    const ecoCards = document.querySelectorAll('#slide-5 .regulator-bar, #slide-5 .eco-card');
    const slide5DescTitle = document.getElementById('slide5-desc-title');
    const slide5DescText = document.getElementById('slide5-desc-text');
    const slide5Content = document.querySelector('#slide-5 .flow-interactive-wrapper');

    if (ecoCards.length > 0 && slide5DescTitle && slide5DescText) {
        ecoCards.forEach(card => {
            const handleInteraction = () => {
                ecoCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                slide5DescTitle.textContent = card.getAttribute('data-title');
                slide5DescText.textContent = card.getAttribute('data-desc');
            };

            card.addEventListener('mouseenter', handleInteraction);
            card.addEventListener('click', handleInteraction);
        });
    }

    // Slide 6 Ecosystem Grid Event Listeners
    const derivatifCards = document.querySelectorAll('#slide-6 .regulator-bar, #slide-6 .eco-card');
    const slide6DescTitle = document.getElementById('slide6-desc-title');
    const slide6DescText = document.getElementById('slide6-desc-text');

    if (derivatifCards.length > 0 && slide6DescTitle && slide6DescText) {
        derivatifCards.forEach(card => {
            const handleInteraction = () => {
                derivatifCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                slide6DescTitle.textContent = card.getAttribute('data-title');
                slide6DescText.textContent = card.getAttribute('data-desc');
            };

            card.addEventListener('mouseenter', handleInteraction);
            card.addEventListener('click', handleInteraction);
        });
    }

    // Slide 7 Ecosystem Grid Event Listeners
    const digitalCards = document.querySelectorAll('#slide-7 .regulator-bar, #slide-7 .eco-card');
    const slide7DescTitle = document.getElementById('slide7-desc-title');
    const slide7DescText = document.getElementById('slide7-desc-text');

    if (digitalCards.length > 0 && slide7DescTitle && slide7DescText) {
        digitalCards.forEach(card => {
            const handleInteraction = () => {
                digitalCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                slide7DescTitle.textContent = card.getAttribute('data-title');
                slide7DescText.textContent = card.getAttribute('data-desc');
            };

            card.addEventListener('mouseenter', handleInteraction);
            card.addEventListener('click', handleInteraction);
        });
    }


    // -------------------------------------------------------------
    // BACKGROUND CANVAS ANIMATION
    // -------------------------------------------------------------
    const canvas = document.getElementById('bg-canvas');
    const ctx = canvas.getContext('2d');
    
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    // Particle pool definition
    const particles = [];
    const particleCount = 45;

    class Particle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.radius = Math.random() * 2 + 1;
            this.vx = (Math.random() - 0.5) * 0.4;
            this.vy = (Math.random() - 0.5) * 0.4;
            this.alpha = Math.random() * 0.5 + 0.2;
        }

        update() {
            this.x += this.vx;
            this.y += this.vy;

            // Bounce off boundaries or wrap
            if (this.x < 0 || this.x > width) this.vx = -this.vx;
            if (this.y < 0 || this.y > height) this.vy = -this.vy;
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(51, 232, 24, ${this.alpha})`;
            ctx.shadowBlur = 4;
            ctx.shadowColor = '#33E818';
            ctx.fill();
            ctx.shadowBlur = 0; // reset
        }
    }

    // Populate particles
    for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle());
    }

    // Secondary layer: Market Graph wave illustration in background
    let time = 0;
    
    function drawMarketWave(amplitude, frequency, offset, color) {
        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineWidth = 1.5;
        
        for (let x = 0; x < width; x += 10) {
            // Combine sine and cosine waves for an organic financial chart look
            const y = height * 0.7 + 
                      Math.sin(x * frequency + time + offset) * amplitude + 
                      Math.cos(x * (frequency * 0.5) + time * 0.7) * (amplitude * 0.4);
            
            if (x === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }
        ctx.stroke();
    }

    // Main animation loop
    function animate() {
        ctx.clearRect(0, 0, width, height);

        // Draw market trend lines (representing background assets/volatility)
        time += 0.003;
        drawMarketWave(40, 0.002, 0, 'rgba(51, 232, 24, 0.04)');
        drawMarketWave(25, 0.0035, Math.PI / 4, 'rgba(38, 173, 18, 0.03)');

        // Update and draw particles
        particles.forEach(p => {
            p.update();
            p.draw();
        });

        // Draw network connections between nearby nodes
        ctx.strokeStyle = 'rgba(51, 232, 24, 0.035)';
        ctx.lineWidth = 0.8;
        for (let i = 0; i < particleCount; i++) {
            for (let j = i + 1; j < particleCount; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 120) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        requestAnimationFrame(animate);
    }

    // -------------------------------------------------------------
    // MODAL POPUP LOGIC FOR CERTIFICATIONS & WPA
    // -------------------------------------------------------------
    const certModal = document.getElementById('cert-modal');
    const modalCloseBtn = document.querySelector('.modal-close');
    const modalTitle = document.getElementById('modal-title');
    const modalCertName = document.getElementById('modal-cert-name');
    
    // For Legalitas & Rekomendasi
    const certImages = {
        'bi': 'images/legalitas/bi.jpg',
        'ojk': 'images/legalitas/ojk.jpg',
        'bappebti': 'images/legalitas/Izin-Penasihat-Berjangka&EA.jpg',
        'komdigi': 'images/legalitas/komdigi.png',
        'jfx': 'images/rekomendasi/jfx.jpg',
        'cfx': 'images/rekomendasi/cfx.jpg'
    };

    const certLinks = document.querySelectorAll('.cert-link');
    certLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const certId = e.currentTarget.getAttribute('data-cert');
            const certTitles = {
                'bi': 'Bank Indonesia (PUVA)',
                'ojk': 'OJK (Penasihat Investasi)',
                'bappebti': 'Bappebti (Expert Advisor)',
                'komdigi': 'Komdigi (PSE)',
                'jfx': 'Bursa JFX',
                'cfx': 'Bursa CFX',
                'icdx': 'Bursa ICDX'
            };
            if (modalTitle) modalTitle.textContent = "Legalitas Perusahaan - " + (certTitles[certId] || certId.toUpperCase());
            
            const certImg = document.getElementById('modal-cert-img');
            const certPlaceholder = document.getElementById('modal-cert-placeholder');
            const certNameSpan = document.getElementById('modal-cert-name');
            const certDesc = document.getElementById('modal-cert-desc');
            
            if (certImages[certId]) {
                certImg.src = '<?= base_url() ?>' + certImages[certId];
                certImg.style.display = 'block';
                if (certPlaceholder) certPlaceholder.style.display = 'none';
            } else {
                certImg.style.display = 'none';
                if (certPlaceholder) {
                    certPlaceholder.style.display = 'block';
                    if (certNameSpan) certNameSpan.textContent = certTitles[certId] || certId.toUpperCase();
                    if (certDesc) {
                        if (certId === 'icdx') {
                            certDesc.innerHTML = '<strong style="color: var(--color-primary); font-size: 1.2rem;">Segera / Coming Soon</strong>';
                        } else {
                            certDesc.textContent = 'Sertifikat / Dokumen belum tersedia.';
                        }
                    }
                }
            }

            if (certModal) certModal.classList.add('show');
        });
    });

    // Function to cleanly close cert modal
    function closeCertModal() {
        if (certModal) {
            certModal.classList.remove('show');
            const modalIframe = document.getElementById('modal-iframe');
            if (modalIframe) {
                modalIframe.src = '';
                modalIframe.style.display = 'none';
            }
            const modalContent = certModal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.maxWidth = '600px';
                modalContent.style.width = 'auto';
            }
        }
    }

    // For WPA Profiles
    const wpaLinks = document.querySelectorAll('.wpa-link');
    wpaLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const wpaName = e.currentTarget.getAttribute('data-wpa');
            const wpaSlug = e.currentTarget.getAttribute('data-slug');
            if (modalTitle) modalTitle.textContent = "Profil WPA: " + wpaName;
            
            const certImg = document.getElementById('modal-cert-img');
            const certPlaceholder = document.getElementById('modal-cert-placeholder');
            const modalIframe = document.getElementById('modal-iframe');
            
            if (certImg) certImg.style.display = 'none';
            if (certPlaceholder) certPlaceholder.style.display = 'none';
            if (modalIframe) modalIframe.style.display = 'none';
            
            if (wpaSlug && modalIframe) {
                const modalContent = certModal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.maxWidth = '1000px';
                    modalContent.style.width = '90vw';
                }
                modalIframe.src = '<?= base_url('wpa') ?>/' + wpaSlug + '?embed=1';
                modalIframe.style.display = 'block';
            } else if (modalCertName) {
                modalCertName.textContent = wpaName;
                if (certPlaceholder) certPlaceholder.style.display = 'block';
            }
            
            if (certModal) certModal.classList.add('show');
        });
    });

    // Close Modal
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', closeCertModal);
    }

    if (certModal) {
        certModal.addEventListener('click', (e) => {
            if (e.target === certModal) {
                closeCertModal();
            }
        });
    }

    // Close Modal on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && certModal && certModal.classList.contains('show')) {
            closeCertModal();
        }
    });

    // Initialize first slide
    showSlide(currentIdx);
});

</script>
<!-- PHASE MODAL -->
<div id="phaseModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 1rem;">
    <!-- Backdrop -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);" onclick="closePhaseModal()"></div>
    
    <!-- Modal Box -->
    <div style="position: relative; background: #0a0a0a; border: 1px solid rgba(51, 232, 24, 0.3); border-radius: 1rem; width: 100%; max-width: 400px; overflow: hidden; box-shadow: 0 0 40px rgba(51,232,24,0.15); display: flex; flex-direction: column;">
        <!-- Close Button -->
        <button onclick="closePhaseModal()" style="position: absolute; top: 1rem; right: 1rem; color: #6b7280; background: none; border: none; cursor: pointer; padding: 0.5rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b7280'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

        <div style="padding: 2rem;">
            <div id="modalPhaseNumber" style="width: 3rem; height: 3rem; background: #1A1A1A; border: 1px solid #33e818; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #33e818; font-weight: bold; font-size: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 0 15px rgba(51,232,24,0.3);">
                01
            </div>
            
            <h3 id="modalPhaseTitle" style="font-size: 1.5rem; font-weight: bold; color: white; margin-bottom: 1rem; line-height: 1.25;">
                Pembekalan & Pendampingan
            </h3>
            
            <div style="width: 3rem; height: 0.25rem; background: #33e818; margin-bottom: 1.5rem; border-radius: 9999px;"></div>
            
            <p id="modalPhaseDesc" style="color: #9ca3af; font-size: 1.125rem; line-height: 1.625; font-style: italic; margin-bottom: 2rem;">
                Program persiapan dan pendampingan terpadu hingga kompeten sebagai WPA.
            </p>

            <button id="modalPhaseBtn" onclick="openImageModal()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: rgba(51,232,24,0.2); border: 1px solid #33e818; color: #33e818; border-radius: 9999px; font-weight: bold; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#33e818'; this.style.color='black';" onmouseout="this.style.background='rgba(51,232,24,0.2)'; this.style.color='#33e818';">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                Lihat Foto
            </button>
        </div>

        <!-- Footer Decoration -->
        <div style="height: 0.25rem; width: 100%; background: linear-gradient(90deg, transparent, rgba(51,232,24,0.5), transparent);"></div>
    </div>
</div>

<!-- IMAGE MODAL (LIGHTBOX) -->
<div id="imageModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000; display: none; align-items: center; justify-content: center; padding: 1rem;">
    <!-- Backdrop -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); backdrop-filter: blur(8px);" onclick="closeImageModal()"></div>
    
    <!-- Close Button -->
    <button onclick="closeImageModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; color: rgba(255,255,255,0.5); background: none; border: none; z-index: 10; cursor: pointer; padding: 0.5rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>

    <!-- Image Container -->
    <div style="position: relative; max-width: 1024px; width: 100%; max-height: 90vh; display: flex; align-items: center; justify-content: center;">
        <img id="modalFullImage" src="" alt="Phase Image" style="max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 0.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);">
    </div>
</div>

<script>
let currentImgUrl = '';

function openPhaseModal(title, desc, number, imgUrl) {
    const modal = document.getElementById('phaseModal');
    const modalTitle = document.getElementById('modalPhaseTitle');
    const modalDesc = document.getElementById('modalPhaseDesc');
    const modalNumber = document.getElementById('modalPhaseNumber');

    modalTitle.innerText = title;
    modalDesc.innerText = desc;
    modalNumber.innerText = number;
    currentImgUrl = imgUrl;

    modal.style.display = 'flex';
}

function openImageModal() {
    const imgModal = document.getElementById('imageModal');
    const fullImg = document.getElementById('modalFullImage');
    
    fullImg.src = currentImgUrl;
    imgModal.style.display = 'flex';
}

function closeImageModal() {
    const imgModal = document.getElementById('imageModal');
    imgModal.style.display = 'none';
}

function closePhaseModal() {
    const modal = document.getElementById('phaseModal');
    modal.style.display = 'none';
}

// Active phase detection for mobile scroll
const timeline = document.querySelector('.phase-timeline-wpa');
const items = document.querySelectorAll('.phase-item-wpa');

if (timeline) {
    const observerOptions = {
        root: timeline,
        threshold: 0.6,
        rootMargin: '0px -25% 0px -25%'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                items.forEach(item => item.classList.remove('is-active'));
                entry.target.classList.add('is-active');
            }
        });
    }, observerOptions);

    items.forEach(item => observer.observe(item));
    
    if (window.innerWidth < 1024) {
        items[0].classList.add('is-active');
    }
}
</script>
<?= $this->endSection() ?>
