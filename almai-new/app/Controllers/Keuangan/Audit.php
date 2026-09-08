<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;

class Audit extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Unbalanced Journals (Debit != Kredit per no_reff)
        $unbalancedQuery = $db->query("
            SELECT no_reff, SUM(debit) as total_debit, SUM(kredit) as total_kredit, MAX(tanggal) as tanggal
            FROM jurnal
            WHERE deleted_at IS NULL
            GROUP BY no_reff
            HAVING SUM(debit) != SUM(kredit)
            ORDER BY tanggal DESC
        ");
        $unbalanced = $unbalancedQuery->getResultArray();

        // 2. Negative Cash Balances (Akun Kas & Bank dengan saldo minus)
        $negativeCashQuery = $db->query("
            SELECT a.id, a.kode_akun, a.nama_akun, 
                   (SUM(j.debit) - SUM(j.kredit)) as saldo
            FROM akun a
            JOIN jurnal j ON j.akun_id = a.id
            WHERE a.kategori = 'kas & bank' AND a.deleted_at IS NULL AND j.deleted_at IS NULL
            GROUP BY a.id, a.kode_akun, a.nama_akun
            HAVING saldo < 0
        ");
        $negativeCash = $negativeCashQuery->getResultArray();

        // 3. Zero or Negative Nominal Transactions (Jurnal dengan debit/kredit 0 tapi tercatat)
        $zeroNominalQuery = $db->query("
            SELECT j.*, a.nama_akun
            FROM jurnal j
            JOIN akun a ON a.id = j.akun_id
            WHERE j.deleted_at IS NULL AND (j.debit = 0 AND j.kredit = 0)
            ORDER BY j.tanggal DESC
        ");
        $zeroNominal = $zeroNominalQuery->getResultArray();

        // 4. Orphan Journals (Jurnal dengan akun_id invalid/terhapus)
        $orphanQuery = $db->query("
            SELECT j.*
            FROM jurnal j
            LEFT JOIN akun a ON a.id = j.akun_id
            WHERE j.deleted_at IS NULL AND (a.id IS NULL OR a.deleted_at IS NOT NULL)
            ORDER BY j.tanggal DESC
        ");
        $orphan = $orphanQuery->getResultArray();

        // 5. Potensi Duplikat Jurnal (Grouping berdasarkan no_reff, tanggal, jumlah row, total nominal yang mirip)
        // Kita gunakan query sederhana untuk mencari tanggal & deskripsi persis sama dengan akun id sama
        $duplicateQuery = $db->query("
            SELECT tanggal, akun_id, debit, kredit, deskripsi, COUNT(*) as jumlah_duplikat
            FROM jurnal
            WHERE deleted_at IS NULL
            GROUP BY tanggal, akun_id, debit, kredit, deskripsi
            HAVING COUNT(*) > 1
            ORDER BY jumlah_duplikat DESC, tanggal DESC
            LIMIT 50
        ");
        $duplicates = $duplicateQuery->getResultArray();

        $data = [
            'title' => 'Audit Keuangan',
            'activeMenu' => 'audit', // Will be active if sidebar matches
            'unbalanced' => $unbalanced,
            'negativeCash' => $negativeCash,
            'zeroNominal' => $zeroNominal,
            'orphan' => $orphan,
            'duplicates' => $duplicates
        ];

        return view('keuangan/audit/index', $data);
    }
}
