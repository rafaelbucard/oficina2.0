<?php
require __DIR__. '/vendor/autoload.php';
use \App\Entity\Repair;

define('TITLE','Cadastrar Orçamento');

$obRepair = new Repair;
//validando formulário POST
if(isset($_POST['mecanico'],$_POST['cliente'],$_POST['price'],$_POST['descricao'],$_POST['completo'])){

    $obRepair->namem = $_POST['mecanico'];
    $obRepair->namec = $_POST['cliente'];
    $obRepair->price = $_POST['price'];
    $obRepair->description = $_POST['descricao'];
    $obRepair->completed = $_POST['completo'];
    $obRepair->register();
    header('location: index.php?status=success'); 
}

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/form_edit.php';
include __DIR__. '/includes/footer.php';
