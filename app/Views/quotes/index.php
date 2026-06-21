<?php
/** @var array $quotes @var App\Repositories\QuoteFilter $filter @var array $mechanics @var int $total @var int $lastPage */
?>
<div class="flex items-center justify-between mb-4">
    <p class="text-muted" style="margin:0;"><?= (int) $total ?> orçamento(s) encontrado(s)</p>
    <a class="btn btn-primary" href="<?= url('orcamentos/criar') ?>">+ Novo orçamento</a>
</div>

<div class="card-glass mb-4">
    <form method="get" action="<?= url('orcamentos') ?>">
        <div class="grid grid-4">
            <div>
                <label class="form-label">Busca rápida</label>
                <input type="text" name="q" class="form-control" placeholder="Código, cliente ou placa" value="<?= e($filter->search) ?>">
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach (quote_statuses() as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $filter->status === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label">Mecânico</label>
                <select name="created_by" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($mechanics as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $filter->createdBy === (int) $m['id'] ? 'selected' : '' ?>><?= e($m['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label">Tipo de veículo</label>
                <select name="vehicle_type" class="form-select">
                    <option value="">Todos</option>
                    <option value="carro" <?= $filter->vehicleType === 'carro' ? 'selected' : '' ?>>Carro</option>
                    <option value="moto" <?= $filter->vehicleType === 'moto' ? 'selected' : '' ?>>Moto</option>
                </select>
            </div>
            <div>
                <label class="form-label">Cliente</label>
                <input type="text" name="client" class="form-control" value="<?= e($filter->client) ?>">
            </div>
            <div>
                <label class="form-label">Placa</label>
                <input type="text" name="plate" class="form-control" value="<?= e($filter->plate) ?>">
            </div>
            <div>
                <label class="form-label">De</label>
                <input type="date" name="date_from" class="form-control" value="<?= e($filter->dateFrom) ?>">
            </div>
            <div>
                <label class="form-label">Até</label>
                <input type="date" name="date_to" class="form-control" value="<?= e($filter->dateTo) ?>">
            </div>
        </div>
        <div class="page-actions mt-3">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a class="btn btn-ghost" href="<?= url('orcamentos') ?>">Limpar filtros</a>
        </div>
    </form>
</div>

<div class="card-glass">
    <table class="table-glass">
        <thead>
            <tr>
                <th>Código</th><th>Cliente</th><th>Veículo</th><th>Status</th>
                <th>Mecânico</th><th>Data</th><th class="text-end">Total</th><th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($quotes)): ?>
            <tr><td colspan="8" class="text-muted" style="text-align:center; padding:24px;">Nenhum orçamento encontrado.</td></tr>
        <?php else: foreach ($quotes as $q): ?>
            <tr>
                <td><a href="<?= url('orcamentos/' . $q['id']) ?>"><strong><?= e($q['code']) ?></strong></a></td>
                <td><?= e($q['client_name']) ?><br><small class="text-muted"><?= e($q['client_phone']) ?></small></td>
                <td>
                    <?= e($q['vehicle_plate'] ?: '—') ?>
                    <?php if ($q['vehicle_type']): ?><br><small class="text-muted"><?= ucfirst($q['vehicle_type']) ?></small><?php endif; ?>
                </td>
                <td><span class="badge badge-<?= status_badge($q['status']) ?>"><?= e(status_label($q['status'])) ?></span></td>
                <td><?= e($q['created_by_name'] ?: '—') ?></td>
                <td><?= e(format_date($q['created_at'], 'd/m/Y H:i')) ?></td>
                <td class="text-end"><strong><?= money($q['total']) ?></strong></td>
                <td class="text-end"><a class="btn btn-ghost btn-sm" href="<?= url('orcamentos/' . $q['id']) ?>">Ver</a></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>

    <?php if ($lastPage > 1): ?>
        <div class="flex items-center justify-between mt-3">
            <span class="text-muted">Página <?= $filter->page ?> de <?= $lastPage ?></span>
            <div class="page-actions">
                <?php if ($filter->page > 1): ?>
                    <a class="btn btn-ghost btn-sm" href="<?= url('orcamentos?' . $filter->toQuery(['page' => $filter->page - 1])) ?>">Anterior</a>
                <?php endif; ?>
                <?php if ($filter->page < $lastPage): ?>
                    <a class="btn btn-ghost btn-sm" href="<?= url('orcamentos?' . $filter->toQuery(['page' => $filter->page + 1])) ?>">Próxima</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
