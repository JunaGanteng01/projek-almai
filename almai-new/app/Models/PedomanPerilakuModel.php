<?php

namespace App\Models;

use CodeIgniter\Model;

class PedomanPerilakuModel extends Model
{
    protected $table = 'pedoman_perilaku';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'transaksi_id',
        'no_akun',
        'kuesioner_latar_belakang',
        'kuesioner_profil_risiko',
        'perjanjian_jasa_penjelasan',
        'perjanjian_jasa_ttd',
        'no_dok_perjanjian',
        'ket_perusahaan_penjelasan',
        'ket_perusahaan_ttd',
        'pernyataan_risiko_penjelasan',
        'pernyataan_risiko_ttd',
        'keterangan'
    ];

    public function getPaginatedData($perPage = 20, $wpaId = null, $keyword = null)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('transaksi t');
        
        $select = '
            t.id as transaksi_id,
            t.invoice_number,
            u.name as nama_klien,
            w.name as wpa_name,
            pp.no_akun,
            pp.kuesioner_latar_belakang,
            pp.kuesioner_profil_risiko,
            pp.perjanjian_jasa_penjelasan,
            pp.perjanjian_jasa_ttd,
            pp.no_dok_perjanjian,
            pp.ket_perusahaan_penjelasan,
            pp.ket_perusahaan_ttd,
            pp.pernyataan_risiko_penjelasan,
            pp.pernyataan_risiko_ttd,
            pp.keterangan
        ';

        $builder->select($select)
            ->join('users u', 'u.id = t.user_id', 'left')
            // To get WPA, we need to join layanan or check how it's linked.
            // Based on TransaksiModel, it joins layanan, layanan_event, dll. 
            // We'll use a subquery or join for simplicity here.
            ->join('layanan l', 'l.id = t.layanan_id AND t.product_type IN ("layanan","course")', 'left')
            ->join('layanan_event le', 'le.id = t.layanan_id AND t.product_type IN ("event","webinar")', 'left')
            ->join('layanan_tools lt', 'lt.id = t.layanan_id AND t.product_type IN ("tool","tools")', 'left')
            ->join('layanan_subscription ls', 'ls.id = t.layanan_id AND t.product_type IN ("subscription","pendampingan","cwpa")', 'left')
            ->join('wpa w', 'w.id = COALESCE(l.wpa_id, le.wpa_id, lt.wpa_id, ls.wpa_id)', 'left')
            ->join('pedoman_perilaku pp', 'pp.transaksi_id = t.id', 'left')
            ->where('t.status', 'confirmed');

        if ($wpaId) {
            $builder->where('COALESCE(l.wpa_id, le.wpa_id, lt.wpa_id, ls.wpa_id)', $wpaId);
        }

        if ($keyword) {
            $builder->groupStart()
                ->like('u.name', $keyword)
                ->orLike('t.invoice_number', $keyword)
                ->groupEnd();
        }

        $builder->orderBy('t.created_at', 'DESC');

        // Pagination
        $request = \Config\Services::request();
        $page = $request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($page, $perPage, $total, 'admin_pagination');

        return [
            'data' => $results,
            'pager_links' => $pagerLinks
        ];
    }
}
