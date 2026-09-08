<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ChatLeadModel;

class ChatBot extends BaseController
{
    use ResponseTrait;

    public function saveLead()
    {
        $chatLeadModel = new ChatLeadModel();

        // Get JSON input
        $json = $this->request->getJSON();

        $data = [];
        $id = null;
        $referralCode = null;

        if (!$json) {
            $data = [
                'name' => $this->request->getPost('name'),
                'whatsapp' => $this->request->getPost('whatsapp') ?? $this->request->getPost('phone'),
                'level' => $this->request->getPost('level'),
                'budget' => $this->request->getPost('budget'),
                'service_interested' => $this->request->getPost('service_interested') ?? $this->request->getPost('message'),
                'referral_code' => $this->request->getPost('referral_code'),
            ];
            $id = $this->request->getPost('id');
            $referralCode = $this->request->getPost('referral_code');
        } else {
            $data = [
                'name' => $json->name ?? null,
                'whatsapp' => $json->whatsapp ?? $json->phone ?? null,
                'level' => $json->level ?? null,
                'budget' => $json->budget ?? null,
                'service_interested' => $json->service_interested ?? $json->message ?? null,
                'referral_code' => $json->referral_code ?? null,
            ];
            $id = $json->id ?? null;
            $referralCode = $json->referral_code ?? null;
        }

        // Remove null values to avoid overwriting with null on updates
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        if ($id) {
            // Update
            if ($chatLeadModel->update($id, $data)) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Lead updated successfully',
                    'id' => $id,
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return $this->failValidationErrors($chatLeadModel->errors());
            }
        } else {
            // Insert
            $data['ip_address'] = $this->request->getIPAddress();
            $leadId = $chatLeadModel->insert($data);

            if ($leadId) {
                // Auto-register user if referral code provided and user doesn't exist
                if (!empty($referralCode) && !empty($data['whatsapp'])) {
                    $this->autoRegisterFromLead($data, $referralCode);
                }

                return $this->respondCreated([
                    'status' => 'success',
                    'message' => 'Lead saved successfully',
                    'id' => $leadId,
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return $this->failValidationErrors($chatLeadModel->errors());
            }
        }
    }

    public function processChat()
    {
        $json = $this->request->getJSON();
        $message = $json->message ?? '';
        $history = $json->history ?? [];

        if (empty($message)) {
            return $this->fail('Message is required');
        }

        $apiKey = env('GROQ_API_KEY');
        $model = env('GROQ_MODEL', 'llama-3.1-8b-instant');

        if (empty($apiKey)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Susani Assistant sedang dalam pemeliharaan (API Key belum dikonfigurasi). Silakan hubungi support.',
            ]);
        }

        $client = \Config\Services::curlrequest();

        // --- FETCH DYNAMIC DATA (Optimized) ---
        $wpaModel = new \App\Models\WpaModel();
        $layananModel = new \App\Models\LayananModel();
        $teamModel = new \App\Models\TeamModel();
        $eventModel = new \App\Models\LayananEventModel();
        $toolsModel = new \App\Models\LayananToolsModel();
        $cwpaModel = new \App\Models\CwpaModel();
        $faqModel = new \App\Models\FaqModel();
        $glossaryModel = new \App\Models\GlossaryModel();
        $legalDocumentModel = new \App\Models\LegalDocumentModel();
        $settingModel = new \App\Models\SettingModel();
        $settings = $settingModel->getAllAsArray();

        // Get active WPAs (Top 5)
        $activeWpas = $wpaModel->where('status', 'active')->orderBy('rating', 'DESC')->limit(5)->findAll();
        $wpaList = "";
        foreach ($activeWpas as $w) {
            $wpaServices = $layananModel->where('wpa_id', $w['id'])->whereIn('status', ['active', 'aktif', 'published'])->limit(2)->findAll();
            $serviceNames = array_column($wpaServices, 'name');
            $serviceString = !empty($serviceNames) ? " [Layanan: " . implode(", ", $serviceNames) . "]" : "";
            $wpaList .= "- {$w['name']} ({$w['specialty']}){$serviceString}\n";
        }

