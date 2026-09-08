<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jurnal Umum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
        }
        table th {
            background-color: #f0f0f0;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN JURNAL UMUM</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <?php if ($startDate && $endDate): ?>
            <p>Periode: <?= date('d/m/Y', strtotime($startDate)) ?> s/d <?= date('d/m/Y', strtotime($endDate)) ?></p>
        <?php else: ?>
            <p>Periode: Semua Periode</p>
        <?php endif; ?>
        <?php if ($search): ?>
            <p>Pencarian: "<?= esc($search) ?>"</p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="15%">No. Reff</th>
                <th width="15%">Akun</th>
                <th width="25%">Deskripsi</th>
                <th width="15%">Debit (Rp)</th>
                <th width="15%">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $totalDebit = 0;
            $totalKredit = 0;
            if (empty($jurnalData)): 
            ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada jurnal pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($jurnalData as $row): 
                    $totalDebit += $row['debit'];
                    $totalKredit += $row['kredit'];
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td><?= esc($row['no_reff']) ?></td>
                        <td>
                            <span class="font-bold"><?= esc($row['nama_akun']) ?></span><br>
                            <span style="font-size:9px;color:#555;"><?= esc($row['kode_akun']) ?></span>
                        </td>
                        <td><?= esc($row['deskripsi']) ?></td>
                        <td class="text-right"><?= $row['debit'] > 0 ? number_format($row['debit'], 2, ',', '.') : '-' ?></td>
                        <td class="text-right"><?= $row['kredit'] > 0 ? number_format($row['kredit'], 2, ',', '.') : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-right font-bold">TOTAL</td>
                    <td class="text-right font-bold"><?= number_format($totalDebit, 2, ',', '.') ?></td>
                    <td class="text-right font-bold"><?= number_format($totalKredit, 2, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
