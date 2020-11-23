<?php
/**
  * Ofcina2.0.
  *      
  * @author    Rafael Buçard
  */
require __DIR__. '/vendor/autoload.php';
use\App\Entity\Repair;

$repair = Repair::getRepair();

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/list.php';
include __DIR__. '/includes/footer.php';
