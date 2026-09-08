<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;

class WhatsappGateway extends BaseController
{
    private function getNodeUrl()
    {
        return trim(env('WAGW_URL', 'http://localhost:1337'));
    }

    private function getApiKey()
    {
        return trim(env('WAGW_API_KEY', ''));
    }

    public function index()
    {
        $templateModel = new \App\Models\WhatsappTemplateModel();
        
        $data = [
            'title' => 'WhatsApp Gateway',
            'api_url' => base_url('superadmin/whatsapp-gateway'),
            'templates' => $templateModel->findAll()
        ];

        return view('superadmin/whatsapp_gateway/index', $data);
    }

    private function proxyRequest($endpoint, $method = 'GET', $data = [])
    {
        $ch = curl_init($this->getNodeUrl() . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [];
        $apiKey = $this->getApiKey();
        if ($apiKey) {
            $headers[] = 'x-api-key: ' . $apiKey;
        }

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'Content-Type: application/json';
            }
        }
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            $error = curl_error($ch);
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false, 
                'message' => 'Cannot connect to Node Server: ' . $error,
                'connected' => false,
                'status' => 'OFFLINE'
            ]);
        }

        return $this->response->setStatusCode($httpCode ?: 200)->setBody($response)->setHeader('Content-Type', 'application/json');
    }

    public function status()
    {
        return $this->proxyRequest('/status');
    }

    public function qr()
    {
        return $this->proxyRequest('/qr');
    }

    public function start()
    {
        return $this->proxyRequest('/start', 'POST');
    }

    public function logout()
    {
        return $this->proxyRequest('/logout', 'POST');
    }

    public function sendTest()
    {
        $data = $this->request->getPost();
        
        // Remove CI4 CSRF token before sending to Node.js
        if (isset($data[csrf_token()])) {
            unset($data[csrf_token()]);
        }

        return $this->proxyRequest('/send', 'POST', $data);
    }
    public function templates()
    {
        $model = new \App\Models\WhatsappTemplateModel();
        $data = [
            'title' => 'Template WhatsApp',
            'templates' => $model->findAll()
        ];
        return view('superadmin/whatsapp_gateway/templates', $data);
    }

    public function createTemplate()
    {
        $data = ['title' => 'Tambah Template WhatsApp'];
        return view('superadmin/whatsapp_gateway/create_template', $data);
    }

    public function storeTemplate()
    {
        $model = new \App\Models\WhatsappTemplateModel();
        $data = [
            'nama_template' => $this->request->getPost('nama_template'),
            'isi_pesan' => $this->request->getPost('isi_pesan')
        ];
        if ($model->insert($data)) {
            return redirect()->to('/superadmin/whatsapp-gateway/templates')->with('success', 'Template berhasil disimpan.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan template.');
    }

    public function editTemplate($id)
    {
        $model = new \App\Models\WhatsappTemplateModel();
        $template = $model->find($id);
        if (!$template) return redirect()->to('/superadmin/whatsapp-gateway/templates')->with('error', 'Template tidak ditemukan.');
        $data = ['title' => 'Edit Template WhatsApp', 'template' => $template];
        return view('superadmin/whatsapp_gateway/edit_template', $data);
    }

    public function updateTemplate($id)
    {
        $model = new \App\Models\WhatsappTemplateModel();
        $data = [
            'nama_template' => $this->request->getPost('nama_template'),
            'isi_pesan' => $this->request->getPost('isi_pesan')
        ];
        if ($model->update($id, $data)) {
            return redirect()->to('/superadmin/whatsapp-gateway/templates')->with('success', 'Template berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui template.');
    }

    public function deleteTemplate($id)
    {
        $model = new \App\Models\WhatsappTemplateModel();
        if ($model->delete($id)) {
            return redirect()->to('/superadmin/whatsapp-gateway/templates')->with('success', 'Template berhasil dihapus.');
        }
        return redirect()->to('/superadmin/whatsapp-gateway/templates')->with('error', 'Gagal menghapus template.');
    }
}
