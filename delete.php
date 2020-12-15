<?php
require __DIR__. '/vendor/autoload.php';
use \App\Entity\Repair;



//validando o recebimento do id
if(!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');
    exit;
}

$ide = $_GET['id'];
$obRepair = Repair::getEdit($ide);

if(!$obRepair instanceof Repair){
    header('location: index.php?status=error');
    exit;
}

//validando formulário POST
if(isset($_POST['delete'])){

    $obRepair->delete();
    header('location: index.php?status=success');
   
}

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/confirm_delete.php';
include __DIR__. '/includes/footer.php';
