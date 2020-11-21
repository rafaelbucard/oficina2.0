<?php
$result = '';
foreach($repair as $rep) {
    $result = '<tr>
                    <tb>'. $rep->id.'</tb>
                    <tb>'. $rep->namem.'</tb>
                    <tb>'. $rep->namec.'</tb>
                    <tb>'. $rep->desription.'</tb>
                    <tb>'. $rep->price.'</tb>
                    <tb>'. $rep->price.'</tb>
                    
    
    
    
    
                </tr>';
}
?>
<main>
    <section>
        <a href="create.php">
            <button class="btn btn-success">Novo Orçamento</button>
        </a>
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
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result;
            ?>
            </tbody>
        </table>
    </section>
</main>