<?php
/** @var string $content */
use App\Core\Auth;

$user    = $authUser ?? Auth::user();
$isAdmin = ($user['role'] ?? '') === 'admin';
$path    = $_SERVER['REQUEST_URI'] ?? '/';

$nav = function (string $href, string $label) use ($path): string {
    $active = $href === '/'
        ? ($path === '/' ? 'active' : '')
        : (str_starts_with($path, $href) ? 'active' : '');
    return '<a class="nav-link-side ' . $active . '" href="' . e($href) . '">' . e($label) . '</a>';
};
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? config('app.name')) ?> · <?= e(config('app.name')) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand"><span class="dot"></span> Oficina 2.0</div>
        <nav>
            <?= $nav('/', 'Painel') ?>
            <?= $nav('/orcamentos', 'Orçamentos') ?>
            <?= $nav('/clientes', 'Clientes') ?>
            <?php if ($isAdmin): ?>
                <?= $nav('/usuarios', 'Usuários') ?>
            <?php endif; ?>
        </nav>
    </aside>

    <div class="content">
        <header class="topbar">
            <div class="flex items-center gap-2">
                <button class="btn btn-ghost btn-sm menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
                <h1><?= e($title ?? 'Painel') ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="user-chip">
                    <span class="avatar"><?= e(mb_strtoupper(mb_substr($user['name'] ?? '?', 0, 1))) ?></span>
                    <span>
                        <?= e($user['name'] ?? '') ?><br>
                        <small class="text-muted"><?= $isAdmin ? 'Administrador' : 'Mecânico' ?></small>
                    </span>
                </div>
                <form method="post" action="<?= url('logout') ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-ghost btn-sm" type="submit">Sair</button>
                </form>
            </div>
        </header>

        <main class="page">
            <?php if (!empty($flash['success'])): ?>
                <div class="alert alert-success"><?= e($flash['success']) ?></div>
            <?php endif; ?>
            <?php if (!empty($flash['error'])): ?>
                <div class="alert alert-danger"><?= e($flash['error']) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:18px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</div>
</body>
</html>