        // Get active CWPA (Top 5)
        $activeCwpas = $cwpaModel->where('status', 'active')->limit(5)->findAll();
        $cwpaList = "";
        foreach ($activeCwpas as $c) {
            $phase = \App\Models\CwpaModel::getPhaseLabel($c['current_phase']);
            $cwpaList .= "- {$c['name']} (Batch {$c['batch']}): {$phase}\n";
        }

        // Get active Team (Max 5)
        $activeTeam = $teamModel->where('is_active', 1)->orderBy('order_number', 'ASC')->limit(5)->findAll();
        $teamList = "";
        foreach ($activeTeam as $t) {
            $teamList .= "- {$t['name']} ({$t['role']})\n";
        }

        // Get upcoming events (Max 3)
        $upcomingEvents = $eventModel->where('status', 'upcoming')->orderBy('event_date', 'ASC')->limit(3)->findAll();
        $eventList = "";
        foreach ($upcomingEvents as $e) {
            $ePrice = $e['price'] > 0 ? "Rp " . number_format($e['price'], 0, ',', '.') : "Poin";
            $eventList .= "- {$e['title']} ({$ePrice})\n";
        }

        $knowledgeBase = $this->buildKnowledgeBase(
            $message,
            $settings,
            $layananModel,
            $faqModel,
            $glossaryModel,
            $legalDocumentModel
        );

        $handbookText = !empty($knowledgeBase['handbook'])
            ? implode("\n", array_map(function ($item) {
                return "[Handbook: " . $item['title'] . "] " . $item['content'];
            }, $knowledgeBase['handbook']))
            : "- Gunakan informasi umum ALMAI jika tidak ada di handbook.";

        $quickReply = $this->buildQuickReply($message, $knowledgeBase);
        if (!empty($quickReply)) {
            return $this->respond([
                'status' => 'success',
                'reply' => $quickReply,
                'csrf_token' => csrf_hash(),
            ]);
        }

        $systemPrompt = "Anda adalah Susani Assistant, asisten Customer Service AI profesional dari ALMAI (almai.id).
        Tugas utama Anda adalah memberikan informasi, membantu kendala teknis, dan mengarahkan pengguna ke solusi atau layanan yang tepat.

        PERSONALITY:
        - Ramah, solutif, proaktif, dan profesional.
        - Gunakan Bahasa Indonesia yang santun tapi tetap modern.

        DATA INTERNAL PLATFORM (Gunakan hanya jika relevan):
        TIM MANAJEMEN: {$teamList}
        MENTOR WPA: {$wpaList}
        PELATIHAN CWPA: {$cwpaList}
        EVENT MENDATANG: {$eventList}

        INFORMASI KONTAK & SUPPORT:
        - CS WhatsApp: {$knowledgeBase['contact']['whatsapp_display']} ({$knowledgeBase['contact']['whatsapp_link']}) (Gunakan ini jika User butuh bantuan manusia/ops/pembayaran manual).
        - Email: {$knowledgeBase['contact']['email']}
        - Alamat Office: {$knowledgeBase['contact']['address']}

        PANDUAN SUPPORT UMUM:
        1. AKUN: Jika user lupa password/tidak bisa login, arahkan ke halaman 'Lupa Password' di: " . base_url('forgot-password') . ".
        2. PEMBAYARAN: Kami menggunakan payment gateway (Xendit) untuk otomatisasi. Jika ada kendala pembayaran, arahkan ke WhatsApp CS dengan bukti transfer.
        3. UPGRADE AKUN: Member bisa upgrade dari User ke PRO atau CWPA melalui halaman Dashboard.
        4. ADVOKASI: Jika user merasa dirugikan dalam trading, arahkan ke link Advokasi: {$knowledgeBase['links']['advokasi']}.

        RESOURCHES & LINK:
        - FAQ (Tanya Jawab): {$knowledgeBase['links']['faq']}
        - Glosarium (Istilah): {$knowledgeBase['links']['glosarium']}
        - Legalitas & Izin: {$knowledgeBase['links']['legalitas']}
        - Daftar Layanan: {$knowledgeBase['links']['layanan']}
        - Tentang Kami: {$knowledgeBase['links']['about']}

        RELEVANSI LAYANAN SAAT INI:
        {$knowledgeBase['servicesText']}

        REFERENSI FAQ/GLOSARIUM RELEVAN:
        {$knowledgeBase['referencesText']}

        PANDUAN RESMI (HANDBOOK ALMAI):
        {$handbookText}

