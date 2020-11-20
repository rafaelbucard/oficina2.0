<?php
require __DIR__. '/vendor/autoload.php';

//validando formulário POST
if(isset($_POST['vendedor'],$_POST['cliente'],$_POST['price'],$_POST['descricao'],$_POST['ativo'])){
    die("cadastro ");
}

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/form.php';
include __DIR__. '/includes/footer.php';
