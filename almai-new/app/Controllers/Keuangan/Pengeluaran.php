<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\PembelianModel;
use App\Models\SupplierModel;
use App\Services\Keuangan\JournalService;

class Pengeluaran extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('pembelian')) {
            return view('keuangan/pengeluaran/index', [
                'title' => 'Pengeluaran',
                'activeMenu' => 'pengeluaran',
                'pengeluaranList' => [],
                'pager' => null,
                'totalPengeluaran' => 0,
                'totalPending' => 0,
                'totalLunas' => 0,
                'countPending' => 0,
                'currentSearch' => '',
                'currentStatus' => '',
                'dateFrom' => '',
                'dateTo' => '',
                'suppliers' => []
            ]);
        }

        $pembelianModel = new PembelianModel();

        // Filtering
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $query = $pembelianModel->select('pembelian.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = pembelian.supplier_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('pembelian.no_faktur', $search)
                ->orLike('pembelian.keterangan', $search)
                ->orLike('suppliers.name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $query->where('pembelian.status', $status);
        }

        if ($dateFrom) {
            $query->where('pembelian.tanggal >=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('pembelian.tanggal <=', $dateTo);
        }

        $pembelianList = $query->orderBy('pembelian.tanggal', 'DESC')->paginate(20);

        // Stats
        $totalPengeluaran = $pembelianModel->selectSum('total')->first()['total'] ?? 0;
        $totalPending = $pembelianModel->where('status', 'pending')->selectSum('total')->first()['total'] ?? 0;
        $totalLunas = $pembelianModel->where('status', 'lunas')->selectSum('total')->first()['total'] ?? 0;
        $countPending = $pembelianModel->where('status', 'pending')->countAllResults();

        // Get suppliers for dropdown
        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->orderBy('name', 'ASC')->findAll();

        $data = [
            'title' => 'Pengeluaran',
            'activeMenu' => 'pengeluaran',
            'pengeluaranList' => $pembelianList,
            'pager' => $pembelianModel->pager,
            'totalPengeluaran' => $totalPengeluaran,
            'totalPending' => $totalPending,
            'totalLunas' => $totalLunas,
            'countPending' => $countPending,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'suppliers' => $suppliers
        ];

        return view('keuangan/pengeluaran/index', $data);
    }

    public function save()
    {
        $pembelianModel = new PembelianModel();
        $id = $this->request->getPost('id');

        $supplierName = trim($this->request->getPost('supplier_name') ?? '');
        $supplierId = null;

        if (!empty($supplierName)) {
            $supplierModel = new SupplierModel();
            // Case-insensitive search
            $supplier = $supplierModel->where('LOWER(name)', strtolower($supplierName))->first();
            if ($supplier) {
                $supplierId = $supplier['id'];
            } else {
                $supplierId = $supplierModel->insert([
                    'name' => $supplierName,
                    'status' => 'active'
                ]);
            }
        }

        $data = [
            'no_faktur' => $this->request->getPost('no_faktur'),
            'tanggal' => $this->request->getPost('tanggal'),
            'supplier_id' => $supplierId,
            'subtotal' => $this->request->getPost('subtotal'),
            'ppn' => $this->request->getPost('ppn') ?? 0,
            'total' => $this->request->getPost('total'),
            'status' => $this->request->getPost('status'),
            'jatuh_tempo' => $this->request->getPost('jatuh_tempo'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // Hook real-time automated journaling upon purchase settlement status transitions to 'lunas'
        $shouldJournal = false;
        if ($data['status'] === 'lunas') {
            if (!$id) {
                $shouldJournal = true;
            } else {
                $oldPembelian = $pembelianModel->find($id);
                if ($oldPembelian && $oldPembelian['status'] !== 'lunas') {
                    $shouldJournal = true;
                }
            }
        }

        try {
            if ($id) {
                $pembelianModel->update($id, $data);
                $msg = 'Data pengeluaran berhasil diperbarui';
            } else {
                $pembelianModel->insert($data);
                $msg = 'Data pengeluaran berhasil ditambahkan';
            }

            if ($shouldJournal) {
                $jr = (new JournalService())->postPengeluaranLunas(array_merge($data, ['no_faktur' => $data['no_faktur']]));
                if (!$jr['ok']) {
                    return redirect()->back()->withInput()->with('error', $msg . ' — jurnal: ' . ($jr['message'] ?? ''));
                }
            }

            return redirect()->to('/keuangan/pengeluaran')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        $pembelianModel = new PembelianModel();

        try {
            $pembelianModel->delete($id);
            return redirect()->to('/keuangan/pengeluaran')->with('success', 'Data pengeluaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function get(string $id)
    {
        $pembelianModel = new PembelianModel();
        $data = $pembelianModel->find($id);

        if ($data) {
            if (!empty($data['supplier_id'])) {
                $supplierModel = new SupplierModel();
                $supplier = $supplierModel->find($data['supplier_id']);
                $data['supplier_name'] = $supplier ? $supplier['name'] : '';
            } else {
                $data['supplier_name'] = '';
            }
            return $this->response->setJSON(['success' => true, 'data' => $data]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
    }

    public function generateFaktur()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $time = strtotime($date);

        $day = date('d', $time);
        $month = intval(date('m', $time));
        $shortYear = date('y', $time);

        $romanMonth = $this->integerToRoman($month);

        $pembelianModel = new PembelianModel();
        $latest = $pembelianModel->select('no_faktur')
            ->like('no_faktur', 'INV/ALMAI/', 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $nextNum = 1;
        if ($latest) {
            $parts = explode('/', $latest['no_faktur']);
            if (count($parts) >= 3) {
                $nextNum = intval($parts[2]) + 1;
            }
        }

        $nomorStr = str_pad($nextNum, 4, '0', STR_PAD_LEFT);
        $noFaktur = "INV/ALMAI/{$nomorStr}/{$day}/{$romanMonth}/{$shortYear}/";

        return $this->response->setJSON([
            'success' => true,
            'no_faktur' => $noFaktur
        ]);
    }

    public function detail(string $id)
    {
        $pembelianModel = new PembelianModel();
        $pembelian = $pembelianModel->select('pembelian.*, suppliers.name as supplier_name, suppliers.notes as supplier_notes')
            ->join('suppliers', 'suppliers.id = pembelian.supplier_id', 'left')
            ->find($id);

        if (!$pembelian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data pengeluaran tidak ditemukan.");
        }

        $data = [
            'title' => 'Invoice Pengeluaran #' . $pembelian['no_faktur'],
            'trx' => $pembelian
        ];

        return view('keuangan/pengeluaran/detail', $data);
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
