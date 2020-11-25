<?php
/**
  * Ofcina2.0.
  *      
  * @author    Rafael Buçard
  */
require __DIR__. '/vendor/autoload.php';
use\App\Entity\Repair;

//Buscas 
$search = filter_input(INPUT_GET,'search', FILTER_SANITIZE_STRING);
$condition = [
  strlen($search) ? 'namem LIKE "%'.str_replace(' ','%',$search).'%"' : null

];
$where = implode(' AND ',$condition);
//echo'<pre>'; print_r($where);echo '</pre>';exit;
if($search){
$repair = Repair::getSearch($where);
} else {

  $repair = Repair::getRepair();
}


include __DIR__. '/includes/header.php';
include __DIR__. '/includes/list.php';
include __DIR__. '/includes/footer.php';
