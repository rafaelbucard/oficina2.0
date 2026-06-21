<?php /** @var array $users */ ?>
<div class="flex items-center justify-between mb-4">
    <p class="text-muted" style="margin:0;">Gerencie os usuários que acessam o sistema.</p>
    <a class="btn btn-primary" href="<?= url('usuarios/criar') ?>">+ Novo usuário</a>
</div>

<div class="card-glass">
    <table class="table-glass">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Papel</th>
                <th>Situação</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><strong><?= e($u['name']) ?></strong></td>
                <td><?= e($u['email']) ?></td>
                <td>
                    <span class="badge badge-<?= $u['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                        <?= $u['role'] === 'admin' ? 'Administrador' : 'Mecânico' ?>
                    </span>
                </td>
                <td>
                    <span class="badge badge-<?= $u['active'] ? 'success' : 'danger' ?>">
                        <?= $u['active'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </td>
                <td class="text-end">
                    <div class="page-actions" style="justify-content:flex-end;">
                        <a class="btn btn-ghost btn-sm" href="<?= url('usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
                        <form method="post" action="<?= url('usuarios/' . $u['id'] . '/status') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm <?= $u['active'] ? 'btn-danger' : 'btn-success' ?>" type="submit">
                                <?= $u['active'] ? 'Desativar' : 'Ativar' ?>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
