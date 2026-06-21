<?php
/** @var array $statusSummary @var float $totalApproved @var array $recent @var int $clientsCount */
$totalQuotes = array_sum($statusSummary);
$open = ($statusSummary['rascunho'] ?? 0) + ($statusSummary['aguardando'] ?? 0) + ($statusSummary['em_andamento'] ?? 0);
?>
<div class="grid grid-4 mb-4">
    <div class="card-glass kpi">
        <span class="label">Orçamentos</span>
        <span class="value"><?= (int) $totalQuotes ?></span>
    </div>
    <div class="card-glass kpi">
        <span class="label">Em aberto</span>
        <span class="value"><?= (int) $open ?></span>
    </div>
    <div class="card-glass kpi">
        <span class="label">Valor aprovado</span>
        <span class="value" style="font-size:1.4rem;"><?= money($totalApproved) ?></span>
    </div>
    <div class="card-glass kpi">
        <span class="label">Clientes</span>
        <span class="value"><?= (int) $clientsCount ?></span>
    </div>
</div>

<div class="grid grid-2">
    <div class="card-glass">
        <h2 class="section-title">Por status</h2>
        <?php foreach (quote_statuses() as $key => $label): $count = $statusSummary[$key] ?? 0; ?>
            <div class="flex items-center justify-between mt-2">
                <span class="badge badge-<?= status_badge($key) ?>"><?= e($label) ?></span>
                <strong><?= (int) $count ?></strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card-glass">
        <div class="flex items-center justify-between mb-3">
            <h2 class="section-title" style="margin:0;">Últimos orçamentos</h2>
            <a class="btn btn-ghost btn-sm" href="<?= url('orcamentos') ?>">Ver todos</a>
        </div>
        <?php if (empty($recent)): ?>
            <p class="text-muted">Nenhum orçamento ainda. <a href="<?= url('orcamentos/criar') ?>">Criar o primeiro</a>.</p>
        <?php else: ?>
            <table class="table-glass">
                <tbody>
                <?php foreach ($recent as $q): ?>
                    <tr>
                        <td><a href="<?= url('orcamentos/' . $q['id']) ?>"><strong><?= e($q['code']) ?></strong></a></td>
                        <td><?= e($q['client_name']) ?></td>
                        <td><span class="badge badge-<?= status_badge($q['status']) ?>"><?= e(status_label($q['status'])) ?></span></td>
                        <td class="text-end"><?= money($q['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
