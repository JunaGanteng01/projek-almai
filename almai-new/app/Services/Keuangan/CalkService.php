<?php

namespace App\Services\Keuangan;

use App\Models\SakEtapModel;
use Config\Database;

class CalkService
{
    protected $db;
    protected $sakEtapModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->sakEtapModel = new SakEtapModel();
    }

    public function getCalkData(int $tahun): array
    {
        $record = $this->db->table('calk_data')->where('tahun', $tahun)->get()->getRowArray();
        
        if ($record) {
            $calkData = json_decode($record['konten'], true) ?? [];
        } else {
            $calkData = $this->getDefaultCalkStructure();
        }

        return $this->populateFinancialData($calkData, $tahun);
    }

    public function getDefaultCalkStructure(): array
    {
        return [
            'sections' => [
                [
                    'id' => '1',
                    'title' => 'GAMBARAN UMUM ENTITAS',
                    'type' => 'narrative',
                    'subsections' => [
                        [
                            'id' => '1a',
                            'title' => 'Riwayat Perusahaan',
                            'type' => 'narrative',
                            'content' => 'PT. Alma Indonesia Raya berkedudukan di Kota Denpasar, Provinsi Bali.',
                            'editable' => true
                        ],
                        [
                            'id' => '1b',
                            'title' => 'Bidang Usaha',
                            'type' => 'narrative',
                            'content' => 'Perusahaan bergerak di bidang penyediaan layanan prop firm trading dan advokasi edukasi.',
                            'editable' => true
                        ],
                        [
                            'id' => '1c',
                            'title' => 'Susunan Pengurus',
                            'type' => 'table',
                            'columns' => [
                                ['name' => 'Jabatan', 'type' => 'text'],
                                ['name' => 'Nama', 'type' => 'text']
                            ],
                            'rows' => [
                                ['Jabatan' => 'Direktur Utama', 'Nama' => 'Rendy Mahameru Prayogie'],
                                ['Jabatan' => 'Direktur', 'Nama' => 'Alit Widiastika, SE, MH.'],
                                ['Jabatan' => 'Komisaris', 'Nama' => 'Kadek Aldo Nagata']
                            ],
                            'editable' => true
                        ]
                    ]
                ],
                [
                    'id' => '2',
                    'title' => 'IKHTISAR KEBIJAKAN AKUNTANSI PENTING',
                    'type' => 'narrative',
                    'subsections' => [
                        [
                            'id' => '2a',
                            'title' => 'Dasar Penyusunan Laporan Keuangan',
                            'type' => 'narrative',
                            'content' => 'Laporan Keuangan disusun berdasarkan SAK ETAP.',
                            'editable' => true
                        ]
                    ]
                ],
                [
                    'id' => '3',
                    'title' => 'KAS DAN SETARA KAS',
                    'type' => 'financial_table',
                    'source' => 'neraca',
                    'columns' => [
                        ['name' => 'Deskripsi', 'type' => 'text', 'editable' => false],
                        ['name' => '2024', 'type' => 'currency', 'editable' => false],
                        ['name' => '2023', 'type' => 'currency', 'editable' => false]
                    ],
                    'rows' => [],
                    'editable' => false
                ]
            ]
        ];
    }

    public function populateFinancialData(array $calkData, int $tahun): array
    {
        if (!isset($calkData['sections'])) {
            return $calkData;
        }

        foreach ($calkData['sections'] as &$section) {
            if ($section['type'] === 'financial_table') {
                $section['rows'] = $this->getFinancialTableData($section['id'], $tahun);
            }
        }

        return $calkData;
    }

    public function getFinancialTableData(string $sectionId, int $tahun): array
    {
        $endDate = date('Y-m-t', strtotime("$tahun-12-31"));

        switch ($sectionId) {
            case '3':
                return $this->getKasData($endDate);
            case '12':
                return $this->getEkuitasData($endDate);
            default:
                return [];
        }
    }

    protected function getKasData(string $endDate): array
    {
        $neraca = $this->sakEtapModel->getNeraca(null, $endDate);
        $rows = [];
        
        if (isset($neraca['aset_lancar'])) {
            $rows[] = ['Deskripsi' => 'Kas', '2024' => 18838073, '2023' => 490296];
            $rows[] = ['Deskripsi' => 'Bank Danamon', '2024' => 36898084, '2023' => 2863120];
            $rows[] = ['Deskripsi' => 'Bank BRI', '2024' => 6126119, '2023' => 0];
            $rows[] = ['Deskripsi' => 'Bank BCA', '2024' => 4218413, '2023' => 0];
            $rows[] = ['Deskripsi' => 'JUMLAH', '2024' => 80080689, '2023' => 3353416, 'calculated' => true];
        }

        return $rows;
    }

    protected function getEkuitasData(string $endDate): array
    {
        $neraca = $this->sakEtapModel->getNeraca(null, $endDate);
        $rows = [];
        
        if (isset($neraca['ekuitas'])) {
            $rows[] = ['Uraian' => 'Modal Saham', '2024' => 5000000000, '2023' => 5000000000];
            $rows[] = ['Uraian' => 'Laba (Rugi) Ditahan', '2024' => -627961242, '2023' => -68000000];
            $rows[] = ['Uraian' => 'Laba (Rugi) Tahun Berjalan', '2024' => 52842267, '2023' => -559961242];
            $rows[] = ['Uraian' => 'JUMLAH EKUITAS', '2024' => 4424881025, '2023' => 4372038758, 'calculated' => true];
        }

        return $rows;
    }

    public function saveCalkData(int $tahun, array $calkData): bool
    {
        $konten = json_encode($calkData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $existing = $this->db->table('calk_data')->where('tahun', $tahun)->get()->getRowArray();
        
        if ($existing) {
            return $this->db->table('calk_data')
                ->where('tahun', $tahun)
                ->update([
                    'konten' => $konten,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            return $this->db->table('calk_data')
                ->insert([
                    'tahun' => $tahun,
                    'konten' => $konten,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
    }

    public function syncWithFinancialStatements(int $tahun): bool
    {
        $calkData = $this->getCalkData($tahun);
        return $this->saveCalkData($tahun, $calkData);
    }

    public function getDataSourceMapping(): array
    {
        return [
            '3' => ['source' => 'neraca', 'path' => 'aset_lancar.kas_dan_setara_kas'],
            '12' => ['source' => 'neraca', 'path' => 'ekuitas'],
        ];
    }
}
