<?php /** @var array $clients @var string $term @var int $page @var int $lastPage @var int $total */ ?>
<div class="flex items-center justify-between mb-4">
    <form method="get" action="<?= url('clientes') ?>" class="flex gap-2" style="flex:1; max-width:480px;">
        <input type="text" name="q" class="form-control" placeholder="Buscar por nome, telefone ou documento" value="<?= e($term) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
        <?php if ($term !== ''): ?><a class="btn btn-ghost" href="<?= url('clientes') ?>">Limpar</a><?php endif; ?>
    </form>
    <a class="btn btn-primary" href="<?= url('clientes/criar') ?>">+ Novo cliente</a>
</div>

<div class="card-glass">
    <table class="table-glass">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Contato</th>
                <th>Veículos</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($clients)): ?>
            <tr><td colspan="5" class="text-muted" style="text-align:center; padding:24px;">Nenhum cliente encontrado.</td></tr>
        <?php else: foreach ($clients as $c): ?>
            <tr>
                <td><a href="<?= url('clientes/' . $c['id']) ?>"><strong><?= e($c['name']) ?></strong></a></td>
                <td><?= e($c['phone']) ?></td>
                <td><?= e($c['contact'] ?: '—') ?></td>
                <td><span class="badge badge-secondary"><?= (int) $c['vehicles_count'] ?></span></td>
                <td class="text-end">
                    <a class="btn btn-ghost btn-sm" href="<?= url('clientes/' . $c['id']) ?>">Detalhes</a>
                    <a class="btn btn-ghost btn-sm" href="<?= url('clientes/' . $c['id'] . '/editar') ?>">Editar</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>

    <?php if ($lastPage > 1): ?>
        <div class="flex items-center justify-between mt-3">
            <span class="text-muted"><?= (int) $total ?> cliente(s)</span>
            <div class="page-actions">
                <?php $q = $term !== '' ? '&q=' . urlencode($term) : ''; ?>
                <?php if ($page > 1): ?><a class="btn btn-ghost btn-sm" href="<?= url('clientes?page=' . ($page - 1) . $q) ?>">Anterior</a><?php endif; ?>
                <span class="text-muted">Página <?= $page ?> de <?= $lastPage ?></span>
                <?php if ($page < $lastPage): ?><a class="btn btn-ghost btn-sm" href="<?= url('clientes?page=' . ($page + 1) . $q) ?>">Próxima</a><?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
