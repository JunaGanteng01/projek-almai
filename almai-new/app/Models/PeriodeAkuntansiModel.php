<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodeAkuntansiModel extends Model
{
    protected $table            = 'periode_akuntansi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'bulan',
        'tahun',
        'is_closed',
        'closed_by',
        'closed_at',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // ═══════════════════════════════════════════════════════
    // CUSTOM METHODS
    // ═══════════════════════════════════════════════════════

    /**
     * Cek apakah periode sudah ditutup (is_closed = 1)
     */
    public function isClosed(int $bulan, int $tahun): bool
    {
        $result = $this->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('is_closed', 1)
            ->first();

        return !empty($result);
    }

    /**
     * Dapatkan detail periode
     */
    public function getPeriod(int $bulan, int $tahun): ?array
    {
        return $this->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();
    }

    /**
     * Dapatkan semua periode untuk tahun tertentu
     */
    public function getPeriodsByYear(int $tahun): array
    {
        return $this->where('tahun', $tahun)
            ->orderBy('bulan', 'ASC')
            ->findAll();
    }

    /**
     * Tutup periode (set is_closed = 1)
     */
    public function closePeriod(int $bulan, int $tahun, int $userId): bool
    {
        $period = $this->getPeriod($bulan, $tahun);

        $data = [
            'is_closed'  => 1,
            'closed_by'  => $userId,
            'closed_at'  => date('Y-m-d H:i:s'),
        ];

        if ($period) {
            return $this->update($period['id'], $data);
        } else {
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            return $this->insert($data) !== false;
        }
    }

    /**
     * Buka periode kembali (set is_closed = 0)
     */
    public function openPeriod(int $bulan, int $tahun): bool
    {
        $period = $this->getPeriod($bulan, $tahun);

        if (!$period) {
            return false;
        }

        return $this->update($period['id'], [
            'is_closed'  => 0,
            'closed_by'  => null,
            'closed_at'  => null,
        ]);
    }

    /**
     * Hitung total periode yang ditutup dalam tahun
     */
    public function countClosedPeriods(int $tahun): int
    {
        return $this->where('tahun', $tahun)
            ->where('is_closed', 1)
            ->countAllResults();
    }

    /**
     * Hitung total periode yang terbuka (ada transaksi) dalam tahun
     */
    public function countOpenPeriods(int $tahun, array $monthsWithTransactions = []): int
    {
        $count = 0;
        for ($m = 1; $m <= 12; $m++) {
            if (in_array($m, $monthsWithTransactions)) {
                $period = $this->getPeriod($m, $tahun);
                if (!$period || !$period['is_closed']) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
