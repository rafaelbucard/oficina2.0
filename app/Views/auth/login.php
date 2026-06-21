<div class="login-card">
    <h1><span class="brand"><span class="dot"></span></span> Oficina 2.0</h1>
    <p class="text-muted mb-4">Acesse o painel de orçamentos</p>

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

    <form method="post" action="<?= url('login') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" autofocus required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <p class="text-muted mt-4" style="font-size:0.8rem;">
        Acesso padrão: <strong>admin@oficina.local</strong> / <strong>admin123</strong>
    </p>
</div>
