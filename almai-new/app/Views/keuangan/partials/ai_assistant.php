<!-- Susani AI Accounting Assistant — Floating Chat Widget -->
<div id="susani-ai-widget" class="fixed bottom-16 right-4 md:bottom-8 md:right-8 z-50">
    <!-- Floating Button -->
    <div id="susani-toggle-btn" onclick="susaniToggleChat()" class="group relative flex items-center justify-center w-20 h-20 md:w-24 md:h-24 hover:-translate-y-2 hover:scale-105 transition-all duration-300 cursor-pointer drop-shadow-[0_10px_15px_rgba(51,232,24,0.3)]">
        <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-full h-full object-contain drop-shadow-xl">
        <div class="absolute -top-1 -right-1 md:-top-2 md:-right-2 w-5 h-5 md:w-6 md:h-6 bg-red-500 rounded-full flex items-center justify-center">
            <span class="text-[10px] md:text-xs font-bold text-white">AI</span>
        </div>
    </div>

    <!-- Chat Panel -->
    <div id="susani-chat-panel" class="fixed top-0 left-0 right-0 bottom-0 sm:top-auto sm:left-auto sm:bottom-24 sm:right-8 z-[60] flex flex-col bg-[#111] shadow-2xl transform scale-0 origin-bottom-right transition-all duration-300 w-full h-full sm:w-[400px] sm:h-[600px] sm:max-h-[80vh] sm:rounded-2xl sm:border sm:border-white/10 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-accent to-green-600 p-4 text-black flex-shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black/20 rounded-full flex items-center justify-center">
                        <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-6 h-6 object-contain">
                    </div>
                    <div>
                        <h3 class="font-bold">Susani Assistant</h3>
                        <p class="text-xs opacity-80">AI Accounting Assistant</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="susaniClearChat()" class="w-8 h-8 bg-black/20 rounded-full flex items-center justify-center hover:bg-black/30 transition" title="Clear Chat">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                    <button onclick="susaniToggleChat()" class="w-8 h-8 bg-black/20 rounded-full flex items-center justify-center hover:bg-black/30 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div id="susani-messages" class="flex-1 p-4 overflow-y-auto space-y-3">
            <!-- Welcome -->
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                    <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white/10 rounded-lg p-3 max-w-xs text-white">
                    <p class="text-sm whitespace-pre-wrap">Halo! Saya <strong>Susani</strong>, AI Accounting Assistant Anda. 📊<br><br>Saya bisa membantu Anda untuk:
- Audit neraca & balance sheet
- Analisis cashflow & laba rugi
- Cek piutang & hutang
- Rekomendasi keuangan

