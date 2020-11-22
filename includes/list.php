<main>
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
                    <td><?=$rep->date;?></td>
                    <td></td>
            </tr>
            <?php endforeach; ?> 
        </table>
    </section>
</main>