<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;

class CalkImport extends BaseController
{
    /**
     * Import CALK data from calk.txt file
     */
    public function importFromFile()
    {
        // Check if user is authenticated and has permission
        if (!session()->get('userId')) {
            return redirect()->to('/login');
        }

        try {
            // Read the calk.txt file
            $filePath = ROOTPATH . 'calk.txt';
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File calk.txt tidak ditemukan');
            }

            $content = file_get_contents($filePath);

            // Parse the content into sections
            $sections = [
                'gambaran_umum' => '',
                'kebijakan_akuntansi' => '',
                'rincian_kas' => '',
                'rincian_piutang' => '',
                'rincian_aset_tetap' => '',
                'rincian_hutang' => '',
            ];

            // Extract Gambaran Umum (section 1)
            preg_match('/1\.\s+GAMBARAN UMUM(.*?)(?=page 2|2\.)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['gambaran_umum'] = trim($matches[1]);
            }

            // Extract Kebijakan Akuntansi (section 2)
            preg_match('/2\.\s+IKHTISAR KEBIJAKAN AKUNTANSI(.*?)(?=3\.|PERISTIWA)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['kebijakan_akuntansi'] = trim($matches[1]);
            }

            // Extract Kas dan Setara Kas (section 5)
            preg_match('/5\s+KAS DAN SETARA KAS(.*?)(?=6\s+Piutang|PIUTANG)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['rincian_kas'] = trim($matches[1]);
            }

            // Extract Piutang (section 6)
            preg_match('/6\s+Piutang Pemegang Saham(.*?)(?=7\s+INVESTASI|INVESTASI)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['rincian_piutang'] = trim($matches[1]);
            }

            // Extract Aset Tetap (section 9)
            preg_match('/9\s+ASET TETAP(.*?)(?=10\s+ASET|ASET TAKBERWUJUD)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['rincian_aset_tetap'] = trim($matches[1]);
            }

            // Extract Utang Pajak (section 11)
            preg_match('/11\s+UTANG PAJAK(.*?)(?=12\s+EKUITAS|EKUITAS)/is', $content, $matches);
            if (!empty($matches[1])) {
                $sections['rincian_hutang'] = trim($matches[1]);
            }

            // Prepare data for database
            $tahun = date('Y');
            $calkData = [
                'gambaran_umum' => $sections['gambaran_umum'] ?: 'Perusahaan bergerak di bidang penyediaan layanan prop firm trading dan advokasi edukasi.',
                'kebijakan_akuntansi' => $sections['kebijakan_akuntansi'] ?: 'Laporan keuangan disusun berdasarkan Standar Akuntansi Keuangan Entitas Tanpa Akuntabilitas Publik (SAK ETAP).',
                'rincian_kas' => $sections['rincian_kas'] ?: 'Kas dan setara kas terdiri dari kas, bank dan semua investasi yang jatuh tempo dalam waktu 3 bulan atau kurang.',
                'rincian_piutang' => $sections['rincian_piutang'] ?: 'Piutang usaha disajikan sebesar jumlah neto setelah dikurangi dengan penurunan nilai.',
                'rincian_aset_tetap' => $sections['rincian_aset_tetap'] ?: 'Aset tetap dicatat berdasarkan harga perolehannya dengan penyusutan menggunakan metode garis lurus.',
                'rincian_hutang' => $sections['rincian_hutang'] ?: 'Utang usaha adalah kewajiban membayar barang dan jasa yang telah diterima.',
            ];

            // Check if record exists
            $db = \Config\Database::connect();
            $builder = $db->table('calk_data');
            $exists = $builder->where('tahun', $tahun)->countAllResults() > 0;

            if ($exists) {
                // Update existing record
                $builder->where('tahun', $tahun)->update([
                    'konten' => json_encode($calkData),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $message = 'Data CALK untuk tahun ' . $tahun . ' berhasil diperbarui dari file calk.txt';
            } else {
                // Insert new record
                $builder->insert([
                    'tahun' => $tahun,
                    'konten' => json_encode($calkData),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $message = 'Data CALK untuk tahun ' . $tahun . ' berhasil diimpor dari file calk.txt';
            }

            return redirect()->to('/keuangan/calk')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor CALK: ' . $e->getMessage());
        }
    }
}
