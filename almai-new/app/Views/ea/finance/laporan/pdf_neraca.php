<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #555; }
        
        .container { width: 100%; display: table; }
        .column { display: table-cell; width: 48%; vertical-align: top; }
        .gap { display: table-cell; width: 4%; }
        
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border-bottom: 1px solid #ccc; padding: 6px; }
        .no-border td { border: none !important; }
        
        .section-title { font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; border: 1px solid #000; padding: 6px; margin-bottom: 10px; font-size:12px; }
        .sub-title { font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; border-bottom:1px solid #000;}
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .total-box { border: 2px solid #000; padding: 10px; margin-top: 20px; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NERACA (BALANCE SHEET)</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <p>Posisi Per Tanggal: <?= date('d F Y', strtotime($date)) ?></p>
    </div>

    <div class="container">
        <!-- AKTIVA -->
        <div class="column">
            <div class="section-title">AKTIVA (ASSETS)</div>
            
            <div class="sub-title">Aset Lancar</div>
            <table>
                <?php foreach ($neracaData['aset_lancar'] as $row): ?>
                <tr class="no-border">
                    <td><?= esc($row['nama_akun']) ?></td>
                    <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="sub-title">Aset Tetap</div>
            <table>
                <?php foreach ($neracaData['aset_tetap'] as $row): ?>
                <tr class="no-border">
                    <td><?= esc($row['nama_akun']) ?></td>
                    <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="total-box">
                <span style="float:left">TOTAL AKTIVA</span>
                <span style="float:right"><?= number_format($totals['aset'], 0, ',', '.') ?></span>
                <div style="clear:both"></div>
            </div>
        </div>
        
        <div class="gap"></div>

        <!-- PASIVA -->
        <div class="column">
            <div class="section-title">PASIVA (LIABILITIES & EQUITY)</div>
            
            <div class="sub-title">Kewajiban</div>
            <table>
                <?php foreach ($neracaData['kewajiban'] as $row): ?>
                <tr class="no-border">
                    <td><?= esc($row['nama_akun']) ?></td>
                    <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="sub-title">Ekuitas</div>
            <table>
                <?php foreach ($neracaData['ekuitas'] as $row): ?>
                <tr class="no-border">
                    <td><?= esc($row['nama_akun']) ?></td>
                    <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="no-border">
                    <td style="font-style:italic">Laba Tahun Berjalan</td>
                    <td class="text-right"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                </tr>
            </table>
            
            <div class="total-box">
                <span style="float:left">TOTAL PASIVA</span>
                <span style="float:right"><?= number_format($totals['kewajiban'] + $totals['ekuitas'], 0, ',', '.') ?></span>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>
</body>
</html>