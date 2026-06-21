<?php /** @var array $quote @var array $items @var float $subtotal */ ?>
<div class="flex items-center justify-between mb-4">
    <div class="page-actions">
        <a class="btn btn-ghost" href="<?= url('orcamentos') ?>">← Voltar</a>
        <a class="btn btn-ghost" href="<?= url('orcamentos/' . $quote['id'] . '/editar') ?>">Editar</a>
        <a class="btn btn-primary" href="<?= url('orcamentos/' . $quote['id'] . '/pdf') ?>" target="_blank">Imprimir / PDF</a>
    </div>
    <form method="post" action="<?= url('orcamentos/' . $quote['id'] . '/excluir') ?>" onsubmit="return confirm('Excluir este orçamento?')">
        <?= csrf_field() ?>
        <button class="btn btn-danger" type="submit">Excluir</button>
    </form>
</div>

<div class="grid grid-3 mb-4">
    <div class="card-glass">
        <h2 class="section-title">Orçamento</h2>
        <p class="mt-2" style="font-size:1.4rem; color:#fff;"><strong><?= e($quote['code']) ?></strong></p>
        <p><span class="badge badge-<?= status_badge($quote['status']) ?>"><?= e(status_label($quote['status'])) ?></span></p>
        <p class="text-muted">Criado por <?= e($quote['created_by_name'] ?: '—') ?><br><?= e(format_date($quote['created_at'])) ?></p>
    </div>
    <div class="card-glass">
        <h2 class="section-title">Cliente</h2>
        <p class="mt-2"><strong><?= e($quote['client_name']) ?></strong></p>
        <p><span class="text-muted">Telefone:</span> <?= e($quote['client_phone']) ?></p>
        <p><span class="text-muted">Contato:</span> <?= e($quote['client_contact'] ?: '—') ?></p>
    </div>
    <div class="card-glass">
        <h2 class="section-title">Veículo</h2>
        <?php if ($quote['vehicle_plate']): ?>
            <p class="mt-2"><strong><?= e($quote['vehicle_plate']) ?></strong> · <?= ucfirst((string) $quote['vehicle_type']) ?></p>
            <p><?= e(trim(($quote['vehicle_brand'] ?? '') . ' ' . ($quote['vehicle_model'] ?? '')) ?: '—') ?></p>
            <p><span class="text-muted">Ano:</span> <?= e($quote['vehicle_year'] ?: '—') ?></p>
        <?php else: ?>
            <p class="text-muted mt-2">Sem veículo vinculado.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card-glass mb-4">
    <div class="flex items-center justify-between mb-3">
        <h2 class="section-title" style="margin:0;">Atualizar status</h2>
    </div>
    <form method="post" action="<?= url('orcamentos/' . $quote['id'] . '/status') ?>" class="flex gap-2" style="max-width:420px;">
        <?= csrf_field() ?>
        <select name="status" class="form-select">
            <?php foreach (quote_statuses() as $key => $label): ?>
                <option value="<?= $key ?>" <?= $quote['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Aplicar</button>
    </form>
</div>

<div class="card-glass">
    <h2 class="section-title">Itens</h2>
    <table class="table-glass">
        <thead>
            <tr><th>Tipo</th><th>Descrição</th><th class="text-end">Qtd</th><th class="text-end">Valor unit.</th><th class="text-end">Subtotal</th></tr>
        </thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="5" class="text-muted" style="text-align:center; padding:18px;">Nenhum item.</td></tr>
        <?php else: foreach ($items as $i): ?>
            <tr>
                <td><span class="badge badge-<?= $i['item_type'] === 'servico' ? 'info' : 'secondary' ?>"><?= $i['item_type'] === 'servico' ? 'Serviço' : 'Peça' ?></span></td>
                <td><?= e($i['description']) ?></td>
                <td class="text-end"><?= e(rtrim(rtrim(number_format((float) $i['quantity'], 2, ',', '.'), '0'), ',')) ?></td>
                <td class="text-end"><?= money($i['unit_price']) ?></td>
                <td class="text-end"><?= money($i['line_total']) ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>

    <div class="flex justify-between mt-4" style="max-width:320px; margin-left:auto; flex-direction:column; gap:8px;">
        <div class="flex items-center justify-between"><span class="text-muted">Subtotal</span><span><?= money($subtotal) ?></span></div>
        <div class="flex items-center justify-between"><span class="text-muted">Desconto</span><span>− <?= money($quote['discount']) ?></span></div>
        <hr style="border-color:var(--border); width:100%;">
        <div class="flex items-center justify-between"><span style="font-size:1.1rem;">Total</span><strong style="font-size:1.3rem; color:#fff;"><?= money($quote['total']) ?></strong></div>
    </div>

    <?php if (!empty($quote['notes'])): ?>
        <div class="mt-4">
            <h3 class="form-label">Observações</h3>
            <p><?= nl2br(e($quote['notes'])) ?></p>
        </div>
    <?php endif; ?>
</div>
