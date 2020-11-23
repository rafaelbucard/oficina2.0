<?php
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
        <table class="table bg-light text-dark mt-3">
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
                <?php foreach ($repair as $rep): ?>
                <tr>
                    <td><?=$rep->id;?></td>
                    <td><?=$rep->namem;?></td>            
                    <td><?=$rep->namec;?></td>
                    <td><?=$rep->description;?></td>
                    <td><?=$rep->price;?></td>
                    <td><?=($rep->completed=='s'?'Concluído':'Em andamento');?></td>
                    <td><?=date('d/m/Y à\s H:i:s',strtotime($rep->date));?></td>
                    <td>
                        <a href="edit.php?id=<?=$rep->id;?>">
                        <button type="button" class="btn btn-primary">Editar&nbsp;</button>
                        </a>
                        <a href="delete.php?id=<?=$rep->id;?>" onclick="return confirm('Tem certeza que deseja excluir?')">
                        <button type="button" class="btn btn-danger mt-1" >Excluir</button>
                         </a>
                    </td>
               </tr>
            <?php endforeach; ?> 
        </table>
    </section>
</main>