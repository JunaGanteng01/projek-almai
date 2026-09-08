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
    const derivatifDescTitle = document.getElementById('derivatif-desc-title');
    const derivatifDescText = document.getElementById('derivatif-desc-text');
    const slide5Content = document.querySelector('#slide-5 .flow-interactive-wrapper');

    if (ecoCards.length > 0 && derivatifDescTitle && derivatifDescText) {
        ecoCards.forEach(card => {
            const handleInteraction = () => {
                // Clear active states
                ecoCards.forEach(c => c.classList.remove('active'));
                
                // Add active state to current
                card.classList.add('active');
                
                // Get data attributes
                const title = card.getAttribute('data-title');
                const desc = card.getAttribute('data-desc');
                
                // Update text
                derivatifDescTitle.textContent = title;
                derivatifDescText.textContent = desc;
            };

            card.addEventListener('mouseenter', handleInteraction);
            card.addEventListener('click', handleInteraction);
        });

        // Reset when mouse leaves the slide content interaction container
        if (slide5Content) {
            slide5Content.addEventListener('mouseleave', () => {
                ecoCards.forEach(c => c.classList.remove('active'));
                derivatifDescTitle.textContent = 'Penjelasan';
                derivatifDescText.textContent = 'Arahkan kursor atau klik pada salah satu komponen kartu di atas untuk melihat detail perannya dalam struktur ekosistem derivatif Indonesia.';
            });
        }
    }

    // Slide 6 Ecosystem Grid Event Listeners
    const digitalCards = document.querySelectorAll('#slide-6 .regulator-bar, #slide-6 .eco-card');
    const digitalDescTitle = document.getElementById('digital-desc-title');
    const digitalDescText = document.getElementById('digital-desc-text');
    const slide6Content = document.querySelector('#slide-6 .flow-interactive-wrapper');

    if (digitalCards.length > 0 && digitalDescTitle && digitalDescText) {
        digitalCards.forEach(card => {
            const handleInteraction = () => {
                // Clear active states
                digitalCards.forEach(c => c.classList.remove('active'));
                
                // Add active state to current
                card.classList.add('active');
                
                // Get data attributes
                const title = card.getAttribute('data-title');
                const desc = card.getAttribute('data-desc');
                
                // Update text
                digitalDescTitle.textContent = title;
                digitalDescText.textContent = desc;
            };

            card.addEventListener('mouseenter', handleInteraction);
            card.addEventListener('click', handleInteraction);
        });

        // Reset when mouse leaves the slide content interaction container
        if (slide6Content) {
            slide6Content.addEventListener('mouseleave', () => {
                digitalCards.forEach(c => c.classList.remove('active'));
                digitalDescTitle.textContent = 'Penjelasan';
                digitalDescText.textContent = 'Arahkan kursor atau klik pada salah satu komponen kartu di atas untuk melihat detail perannya dalam ekosistem aset keuangan digital.';
            });
        }
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
    
    // For Legalitas
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
            if (modalTitle) modalTitle.textContent = "Legalitas Perusahaan";
            if (modalCertName) modalCertName.textContent = certTitles[certId] || certId.toUpperCase();
            if (certModal) certModal.classList.add('show');
        });
    });

    // For WPA Profiles
    const wpaLinks = document.querySelectorAll('.wpa-link');
    wpaLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const wpaName = e.currentTarget.getAttribute('data-wpa');
            if (modalTitle) modalTitle.textContent = "Profil WPA";
            if (modalCertName) modalCertName.textContent = wpaName;
            if (certModal) certModal.classList.add('show');
        });
    });

    // Close Modal
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', () => {
            if (certModal) certModal.classList.remove('show');
        });
    }

    if (certModal) {
        certModal.addEventListener('click', (e) => {
            if (e.target === certModal) {
                certModal.classList.remove('show');
            }
        });
    }

    // Close Modal on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && certModal && certModal.classList.contains('show')) {
            certModal.classList.remove('show');
        }
    });

    // Initialize first slide
    showSlide(currentIdx);
});
