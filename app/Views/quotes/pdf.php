<?php /** @var array $quote @var array $items @var float $subtotal @var string $appName */ ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #1a1a1a; font-size: 12px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 10px; margin-bottom: 18px; }
        .header h1 { color: #1d4ed8; margin: 0; font-size: 22px; }
        .header .code { float: right; text-align: right; }
        .header .code strong { font-size: 16px; }
        .muted { color: #666; }
        .grid { width: 100%; margin-bottom: 16px; }
        .grid td { vertical-align: top; width: 50%; padding: 6px 0; }
        .box { border: 1px solid #ddd; border-radius: 6px; padding: 10px; }
        .box h3 { margin: 0 0 6px; font-size: 12px; color: #1d4ed8; text-transform: uppercase; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.items th { background: #2563eb; color: #fff; text-align: left; padding: 7px; font-size: 11px; }
        table.items td { padding: 7px; border-bottom: 1px solid #eee; }
        .right { text-align: right; }
        .totals { width: 40%; float: right; margin-top: 12px; }
        .totals td { padding: 5px 7px; }
        .totals .total td { border-top: 2px solid #2563eb; font-size: 15px; font-weight: bold; color: #1d4ed8; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 10px; background: #e0ecff; color: #1d4ed8; font-size: 11px; }
        .notes { clear: both; margin-top: 28px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="code">
            <span class="muted">Orçamento</span><br>
            <strong><?= e($quote['code']) ?></strong><br>
            <span class="status"><?= e(status_label($quote['status'])) ?></span>
        </div>
        <h1><?= e($appName) ?></h1>
        <span class="muted">Emitido em <?= e(format_date($quote['created_at'], 'd/m/Y H:i')) ?></span>
    </div>

    <table class="grid">
        <tr>
            <td style="padding-right:8px;">
                <div class="box">
                    <h3>Cliente</h3>
                    <strong><?= e($quote['client_name']) ?></strong><br>
                    Telefone: <?= e($quote['client_phone']) ?><br>
                    Contato: <?= e($quote['client_contact'] ?: '—') ?>
                </div>
            </td>
            <td style="padding-left:8px;">
                <div class="box">
                    <h3>Veículo</h3>
                    <?php if ($quote['vehicle_plate']): ?>
                        <strong><?= e($quote['vehicle_plate']) ?></strong> (<?= ucfirst((string) $quote['vehicle_type']) ?>)<br>
                        <?= e(trim(($quote['vehicle_brand'] ?? '') . ' ' . ($quote['vehicle_model'] ?? '')) ?: '—') ?><br>
                        Ano: <?= e($quote['vehicle_year'] ?: '—') ?>
                    <?php else: ?>
                        <span class="muted">Sem veículo vinculado.</span>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Tipo</th><th>Descrição</th>
                <th class="right">Qtd</th><th class="right">Valor unit.</th><th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $i): ?>
            <tr>
                <td><?= $i['item_type'] === 'servico' ? 'Serviço' : 'Peça' ?></td>
                <td><?= e($i['description']) ?></td>
                <td class="right"><?= e(rtrim(rtrim(number_format((float) $i['quantity'], 2, ',', '.'), '0'), ',')) ?></td>
                <td class="right"><?= money($i['unit_price']) ?></td>
                <td class="right"><?= money($i['line_total']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="muted">Subtotal</td><td class="right"><?= money($subtotal) ?></td></tr>
        <tr><td class="muted">Desconto</td><td class="right">− <?= money($quote['discount']) ?></td></tr>
        <tr class="total"><td>Total</td><td class="right"><?= money($quote['total']) ?></td></tr>
    </table>

    <?php if (!empty($quote['notes'])): ?>
        <div class="notes">
            <strong>Observações:</strong><br>
            <?= nl2br(e($quote['notes'])) ?>
        </div>
    <?php endif; ?>
</body>
</html>