Silakan gunakan tombol cepat di bawah atau ketik pertanyaan Anda! 💬</p>
                    <span class="text-[10px] text-gray-400 mt-2 block text-right">Susani AI</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Hidden by default) -->
        <div id="susani-quick-actions" class="hidden p-4 border-t border-white/10 flex-shrink-0 animate-in slide-in-from-bottom-2 duration-200">
            <div class="grid grid-cols-2 gap-2 mb-1">
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Lakukan FULL AUDIT: cek neraca balance, cari semua jurnal yang tidak balance, dan deteksi akun dengan saldo abnormal. Sebutkan detail masalah dan berikan link halaman untuk perbaikan.')">
                    <i class="fas fa-search-dollar text-[10px]"></i> Full Audit Balance
                </button>
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Berikan ringkasan posisi keuangan hari ini: saldo kas & bank, piutang, hutang, dan cashflow.')">
                    <i class="fas fa-wallet text-[10px]"></i> Cek Balance
                </button>
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Analisis arus kas (cashflow) secara detail. Bandingkan kas masuk vs keluar, dan berikan insight.')">
                    <i class="fas fa-exchange-alt text-[10px]"></i> Analisis Cashflow
                </button>
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Berikan ringkasan keuangan bulan ini: pendapatan, beban, laba rugi, dan rekomendasi untuk bulan depan.')">
                    <i class="fas fa-chart-line text-[10px]"></i> Ringkasan Bulanan
                </button>
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Analisis piutang dan hutang. Apakah ada risiko kredit macet? Berikan rekomendasi penagihan.')">
                    <i class="fas fa-file-invoice-dollar text-[10px]"></i> Piutang & Hutang
                </button>
                <button class="px-3 py-2 bg-accent/20 border border-accent/30 rounded-lg text-xs text-accent hover:bg-accent hover:text-black transition flex items-center gap-2" onclick="susaniQuickAction('Cek saldo dan transaksi Xendit. Berikan ringkasan volume, withdraw, dan saldo tersisa.')">
                    <i class="fas fa-credit-card text-[10px]"></i> Status Xendit
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t border-white/10 flex-shrink-0">
            <div class="flex gap-2">
                <button id="susaniToggleQuickActions" onclick="susaniToggleQA()" class="w-10 h-10 bg-white/10 border border-white/20 rounded-lg flex items-center justify-center text-accent hover:bg-accent hover:text-black transition shadow-sm" title="Action Cepat">
                    <i class="fas fa-th-large text-sm"></i>
                </button>
                <input type="text" id="susani-input" onkeydown="susaniHandleKey(event)" placeholder="Tanya Susani tentang keuangan..." class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-sm text-white placeholder-gray-400 focus:outline-none focus:border-accent">
                <button id="susani-send-btn" onclick="susaniSendMessage()" class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-black hover:bg-white transition">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </div>
            <p class="text-[9px] text-gray-500 text-center mt-2">Susani AI dapat membuat kesalahan. Verifikasi data penting.</p>
        </div>
    </div>
</div>

<style>
    /* Force floating behavior on desktop */
    @media (min-width: 640px) {
        #susani-chat-panel {
            bottom: 6rem !important;
            right: 2rem !important;
            top: auto !important;
            left: auto !important;
            width: 400px !important;
            height: 600px !important;
            max-height: 80vh !important;
            border-radius: 1rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    }
    
    /* Markdown Styles for Bot */
    .susani-msg-bubble-bot h1, .susani-msg-bubble-bot h2, .susani-msg-bubble-bot h3 {
        font-size: 13px;
        font-weight: 700;
        color: #33e818;
        margin: 12px 0 6px 0;
    }
    .susani-msg-bubble-bot h1:first-child, .susani-msg-bubble-bot h2:first-child, .susani-msg-bubble-bot h3:first-child { margin-top: 0; }
    .susani-msg-bubble-bot strong { color: #fff; font-weight: 700; }
    .susani-msg-bubble-bot em { color: #aaa; }
    .susani-msg-bubble-bot ul, .susani-msg-bubble-bot ol {
        margin: 6px 0;
        padding-left: 18px;
        list-style-type: disc;
    }
    .susani-msg-bubble-bot li { margin: 3px 0; }
    .susani-msg-bubble-bot code {
        background: rgba(51,232,24,0.1);
        color: #33e818;
        padding: 1px 5px;
        border-radius: 4px;
        font-size: 12px;
        font-family: 'Courier New', monospace;
    }
    .susani-msg-bubble-bot pre {
        background: #111;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px;
        padding: 10px;
        overflow-x: auto;
        margin: 8px 0;
    }
    .susani-msg-bubble-bot table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
        font-size: 12px;
    }
    .susani-msg-bubble-bot th, .susani-msg-bubble-bot td {
        border: 1px solid rgba(255,255,255,0.08);
        padding: 6px 8px;
        text-align: left;
    }
    .susani-msg-bubble-bot th {
        background: rgba(51,232,24,0.08);
        color: #33e818;
        font-weight: 700;
    }
    .susani-msg-bubble-bot hr {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.08);
        margin: 10px 0;
    }
</style>

<script>
// === Susani AI Chat Widget ===
const SUSANI_CHAT_URL = '<?= base_url("keuangan/ai-assistant/chat") ?>';
let susaniHistory = [];
let susaniIsOpen = false;
let susaniIsLoading = false;

