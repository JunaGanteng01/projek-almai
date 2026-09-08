<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bot Users</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h2 { text-align: center; margin-bottom: 5px; }
        .bot-item { margin-bottom: 10px; border-bottom: 1px dashed #ccc; padding-bottom: 5px; }
        .bot-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .badge { display: inline-block; padding: 2px 5px; font-size: 10px; border-radius: 3px; font-weight: bold; }
        .badge-live { background-color: #ffc107; color: #000; }
        .badge-demo { background-color: #17a2b8; color: #fff; }
        .badge-active { background-color: #28a745; color: #fff; }
        .badge-inactive { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <h2>Laporan Pengguna RWA BOT</h2>
    <p class="text-center">Tanggal: <?= date('d M Y H:i') ?></p>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 25%">Nama User</th>
                <th style="width: 15%">Total Profit</th>
                <th style="width: 55%">Detail Bot & Strategi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($groupedUsers)): ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach($groupedUsers as $index => $group): ?>
                    <tr>
                        <td class="text-center" valign="top"><?= $index + 1 ?></td>
                        <td valign="top"><strong><?= esc($group['user_name'] ?? '-') ?></strong></td>
                        <td valign="top">
                            <strong>Rp <?= number_format($group['total_profit_all'] ?? 0, 0, ',', '.') ?></strong>
                        </td>
                        <td valign="top">
                            <?php foreach($group['bots'] as $bot): ?>
                                <?php 
                                    $config = json_decode($bot->config ?? '{}', true);
                                    $isLive = !empty($config['liveMode']);
                                ?>
                                <div class="bot-item">
                                    <strong><?= esc($bot->bot_name) ?> (<?= esc($bot->symbol) ?>)</strong><br>
                                    <span style="color:#555;"><?= esc($bot->strategy) ?></span><br>
                                    <?php if ($bot->exchange): ?>
                                        <small>Exchange: <?= esc($bot->exchange) ?> <?= $bot->account_name ? ' - ' . esc($bot->account_name) : '' ?></small><br>
                                    <?php endif; ?>
                                    Profit: <strong>Rp <?= number_format($bot->total_profit ?? 0, 0, ',', '.') ?></strong> &nbsp;|&nbsp;
                                    
                                    <?php if ($isLive): ?>
                                        <span class="badge badge-live">LIVE</span>
                                    <?php else: ?>
                                        <span class="badge badge-demo">DEMO</span>
                                    <?php endif; ?>

                                    <?php if ($bot->status === 'active'): ?>
                                        <span class="badge badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
