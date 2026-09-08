<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EaLicenseModel;

class License extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\EaLicenseModel';

    public function verify()
    {
        // Get JSON body or POST data
        $licenseKey = $this->request->getVar('license_key');
        $accountNumber = $this->request->getVar('account_number');

        if (!$licenseKey || !$accountNumber) {
            return $this->failValidationError('License Key dan Akun MT5 wajib diisi.');
        }

        $model = new EaLicenseModel();
        $result = $model->validateLicense($licenseKey, $accountNumber);

        if ($result['valid']) {
            return $this->respond([
                'status' => true,
                'message' => 'License Valid',
                'data' => $result['data']
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => $result['message']
            ], 200); // Return 200 even for fail logic to let EA handle it easily (or 403)
            // But usually EA parses JSON. If 200, it parses body. If 403, Request fails. 
            // Better to return 200 with status=false for logic errors.
        }
    }
}
