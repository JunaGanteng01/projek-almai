<?php

namespace App\Services\Keuangan;

use App\Models\KontakModel;
use CodeIgniter\HTTP\IncomingRequest;

class FakturFormService
{
    /**
     * Parse baris item dari form faktur.
     *
     * @return array{items: array, subtotal: float, item_count: int}
     */
    public static function parseLineItems(IncomingRequest $request): array
    {
        $produk = $request->getPost('item_produk') ?: [];
        $deskripsi = $request->getPost('item_deskripsi') ?: [];
        $qty = $request->getPost('item_qty') ?: [];
        $satuan = $request->getPost('item_satuan') ?: [];
        $diskon = $request->getPost('item_diskon') ?: [];
        $harga = $request->getPost('item_harga') ?: [];
        $pajak = $request->getPost('item_pajak') ?: [];

        $items = [];
        $subtotal = 0.0;
        $count = max(count($produk), count($harga));

        for ($i = 0; $i < $count; $i++) {
            $nama = trim((string) ($produk[$i] ?? $deskripsi[$i] ?? ''));
            if ($nama === '') {
                continue;
            }

            $q = max(0, (float) ($qty[$i] ?? 1));
            if ($q <= 0) {
                $q = 1;
            }
            $h = max(0, (float) ($harga[$i] ?? 0));
            $d = (float) ($diskon[$i] ?? 0);
            $pajakPct = (float) ($pajak[$i] ?? 0);

            $lineSub = ($q * $h) - $d;
            if ($lineSub < 0) {
                $lineSub = 0;
            }
            $linePajak = $lineSub * ($pajakPct / 100);
            $jumlah = $lineSub + $linePajak;

            $items[] = [
                'produk' => $nama,
                'deskripsi' => trim((string) ($deskripsi[$i] ?? '')),
                'qty' => $q,
                'satuan' => trim((string) ($satuan[$i] ?? 'pcs')) ?: 'pcs',
                'diskon' => $d,
                'harga' => $h,
                'pajak' => $pajakPct,
                'jumlah' => round($jumlah, 2),
            ];
            $subtotal += $jumlah;
        }

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'item_count' => count($items),
        ];
    }

    public static function calculateTotals(
        float $subtotal,
        float $ppnPercent,
        float $biayaPengiriman,
        float $diskonTambahan,
        float $biayaTransaksi,
        float $uangMuka,
        float $pemotongan,
        bool $hargaTermasukPajak
    ): array {
        $dpp = $subtotal;
        if (!$hargaTermasukPajak && $ppnPercent > 0) {
            $ppnNominal = $dpp * ($ppnPercent / 100);
        } else {
            $ppnNominal = 0;
        }

        $total = $dpp + $ppnNominal + $biayaPengiriman + $biayaTransaksi - $diskonTambahan - $pemotongan - $uangMuka;
        if ($total < 0) {
            $total = 0;
        }

        return [
            'subtotal' => round($dpp, 2),
            'ppn_nominal' => round($ppnNominal, 2),
            'total' => round($total, 2),
            'sisa_tagihan' => round($total, 2),
        ];
    }

    /** Ringkas item pertama untuk kolom legacy single-product */
    public static function legacyProductFields(array $items): array
    {
        if (empty($items)) {
            return [
                'nama_produk' => 'Layanan',
                'kode_produk' => null,
                'jumlah_produk' => 1,
                'satuan_produk' => 'pcs',
                'harga_produk' => 0,
                'diskon_produk' => 0,
            ];
        }

        $first = $items[0];
        $summary = $first['produk'];
        if (count($items) > 1) {
            $summary .= ' +' . (count($items) - 1) . ' item';
        }

        return [
            'nama_produk' => $summary,
            'kode_produk' => null,
            'jumlah_produk' => $first['qty'],
            'satuan_produk' => $first['satuan'],
            'harga_produk' => $first['harga'],
            'diskon_produk' => $first['diskon'],
        ];
    }

    public static function resolveKontak(int $kontakId, string $fallbackName = ''): array
    {
        if ($kontakId <= 0) {
            return [
                'nama' => $fallbackName,
                'email' => null,
                'perusahaan' => null,
                'alamat' => null,
            ];
        }

        $kontak = (new KontakModel())->find($kontakId);
        if (!$kontak) {
            return ['nama' => $fallbackName, 'email' => null, 'perusahaan' => null, 'alamat' => null];
        }

        return [
            'nama' => $kontak['nama'],
            'email' => $kontak['email'],
            'perusahaan' => $kontak['perusahaan'],
            'alamat' => $kontak['alamat'],
        ];
    }

    public static function itemsFromRecord(?array $record): array
    {
        if (!$record) {
            return [['produk' => '', 'deskripsi' => '', 'qty' => 1, 'satuan' => 'pcs', 'diskon' => 0, 'harga' => 0, 'pajak' => 0, 'jumlah' => 0]];
        }

        if (!empty($record['items_json'])) {
            $decoded = json_decode($record['items_json'], true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }

        return [[
            'produk' => $record['nama_produk'] ?? '',
            'deskripsi' => '',
            'qty' => (float) ($record['jumlah_produk'] ?? 1),
            'satuan' => $record['satuan_produk'] ?? 'pcs',
            'diskon' => (float) ($record['diskon_produk'] ?? 0),
            'harga' => (float) ($record['harga_produk'] ?? 0),
            'pajak' => (float) ($record['ppn'] ?? 0),
            'jumlah' => (float) ($record['subtotal'] ?? 0),
        ]];
    }
}
