<?php
$result = '';
foreach($repair as $rep){
  $result .= '<tr>
                    <td>'.$rep->id.'</td>
                    <td>'.$rep->namem.'</td>
                    <td>'.$rep->namec.'</td>
                    <td>'.$rep->description.'</td>
                    <td>'.($rep->completed == 's' ? 'Conclúdo' : 'Em Andamento').'</td>
                    <td>'.date('d/m/Y à\s H:i:s',strtotime($rep->date)).'</td>
                    <td>
                      <a href="edit.php?id='.$rep->id.'">
                        <button type="button" class="btn btn-primary">Editar&nbsp;</button>
                      </a>
                      <a href="delete.php?id='.$rep->id.'">
                        <button type="button" class="btn btn-danger mt-1">Excluir</button>
                      </a>
                    </td>
                  </tr>';
}

$result = strlen($result) ? $result : '<tr>
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
    <section>
        <a href="create.php">
            <button class="btn btn-success">Novo Orçamento</button>
        </a>
    </section>
    <section>
        <form method="get">
            <div class="row my-4">
                <div class="col">
                    <label>Buscar por Mecânico</label>
                    <input type="text" name="search" class="form-control" value="<?=$search ?>">
                </div>
                <div class="col d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Buscar</button>
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