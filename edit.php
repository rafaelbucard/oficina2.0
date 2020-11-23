<?php
require __DIR__. '/vendor/autoload.php';
use \App\Entity\Repair;

//validando o recebimento do id
if(!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');
    exit;
}
$ide = $_GET['id'];
echo $ide ;
$obRepair = Repair::getEdit($ide);
echo "<pre>"; print_r($obRepair); echo "</pre>"; exit;


//validando formulário POST
if(isset($_POST['vendedor'],$_POST['cliente'],$_POST['price'],$_POST['descricao'],$_POST['completo'])){

    $obRepair = new Repair;
    $obRepair->namem = $_POST['vendedor'];
    $obRepair->namec = $_POST['cliente'];
    $obRepair->price = $_POST['price'];
    $obRepair->desription = $_POST['descricao'];
    $obRepair->completed = $_POST['completo'];
    $obRepair->register();

    header('location: index.php?status=sucess');
   
}

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/form.php';
include __DIR__. '/includes/footer.php';
