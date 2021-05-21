<?php
/**
  * Ofcina2.0.
  *      
  * @author    Rafael Buçard
  */
require __DIR__. '/vendor/autoload.php';
use \App\Entity\Repair;
//Recebendo valores de Buscas 
$search = filter_input(INPUT_GET,'search', FILTER_SANITIZE_STRING);
$searchClient = filter_input(INPUT_GET,'searchClient', FILTER_SANITIZE_STRING);
$searchDate = filter_input(INPUT_GET,'date', FILTER_SANITIZE_STRING);

//Construindo String para Buscas
$condition = [
  strlen($search) ? 'namem LIKE "%'.str_replace(' ','%',$search).'%"' : null

];
$conditionClient = [
  strlen($searchClient) ? 'namec LIKE "%'.str_replace(' ','%',$searchClient).'%"' : null

];
$conditionDate = [
  strlen($searchDate) ? 'date LIKE "%'.str_replace(' ','%',str_replace('/','%',$searchDate)).'%"' : null

];

if($search){

    $where = implode(' AND ',$condition);
   
 } elseif($searchDate){

    $where = implode(' AND ',$conditionDate);
 }
  else{

  $where = implode(' AND ',$conditionClient);
 }
 

//Condicional para exibir resultado da busca
 if($search || $searchClient ){

    $repair = Repair::getSearch($where);

} elseif($searchDate){

   $repair = Repair::getSearch($where);
}
else {

  $repair = Repair::getRepair();
}


include __DIR__. '/includes/header.php';
include __DIR__. '/includes/list.php';
//echo phpinfo();
include __DIR__. '/includes/footer.php';
