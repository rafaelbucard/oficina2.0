<?php
require __DIR__. '/vendor/autoload.php';
use \App\Entity\Repair;

//validando formulário POST
if(isset($_POST['vendedor'],$_POST['cliente'],$_POST['price'],$_POST['descricao'],$_POST['completo'])){

    $obRepair = new Repair;
    $obRepair->namem = $_POST['vendedor'];
    $obRepair->namec = $_POST['cliente'];
    $obRepair->price = $_POST['price'];
    $obRepair->desription = $_POST['descricao'];
    $obRepair->completed = $_POST['completo'];
    $obRepair->register();

    print_r($obRepair);
}

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/form.php';
include __DIR__. '/includes/footer.php';
