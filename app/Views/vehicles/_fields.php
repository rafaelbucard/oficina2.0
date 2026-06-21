<?php
/** @var array|null $vehicle */
$v = fn (string $k, $d = '') => e(old($k, $vehicle[$k] ?? $d));
$type = old('type', $vehicle['type'] ?? 'carro');
?>
<div class="grid grid-2">
    <div class="mb-3">
        <label class="form-label">Tipo *</label>
        <select name="type" class="form-select">
            <option value="carro" <?= $type === 'carro' ? 'selected' : '' ?>>Carro</option>
            <option value="moto" <?= $type === 'moto' ? 'selected' : '' ?>>Moto</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Placa *</label>
        <input type="text" name="plate" class="form-control" value="<?= $v('plate') ?>" style="text-transform:uppercase" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Marca</label>
        <input type="text" name="brand" class="form-control" value="<?= $v('brand') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Modelo</label>
        <input type="text" name="model" class="form-control" value="<?= $v('model') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Ano</label>
        <input type="number" name="year" class="form-control" value="<?= $v('year') ?>" min="1900" max="2100">
    </div>
    <div class="mb-3">
        <label class="form-label">Cor</label>
        <input type="text" name="color" class="form-control" value="<?= $v('color') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Quilometragem</label>
        <input type="number" name="mileage" class="form-control" value="<?= $v('mileage') ?>" min="0">
    </div>
</div>
