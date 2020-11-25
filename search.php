<?php

require __DIR__. '/vendor/autoload.php';
use\App\Entity\Repair;

//Buscas 
$search = filter_input(INPUT_GET,'search', FILTER_SANITIZE_STRING);
$condition = [
  strlen($search) ? 'namem LIKE "%'.$search.'%"' : null

];
$where = implode(' AND ',$condition);
//echo'<pre>'; print_r($where);echo '</pre>';exit;
$repair = Repair::getSearch($where);

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/list.php';
include __DIR__. '/includes/footer.php';
