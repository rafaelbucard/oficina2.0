<main>
    <h2 class="mt-3">Excluir Orçamento com Id:  <?=$obRepair->id;?></h2>
        <form method="post">
            <div class="form-group">
                <p>Ao clicar em <strong>Excluir</strong> o Orçamento será excluído permanentemente!</p>
            </div>   
            <div class="form-group">
                <a href="index.php">
                     <button  type="button" class="btn btn-success">Voltar</button>
                </a>
                <button type="submit"  name="delete" class="btn btn-danger">Excluir</button>
            </div>
        </form>
</main>
