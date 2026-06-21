<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Entrar') ?> · <?= e(config('app.name')) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
</head>
<body>
    <div class="login-wrap">
        <?= $content ?>
    </div>
</body>
</html>
