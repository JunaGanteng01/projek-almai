<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'kontak_id',
        'nomor_tagihan',
        'nama_kontak',
        'referensi',
        'termin_hari',
        'tag',
        'items_json',
        'diskon_tambahan',
        'biaya_transaksi',
        'uang_muka',
        'pemotongan',
        'harga_termasuk_pajak',
        'perusahaan',
        'email',
        'alamat',
        'provinsi',
        'kota',
        'nomor_po',
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
        'subtotal',
        'total',
        'status_pembayaran',
        'tanggal_pembayaran',
        'kode_akun_revenue',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'nomor_tagihan' => 'required|is_unique[penjualan.nomor_tagihan]',
        'nama_kontak' => 'required|min_length[3]',
        'tanggal_transaksi' => 'required|valid_date',
        'nama_produk' => 'required',
        'total' => 'required|numeric',
        'status_pembayaran' => 'required|in_list[belum_dibayar,sebagian_dibayar,lunas]',
    ];

    protected $validationMessages = [
        'nomor_tagihan' => [
            'required' => 'Nomor tagihan harus diisi',
            'is_unique' => 'Nomor tagihan sudah terdaftar',
        ],
    ];

    // Ambil data dengan filter
    public function getWithFilters($filters = [])
    {
        $query = $this->select('*');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->groupStart()
                ->like('nomor_tagihan', $search)
                ->orLike('nama_kontak', $search)
                ->orLike('nama_produk', $search)
                ->orLike('perusahaan', $search)
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
            SUM(CASE WHEN status_pembayaran IN ("belum_dibayar", "sebagian_dibayar") THEN total ELSE 0 END) as total_piutang
        ');

        if (!empty($filters['tanggal_dari'])) {
            $query->where('tanggal_transaksi >=', $filters['tanggal_dari']);
        }

        if (!empty($filters['tanggal_sampai'])) {
            $query->where('tanggal_transaksi <=', $filters['tanggal_sampai']);
        }

        return $query->first();
    }

    // Ambil piutang (belum lunas)
    public function getPiutang()
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
