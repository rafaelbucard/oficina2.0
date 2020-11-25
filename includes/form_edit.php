<main>
    <section>
        <a href="index.php">
            <button class="btn btn-success">Voltar</button>
        </a>
    </section>
    <h2 class="mt-3"><?=TITLE?> </h2>
        <form method="post">
            <div class="form-group">
                <label>Mecânico</label>
                <input type="text" class="form-control" name="mecanico" value="<?=$obRepair->namem?>">
            </div>
            <div class="form-group">
                <label>Cliente</label>
                <input type="text" class="form-control" name="cliente" value="<?=$obRepair->namec?>">
            </div>
            <div class="form-group">
                <label>Preço</label>
                <input type="text" class="form-control" name="price" value="<?=$obRepair->price?>">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea class="form-control" name="descricao" rows="5" ><?=$obRepair->description?></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <div>
                    <div class="form-check form-check-inline">
                        <label class="form-control">
                            <input type="radio" name="completo" value="s" checked> Concluído
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-control">
                            <input type="radio" name="completo" value="n" <?=$obRepair->completed =='n'?'checked' :''?>> Em andamento
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
</main>