function susaniToggleChat() {
    const panel = document.getElementById('susani-chat-panel');
    
    susaniIsOpen = !susaniIsOpen;
    
    if (susaniIsOpen) {
        panel.classList.remove('scale-0');
        panel.classList.add('scale-100');
        document.getElementById('susani-input').focus();
    } else {
        panel.classList.remove('scale-100');
        panel.classList.add('scale-0');
        document.getElementById('susani-quick-actions').classList.add('hidden');
        document.getElementById('susaniToggleQuickActions').classList.replace('bg-accent', 'bg-white/10');
        document.getElementById('susaniToggleQuickActions').classList.replace('text-black', 'text-accent');
    }
}

function susaniToggleQA() {
    const qa = document.getElementById('susani-quick-actions');
    const btn = document.getElementById('susaniToggleQuickActions');
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

function susaniClearChat() {
    susaniHistory = [];
    const msgContainer = document.getElementById('susani-messages');
    msgContainer.innerHTML = `
        <div class="flex gap-3">
            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-5 h-5 object-contain">
            </div>
            <div class="bg-white/10 rounded-lg p-3 max-w-xs text-white">
                <p class="text-sm whitespace-pre-wrap">Chat dibersihkan! 🔄 Ada yang bisa saya bantu?</p>
                <span class="text-[10px] text-gray-400 mt-2 block text-right">Susani AI</span>
            </div>
        </div>
    `;
}

function susaniQuickAction(prompt) {
    susaniToggleQA(); // close quick action menu
    document.getElementById('susani-input').value = prompt;
    susaniSendMessage();
}

function susaniHandleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        susaniSendMessage();
    }
}

async function susaniSendMessage() {
    const input = document.getElementById('susani-input');
    const message = input.value.trim();
    if (!message || susaniIsLoading) return;

    susaniIsLoading = true;
    document.getElementById('susani-send-btn').disabled = true;

    // Add user message
    susaniAddMessage('user', message);
    susaniHistory.push({ role: 'user', content: message });
    input.value = '';

    // Show typing
    susaniShowTyping();

    try {
        const response = await fetch(SUSANI_CHAT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                message: message,
                history: susaniHistory.slice(-10) // Last 10 messages for context
            })
        });

        const data = await response.json();

        susaniHideTyping();

        if (data.status === 'success' && data.reply) {
            susaniAddMessage('bot', data.reply);
            susaniHistory.push({ role: 'bot', content: data.reply });
            // Update CSRF token
            if (data.csrf_token) {
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', data.csrf_token);
            }
        } else {
            susaniAddMessage('bot', '⚠️ ' + (data.message || 'Maaf, terjadi kesalahan. Coba lagi.'));
        }
    } catch (error) {
        susaniHideTyping();
        susaniAddMessage('bot', '⚠️ Koneksi terputus. Pastikan Anda terhubung ke internet.');
    }

    susaniIsLoading = false;
    document.getElementById('susani-send-btn').disabled = false;
    document.getElementById('susani-input').focus();
}

function susaniAddMessage(role, content) {
    const msgContainer = document.getElementById('susani-messages');
    const isBot = role === 'bot';
    const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    const formattedContent = isBot ? susaniFormatMarkdown(content) : susaniEscapeHtml(content);

    let msgHtml = '';
    
    if (isBot) {
        msgHtml = `
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                    <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white/10 rounded-lg p-3 max-w-xs text-white">
                    <div class="text-sm whitespace-pre-wrap susani-msg-bubble-bot">${formattedContent}</div>
                    <span class="text-[10px] text-gray-400 mt-2 block text-right">Susani AI • ${time}</span>
                </div>
            </div>
        `;
    } else {
        msgHtml = `
            <div class="flex gap-3 justify-end">
                <div class="bg-accent rounded-lg p-3 max-w-xs text-black">
                    <div class="text-sm whitespace-pre-wrap">${formattedContent}</div>
                    <span class="text-[10px] text-black/60 mt-2 block text-right">Anda • ${time}</span>
                </div>
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </div>
        `;
    }

    msgContainer.insertAdjacentHTML('beforeend', msgHtml);
    msgContainer.scrollTop = msgContainer.scrollHeight;
}

