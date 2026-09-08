<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - WPA Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #33e818;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #111;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .filter-info {
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .filter-info span {
            margin-right: 20px;
        }

        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            flex: 1;
            text-align: center;
        }

        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #33e818;
        }

        .summary-item .label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background: #111;
            color: #fff;
            font-weight: 600;
            font-size: 11px;
        }

        td {
            font-size: 11px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .status {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #33e818; color: #000; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            <i class="fas fa-print"></i> Print / Save PDF
        </button>
    </div>

    <div class="header">
        <h1>Laporan Transaksi</h1>
        <p>WPA Platform - Almai ID</p>
    </div>

    <div class="filter-info">
        <span><strong>Tanggal Cetak:</strong> <?= date('d M Y H:i') ?></span>
        <?php if ($dateFrom || $dateTo): ?>
            <span><strong>Periode:</strong> <?= $dateFrom ? date('d/m/Y', strtotime($dateFrom)) : '-' ?> s/d <?= $dateTo ? date('d/m/Y', strtotime($dateTo)) : '-' ?></span>
        <?php endif; ?>
        <?php if ($status && $status !== 'all'): ?>
            <span><strong>Status:</strong> <?= ucfirst($status) ?></span>
        <?php endif; ?>
        <?php if ($productType && $productType !== 'all'): ?>
            <span><strong>Tipe:</strong> <?= ucfirst($productType) ?></span>
        <?php endif; ?>
        <?php if ($productName && $productName !== 'all'): ?>
            <span><strong>Layanan:</strong> <?= esc($productName) ?></span>
        <?php endif; ?>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value"><?= count($data) ?></div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="summary-item">
            <div class="value"><?= $totalConfirmed ?></div>
            <div class="label">Confirmed</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp <?= number_format($totalAmount, 0, ',', '.') ?></div>
            <div class="label">Total Nilai (Rupiah)</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th class="text-right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data transaksi</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($row['invoice_number']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['user_name'] ?? '-') ?></td>
                        <td><?= esc($row['product_name']) ?></td>
                        <td><?= ucfirst($row['product_type']) ?></td>
                        <td class="text-right">
                            <?php if (($row['payment_method'] ?? '') === 'poin'): ?>
                                <span style="color: #facc15;">
                                    <?= number_format($row['total'], 0, ',', '.') ?> 
                                    <svg width="10" height="10" viewBox="0 0 20 20" style="display:inline; vertical-align:middle;"><circle cx="10" cy="10" r="9" fill="#fbbf24" stroke="#d97706" stroke-width="1"/></svg>
                                </span>
                            <?php else: ?>
                                Rp <?= number_format($row['total'], 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem WPA Platform</p>
        <p>&copy; <?= date('Y') ?> Almai ID - All Rights Reserved</p>
    </div>
</body>

</html>