<?php

use App\Entity\Repair;

$result = '';
rsort($repair);
foreach($repair as $rep){
  $result .= 
  '<tr>
         <td>'.$rep->id.'</td>
         <td>'.$rep->namem.'</td>
         <td>'.$rep->namec.'</td>
         <td>'.$rep->description.'</td>
         <td>'.$rep->price.'</td>
         <td>'.($rep->completed == 's' ? 'Conclúdo' : 'Em Andamento').'</td>
         <td>'.date('d/m/Y à\s H:i:s',strtotime($rep->date)).'</td>
         <td class="d-flex  ">
            <a href="edit.php?id='.$rep->id.'">
                 <button type="button" class="btn btn-primary">Editar&nbsp;</button>
            </a>
            <a href="delete.php?id='.$rep->id.'">
                 <button type="button" class="btn btn-danger align-items-end ml-2 ">Excluir</button>
            </a>
         </td>
  </tr>';
}
$result = strlen($result) ? $result : 
                '<tr>
                     <td colspan="6" class="text-center">
                     Nenhum Orçamento encontrado!
                     </td>
                 </tr>';

$msg = '';
if(isset($_GET['status'])){
    switch($_GET['status']) {
        case 'success':
            $msg = '<div class="alert alert-success">Ação executada com sucesso</div>';
        break;
        case 'error':
            $msg = '<div class="alert alert-danger">Erro ao executar ação</div>';
        break;
    }
}
?>
<main>
    <?=$msg;?>
    <section class="d-flex  ">
        <a href="create.php">
            <button class="btn btn-success">Novo Orçamento</button>
        </a>
        <a href="index.php">
             <button class="btn btn-primary align-items-end ml-2">Limpar pesquisa</button>
        </a>
    </section>
    <section class="d-flex">
        <form method="get" class="d-flex align-items-end" >
            <div class="row my-4">
                <div class="col">
                    <label>Buscar Mecânico</label>
                    <input type="text" name="search" class="form-control" value="<?=$search ?>">
                </div>
                <div class="col d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
        <form method="get" class="d-flex align-items-end">
            <div class="row my-4">
                <div class="col">
                    <label>Buscar Cliente</label>
                    <input type="text" name="searchClient" class="form-control" value="<?=$searchClient ?>">
                 </div>
                <div class="col d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
        <form method="get" class="d-flex align-items-end">
            <div class="row my-4">
                <div class="col">
                    <label>Por data</label>
                    <select name="date" class="form-control">
                        <?php foreach ($repair as $rep): ?>
                            <option value="<?=substr(date('Y-m-d H:i:s',strtotime($rep->date)), 0, 10);?>">
                                    <?=substr(date('Y-m-d H:i:s',strtotime($rep->date)), 0, 10);?></option>
                        <?php endforeach; ?>

                    </select>
                 </div>
                <div class="col d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
    </section>
    <section>
        <table class="table bg-light text-dark mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mecânico</th>
                    <th>Cliente</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th>Data e Hora</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?=$result?>
             </tbody>    
        </table>
    </section>
</main>



