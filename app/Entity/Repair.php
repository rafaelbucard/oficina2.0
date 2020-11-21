<?php

namespace App\Entity;

class Repair {

//identificador primário integer 
public $id;

// String vendedor
public $namem;

//string cliente
public $namec;

//text string descrição
public $desription;

//string : 's' ou 'n'
public $completed;

//data e hora string 
public $date;

// float preço
public $price;

//Cadastrando um novo orçamento 
public function register(){

    $this->date = date('Y-m-d H:i:s');

}

}