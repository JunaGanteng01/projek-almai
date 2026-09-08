<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'kontak_id',
        'nomor_pi',
        'nama_supplier',
        'referensi',
        'termin_hari',
        'tag',
        'items_json',
        'diskon_tambahan',
        'biaya_transaksi',
        'uang_muka',
        'pemotongan',
        'harga_termasuk_pajak',
        'email',
        'alamat',
        'status',
        'tanggal_transaksi',
        'tanggal_jatuh_tempo',
        'catatan',
        'nama_produk',
        'kode_produk',
        'jumlah_produk',
        'satuan_produk',
        'harga_produk',
        'diskon_produk',
        'ppn',
        'biaya_pengiriman',
        'biaya_lainnya',
        'subtotal',
        'total',
        'status_pembayaran',
        'tanggal_pembayaran',
        'kode_akun_beban',
        'kode_akun_hutang',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'nomor_pi' => 'required|is_unique[pembelian.nomor_pi]',
        'nama_supplier' => 'required|min_length[3]',
        'tanggal_transaksi' => 'required|valid_date',
        'nama_produk' => 'required',
        'total' => 'required|numeric',
        'status_pembayaran' => 'required|in_list[belum_dibayar,sebagian_dibayar,lunas]',
    ];

    protected $validationMessages = [
        'nomor_pi' => [
            'required' => 'Nomor PI harus diisi',
            'is_unique' => 'Nomor PI sudah terdaftar',
        ],
    ];

    // Ambil data dengan filter
    public function getWithFilters($filters = [])
    {
        $query = $this->select('*');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->groupStart()
                ->like('nomor_pi', $search)
                ->orLike('nama_supplier', $search)
                ->orLike('nama_produk', $search)
                ->groupEnd();
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['status_pembayaran'])) {
            $query->where('status_pembayaran', $filters['status_pembayaran']);
        }

        if (!empty($filters['tanggal_dari'])) {
            $query->where('tanggal_transaksi >=', $filters['tanggal_dari']);
        }

        if (!empty($filters['tanggal_sampai'])) {
            $query->where('tanggal_transaksi <=', $filters['tanggal_sampai']);
        }

        return $query->orderBy('tanggal_transaksi', 'DESC')
            ->orderBy('id', 'DESC');
    }

    // Summary data
    public function getSummary($filters = [])
    {
        $query = $this->select('
            COUNT(*) as total_transaksi,
            SUM(total) as total_nilai,
            SUM(CASE WHEN status_pembayaran = "lunas" THEN total ELSE 0 END) as total_lunas,
            SUM(CASE WHEN status_pembayaran IN ("belum_dibayar", "sebagian_dibayar") THEN total ELSE 0 END) as total_hutang
        ');

        if (!empty($filters['tanggal_dari'])) {
            $query->where('tanggal_transaksi >=', $filters['tanggal_dari']);
        }

        if (!empty($filters['tanggal_sampai'])) {
            $query->where('tanggal_transaksi <=', $filters['tanggal_sampai']);
        }

        return $query->first();
    }

    // Ambil hutang (belum lunas)
    public function getHutang()
    {
        return $this->where('status_pembayaran !=', 'lunas')
            ->orderBy('tanggal_jatuh_tempo', 'ASC')
            ->findAll();
    }

    // Ambil overdue
    public function getOverdue()
    {
        return $this->where('tanggal_jatuh_tempo <', date('Y-m-d'))
            ->where('status_pembayaran !=', 'lunas')
            ->orderBy('tanggal_jatuh_tempo', 'ASC')
            ->findAll();
    }
}
