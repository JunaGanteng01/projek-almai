<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1, h2, h3, h4 {
            color: #333;
        }
        .variable {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= esc($title) ?></h1>
        <hr>
        <div class="content">
            <?= $content ?>
        </div>
    </div>
</body>
</html>
