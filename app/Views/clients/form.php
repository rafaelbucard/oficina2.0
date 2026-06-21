<?php
/** @var array|null $client */
$isEdit = $client !== null;
$action = $isEdit ? url('clientes/' . $client['id']) : url('clientes');
$val = fn (string $k, $d = '') => e(old($k, $client[$k] ?? $d));
?>
<div class="card-glass" style="max-width:680px;">
    <h2 class="section-title"><?= $isEdit ? 'Editar cliente' : 'Novo cliente' ?></h2>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="grid grid-2">
            <div class="mb-3">
                <label class="form-label">Nome *</label>
                <input type="text" name="name" class="form-control" value="<?= $val('name') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefone *</label>
                <input type="text" name="phone" class="form-control" value="<?= $val('phone') ?>" placeholder="(00) 00000-0000" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contato (e-mail / WhatsApp)</label>
                <input type="text" name="contact" class="form-control" value="<?= $val('contact') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Documento (CPF/CNPJ)</label>
                <input type="text" name="document" class="form-control" value="<?= $val('document') ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Observações</label>
            <textarea name="notes" class="form-control" rows="3"><?= $val('notes') ?></textarea>
        </div>

        <div class="page-actions mt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a class="btn btn-ghost" href="<?= $isEdit ? url('clientes/' . $client['id']) : url('clientes') ?>">Cancelar</a>
        </div>
    </form>
</div>
