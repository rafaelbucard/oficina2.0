<?php

namespace App\Entity;
use App\Db\Database;
use \PDO;

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
    $obDatabase = new Database();
    //echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->id = $obDatabase->insert([
        'namem' => $this->namem,
        'namec' => $this->namec,
        'description' => $this->desription,
        'completed' => $this->completed,
        'price' => $this->price,
        'date' => $this->date
    ]);
    //echo "<pre>";print_r($this); echo "</pre>"; exit;
    return true;
}

//metodo para pegar listagem em banco de dados
// retorna um arrey
public static function getRepair($where = null, $order = null, $limit = null) {
    return(new Database ())->select($where,$order,$limit)->fetchAll(PDO::FETCH_CLASS,self::class);
}


//metodo que busca o reparo de acordo com o id
//retorna uma unica posição
public static function getEdit($id) {
    return(new Database ())->execute("SELECT * FROM repair WHERE id = $id")->fetchObject(self::class);
}

}


/**
  * Ofcina2.0.
  * 
  * @param 
  * @return      
  * @author    Rafael Buçard
  */