<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Referral extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ── Filters ──────────────────────────────────────────────
        $search    = $this->request->getGet('search') ?? '';
        $layananId = $this->request->getGet('layanan_id') ?? '';
        $dateFrom  = $this->request->getGet('date_from') ?? '';
        $dateTo    = $this->request->getGet('date_to') ?? '';
        $page      = (int)($this->request->getGet('page') ?? 1);
        $perPage   = 20;
        $offset    = ($page - 1) * $perPage;

        // ── Stats ─────────────────────────────────────────────────
        // Total transaksi yang punya referrer
        $totalTrx = $db->query("SELECT COUNT(*) as c FROM transaksi WHERE referrer_id IS NOT NULL AND status = 'confirmed'")->getRowArray()['c'] ?? 0;

        // Total cash terdistribusi (dari balance yang diberikan via referral)
        // Kita hitung dari points table dengan description mengandung 'Komisi referral'
        $totalCash = $db->query("
            SELECT COALESCE(SUM(t.total), 0) as total
            FROM transaksi t
            WHERE t.referrer_id IS NOT NULL AND t.status = 'confirmed'
        ")->getRowArray()['total'] ?? 0;

        // Total poin referral yang sudah diberikan
        $totalPoin = $db->query("
            SELECT COALESCE(SUM(p.point), 0) as total
            FROM points p
            WHERE p.description LIKE '%Komisi referral%'
              AND p.type = 'earn'
        ")->getRowArray()['total'] ?? 0;

        // Total cash referral dari balance update (dari points description)
        $totalCashDist = $db->query("
            SELECT COALESCE(SUM(p.point), 0) as total
            FROM points p
            WHERE p.description LIKE '%Komisi referral%'
              AND p.type = 'earn'
        ")->getRowArray()['total'] ?? 0;

        // Unique referrers
        $totalReferrers = $db->query("
            SELECT COUNT(DISTINCT referrer_id) as c
            FROM transaksi
            WHERE referrer_id IS NOT NULL AND status = 'confirmed'
        ")->getRowArray()['c'] ?? 0;

        // ── Layanan list for filter dropdown ─────────────────────
        $layananList = $db->query("
            SELECT id, name as label, 'layanan' as type FROM layanan WHERE status = 'aktif'
            UNION ALL
            SELECT id, title as label, type FROM layanan_event WHERE status = 'aktif'
            UNION ALL
            SELECT id, name as label, type FROM layanan_tools WHERE status = 'aktif'
            UNION ALL
            SELECT id, name as label, type FROM layanan_subscription WHERE status = 'aktif'
            ORDER BY label ASC
        ")->getResultArray();

        // ── Main Query: Referral History ──────────────────────────
        $where = "t.referrer_id IS NOT NULL AND t.status = 'confirmed'";
        $params = [];

        if ($search) {
            $where .= " AND (buyer.name LIKE ? OR referrer.name LIKE ? OR t.invoice_number LIKE ? OR t.product_name LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
        }
        if ($layananId) {
            $where .= " AND t.layanan_id = ?";
            $params[] = $layananId;
        }
        if ($dateFrom) {
            $where .= " AND DATE(t.confirmed_at) >= ?";
            $params[] = $dateFrom;
        }
        if ($dateTo) {
            $where .= " AND DATE(t.confirmed_at) <= ?";
            $params[] = $dateTo;
        }

        $countSql = "
            SELECT COUNT(*) as c
            FROM transaksi t
            LEFT JOIN users buyer    ON buyer.id    = t.user_id
            LEFT JOIN users referrer ON referrer.id = t.referrer_id
            WHERE $where
        ";
        $totalRows = $db->query($countSql, $params)->getRowArray()['c'] ?? 0;
        $totalPages = max(1, ceil($totalRows / $perPage));

        $sql = "
            SELECT
                t.id,
                t.invoice_number,
                t.product_name,
                t.product_type,
                t.total,
                t.referral_poin,
                t.referral_code,
                t.confirmed_at,
                t.layanan_id,
                buyer.id    as buyer_id,
                buyer.name  as buyer_name,
                buyer.email as buyer_email,
                referrer.id    as referrer_id,
                referrer.name  as referrer_name,
                referrer.email as referrer_email,
                referrer.code_referral
            FROM transaksi t
            LEFT JOIN users buyer    ON buyer.id    = t.user_id
            LEFT JOIN users referrer ON referrer.id = t.referrer_id
            WHERE $where
            ORDER BY t.confirmed_at DESC
            LIMIT $perPage OFFSET $offset
        ";
        $history = $db->query($sql, $params)->getResultArray();

        // ── Per-row: ambil distribusi chain dari points table ─────
        foreach ($history as &$row) {
            $chain = $db->query("
                SELECT p.user_id, u.name as user_name, p.point, p.description
                FROM points p
                LEFT JOIN users u ON u.id = p.user_id
                WHERE p.pointable_type = 'transaksi'
                  AND p.pointable_id   = ?
                  AND p.description LIKE '%Komisi referral%'
                ORDER BY p.id ASC
            ", [$row['id']])->getResultArray();
            $row['chain'] = $chain;
            $row['total_poin_dist'] = array_sum(array_column($chain, 'point'));
        }
        unset($row);

        // ── Top Referrers ─────────────────────────────────────────
        $topReferrers = $db->query("
            SELECT
                u.id,
                u.name,
                u.email,
                u.code_referral,
                COUNT(t.id)       as total_referral,
                SUM(t.total)      as total_omzet,
                SUM(t.referral_poin) as total_poin
            FROM transaksi t
            JOIN users u ON u.id = t.referrer_id
            WHERE t.referrer_id IS NOT NULL AND t.status = 'confirmed'
            GROUP BY u.id
            ORDER BY total_referral DESC
            LIMIT 10
        ")->getResultArray();

        return view('admin/referral/index', [
            'title'          => 'Dashboard Referral',
            'activeMenu'     => 'referral',
            'stats' => [
                'total_trx'      => $totalTrx,
                'total_referrers'=> $totalReferrers,
                'total_poin'     => $totalPoin,
            ],
            'history'        => $history,
            'topReferrers'   => $topReferrers,
            'layananList'    => $layananList,
            'search'         => $search,
            'layananId'      => $layananId,
            'dateFrom'       => $dateFrom,
            'dateTo'         => $dateTo,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'totalRows'      => $totalRows,
            'perPage'        => $perPage,
        ]);
    }
}
