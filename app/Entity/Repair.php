<?php

namespace App\Entity;
use App\Db\Database;
use \PDO;

class Repair {

    //identificador primário integer 
    public $id;
    // String mecanico 
    public $namem;
    //string cliente
    public $namec;
    //text string descrição
    public $description;
    //string : 's' ou 'n'
    public $completed;
    //data e hora string 
    public $date;
    // float preço
    public $price;

        /**
      * Atualiza
      * Metodo para Atualizar no banco de dados
      * @param string $where
      * @return  boolean    
      */
    public function register(){

        //pegando hora e data do sistema 
        $this->date = date('Y-m-d H:i:s');

        // definindo tabela criando objeto DB
        $obDatabase = new Database('repair');
        $this->id = $obDatabase->insert([
            'namem' => $this->namem,
            'namec' => $this->namec,
            'description' => $this->description,
            'completed' => $this->completed,
            'price' => $this->price,
            'date' => $this->date
        ]);
      
        return true;
    }
    /**
      * Atualiza
      * Metodo para Atualizar no banco de dados
      * @return  boolean    
      */
    public function update() {

        return ( new Database('repair'))->updateRepair('id = '.$this->id,[
              'namem' => $this->namem,
              'namec' => $this->namec,
              'description' => $this->description,
              'completed' => $this->completed,
              'price' => $this->price,
              'date' => $this->date
      ]);
    }
    /**
      * Deletar
      * Metodo para Deletar do banco de dados
      * @return  boolean    
      */
    public function delete() {

        return ( new Database('repair'))->deleteRepair('id = '.$this->id);  

      }
    /**
      * Pegar
      * Metodo para pegar listagem 
      * @return  array  
      */
    public static function getRepair($where = null, $order = null, $limit = null) {
        return(new Database ('repair'))->select($where,$order,$limit)
        ->fetchAll(PDO::FETCH_CLASS,self::class);
    }
    /**
      * Pegar para editar 
      * Metodo para pegar uma posição no banco de dados
      * @return  array  
      */
    public static function getEdit($id) {

        return(new Database ())->execute("SELECT * FROM repair WHERE id = $id")
        ->fetchObject(self::class);
    }
    /**
      * Para buscar 
      * Metodo para pegar uma posição no banco de dados
      * @return  array  
      */
    public static function getSearch($where){ 

      $query = 'SELECT * FROM repair WHERE '.$where; 
      return(new Database ('repair'))->execute($query)
      ->fetchAll(PDO::FETCH_CLASS,self::class);;
      
  }
}
