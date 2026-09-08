<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\WpaSignalModel;
use CodeIgniter\API\ResponseTrait;

class SignalCenter extends BaseController
{
    use ResponseTrait;

    protected $wpaSignalModel;

    public function __construct()
    {
        $this->wpaSignalModel = new WpaSignalModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        
        $data = [
            'title' => 'Signal Center',
            'signals' => $this->wpaSignalModel->where('wpa_id', $userId)->orderBy('created_at', 'DESC')->findAll()
        ];
        
        return view('wpa/signal_center/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Signal Baru'
        ];
        
        return view('wpa/signal_center/create', $data);
    }

    public function store()
    {
        $userId = session()->get('userId');
        
        $rules = [
            'pair' => 'required',
            'timeframe' => 'required',
            'type' => 'required|in_list[BUY,SELL]',
            'entry_price' => 'required|numeric',
            'sl' => 'required|numeric',
            'price_points' => 'required|numeric',
            'price_idr' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $base64Image = $this->request->getPost('chart_capture_base64');
        $imagePath = null;
        if ($base64Image && strpos($base64Image, 'data:image/') === 0) {
            list($type, $data) = explode(';', $base64Image);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $fileName = 'chart_' . time() . '_' . uniqid() . '.png';
            $path = FCPATH . 'uploads/signals/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($path . $fileName, $data);
            $imagePath = 'uploads/signals/' . $fileName;
        }

        $data = [
            'wpa_id' => $userId,
            'pair' => $this->request->getPost('pair'),
            'timeframe' => $this->request->getPost('timeframe'),
            'type' => $this->request->getPost('type'),
            'entry_price' => $this->request->getPost('entry_price'),
            'sl' => $this->request->getPost('sl'),
            'tp1' => $this->request->getPost('tp1') ?: null,
            'tp2' => $this->request->getPost('tp2') ?: null,
            'tp3' => $this->request->getPost('tp3') ?: null,
            'description' => $this->request->getPost('description'),
            'ai_review' => $this->request->getPost('ai_review'),
            'price_points' => $this->request->getPost('price_points'),
            'price_idr' => $this->request->getPost('price_idr'),
            'chart_capture' => $imagePath,
            'status' => 'ACTIVE'
        ];

        $this->wpaSignalModel->insert($data);

        return redirect()->to('/wpa/dashboard/signal-center')->with('success', 'Signal berhasil dibuat.');
    }

    public function delete($id)
    {
        $userId = session()->get('userId');
        $signal = $this->wpaSignalModel->where('wpa_id', $userId)->find($id);
        
        if (!$signal) {
            return redirect()->back()->with('error', 'Signal tidak ditemukan.');
        }

        $this->wpaSignalModel->delete($id);
        return redirect()->to('/wpa/dashboard/signal-center')->with('success', 'Signal berhasil dihapus.');
    }

    public function generateAiReview()
    {
        $pair = $this->request->getPost('pair');
        $timeframe = $this->request->getPost('timeframe');
        $type = $this->request->getPost('type');
        $entry_price = $this->request->getPost('entry_price');
        $sl = $this->request->getPost('sl');
        $tp1 = $this->request->getPost('tp1');
        
        $prompt = "Tolong berikan ulasan teknikal profesional dan singkat (maksimal 3 paragraf) sebagai rekomendasi untuk setup trading berikut:\n";
        $prompt .= "Pair: {$pair}\nTimeframe: {$timeframe}\nAction: {$type}\nEntry Price: {$entry_price}\nStop Loss: {$sl}\nTake Profit 1: {$tp1}\n";
        $prompt .= "Fokus pada rasio risk-to-reward dan kondisi pasar secara umum. Gunakan bahasa Indonesia yang baik dan meyakinkan.";

        // GROQ API Call
        $apiKey = env('GROQ_API_KEY') ?: 'gsk_PLACEHOLDER';
        
        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        
        $payload = json_encode([
            "model" => "llama3-8b-8192", // Use a standard fast groq model
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Anda adalah seorang analis pasar keuangan profesional yang memberikan analisis trading singkat dan akurat."
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ],
            "temperature" => 0.7,
            "max_tokens" => 500
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $response) {
            $responseData = json_decode($response, true);
            $review = $responseData['choices'][0]['message']['content'] ?? 'Tidak dapat menghasilkan review saat ini.';
            return $this->respond([
                'success' => true, 
                'review' => trim($review),
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->respond([
            'success' => false, 
            'message' => 'Gagal menghubungi Groq API. Pastikan API key sudah diatur.', 
            'response' => $response,
            'csrf_hash' => csrf_hash()
        ]);
    }
}
