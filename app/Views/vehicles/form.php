<?php /** @var array $vehicle @var array $client */ ?>
<div class="card-glass" style="max-width:680px;">
    <h2 class="section-title">Editar veículo · <?= e($client['name'] ?? '') ?></h2>

    <form method="post" action="<?= url('veiculos/' . $vehicle['id']) ?>">
        <?= csrf_field() ?>
        <?= App\Core\View::renderPartial('vehicles/_fields', ['vehicle' => $vehicle]) ?>
        <div class="page-actions mt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a class="btn btn-ghost" href="<?= url('clientes/' . ($client['id'] ?? '')) ?>">Cancelar</a>
        </div>
    </form>
</div>
