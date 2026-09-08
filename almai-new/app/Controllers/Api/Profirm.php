<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProfirmEaAccountModel;
use App\Models\ProfirmEaTradeModel;

class Profirm extends BaseController
{
    use ResponseTrait;

    public function update()
    {
        $request = service('request');
        $data = $request->getPost();

        // MQL5 sering mengirim JSON di Body, bukan POST standar
        if (empty($data)) {
            $body = $request->getBody();
            if (!empty($body)) {
                $decoded = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                }
            }
        }

        // Catat log untuk debbuging jika diperlukan (Opsional)
        // log_message('debug', 'API Profirm Receive: ' . json_encode($data));

        if (empty($data)) {
            return $this->fail('No data received from EA. Body was: ' . $request->getBody(), 400);
        }

        // Validate required fields
        if (!isset($data['account_login'])) {
            return $this->fail('Field "account_login" is required.', 400);
        }

        $model = new ProfirmEaAccountModel();
        
        $login = $data['account_login'];
        $existing = $model->where('account_login', $login)->first();

        // Data array to insert/update
        $updateData = [
            'account_login'     => $data['account_login'] ?? '',
            'account_name'      => $data['account_name'] ?? 'Unknown',
            'broker'            => $data['broker'] ?? '',
            'balance'           => $data['balance'] ?? 0,
            'equity'            => $data['equity'] ?? 0,
            'total_profit'      => $data['total_profit'] ?? 0, 
            'total_deposits'    => $data['total_deposits'] ?? 0,
            'total_withdrawals' => $data['total_withdrawals'] ?? 0,
            'margin'            => $data['margin'] ?? 0,
            'free_margin'       => $data['free_margin'] ?? 0,
            'margin_level'      => $data['margin_level'] ?? 0,
            'open_trades'       => $data['open_trades'] ?? 0,
            'server'            => $data['server'] ?? '',
            'currency'          => $data['currency'] ?? 'USD',
            'leverage'          => $data['leverage'] ?? 100,
            'profirm'           => $data['profirm'] ?? 'Unknown' 
        ];

        if (!$existing) {
            return $this->fail('Maaf, nomor akun ' . $login . ' belum terdaftar. Silakan hubungkan akun trading via Dashboard (isi form portofolio) terlebih dahulu agar data bisa diproses.');
        }

        $model->update($existing['id'], $updateData);

        // --- Handle Trades (Real History) ---
        if (isset($data['trades']) && is_array($data['trades'])) {
            $tradeModel = new ProfirmEaTradeModel();
            foreach ($data['trades'] as $trade) {
                // Upsert trade based on account_login and ticket
                $existingTrade = $tradeModel->where('account_login', $login)
                                           ->where('ticket', $trade['ticket'])
                                           ->first();
                
                $tradeData = [
                    'account_login' => $login,
                    'ticket'        => $trade['ticket'],
                    'symbol'        => $trade['symbol'] ?? '',
                    'type'          => strtoupper($trade['type'] ?? 'BUY'),
                    'lots'          => $trade['lots'] ?? 0,
                    'open_price'    => $trade['open_price'] ?? 0,
                    'close_price'   => $trade['close_price'] ?? 0,
                    'sl'            => $trade['sl'] ?? 0,
                    'tp'            => $trade['tp'] ?? 0,
                    'swap'          => $trade['swap'] ?? 0,
                    'profit'        => $trade['profit'] ?? 0,
                    'open_time'     => $trade['open_time'] ?? null,
                    'close_time'    => $trade['close_time'] ?? null,
                    'slippage'      => $trade['slippage'] ?? 0,
                    'execution_speed' => $trade['execution_speed'] ?? 0,
                ];

                if ($existingTrade) {
                    $tradeModel->update($existingTrade['id'], $tradeData);
                } else {
                    $tradeModel->insert($tradeData);
                }
            }
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Account ' . $login . ' data processed successfully'
        ], 200);
    }
}
