<?php
/** @var array|null $user */
$isEdit = $user !== null;
$action = $isEdit ? url('usuarios/' . $user['id']) : url('usuarios');
$val = fn (string $k, $d = '') => e(old($k, $user[$k] ?? $d));
?>
<div class="card-glass" style="max-width:620px;">
    <h2 class="section-title"><?= $isEdit ? 'Editar usuário' : 'Novo usuário' ?></h2>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="grid grid-2">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= $val('name') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="<?= $val('email') ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Papel</label>
            <select name="role" class="form-select">
                <?php $role = old('role', $user['role'] ?? 'mecanico'); ?>
                <option value="mecanico" <?= $role === 'mecanico' ? 'selected' : '' ?>>Mecânico</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div class="grid grid-2">
            <div class="mb-3">
                <label class="form-label">Senha <?= $isEdit ? '(deixe em branco para manter)' : '' ?></label>
                <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div class="page-actions mt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a class="btn btn-ghost" href="<?= url('usuarios') ?>">Cancelar</a>
        </div>
    </form>
</div>