function susaniShowTyping() {
    const msgContainer = document.getElementById('susani-messages');
    const typingHtml = `
        <div id="susani-typing-indicator" class="flex gap-3 animate-pulse">
            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                <img src="<?= base_url('images/a.gif') ?>" alt="CS" class="w-5 h-5 object-contain">
            </div>
            <div class="bg-white/10 rounded-lg p-3 max-w-xs">
                <div class="flex gap-1">
                    <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce" style="animation-delay:0.2s"></div>
                    <div class="w-1.5 h-1.5 bg-accent rounded-full animate-bounce" style="animation-delay:0.4s"></div>
                </div>
            </div>
        </div>
    `;
    msgContainer.insertAdjacentHTML('beforeend', typingHtml);
    msgContainer.scrollTop = msgContainer.scrollHeight;
}

function susaniHideTyping() {
    const el = document.getElementById('susani-typing-indicator');
    if (el) el.remove();
}

function susaniEscapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}

function susaniFormatMarkdown(text) {
    if (!text) return '';
    let html = text;

    // Escape HTML first
    html = html.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    // Code blocks (\`\`\`...\`\`\`)
    html = html.replace(/\`\`\`(\w*)\n?([\s\S]*?)\`\`\`/g, function(m, lang, code) {
        return '<pre><code>' + code.trim() + '</code></pre>';
    });

    // Inline code
    html = html.replace(/\`([^\`]+)\`/g, '<code>$1</code>');

    // Tables
    html = html.replace(/^(\|.+\|)\n(\|[-:| ]+\|)\n((?:\|.+\|\n?)+)/gm, function(m, header, sep, body) {
        const headers = header.split('|').filter(c => c.trim()).map(c => '<th>' + c.trim() + '</th>');
        const rows = body.trim().split('\n').map(row => {
            const cells = row.split('|').filter(c => c.trim()).map(c => '<td>' + c.trim() + '</td>');
            return '<tr>' + cells.join('') + '</tr>';
        });
        return '<table><thead><tr>' + headers.join('') + '</tr></thead><tbody>' + rows.join('') + '</tbody></table>';
    });

    // Headings
    html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');

    // Bold & italic
    html = html.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');

    // Markdown links [text](url)
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-accent underline hover:text-white">$1</a>');

    // Auto-link URLs (http/https) that are not already inside href
    html = html.replace(/(?<!="|'>)(https?:\/\/[^\s<\)]+)/g, '<a href="$1" target="_blank" class="text-accent underline hover:text-white">$1</a>');

    // Horizontal rules
    html = html.replace(/^---$/gm, '<hr>');

    // Lists (unordered)
    html = html.replace(/^[\-\*] (.+)$/gm, '<li>$1</li>');
    html = html.replace(/((?:<li>.*<\/li>\n?)+)/g, '<ul class="list-disc pl-4 my-2">$1</ul>');

    // Ordered lists
    html = html.replace(/^\d+\. (.+)$/gm, '<li>$1</li>');

    // Line breaks
    html = html.replace(/\n\n/g, '</p><p class="mt-2">');
    html = html.replace(/\n/g, '<br>');

    // Wrap in paragraph if not already block
    if (!html.startsWith('<')) {
        html = '<p>' + html + '</p>';
    }

    // Clean up empty paragraphs
    html = html.replace(/<p><\/p>/g, '');
    html = html.replace(/<p>(<h[1-3]>)/g, '$1');
    html = html.replace(/(<\/h[1-3]>)<\/p>/g, '$1');
    html = html.replace(/<p>(<ul)/g, '$1');
    html = html.replace(/(<\/ul>)<\/p>/g, '$1');
    html = html.replace(/<p>(<table>)/g, '$1');
    html = html.replace(/(<\/table>)<\/p>/g, '$1');
    html = html.replace(/<p>(<pre>)/g, '$1');
    html = html.replace(/(<\/pre>)<\/p>/g, '$1');
    html = html.replace(/<p>(<hr>)<\/p>/g, '$1');

    return html;
}
</script>
