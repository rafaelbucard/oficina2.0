<?php

namespace App\Entity;
use App\Db\Database;

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

    //pegando hora e data do sistema 
    $this->date = date('Y-m-d H:i:s');

    // definindo tabela criando objeto DB
    $obDatabase = new Database('repair');
    //echo "<pre>";print_r($obDatabase); echo "</pre>"; exit;

    $obDatabase->insert([
        'namem' => $this->namem,
        'namec' => $this->namec,
        'description' => $this->desription,
        'completed' => $this->completed,
        'price' => $this->price,
        'date' => $this->date

    ]);
}

}