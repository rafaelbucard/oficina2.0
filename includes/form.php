<main>
    <section>
        <a href="index.php">
            <button class="btn btn-success">Voltar</button>
        </a>
    </section>
    <h2 class="mt-3">Cadastro de Orçamento </h2>
        <form method="post">
            <div class="form-group">
                <label>Vendedor</label>
                <input type="text" class="form-control" name="vendedor">
            </div>
            <div class="form-group">
                <label>Cliente</label>
                <input type="text" class="form-control" name="cliente">
            </div>
            <div class="form-group">
                <label>preço</label>
                <input type="number" class="form-control" name="price">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea class="form-control" name="descricao" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <div>
                    <div class="form-check form-check-inline">
                        <label class="form-control">
                            <input type="radio" name="ativo" value="s"> Concluído
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-control">
                            <input type="radio" name="ativo" value="n" checked> Em andamento
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
</main>