        RULES:
        - Jika tidak tahu jawabannya, jangan mengarang. Sarankan untuk menghubungi CS WhatsApp.
        - Jika user mengeluh (komplain), tanggapi dengan empati dan arahkan ke jalur pengaduan/advokasi.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Add history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg->role === 'bot' ? 'assistant' : 'user',
                'content' => $msg->content
            ];
        }

        // Add current message
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1024,
                ],
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody());

            if (isset($result->choices[0]->message->content)) {
                return $this->respond([
                    'status' => 'success',
                    'reply' => $result->choices[0]->message->content,
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                $errorMessage = $result->error->message ?? 'Gagal mendapatkan respon valid dari AI.';
                return $this->respond([
                    'status' => 'error',
                    'message' => 'AI Error: ' . $errorMessage,
                    'debug' => $result
                ]);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Sistem Error: ' . $e->getMessage()
            ]);
        }
    }

    private function buildKnowledgeBase(string $message, array $settings, \App\Models\LayananModel $layananModel, \App\Models\FaqModel $faqModel, \App\Models\GlossaryModel $glossaryModel, \App\Models\LegalDocumentModel $legalDocumentModel): array
    {
        $db = \Config\Database::connect();
        $keywords = $this->extractKeywords($message);

        $contact = $this->getContactContext($settings);
        $links = [
            'about' => base_url('about'),
            'about_role' => base_url('about/role'),
            'faq' => base_url('faq'),
            'glosarium' => base_url('glosarium'),
            'legalitas' => base_url('legalitas'),
            'rekomendasi' => base_url('rekomendasi'),
            'advokasi' => base_url('advokasi'),
            'layanan' => base_url('layanan'),
        ];

        $services = $this->getServiceRecommendations($layananModel, $db, $keywords, 6);
        $glossaries = $this->getGlossaryMatches($glossaryModel, $keywords, 6);
        $faqs = $this->getFaqMatches($faqModel, $keywords, 5);
        $legalDocs = $this->getLegalMatches($legalDocumentModel, $keywords, 4);
        $handbook = $this->getHandbookMatches($keywords, 6);

        $servicesText = !empty($services)
            ? implode("\n", array_map(function ($item) {
                return '- ' . $item['name'] . ' (' . $item['category'] . ' / ' . $item['subcategory'] . ') - ' . $item['url'];
            }, $services))
            : '- Tidak ada layanan spesifik yang cocok dari konteks saat ini. Gunakan halaman ' . $links['layanan'];

        $references = [];
        foreach ($glossaries as $item) {
            $references[] = 'Glosarium: ' . $item['term'] . ' - ' . $item['definition'] . ' (' . $item['url'] . ')';
        }
        foreach ($faqs as $item) {
            $references[] = 'FAQ: ' . $item['question'] . ' - ' . $item['answer'];
        }
        foreach ($legalDocs as $item) {
            $references[] = 'Legalitas: ' . $item['title'] . ' (' . $item['url'] . ')';
        }

        return [
            'contact' => $contact,
            'links' => $links,
            'services' => $services,
            'servicesText' => $servicesText,
            'glossaries' => $glossaries,
            'faqs' => $faqs,
            'legalDocs' => $legalDocs,
            'referencesText' => !empty($references) ? implode("\n", $references) : '- Tidak ada referensi relevan dari glosarium/FAQ/legalitas.',
            'handbook' => $handbook,
        ];
    }

    private function buildQuickReply(string $message, array $knowledgeBase): ?string
    {
        $normalized = strtolower(trim($message));

        // Greetings & Basic
        if ($this->containsAny($normalized, ['halo', 'hi', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum', 'ping'])) {
            return "Halo! Saya Susani Assistant. Ada yang bisa saya bantu terkait layanan, akun, atau kendala teknis Anda hari ini? 😊";
        }

        // Support Account
        if ($this->containsAny($normalized, ['login', 'masuk', 'lupa password', 'ganti sandi', 'password', 'sandinya', 'daftar akun', 'registrasi'])) {
            return "Untuk kendala akun:\n1. Lupa Password? Klik di sini: " . base_url('forgot-password') . "\n2. Ganti Password? Masuk ke Profile Dashboard Anda.\n3. Masih belum bisa login? Hubungi CS kami di: {$knowledgeBase['contact']['whatsapp_link']}\n4. Belum punya akun? Daftar di sini: " . base_url('register');
        }

        // Support Payment
        if ($this->containsAny($normalized, ['bayar', 'pembayaran', 'cara beli', 'transfer', 'invoice', 'nota', 'xendit', 'beli poin'])) {
            return "ALMAI mendukung pembayaran via Virtual Account, QRIS, dan Bank Transfer (Xendit).\n- Jika sudah membayar tapi belum aktif: Sertakan screenshot bukti transfer dan nomor Invoice ke WhatsApp CS kami.\n- Cara Beli Layanan: Pilih layanan di {$knowledgeBase['links']['layanan']} lalu klik tombol 'Beli' atau 'Daftar'.\n- Beli Poin: Masuk ke Dashboard > Poin > Beli Poin.";
        }

        // Error & technical
        if ($this->containsAny($normalized, ['error', 'rusak', 'tidak bisa', 'kendala', 'masalah', 'bug', 'lemot', 'blank', 'loading'])) {
            return "Mohon maaf atas ketidaknyamanannya. 🙏\nSilakan jelaskan kendala Anda secara detail, atau jika mendesak, Anda bisa menghubungi Technical Support kami via WhatsApp: {$knowledgeBase['contact']['whatsapp_link']} dengan melampirkan screenshot error-nya.";
        }

        if ($this->containsAny($normalized, ['kontak', 'wa', 'whatsapp', 'telpon', 'telepon', 'email', 'alamat'])) {
            return "Kontak resmi ALMAI:\n- WhatsApp CS: {$knowledgeBase['contact']['whatsapp_display']}\n- Email: {$knowledgeBase['contact']['email']}\n- Alamat Office: {$knowledgeBase['contact']['address']}\n- Halaman kontak: {$knowledgeBase['contact']['url']}";
        }

        if ($this->containsAny($normalized, ['tentang', 'about', 'siapa almai', 'profil', 'visi', 'misi', 'tujuan'])) {
            return "ALMAI adalah platform penasihat perdagangan berjangka berbasis teknologi. Visi kami adalah menjadi platform terpercaya dalam pendampingan trader. Informasi lengkap ada di {$knowledgeBase['links']['about']}.";
        }

        if ($this->containsAny($normalized, ['poin', 'point', 'almaipoin', 'tukar poin', 'hadiah', 'reward'])) {
            return "Almai Poin adalah sistem loyalitas kami. Anda bisa mendapatkan poin dari transaksi, referral, atau event, lalu menukarkannya dengan merchandise atau voucher diskon. Cek poin Anda di Dashboard.";
        }

        if ($this->containsAny($normalized, ['faq', 'pertanyaan umum', 'tanya jawab'])) {
            return "FAQ ALMAI ada di {$knowledgeBase['links']['faq']}. Jika Anda mau, saya juga bisa bantu cari jawaban dari pertanyaan spesifiknya.";
        }

        if ($this->containsAny($normalized, ['legalitas', 'izin', 'resmi', 'perjanjian', 'risiko'])) {
            return "Dokumen legalitas dan perjanjian ada di {$knowledgeBase['links']['legalitas']}. Untuk perjanjian jasa: " . base_url('legalitas/perjanjian-pemberian-jasa') . " dan risiko: " . base_url('legalitas/dokumen-pemberitahuan-risiko') . ".";
        }

        if ($this->containsAny($normalized, ['rekomendasi', 'testimoni', 'review', 'ulasan'])) {
            return "Halaman rekomendasi/testimoni ada di {$knowledgeBase['links']['rekomendasi']}.";
        }

        if ($this->containsAny($normalized, ['advokasi', 'pengaduan', 'keluhan'])) {
            return "Layanan advokasi ada di {$knowledgeBase['links']['advokasi']} dan pengaduan di " . base_url('daftar-advokasi') . ".";
        }

        if ($this->containsAny($normalized, ['glosarium', 'istilah', 'arti', 'definisi', 'apa itu'])) {
            if (!empty($knowledgeBase['glossaries'])) {
                $top = $knowledgeBase['glossaries'][0];
                return "Saya menemukan istilah terkait: {$top['term']} - {$top['definition']} ({$top['url']}). Untuk daftar lengkap, buka {$knowledgeBase['links']['glosarium']}.";
            }

            return "Glosarium ALMAI ada di {$knowledgeBase['links']['glosarium']}.";
        }

        if ($this->containsAny($normalized, ['layanan', 'produk', 'kelas', 'artikel', 'event', 'webinar', 'workshop', 'tool', 'ea'])) {
            if (!empty($knowledgeBase['services'])) {
                $items = array_slice($knowledgeBase['services'], 0, 3);
                $lines = array_map(function ($item) {
                    return '- ' . $item['name'] . ' (' . $item['url'] . ')';
                }, $items);

                return "Berikut layanan yang relevan:\n" . implode("\n", $lines) . "\nLihat semua layanan: {$knowledgeBase['links']['layanan']}";
            }

            return "Daftar layanan ada di {$knowledgeBase['links']['layanan']}.";
        }

        return null;
    }

    private function getContactContext(array $settings): array
    {
        $whatsapp = $settings['whatsapp_cs'] ?? '6285117307800';
        $email = $settings['email_cs'] ?? 'info@almai.id';

        return [
            'whatsapp' => $whatsapp,
            'whatsapp_display' => '+62 ' . ltrim(substr($whatsapp, 2), '0'),
            'whatsapp_link' => 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp),
            'email' => $email,
            'address' => 'Jl. Badak Agung No. 22 Kav. 3, Kel. Renon, Denpasar - Bali 80226',
            'url' => base_url('kontak'),
        ];
    }

    private function getServiceRecommendations(\App\Models\LayananModel $layananModel, \CodeIgniter\Database\BaseConnection $db, array $keywords, int $limit = 6): array
    {
        $results = [];
        $tables = [
            'layanan_artikel' => [
                'titleField' => 'title',
                'category' => 'Advokasi',
                'subcategory' => 'Artikel',
                'type' => 'artikel',
                'urlPrefix' => 'layanan/',
                'statusField' => 'status',
                'statuses' => ['published', 'active', 'aktif'],
            ],
            'layanan_event' => [
                'titleField' => 'title',
                'category' => 'Advokasi',
                'subcategory' => 'Event',
                'type' => 'event',
                'urlPrefix' => 'layanan/',
                'statusField' => 'status',
                'statuses' => ['active', 'aktif', 'upcoming', 'published'],
            ],
            'layanan_tools' => [
                'titleField' => 'name',
                'category' => 'Expert Advisor',
                'subcategory' => 'Tool',
                'type' => 'tool',
                'urlPrefix' => 'layanan/',
                'statusField' => 'status',
                'statuses' => ['active', 'aktif', 'published'],
            ],
            'layanan_subscription' => [
                'titleField' => 'name',
                'category' => 'Ultimate',
                'subcategory' => 'Subscription',
                'type' => 'subscription',
                'urlPrefix' => 'layanan/',
                'statusField' => 'status',
                'statuses' => ['active', 'aktif', 'published'],
            ],
            'layanan' => [
                'titleField' => 'name',
                'category' => 'Layanan',
                'subcategory' => 'Layanan',
                'type' => 'layanan',
                'urlPrefix' => 'layanan/',
                'statusField' => 'status',
                'statuses' => ['active', 'aktif', 'published'],
            ],
        ];

        foreach ($tables as $table => $config) {
            $builder = $db->table($table);
            $builder->select(($config['titleField'] . ' as title, slug, ' . $config['statusField'] . ' as status'));
            if ($db->fieldExists('wpa_id', $table)) {
                $builder->select('wpa_id');
            }
            if ($db->fieldExists('cwpa_id', $table)) {
                $builder->select('cwpa_id');
            }

            $builder->whereIn($config['statusField'], $config['statuses']);

            if (!empty($keywords)) {
                $builder->groupStart();
                foreach ($keywords as $keyword) {
                    $builder->orLike($config['titleField'], $keyword);
                    if ($table === 'layanan_artikel') {
                        $builder->orLike('excerpt', $keyword);
                    } else {
                        $builder->orLike('description', $keyword);
                    }
                }
                $builder->groupEnd();
            }

            $rows = $builder->orderBy('id', 'DESC')->limit($limit)->get()->getResultArray();
            foreach ($rows as $row) {
                $results[] = [
                    'name' => $row['title'],
                    'category' => $config['category'],
                    'subcategory' => $config['subcategory'],
                    'url' => base_url($config['urlPrefix'] . $row['slug']),
                ];
            }
        }

        return array_slice($results, 0, $limit);
    }

    private function getGlossaryMatches(\App\Models\GlossaryModel $glossaryModel, array $keywords, int $limit = 6): array
    {
        if (empty($keywords)) {
            $items = $glossaryModel->orderBy('term', 'ASC')->findAll($limit);
        } else {
            $builder = $glossaryModel->orderBy('term', 'ASC');
            $builder->groupStart();
            foreach ($keywords as $keyword) {
                $builder->orLike('term', $keyword);
                $builder->orLike('definition', $keyword);
                $builder->orLike('short_description', $keyword);
            }
            $builder->groupEnd();
            $items = $builder->findAll($limit);
        }

        $results = [];
        foreach ($items as $item) {
            $results[] = [
                'term' => $item->term,
                'definition' => $item->short_description ?: $item->definition,
                'url' => base_url('glosarium/' . $item->slug),
            ];
        }

        return $results;
    }

    private function getFaqMatches(\App\Models\FaqModel $faqModel, array $keywords, int $limit = 5): array
    {
        $builder = $faqModel->orderBy('id', 'ASC');
        if (!empty($keywords)) {
            $builder->groupStart();
            foreach ($keywords as $keyword) {
                $builder->orLike('question', $keyword);
                $builder->orLike('answer', $keyword);
            }
            $builder->groupEnd();
        }

        $items = $builder->findAll($limit);
        return array_map(function ($item) {
            return [
                'question' => $item['question'],
                'answer' => $item['answer'],
            ];
        }, $items);
    }

    private function getLegalMatches(\App\Models\LegalDocumentModel $legalDocumentModel, array $keywords, int $limit = 4): array
    {
        $builder = $legalDocumentModel->where('is_active', 1)->orderBy('id', 'DESC');
        if (!empty($keywords)) {
            $builder->groupStart();
            foreach ($keywords as $keyword) {
                $builder->orLike('title', $keyword);
                $builder->orLike('content', $keyword);
                $builder->orLike('type', $keyword);
            }
            $builder->groupEnd();
        }

        $items = $builder->findAll($limit);
        $results = [];
        foreach ($items as $item) {
            $results[] = [
                'title' => $item['title'],
                'slug' => $item['slug'],
                'url' => base_url('legalitas/' . $item['slug']),
            ];
        }

        return $results;
    }

    /**
     * Get matches from Almai Handbook (Sync with app/Views/pages/handbook.php)
     */
    private function getHandbookMatches(array $keywords, int $limit = 6): array
    {
        $handbook = [
            [
                'title' => 'Visi & Misi',
                'content' => 'Visi: Menjadi platform terpercaya dalam pendampingan trader berbasis profesional dan teknologi. Misi: Meningkatkan literasi trading, menyediakan tenaga ahli, mengembangkan teknologi (AIWE/BIDBOX), dan memberikan perlindungan trader.'
            ],
            [
                'title' => 'Nilai Perusahaan (Trusted, Specialist, Efficient)',
                'content' => 'Trusted: Integritas dan transparansi. Specialist: Tenaga ahli tersertifikasi. Efficient: Layanan cepat berbasis teknologi terintegrasi.'
            ],
            [
                'title' => 'Layanan Utama',
                'content' => 'Advokasi & Mentoring (pendampingan kasus, konsultasi), Ultimate & Teknologi (mentor personal, trading plan, AIWE, dan BIDBOX).'
            ],
            [
                'title' => 'Struktur Level User',
                'content' => 'Jenjang karir di ALMAI: User → User Pro → CWPA (Calon Wakil Penasihat Berjangka) → WPA (Wakil Penasihat Berjangka).'
            ],
            [
                'title' => 'Sistem Event Mingguan',
                'content' => 'Kamis: Audit (Strategi), Jumat: Control (Psikologi & Risk), Sabtu: Defend (Legal & Perlindungan).'
            ],
            [
                'title' => 'Peran WPA',
                'content' => 'Tugas WPA: Memberikan nasihat, menyusun strategi, dan mendampingi klien. Larangan: Tidak boleh menjanjikan profit pasti dan tidak mengelola dana nasabah (dana tetap di broker).'
            ],
            [
                'title' => 'Almai Poin',
                'content' => 'Sistem reward loyalitas. Poin didapat dari beli layanan, referral, atau event. Bisa ditukar Merchandise, Voucher diskon, atau akses premium.'
            ],
            [
                'title' => 'Kebijakan Risiko',
                'content' => 'Trading memiliki risiko volatilitas pasar dan finansial. ALMAI memberikan edukasi, namun risiko sepenuhnya tanggung jawab user. ALMAI bukan broker.'
            ],
            [
                'title' => 'Trading & Dana',
                'content' => 'Almai tidak mengelola dana user. Seluruh dana berada di akun broker masing-masing user. Almai fokus pada edukasi dan pendampingan.'
            ],
            [
                'title' => 'Kode Etik',
                'content' => 'Menjaga kerahasiaan data, transparan, tidak manipulatif, dan patuh pada regulasi.'
            ],
            [
                'title' => 'Pengembangan Karir',
                'content' => 'ALMAI memfasilitasi sertifikasi WPA, training internal, dan upgrade skill bagi member CWPA.'
            ],
            [
                'title' => 'Roadmap 8 Tahap WPA',
                'content' => 'Tahapan menjadi WPA (Penasihat Derivatif & Aset Keuangan Digital): 01. Almai | Pendampingan, 02. Bursa ICDX | Sertifikasi Multilateral, 03. LPK | Pelatihan & Sertifikasi PBK, 04. BNSP | Sertifikasi Kompetensi PBK, 05. BAPPEBTI | Sertifikasi Profesi-TLUP, 06. Almai | BAPPEBTI | Izin WPA, 07. Almai | OJK | Penasihat Investasi, 08. Almai | BI | Penasihat Derivatif PUVA. Link daftar: https://almai.id/daftar-wpa'
            ]
        ];

        if (empty($keywords)) {
            return array_slice($handbook, 0, $limit);
        }

        $matches = [];
        foreach ($handbook as $item) {
            $found = false;
            foreach ($keywords as $keyword) {
                if (stripos($item['title'], $keyword) !== false || stripos($item['content'], $keyword) !== false) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $matches[] = $item;
            }
        }

        return !empty($matches) ? array_slice($matches, 0, $limit) : array_slice($handbook, 0, 2);
    }

    private function extractKeywords(string $message): array
    {
        $message = strtolower(trim($message));
        $message = preg_replace('/[^a-z0-9\s]/i', ' ', $message);
        $tokens = preg_split('/\s+/', $message) ?: [];
        $stopWords = ['apa', 'itu', 'dan', 'yang', 'di', 'ke', 'dari', 'untuk', 'dengan', 'saya', 'mau', 'cari', 'tolong', 'bisa', 'ada', 'pada', 'tentang', 'cara'];
        $keywords = [];

        foreach ($tokens as $token) {
            if (strlen($token) < 3) {
                continue;
            }
            if (in_array($token, $stopWords, true)) {
                continue;
            }
            $keywords[] = $token;
        }

        return array_values(array_unique($keywords));
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    private function autoRegisterFromLead($leadData, $referralCode)
    {
        $userModel = new \App\Models\UserModel();

        // Check if user already exists by phone
        $existingUser = $userModel->where('phone', $leadData['whatsapp'])->first();
        if ($existingUser) {
            return; // User already exists, skip registration
        }

        // Generate email from phone if not provided
        $email = 'lead_' . $leadData['whatsapp'] . '@almai.id';

        // Check if email exists
        if ($userModel->where('email', $email)->first()) {
            return; // Email already exists
        }

        // Find referrer
        $referrer = $userModel->findByReferralCode($referralCode);
        if (!$referrer) {
            return; // Invalid referral code
        }

        // Generate random password
        $password = bin2hex(random_bytes(8));

        // Create user
        $userId = $userModel->insert([
            'name' => $leadData['name'],
            'email' => $email,
            'phone' => $leadData['whatsapp'],
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'level_id' => \App\Models\LevelModel::LEVEL_USER,
            'status' => 'active',
            'affiliator_code' => $referralCode,
        ]);

        // Process referral bonus
        if ($userId) {
            $poinService = new \App\Libraries\PoinService();
            $poinService->processReferralRegistration($userId, $referrer['id']);
        }
    }
}
