<?php
require __DIR__. '/vendor/autoload.php';
use\App\Entity\Repair;

$repair = Repair::getRepair();
//echo "<pre>"; print_r($repair); echo "</pre>";
include __DIR__. '/includes/header.php';
include __DIR__. '/includes/list.php';
include __DIR__. '/includes/footer.php';
