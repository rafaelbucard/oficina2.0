<?php
/**
 * @var array|null $quote
 * @var array $items
 * @var array $clients
 * @var array $vehiclesByClient
 * @var int|null $selectedClient
 */
$isEdit = $quote !== null;
$action = $isEdit ? url('orcamentos/' . $quote['id']) : url('orcamentos');
$currentClient  = (int) old('client_id', $selectedClient ?? ($quote['client_id'] ?? 0));
$currentVehicle = (int) old('vehicle_id', $quote['vehicle_id'] ?? 0);
$currentStatus  = old('status', $quote['status'] ?? 'rascunho');
$discount       = old('discount', $quote['discount'] ?? '0');
?>
<div class="card-glass">
    <h2 class="section-title"><?= $isEdit ? 'Editar orçamento ' . e($quote['code']) : 'Novo orçamento' ?></h2>

    <form method="post" action="<?= $action ?>" id="quoteForm">
        <?= csrf_field() ?>
        <div class="grid grid-3">
            <div class="mb-3">
                <label class="form-label">Cliente *</label>
                <select name="client_id" id="clientSelect" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $currentClient === (int) $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?> · <?= e($c['phone']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Veículo</label>
                <select name="vehicle_id" id="vehicleSelect" class="form-select">
                    <option value="">Selecione o cliente primeiro...</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select">
                    <?php foreach (quote_statuses() as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $currentStatus === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h3 class="section-title mt-3">Itens</h3>
        <table class="table-glass" id="itemsTable">
            <thead>
                <tr>
                    <th style="width:130px">Tipo</th>
                    <th>Descrição</th>
                    <th style="width:110px">Qtd</th>
                    <th style="width:150px">Valor unit.</th>
                    <th style="width:150px" class="text-end">Subtotal</th>
                    <th style="width:50px"></th>
                </tr>
            </thead>
            <tbody id="itemsBody"></tbody>
        </table>
        <button type="button" class="btn btn-ghost btn-sm mt-2" id="addItem">+ Adicionar item</button>

        <div class="grid grid-2 mt-4">
            <div>
                <label class="form-label">Observações</label>
                <textarea name="notes" class="form-control" rows="4"><?= e(old('notes', $quote['notes'] ?? '')) ?></textarea>
            </div>
            <div class="card-glass" style="background:rgba(5,7,13,0.4)">
                <div class="flex items-center justify-between mt-2">
                    <span class="text-muted">Subtotal</span>
                    <strong id="subtotalLabel">R$ 0,00</strong>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-muted">Desconto (R$)</span>
                    <input type="text" name="discount" id="discountInput" class="form-control" style="max-width:140px; text-align:right" value="<?= e($discount) ?>">
                </div>
                <hr style="border-color:var(--border)">
                <div class="flex items-center justify-between">
                    <span style="font-size:1.1rem">Total</span>
                    <strong id="totalLabel" style="font-size:1.3rem; color:#fff">R$ 0,00</strong>
                </div>
            </div>
        </div>

        <div class="page-actions mt-4">
            <button type="submit" class="btn btn-primary">Salvar orçamento</button>
            <a class="btn btn-ghost" href="<?= $isEdit ? url('orcamentos/' . $quote['id']) : url('orcamentos') ?>">Cancelar</a>
        </div>
    </form>
</div>

<script>
const vehiclesByClient = <?= json_encode($vehiclesByClient, JSON_UNESCAPED_UNICODE) ?>;
const existingItems = <?= json_encode(array_map(fn ($i) => [
    'item_type'   => $i['item_type'],
    'description' => $i['description'],
    'quantity'    => $i['quantity'],
    'unit_price'  => $i['unit_price'],
], $items), JSON_UNESCAPED_UNICODE) ?>;
const selectedVehicle = <?= (int) $currentVehicle ?>;

function brl(value) {
    return 'R$ ' + (value || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function parseNumber(raw) {
    if (typeof raw !== 'string') raw = String(raw ?? '');
    raw = raw.trim();
    if (raw.includes(',')) raw = raw.replace(/\./g, '').replace(',', '.');
    return parseFloat(raw) || 0;
}

function populateVehicles() {
    const clientId = document.getElementById('clientSelect').value;
    const select = document.getElementById('vehicleSelect');
    const list = vehiclesByClient[clientId] || [];
    select.innerHTML = '<option value="">Sem veículo</option>';
    list.forEach(v => {
        const label = v.plate + ' · ' + (v.type === 'moto' ? 'Moto' : 'Carro') +
            (v.model ? ' · ' + (v.brand || '') + ' ' + v.model : '');
        const opt = new Option(label.trim(), v.id);
        if (parseInt(v.id) === selectedVehicle) opt.selected = true;
        select.add(opt);
    });
}

function itemRow(data = {}) {
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td>
            <select name="item_type[]" class="form-select">
                <option value="peca" ${data.item_type === 'peca' ? 'selected' : ''}>Peça</option>
                <option value="servico" ${data.item_type === 'servico' ? 'selected' : ''}>Serviço</option>
            </select>
        </td>
        <td><input type="text" name="description[]" class="form-control" value="${(data.description || '').replace(/"/g, '&quot;')}" placeholder="Descrição"></td>
        <td><input type="text" name="quantity[]" class="form-control qty" value="${data.quantity ?? 1}" style="text-align:right"></td>
        <td><input type="text" name="unit_price[]" class="form-control price" value="${data.unit_price ?? 0}" style="text-align:right"></td>
        <td class="text-end line-total">R$ 0,00</td>
        <td class="text-end"><button type="button" class="btn btn-danger btn-sm removeItem">×</button></td>
    `;
    return tr;
}

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseNumber(row.querySelector('.qty').value);
        const price = parseNumber(row.querySelector('.price').value);
        const total = qty * price;
        subtotal += total;
        row.querySelector('.line-total').textContent = brl(total);
    });
    const discount = parseNumber(document.getElementById('discountInput').value);
    document.getElementById('subtotalLabel').textContent = brl(subtotal);
    document.getElementById('totalLabel').textContent = brl(Math.max(0, subtotal - discount));
}

const body = document.getElementById('itemsBody');

function addRow(data) {
    body.appendChild(itemRow(data));
    recalc();
}

document.getElementById('addItem').addEventListener('click', () => addRow({}));
document.getElementById('discountInput').addEventListener('input', recalc);
body.addEventListener('input', recalc);
body.addEventListener('click', e => {
    if (e.target.classList.contains('removeItem')) {
        e.target.closest('tr').remove();
        recalc();
    }
});
document.getElementById('clientSelect').addEventListener('change', populateVehicles);

populateVehicles();
if (existingItems.length) {
    existingItems.forEach(addRow);
} else {
    addRow({});
}
recalc();
</script>
