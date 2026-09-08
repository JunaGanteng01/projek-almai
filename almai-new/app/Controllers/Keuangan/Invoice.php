<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\CustomerInvoiceModel;
use App\Services\Keuangan\JournalService;

class Invoice extends BaseController
{
    public function index()
    {
        $invoiceModel = new CustomerInvoiceModel();

        // Filters
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $query = $invoiceModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('invoice_number', $search)
                ->orLike('customer_name', $search)
                ->orLike('customer_email', $search)
                ->orLike('product_name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $query = $query->where('status', $status);
        }

        if ($dateFrom) {
            $query = $query->where('tanggal >=', $dateFrom);
        }

        if ($dateTo) {
            $query = $query->where('tanggal <=', $dateTo);
        }

        $invoices = $query->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(20, 'default');

        // Stats
        $totalTagihan = $invoiceModel->selectSum('total')->first()['total'] ?? 0;
        $totalPending = $invoiceModel->where('status', 'pending')->countAllResults();
        $totalPaid = $invoiceModel->where('status', 'paid')->countAllResults();
        $totalCancelled = $invoiceModel->where('status', 'cancelled')->countAllResults();

        $data = [
            'title' => 'Invoice Customer',
            'activeMenu' => 'invoices',
            'invoices' => $invoices,
            'pager' => $invoiceModel->pager,
            'totalTagihan' => $totalTagihan,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
            'totalCancelled' => $totalCancelled,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ];

        return view('keuangan/invoices/index', $data);
    }

    public function create()
    {
        return view('keuangan/invoices/create', [
            'title' => 'Tambah Invoice Customer',
            'activeMenu' => 'invoices',
            'invoice' => null
        ]);
    }

    public function edit(string $id)
    {
        $invoiceModel = new CustomerInvoiceModel();
        $invoice = $invoiceModel->find($id);

        if (!$invoice) {
            return redirect()->to(base_url('keuangan/invoices'))->with('error', 'Invoice tidak ditemukan.');
        }

        return view('keuangan/invoices/create', [
            'title' => 'Edit Invoice Customer',
            'activeMenu' => 'invoices',
            'invoice' => $invoice
        ]);
    }

    public function generateFaktur()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $time = strtotime($date);

        $day = date('d', $time);
        $month = intval(date('m', $time));
        $shortYear = date('y', $time);

        $romanMonth = $this->integerToRoman($month);

        $invoiceModel = new CustomerInvoiceModel();
        // Look up the latest invoice prefix in customer_invoices table
        $latest = $invoiceModel->select('invoice_number')
            ->like('invoice_number', 'INV/ALMAI/', 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $nextNum = 1;
        if ($latest) {
            $parts = explode('/', $latest['invoice_number']);
            if (count($parts) >= 3) {
                $nextNum = intval($parts[2]) + 1;
            }
        }

        $nomorStr = str_pad($nextNum, 4, '0', STR_PAD_LEFT);
        $invoiceNumber = "INV/ALMAI/{$nomorStr}/{$day}/{$romanMonth}/{$shortYear}/";

        return $this->response->setJSON([
            'success' => true,
            'invoice_number' => $invoiceNumber
        ]);
    }

