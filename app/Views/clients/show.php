<?php /** @var array $client @var array $vehicles @var array $quotes */ ?>
<div class="flex items-center justify-between mb-4">
    <div class="page-actions">
        <a class="btn btn-ghost" href="<?= url('clientes') ?>">← Voltar</a>
        <a class="btn btn-ghost" href="<?= url('clientes/' . $client['id'] . '/editar') ?>">Editar cliente</a>
    </div>
    <a class="btn btn-primary" href="<?= url('orcamentos/criar?client_id=' . $client['id']) ?>">+ Novo orçamento</a>
</div>

<div class="grid grid-2 mb-4">
    <div class="card-glass">
        <h2 class="section-title">Dados do cliente</h2>
        <p class="mt-2"><span class="text-muted">Telefone:</span> <strong><?= e($client['phone']) ?></strong></p>
        <p><span class="text-muted">Contato:</span> <?= e($client['contact'] ?: '—') ?></p>
        <p><span class="text-muted">Documento:</span> <?= e($client['document'] ?: '—') ?></p>
        <?php if (!empty($client['notes'])): ?>
            <p><span class="text-muted">Observações:</span><br><?= nl2br(e($client['notes'])) ?></p>
        <?php endif; ?>
    </div>

    <div class="card-glass">
        <h2 class="section-title">Adicionar veículo</h2>
        <form method="post" action="<?= url('clientes/' . $client['id'] . '/veiculos') ?>">
            <?= csrf_field() ?>
            <?= App\Core\View::renderPartial('vehicles/_fields', ['vehicle' => null]) ?>
            <button type="submit" class="btn btn-primary mt-2">Adicionar veículo</button>
        </form>
    </div>
</div>

<div class="card-glass mb-4">
    <h2 class="section-title">Veículos (<?= count($vehicles) ?>)</h2>
    <table class="table-glass">
        <thead>
            <tr><th>Tipo</th><th>Placa</th><th>Marca/Modelo</th><th>Ano</th><th>Cor</th><th>KM</th><th class="text-end">Ações</th></tr>
        </thead>
        <tbody>
        <?php if (empty($vehicles)): ?>
            <tr><td colspan="7" class="text-muted" style="text-align:center; padding:18px;">Nenhum veículo cadastrado.</td></tr>
        <?php else: foreach ($vehicles as $v): ?>
            <tr>
                <td><span class="badge badge-<?= $v['type'] === 'moto' ? 'info' : 'primary' ?>"><?= ucfirst($v['type']) ?></span></td>
                <td><strong><?= e($v['plate']) ?></strong></td>
                <td><?= e(trim(($v['brand'] ?? '') . ' ' . ($v['model'] ?? '')) ?: '—') ?></td>
                <td><?= e($v['year'] ?: '—') ?></td>
                <td><?= e($v['color'] ?: '—') ?></td>
                <td><?= $v['mileage'] !== null ? e(number_format((int) $v['mileage'], 0, ',', '.')) : '—' ?></td>
                <td class="text-end">
                    <a class="btn btn-ghost btn-sm" href="<?= url('veiculos/' . $v['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= url('veiculos/' . $v['id'] . '/excluir') ?>" style="display:inline" onsubmit="return confirm('Excluir este veículo?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-danger btn-sm" type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="card-glass">
    <h2 class="section-title">Orçamentos do cliente (<?= count($quotes) ?>)</h2>
    <table class="table-glass">
        <thead>
            <tr><th>Código</th><th>Veículo</th><th>Status</th><th>Criado por</th><th>Data</th><th class="text-end">Total</th></tr>
        </thead>
        <tbody>
        <?php if (empty($quotes)): ?>
            <tr><td colspan="6" class="text-muted" style="text-align:center; padding:18px;">Nenhum orçamento.</td></tr>
        <?php else: foreach ($quotes as $q): ?>
            <tr>
                <td><a href="<?= url('orcamentos/' . $q['id']) ?>"><strong><?= e($q['code']) ?></strong></a></td>
                <td><?= e($q['vehicle_plate'] ?: '—') ?></td>
                <td><span class="badge badge-<?= status_badge($q['status']) ?>"><?= e(status_label($q['status'])) ?></span></td>
                <td><?= e($q['created_by_name'] ?: '—') ?></td>
                <td><?= e(format_date($q['created_at'], 'd/m/Y')) ?></td>
                <td class="text-end"><?= money($q['total']) ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
