<!-- AI Chatbot -->
<div id="aiChatbot" class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-50">
    <!-- Chat Button -->
    <div id="chatButton" class="group relative flex items-center justify-center w-20 h-20 md:w-24 md:h-24 hover:-translate-y-2 hover:scale-105 transition-all duration-300 cursor-pointer drop-shadow-[0_10px_15px_rgba(51,232,24,0.3)]">
        <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-full h-full object-contain drop-shadow-xl">
        <div class="absolute -top-1 -right-1 md:-top-2 md:-right-2 w-5 h-5 md:w-6 md:h-6 bg-red-500 rounded-full flex items-center justify-center">
            <span class="text-[10px] md:text-xs font-bold text-white">AI</span>
        </div>
    </div>

    <!-- Chat Window -->
    <!-- Chat Window -->
    <div id="chatWindow" class="fixed top-0 left-0 right-0 bottom-0 sm:top-auto sm:left-auto sm:bottom-24 sm:right-8 z-[60] flex flex-col bg-[#111] shadow-2xl transform scale-0 origin-bottom-right transition-all duration-300 w-full h-full sm:w-[400px] sm:h-[600px] sm:max-h-[80vh] sm:rounded-2xl sm:border sm:border-white/10 overflow-hidden">

        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-accent to-green-600 p-4 text-black flex-shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black/20 rounded-full flex items-center justify-center">
                        <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-6 h-6 object-contain">
                    </div>
                    <div>
                        <h3 class="font-bold">Susani Assistant</h3>
                        <p class="text-xs opacity-80">AI Konsultan Layanan</p>
                    </div>
                </div>
                <button id="closeChat" class="w-8 h-8 bg-black/20 rounded-full flex items-center justify-center hover:bg-black/30 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div id="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-3">
        </div>

        <!-- Quick Actions (Hidden by default) -->
        <div id="quickActions" class="hidden p-4 border-t border-white/10 flex-shrink-0 animate-in slide-in-from-bottom-2 duration-200">
            <div class="grid grid-cols-2 gap-2 mb-1">
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="level">
                    <i class="fas fa-layer-group text-[10px]"></i>
                    Pilih Level
                </button>
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="budget">
                    <i class="fas fa-wallet text-[10px]"></i>
                    Set Budget
                </button>
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="buy_guide">
                    <i class="fas fa-shopping-cart text-[10px]"></i>
                    Cara Beli
                </button>
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="ea_guide">
                    <i class="fas fa-key text-[10px]"></i>
                    Aktivasi EA
                </button>
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="recommend">
                    <i class="fas fa-star text-[10px]"></i>
                    Rekomendasi
                </button>
                <button class="quick-action-btn px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" data-action="help">
                    <i class="fas fa-question-circle text-[10px]"></i>
                    Bantuan
                </button>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-4 border-t border-white/10 flex-shrink-0">
            <div class="flex gap-2">
                <button id="toggleQuickActions" class="w-10 h-10 bg-white/10 border border-white/20 rounded-lg flex items-center justify-center text-accent hover:bg-accent hover:text-black transition shadow-sm" title="Action Cepat">
                    <i class="fas fa-th-large text-sm"></i>
                </button>
                <input type="text" id="chatInput" placeholder="Ketik pesan..." class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-sm text-white placeholder-gray-400 focus:outline-none focus:border-accent">
                <button id="sendMessage" class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-black hover:bg-white transition">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Force floating behavior on desktop */
    @media (min-width: 640px) {
        #chatWindow {
            bottom: 6rem !important;
            /* 24 units */
            right: 2rem !important;
            /* 8 units */
            top: auto !important;
            left: auto !important;
            width: 400px !important;
            height: 600px !important;
            max-height: 80vh !important;
            border-radius: 1rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    }
</style>