    public function save()
    {
        $invoiceModel = new CustomerInvoiceModel();

        $id = $this->request->getPost('id');
        $invoiceNumber = $this->request->getPost('invoice_number');
        $tanggal = $this->request->getPost('tanggal');
        $jatuhTempo = $this->request->getPost('jatuh_tempo');
        $customerName = $this->request->getPost('customer_name');
        $customerEmail = $this->request->getPost('customer_email');
        $customerPhone = $this->request->getPost('customer_phone');
        $customerAddress = $this->request->getPost('customer_address');
        $currency = $this->request->getPost('currency') ?: 'IDR';
        $ppnPercent = (float) $this->request->getPost('ppn_percent');
        $status = $this->request->getPost('status') ?: 'pending';
        $notes = $this->request->getPost('notes');

        // Process itemized array
        $itemNames = $this->request->getPost('item_name') ?: [];
        $itemQties = $this->request->getPost('item_qty') ?: [];
        $itemPrices = $this->request->getPost('item_price') ?: [];

        $itemsList = [];
        $calculatedSubtotal = 0;

        for ($i = 0; $i < count($itemNames); $i++) {
            if (empty(trim($itemNames[$i]))) continue;
            $qty = max(1, (float) ($itemQties[$i] ?? 1));
            $price = max(0, (float) ($itemPrices[$i] ?? 0));
            $rowTotal = $qty * $price;
            
            $calculatedSubtotal += $rowTotal;
            $itemsList[] = [
                'name' => trim($itemNames[$i]),
                'qty' => $qty,
                'price' => $price,
                'total' => $rowTotal
            ];
        }

        // Product name summary
        $productName = !empty($itemsList) ? $itemsList[0]['name'] : 'Invoice Jasa';
        if (count($itemsList) > 1) {
            $productName .= ' & ' . (count($itemsList) - 1) . ' item lainnya';
        }

        // Calculate PPN and Total
        $ppnNominal = $calculatedSubtotal * ($ppnPercent / 100);
        $total = $calculatedSubtotal + $ppnNominal;

        $data = [
            'invoice_number' => $invoiceNumber,
            'tanggal' => $tanggal,
            'jatuh_tempo' => $jatuhTempo ?: null,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail ?: null,
            'customer_phone' => $customerPhone ?: null,
            'customer_address' => $customerAddress ?: null,
            'product_name' => $productName,
            'currency' => $currency,
            'items' => json_encode($itemsList),
            'subtotal' => $calculatedSubtotal,
            'ppn' => $ppnPercent,
            'total' => $total,
            'status' => $status,
            'notes' => $notes
        ];

        // Hook real-time automated journaling upon customer payment status transitions to 'paid'
        $shouldJournal = false;
        if ($status === 'paid') {
            if (!$id) {
                $shouldJournal = true;
            } else {
                $oldInvoice = $invoiceModel->find($id);
                if ($oldInvoice && $oldInvoice['status'] !== 'paid') {
                    $shouldJournal = true;
                }
            }
        }

        try {
            if ($id) {
                $invoiceModel->update($id, $data);
                $invoiceId = $id;
                $msg = "Invoice customer berhasil diperbarui.";
            } else {
                $invoiceId = $invoiceModel->insert($data);
                if (!$invoiceId) {
                    $invoiceId = $invoiceModel->getInsertID();
                }
                $msg = "Invoice customer berhasil dibuat.";
            }

            if ($shouldJournal) {
                $saved = $invoiceModel->find($invoiceId);
                $jr = (new JournalService())->postInvoicePaid($saved ?: array_merge($data, ['id' => $invoiceId, 'invoice_number' => $invoiceNumber]));
                if (!$jr['ok']) {
                    return redirect()->to(base_url('keuangan/invoices'))->with('error', $msg . ' — jurnal: ' . ($jr['message'] ?? ''));
                }
            }

            // Send WhatsApp Notification if requested
            $sendWa = $this->request->getPost('send_wa') === '1';
            if ($sendWa && !empty($customerPhone)) {
                try {
                    $readableStatus = 'Menunggu Pembayaran';
                    if ($status === 'paid') {
                        $readableStatus = 'Lunas';
                    } elseif ($status === 'cancelled') {
                        $readableStatus = 'Dibatalkan';
                    }

                    if ($currency === 'USD') {
                        $formattedTotal = '$ ' . number_format($total, 2, '.', ',');
                    } else {
                        $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');
                    }
                    $hash = substr(md5($invoiceId . 'PT_ALMA_INDONESIA_RAYA_SECRET_SALT_2026'), 0, 10);
                    $invoiceLink = base_url('invoice/c/' . $invoiceId . '/' . $hash);

                    \App\Libraries\Balesotomatis::configure(
                        env('BALESOTOMATIS_SECRET_KEY', ''),
                        env('BALESOTOMATIS_LICENSES_KEY', '')
                    );

                    $message  = "🧾 *Invoice Pembelian*\n\n";
                    $message .= "Halo *{$customerName}*,\n\n";
                    $message .= "Status  : {$readableStatus}\n";
                    $message .= "Produk  : {$productName}\n";
                    $message .= "Total   : {$formattedTotal}\n";
                    $message .= "Invoice : {$invoiceLink}\n\n";
                    $message .= "Terima kasih telah berbelanja di *Almai* 🇮🇩";

                    $res = \App\Libraries\Balesotomatis::sendPersonalMessage(
                        $customerPhone,
                        $message,
                        'whatsapp'
                    );

                    if (!$res['success']) {
                        log_message('error', 'Failed to send Customer Invoice via Balesotomatis: ' . ($res['error'] ?? 'Unknown Error'));
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send Customer Invoice WA: ' . $e->getMessage());
                }
            }

            return redirect()->to(base_url('keuangan/invoices'))->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan invoice: ' . $e->getMessage());
        }
    }

    public function get(string $id)
    {
        $invoiceModel = new CustomerInvoiceModel();
        $invoice = $invoiceModel->find($id);

        if ($invoice) {
            return $this->response->setJSON(['success' => true, 'data' => $invoice]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        }
    }

    public function delete(string $id)
    {
        $invoiceModel = new CustomerInvoiceModel();
        try {
            $invoiceModel->delete($id);
            return redirect()->to(base_url('keuangan/invoices'))->with('success', 'Invoice customer berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('keuangan/invoices'))->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    public function detail(string $id, ?string $hash = null)
    {
        $uri = service('request')->getUri();
        $path = $uri->getPath();

        // Enforce secure hash verification for public-facing route to prevent IDOR attacks
        if (strpos($path, 'invoice/c/') !== false) {
            $expectedHash = substr(md5($id . 'PT_ALMA_INDONESIA_RAYA_SECRET_SALT_2026'), 0, 10);
            if ($hash !== $expectedHash) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Invoice customer tidak valid.");
            }
        }

        $invoiceModel = new CustomerInvoiceModel();
        $invoice = $invoiceModel->find($id);

        if (!$invoice) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Invoice customer dengan ID #{$id} tidak ditemukan.");
        }

        // Decode itemized list
        $items = [];
        if (!empty($invoice['items'])) {
            $items = json_decode($invoice['items'], true);
        }

        // Fallback to single item if items array is empty
        if (empty($items)) {
            $items[] = [
                'name' => $invoice['product_name'],
                'qty' => 1,
                'price' => $invoice['subtotal'],
                'total' => $invoice['subtotal']
            ];
        }

        $ppnPercent = (float) $invoice['ppn'];
        $subtotal = (float) $invoice['subtotal'];
        $ppnNominal = $subtotal * ($ppnPercent / 100);
        $total = $subtotal + $ppnNominal;

        $invoice['ppn_nominal'] = $ppnNominal;
        $invoice['total'] = $total;

        return view('keuangan/invoices/detail', [
            'title' => 'Detail Invoice Customer',
            'invoice' => $invoice,
            'items' => $items
        ]);
    }

    private function integerToRoman(int $integer)
    {
        $integer = intval($integer);
        $result = '';

        $lookup = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        foreach ($lookup as $roman => $value) {
            $matches = intval($integer / $value);
            $result .= str_repeat($roman, $matches);
            $integer = $integer % $value;
        }

        return $result;
    }
}
