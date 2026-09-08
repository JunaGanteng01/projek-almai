<?php

namespace App\Libraries;

class InvoicePdfService
{
    /**
     * Generate PDF invoice and return file path
     */
    public function generate(array $transaction, array $user, array $items = []): string
    {
        $html = $this->getInvoiceHtml($transaction, $user, $items);
        
        // Create PDF using TCPDF or DOMPDF
        // For now, we'll use a simple HTML to PDF approach
        $filename = 'invoice_' . $transaction['invoice_number'] . '.pdf';
        $filepath = WRITEPATH . 'uploads/invoices/' . $filename;
        
        // Ensure directory exists
        if (!is_dir(WRITEPATH . 'uploads/invoices')) {
            mkdir(WRITEPATH . 'uploads/invoices', 0755, true);
        }
        
        // Check if DOMPDF is available
        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($filepath, $dompdf->output());
        } else {
            // Fallback: save as HTML (you should install dompdf via composer)
            $filepath = WRITEPATH . 'uploads/invoices/' . str_replace('.pdf', '.html', $filename);
            file_put_contents($filepath, $html);
        }
        
        return $filepath;
    }
    
    /**
     * Get invoice HTML template
     */
    protected function getInvoiceHtml(array $transaction, array $user, array $items = []): string
    {
        if (empty($items)) {
            $items = [$transaction];
        }

        $subtotal = 0;
        $totalDiscount = 0;
        $itemsHtml = '';

        foreach ($items as $item) {
            $amount = $item['amount'] ?? $item['total'];
            $subtotal += $amount;
            $totalDiscount += ($item['discount'] ?? 0);

            $itemsHtml .= '
            <tr>
                <td>
                    <strong>' . htmlspecialchars($item['product_name']) . '</strong><br>
                    <span style="color: #666; font-size: 11px;">' . ucfirst($item['product_type'] ?? 'Kelas') . '</span>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">Rp ' . number_format($amount, 0, ',', '.') . '</td>
                <td class="text-right">Rp ' . number_format($amount, 0, ',', '.') . '</td>
            </tr>';
        }

        $totalPaid = $subtotal - $totalDiscount;

        $paidAt = isset($transaction['paid_at']) ? date('d M Y, H:i', strtotime($transaction['paid_at'])) : date('d M Y, H:i');
        $createdAt = isset($transaction['created_at']) ? date('d M Y, H:i', strtotime($transaction['created_at'])) : date('d M Y, H:i');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Invoice ' . $transaction['invoice_number'] . '</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 40px; }
                .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #33e818; padding-bottom: 20px; }
                .logo { font-size: 28px; font-weight: bold; color: #33e818; }
                .logo-sub { font-size: 10px; color: #666; }
                .invoice-title { text-align: right; }
                .invoice-title h1 { font-size: 24px; color: #1a1a1a; margin-bottom: 5px; }
                .invoice-number { font-size: 14px; color: #666; }
                .status-paid { display: inline-block; background: #33e818; color: #000; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 11px; margin-top: 10px; }
                .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
                .info-box { width: 48%; }
                .info-box h3 { font-size: 11px; color: #999; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
                .info-box p { margin-bottom: 5px; line-height: 1.6; }
                .info-box strong { color: #1a1a1a; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                th { background: #f5f5f5; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #666; border-bottom: 2px solid #e0e0e0; }
                td { padding: 15px 12px; border-bottom: 1px solid #eee; }
                .text-right { text-align: right; }
                .total-section { background: #f9f9f9; padding: 20px; border-radius: 8px; }
                .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
                .total-row.final { border-top: 2px solid #33e818; margin-top: 10px; padding-top: 15px; font-size: 16px; font-weight: bold; }
                .total-row.final .amount { color: #33e818; }
                .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #999; font-size: 10px; }
                .footer p { margin-bottom: 5px; }
            </style>
        </head>
        <body>
            <div class="header">
                <div>
                    <div class="logo">ALMAI</div>
                    <div class="logo-sub">E-Learning Trading Platform</div>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="invoice-number">' . $transaction['invoice_number'] . '</div>
                    <div class="status-paid">LUNAS</div>
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-box">
                    <h3>Ditagihkan Kepada</h3>
                    <p><strong>' . htmlspecialchars($user['name']) . '</strong></p>
                    <p>' . htmlspecialchars($user['email']) . '</p>
                    <p>' . htmlspecialchars($user['phone'] ?? '-') . '</p>
                </div>
                <div class="info-box" style="text-align: right;">
                    <h3>Informasi Invoice</h3>
                    <p><strong>Tanggal Invoice:</strong> ' . $createdAt . '</p>
                    <p><strong>Tanggal Bayar:</strong> ' . $paidAt . '</p>
                    <p><strong>Metode:</strong> ' . htmlspecialchars($transaction['payment_method'] ?? 'Xendit') . '</p>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $itemsHtml . '
                </tbody>
            </table>
            
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp ' . number_format($subtotal, 0, ',', '.') . '</span>
                </div>
                ' . ($totalDiscount > 0 ? '
                <div class="total-row">
                    <span>Diskon</span>
                    <span style="color: #ef4444;">- Rp ' . number_format($totalDiscount, 0, ',', '.') . '</span>
                </div>' : '') . '
                <div class="total-row final">
                    <span>Total Dibayar</span>
                    <span class="amount">Rp ' . number_format($totalPaid, 0, ',', '.') . '</span>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>PT. Alma Indonesia Raya</strong></p>
                <p>Jl. Badak Agung No.22 Kav. 3, Kel. Renon, Kec. Denpasar Selatan, Denpasar, Bali 80226</p>
                <p>Email: support@almai.id | Telp: (0361) 3610019</p>
                <p style="margin-top: 15px;">Invoice ini sah dan diproses secara elektronik.</p>
            </div>
        </body>
        </html>';
    }
}
