<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AkunModel;
use App\Models\KapAiModel;
use CodeIgniter\API\ResponseTrait;

class AuditParser extends BaseController
{
    use ResponseTrait;

    protected $akunModel;
    protected $kapAiModel;

    public function __construct()
    {
        $this->akunModel = new AkunModel();
        $this->kapAiModel = new KapAiModel();
        
        // Auto-run migrations to ensure table exists
        try {
            \Config\Services::migrations()->latest();
        } catch (\Throwable $e) {
            log_message('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $kapAiData = $this->kapAiModel->getWithAkun();
        
        $data = [
            'title' => 'Audit Parser (AI OCR)',
            'activeMenu' => 'audit_parser',
            'savedData' => $kapAiData,
        ];
        return view('keuangan/audit_parser/index', $data);
    }

    public function parse()
    {
        // Berikan waktu ekstra untuk proses PHP agar tidak terputus di tengah jalan (5 Menit)
        set_time_limit(300);
        
        $file = $this->request->getFile('pdf_file');
        
        if (!$file || !$file->isValid() || $file->getExtension() !== 'pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Upload file PDF laporan keuangan yang valid.', 'csrf_hash' => csrf_hash()]);
        }

        $content = file_get_contents($file->getTempName());
        $base64Data = base64_encode($content);

        // Ambil data akun untuk referensi mapping
        $akunList = $this->akunModel->findAll();
        $akunReference = array_map(function($a) {
            return ['kode_akun' => $a['kode_akun'], 'nama_akun' => $a['nama_akun']];
        }, $akunList);

        // Panggil Gemini API langsung dengan PDF
        $parsedData = $this->callGeminiApiWithPdf($base64Data, $akunReference);

        if (!$parsedData) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses data dengan Gemini API.', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'data' => $parsedData, 
            'akun_list' => $akunList, 
            'csrf_hash' => csrf_hash()
        ]);
    }

    private function callGeminiApiWithPdf($base64Pdf, $akunReference)
    {
        $apiKey = trim(getenv('GEMINI_API_KEY'), '"\' ');
        if (empty($apiKey)) {
            log_message('error', 'GEMINI_API_KEY tidak ditemukan di .env');
            return null;
        }

        $akunRefStr = json_encode($akunReference);
        $prompt = "Anda adalah asisten AI akuntansi profesional. 
Tugas Anda adalah membaca Laporan Keuangan format PDF terlampir (Audited/KAP), khususnya bagian NERACA dan LABA RUGI, lalu mengekstrak SEMUA ITEM BARIS RINCIAN beserta nominalnya (hilangkan titik/koma pemisah ribuan).

SANGAT PENTING (ATURAN EKSTRAKSI):
1. JANGAN ekstrak baris Total atau Subtotal (misal: 'Total Aset Lancar', 'Jumlah Liabilitas', 'Laba Kotor', dll). Hanya ekstrak akun rinciannya saja.
2. Jika ada 2 kolom tahun (misal 2023 dan 2022), AMBIL HANYA nominal untuk tahun terbaru (tahun berjalan).
3. Abaikan baris yang hanya berupa header/judul tanpa nominal.
4. Tentukan posisinya ('debit' atau 'kredit') sesuai dengan sifat normal akun tersebut.

Berikan rekomendasi mapping (kode_akun_saran) ke chart of account berikut:
$akunRefStr

FORMAT BALASAN (WAJIB JSON ARRAY, jangan ada markdown text lain):
[
    {
        \"nama_item\": \"Nama akun di PDF\",
        \"nominal\": 1000000,
        \"posisi\": \"debit\",
        \"kode_akun_saran\": \"1-1110\"
    }
]";

        $data = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'inlineData' => [
                                'mimeType' => 'application/pdf',
                                'data' => $base64Pdf
                            ]
                        ],
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'responseMimeType' => 'application/json'
            ]
        ];

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-latest:generateContent';
        
        $maxRetries = 5; // Increased retries
        $attempt = 0;
        $response = null;
        $httpCode = 0;
        $sleepTime = 3; // Initial sleep time in seconds
        
        while ($attempt < $maxRetries) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            // Extend timeout since Gemini can be slow or we are handling a large PDF (5 menit)
            curl_setopt($ch, CURLOPT_TIMEOUT, 300); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-goog-api-key: ' . $apiKey
            ]);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($httpCode === 503 || $httpCode === 429 || $response === false) {
                $attempt++;
                $errorMsg = $response === false ? "cURL Error: $curlError" : "HTTP $httpCode";
                log_message('error', "Gemini API failed ($errorMsg) - Attempt $attempt of $maxRetries. Retrying in $sleepTime seconds...");
                
                if ($attempt < $maxRetries) {
                    sleep($sleepTime);
                    $sleepTime *= 2; // Exponential backoff (3s, 6s, 12s, 24s)
                    continue;
                }
            } else {
                break; // Exit loop on success (200) or other unrecoverable errors (e.g. 400 Bad Request)
            }
        }

        if ($httpCode !== 200) {
            log_message('error', 'Gemini API Error (Final): ' . $response);
            return null;
        }

        $result = json_decode($response, true);
        $jsonStr = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        
        // Membersihkan jika Gemini mengembalikan markdown block 
        $jsonStr = preg_replace('/```json/i', '', $jsonStr);
        $jsonStr = preg_replace('/```/', '', $jsonStr);
        
        return json_decode(trim($jsonStr), true);
    }

    public function save()
    {
        $items = $this->request->getPost('items');
        
        if (empty($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data untuk disimpan.']);
        }

        $kapData = [];
        foreach ($items as $item) {
            if (empty($item['akun_id']) || empty($item['nominal'])) continue;
            
            $tanggal = !empty($item['tanggal']) ? $item['tanggal'] : date('Y-01-01');
            $nominal = (float)$item['nominal'];
            
            $kapData[] = [
                'tanggal'   => $tanggal,
                'nama_item' => $item['nama_item'],
                'akun_id'   => $item['akun_id'],
                'posisi'    => $item['posisi'] == 'kredit' ? 'kredit' : 'debit',
                'nominal'   => abs($nominal),
            ];
        }

        if (!empty($kapData)) {
            $this->kapAiModel->insertBatch($kapData);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan ke tabel KAP-AI.', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.', 'csrf_hash' => csrf_hash()]);
    }
}