<script>
    class ALMAIChatbot {
        constructor() {
            this.isOpen = false;
            this.userProfile = {
                name: null,
                whatsapp: null,
                level: null,
                budget: null,
                interests: [],
                leadId: null
            };
            this.csrfToken = '<?= csrf_hash() ?>';
            this.conversationStep = 'welcome';
            this.chatHistory = [];
            this.init();
        }

        init() {
            this.bindEvents();
            this.showWelcomeMessage();
            this.checkAutoOpen();
        }

        checkAutoOpen() {
            // Check if we're on homepage
            const isHomepage = window.location.pathname === '/' || window.location.pathname === '/home';

            // Check if user has seen the chatbot before
            const hasSeenChatbot = localStorage.getItem('almai_chatbot_seen');

            // Auto-open on first visit to homepage
            if (isHomepage && !hasSeenChatbot) {
                setTimeout(() => {
                    this.toggleChat();
                    // Mark as seen
                    localStorage.setItem('almai_chatbot_seen', 'true');
                }, 2000); // Open after 2 seconds
            }
        }

        bindEvents() {
            document.getElementById('chatButton').addEventListener('click', () => this.toggleChat());
            document.getElementById('closeChat').addEventListener('click', () => this.closeChat());
            document.getElementById('toggleQuickActions').addEventListener('click', () => this.toggleQuickActions());
            document.getElementById('sendMessage').addEventListener('click', () => this.sendMessage());
            document.getElementById('chatInput').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.sendMessage();
            });

            // Quick action buttons
            document.querySelectorAll('.quick-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const action = e.target.getAttribute('data-action');
                    this.handleQuickAction(action);
                });
            });
        }

        toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            if (this.isOpen) {
                chatWindow.classList.remove('scale-100');
                chatWindow.classList.add('scale-0');
            } else {
                chatWindow.classList.remove('scale-0');
                chatWindow.classList.add('scale-100');
            }
            this.isOpen = !this.isOpen;
        }

        closeChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.remove('scale-100');
            chatWindow.classList.add('scale-0');
            this.isOpen = false;
            // Also hide quick actions if it was open
            document.getElementById('quickActions').classList.add('hidden');
        }

        toggleQuickActions() {
            const qa = document.getElementById('quickActions');
            const btn = document.getElementById('toggleQuickActions');
            const isHidden = qa.classList.contains('hidden');

            if (isHidden) {
                qa.classList.remove('hidden');
                btn.classList.add('bg-accent', 'text-black');
                btn.classList.remove('bg-white/10', 'text-accent');
            } else {
                qa.classList.add('hidden');
                btn.classList.add('bg-white/10', 'text-accent');
                btn.classList.remove('bg-accent', 'text-black');
            }
        }

        showWelcomeMessage() {
            setTimeout(() => {
                this.addBotMessage("Ada yang bisa saya bantu hari ini? 😊\n\nAnda bisa bertanya apa saja seputar layanan trading kami, mentor WPA, atau cara memulai di platform ALMAI.");
            }, 1000);
        }

        async saveLeadToDatabase() {
            try {
                const response = await fetch('/api/chat/save-lead', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        id: this.userProfile.leadId,
                        name: this.userProfile.name,
                        whatsapp: this.userProfile.whatsapp,
                        level: this.userProfile.level,
                        budget: this.userProfile.budget
                    })
                });
                const data = await response.json();

                // Update CSRF token for next request
                if (data.csrf_token) {
                    this.csrfToken = data.csrf_token;
                }

                if (data.status === 'success' && data.id) {
                    this.userProfile.leadId = data.id;
                }
            } catch (error) {
                console.error('Error saving lead:', error);
            }
        }

        handleQuickAction(action) {
            // Hide menu after selection to save space
            this.toggleQuickActions();

            switch (action) {
                case 'level':
                    this.addBotMessage("Pilih level trading Anda:", [{
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Pemula',
                            value: "pemula"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>Menengah',
                            value: "menengah"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Expert',
                            value: "expert"
                        }
                    ]);
                    break;
                case 'budget':
                    this.addBotMessage("Berapa budget yang Anda siapkan?", [{
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>< 500K',
                            value: "budget_low"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>500K - 2M',
                            value: "budget_mid"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>> 2M',
                            value: "budget_high"
                        }
                    ]);
                    break;
                case 'buy_guide':
                    this.addBotMessage("Membeli layanan di ALMAI sangat mudah! Pilih panduan:", [{
                            text: 'Cara Daftar & Login',
                            value: "guide_register"
                        },
                        {
                            text: 'Cara Pilih Layanan',
                            value: "guide_choose"
                        },
                        {
                            text: 'Metode Pembayaran',
                            value: "guide_payment"
                        },
                        {
                            text: 'Konfirmasi Otomatis',
                            value: "guide_confirm"
                        }
                    ]);
                    break;
                case 'ea_guide':
                    this.addBotMessage("Bantuan Aktivasi Expert Advisor (EA):", [{
                            text: 'Video Tutorial Aktivasi',
                            value: "https://www.youtube.com/results?search_query=cara+aktivasi+ea+metatrader",
                            isLink: true
                        },
                        {
                            text: 'Cara Masukkan License',
                            value: "guide_ea_license"
                        },
                        {
                            text: 'Virtual Private Server (VPS)',
                            value: "guide_vps"
                        },
                        {
                            text: 'Hubungi IT Support',
                            value: "contact_it"
                        }
                    ]);
                    break;
                case 'recommend':
                    this.generateRecommendation();
                    break;
                case 'help':
                    this.addBotMessage("Saya bisa membantu Anda:", [{
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>Info Layanan',
                            value: "info_layanan"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/></svg>Chat WPA',
                            value: "chat_wpa"
                        },
                        {
                            text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>Kontak Support',
                            value: "contact"
                        }
                    ]);
                    break;
            }
        }

        sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            this.addUserMessage(message);
            input.value = '';

            // Process message
            setTimeout(() => {
                this.processMessage(message);
            }, 500);
        }

        async processMessage(message) {
            this.addTypingIndicator();

            try {
                const response = await fetch('/api/chat/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        message: message,
                        history: this.chatHistory.slice(-5) // Send last 5 messages for context
                    })
                });

                const data = await response.json();
                this.removeTypingIndicator();

                if (data.csrf_token) {
                    this.csrfToken = data.csrf_token;
                }

                if (data.status === 'success') {
                    this.addBotMessage(data.reply);
                    this.chatHistory.push({
                        role: 'user',
                        content: message
                    });
                    this.chatHistory.push({
                        role: 'bot',
                        content: data.reply
                    });
                } else {
                    const errorMsg = data.message || "Boleh coba lagi nanti?";
                    this.addBotMessage("Maaf, ada kendala teknis:\n" + errorMsg);
                }
            } catch (error) {
                this.removeTypingIndicator();
                console.error('Error processing chat:', error);
                this.addBotMessage("Terjadi kesalahan koneksi. Pastikan internet Anda aktif.");
            }
        }

        addTypingIndicator() {
            const messagesContainer = document.getElementById('chatMessages');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'flex gap-3 animate-pulse';
            typingDiv.innerHTML = `
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                    <img src="/images/a.gif" alt="CS" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white/10 rounded-lg p-3 max-w-xs">
                    <div class="flex gap-1">
                        <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce [animation-delay:0.2s]"></div>
                        <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce [animation-delay:0.4s]"></div>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(typingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }

        generateRecommendation() {
            if (!this.userProfile.level || !this.userProfile.budget) {
                this.addBotMessage("Untuk memberikan rekomendasi terbaik, saya perlu tahu level dan budget Anda dulu. Mari lengkapi profil Anda!");
                return;
            }

            let recommendations = this.getRecommendations(this.userProfile.level, this.userProfile.budget);

            this.addBotMessage(`Berdasarkan profil Anda (${this.userProfile.level}, ${this.userProfile.budget}), ini rekomendasi saya:`,
                recommendations.map(rec => ({
                    text: rec.name,
                    value: rec.link,
                    isLink: true
                }))
            );
        }

        getRecommendations(level, budget) {
            const layanan = {
                pemula: {
                    budget_low: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>Webinar Fundamental Trading',
                            link: "/layanan/webinar-fundamental-trading"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Angel Gold - Trial',
                            link: "/layanan/angel-gold"
                        }
                    ],
                    budget_mid: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Angel Gold - Pro',
                            link: "/layanan/angel-gold"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Workshop Trading Intensif',
                            link: "/layanan/workshop-trading-intensif"
                        }
                    ],
                    budget_high: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>Angel Gold - Ultimate',
                            link: "/layanan/angel-gold"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>Mentoring 1-on-1',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan program Mentoring 1-on-1"
                        }
                    ]
                },
                menengah: {
                    budget_low: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>Technical Analysis Advanced',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Technical Analysis Advanced"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Angel Gold - Pro',
                            link: "/layanan/angel-gold"
                        }
                    ],
                    budget_mid: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>Angel Gold - Ultimate',
                            link: "/layanan/angel-gold"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/></svg>Risk Management Workshop',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Risk Management Workshop"
                        }
                    ],
                    budget_high: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>VIP Trading Program',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan VIP Trading Program"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>Personal Trading Coach',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Personal Trading Coach"
                        }
                    ]
                },
                expert: {
                    budget_low: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03z" clip-rule="evenodd"/></svg>Advanced Strategy',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Advanced Strategy"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>Market Analysis Pro',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Market Analysis Pro"
                        }
                    ],
                    budget_mid: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>Institutional Trading',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Institutional Trading"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2V3a2 2 0 012-2 2 2 0 012 2v8a4 4 0 01-4 4H6a4 4 0 01-4-4V5z" clip-rule="evenodd"/></svg>Portfolio Management',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Portfolio Management"
                        }
                    ],
                    budget_high: [{
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"/></svg>Elite Trader Program',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Elite Trader Program"
                        },
                        {
                            name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>Hedge Fund Strategy',
                            link: "https://wa.me/6285183231800?text=Halo, saya tertarik dengan Hedge Fund Strategy"
                        }
                    ]
                }
            };

            return layanan[level]?.[budget] || [{
                name: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>Konsultasi Personal',
                link: "tel:+6285183231800"
            }];
        }

        escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        formatMessage(text) {
            if (!text) return '';

            let formatted = this.escapeHtml(text);
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/(?<!\*)\*(?!\*)(.*?)\*/g, '<em>$1</em>');

            formatted = formatted.replace(
                /((?:https?:\/\/|www\.)[\w\-._~:/?#[\]@!$&'()*+,;=%]+)(?![^<]*>)/gi,
                (match) => {
                    const href = match.startsWith('http') ? match : `https://${match}`;
                    const label = match;
                    return `<a href="${href}" target="_blank" rel="noopener noreferrer" class="text-blue-400 underline underline-offset-2 hover:text-blue-300 break-all">${label}</a>`;
                }
            );

            return formatted;
        }

        addBotMessage(text, buttons = []) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3';

            messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                <img src="/images/a.gif" alt="CS" class="w-5 h-5 object-contain">
            </div>
            <div class="bg-white/10 rounded-lg p-3 max-w-xs text-white">
                <p class="text-sm whitespace-pre-wrap">${this.formatMessage(text)}</p>
                ${buttons.length > 0 ? `
                    <div class="mt-2 space-y-1">
                        ${buttons.map(btn => `
                            <button class="block w-full text-left px-2 py-1 bg-accent/20 hover:bg-accent hover:text-black rounded text-xs transition" 
                                    onclick="chatbot.handleButtonClick('${btn.value}', ${btn.isLink || false}, this)">
                                ${btn.text}
                            </button>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        addUserMessage(text) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 justify-end';

            messageDiv.innerHTML = `
            <div class="bg-accent rounded-lg p-3 max-w-xs text-black">
                <p class="text-sm whitespace-pre-wrap">${this.formatMessage(text)}</p>
            </div>
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
        `;

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        async handleButtonClick(value, isLink = false, el = null) {
            if (isLink) {
                if (el) {
                    // Determine service name from button text
                    this.userProfile.service_interested = el.innerText.trim();
                    // Save to database before redirecting
                    await this.saveLeadToDatabase();
                }

                if (value.startsWith('http') || value.startsWith('/')) {
                    // Open internal links in same tab, external in new tab
                    if (value.startsWith('/')) {
                        window.location.href = value;
                    } else {
                        window.open(value, '_blank');
                    }
                } else if (value.startsWith('tel:')) {
                    window.location.href = value;
                } else if (value.startsWith('mailto:')) {
                    window.location.href = value;
                }
                return;
            }

            // Handle specific button actions
            if (value === 'pemula' || value === 'menengah' || value === 'expert') {
                this.userProfile.level = value;
                this.addUserMessage(value.charAt(0).toUpperCase() + value.slice(1));
                this.saveLeadToDatabase(); // Save level to DB
                this.addBotMessage(`Great! Level ${value} dipilih. Sekarang, berapa budget Anda?`, [{
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>< 500K',
                        value: "budget_low"
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>500K - 2M',
                        value: "budget_mid"
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>> 2M',
                        value: "budget_high"
                    }
                ]);
            } else if (value.startsWith('budget_')) {
                this.userProfile.budget = value;
                const budgetText = value === 'budget_low' ? '< 500K' : value === 'budget_mid' ? '500K - 2M' : '> 2M';
                this.addUserMessage(budgetText);
                this.saveLeadToDatabase(); // Save budget to DB
                this.generateRecommendation();
            } else if (value === 'info_layanan') {
                this.addBotMessage("Berikut layanan ALMAI:", [{
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>Webinar & Workshop',
                        value: "/layanan",
                        isLink: true
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Angel Gold Program',
                        value: "/layanan/angel-gold",
                        isLink: true
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>Mentoring Personal',
                        value: "/layanan/mentoring",
                        isLink: true
                    }
                ]);
            } else if (value === 'chat_wpa') {
                this.addBotMessage("Pilih WPA untuk konsultasi:", [{
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/></svg>Chat via WhatsApp',
                        value: "https://wa.me/6285183231800",
                        isLink: true
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>Telepon Langsung',
                        value: "tel:+6285183231800",
                        isLink: true
                    }
                ]);
            } else if (value.startsWith('guide_')) {
                this.handleGuideSteps(value);
            } else if (value === 'contact_it') {
                this.addBotMessage("Hubungi IT Support ALMAI untuk bantuan teknis EA:", [{
                    text: 'Chat via WhatsApp',
                    value: "https://wa.me/6285183231800?text=Halo, saya butuh bantuan teknis aktivasi EA",
                    isLink: true
                }]);
            } else if (value === 'contact') {
                this.addBotMessage("Hubungi kami:", [{
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>+62 851-8323-1800',
                        value: "tel:+6285183231800",
                        isLink: true
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>Email Support',
                        value: "mailto:support@almai.id",
                        isLink: true
                    },
                    {
                        text: '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/></svg>Website',
                        value: "https://almai.id",
                        isLink: true
                    }
                ]);
            }
        }

        handleGuideSteps(value) {
            switch (value) {
                case 'guide_register':
                    this.addBotMessage("1. Klik tombol 'Login' atau 'Daftar' di pojok kanan atas.\n2. Masukkan nomor WhatsApp untuk verifikasi OTP.\n3. Lengkapi profil Anda.");
                    break;
                case 'guide_choose':
                    this.addBotMessage("1. Buka menu 'Layanan'.\n2. Gunakan filter level atau budget.\n3. Klik 'Detail' untuk melihat materi dan mentor.");
                    break;
                case 'guide_payment':
                    this.addBotMessage("Kami mendukung berbagai metode pembayaran:\n- QRIS (ShopeePay, OVO, Dana, dll)\n- Virtual Account (BCA, Mandiri, BRI, BNI)\n- Kartu Kredit.");
                    break;
                case 'guide_confirm':
                    this.addBotMessage("Pembayaran melalui Xendit akan terkonfirmasi otomatis dalam hitungan detik. Layanan akan langsung aktif di dashboard Anda.");
                    break;
                case 'guide_ea_license':
                    this.addBotMessage("1. Login ke Dashboard User.\n2. Buka menu 'Layanan Saya'.\n3. Cari EA Anda, klik 'Aktivasi License'.\n4. Masukkan Nomor Akun MT4/MT5 Anda.");
                    break;
                case 'guide_vps':
                    this.addBotMessage("Untuk hasil maksimal, jalankan EA di VPS agar aktif 24/7. Anda bisa menyewa VPS dari provider favorit atau hubungi kami untuk rekomendasi.");
                    break;
            }
        }
    }

    // Initialize chatbot
    const chatbot = new ALMAIChatbot();
</script>
