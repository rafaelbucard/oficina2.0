<?php
namespace App\Db;
use \PDO;
use \PDOException;

class Database{

    //local
    const HOST = 'localhost';
    //nome do banco de dados 
    const NAME = 'mechanic';
    //usuario
    const USER = 'root';
    //senha
    const PASS = '';
    //nome da tabela
    private $table;
    //PDO
    private $connection;

    //iniciando conecção ecom banco de dados
    public function __construct($table = null){

        $this->table = $table;
        $this->setConection();
      }

    //montagem PDO
    private function setConection() {

        try {
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);    
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die('error:'. $e->getMessage());
        } 
    }
    /**
   * Método de execução da query
   * @param  string $query
   * @param  array  $params
   * @return PDOStatement
   */
  public function execute($query,$params = []){

    try{
      $statement = $this->connection->prepare($query);
      $statement->execute($params);
      return $statement;
    }catch(PDOException $e){
      die('ERROR: '.$e->getMessage());
    }
  }
     /**
  * Inserir
  * Metodo para inserir no banco de dados
  * @param array $values
  * @return int $id  
  */
    public function insert($values) {

        //separa as chaves  do array para motar querry
        $fields = array_keys($values);
        $binds = array_pad([],count($fields),'?');
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        //chamada do metodo execute
        $this->execute($query, array_values($values));
        return $this ->connection->lastInsertId(); 
    }
    /**
    * Selecinoar Todos
    * Metodo para selecionar todos
    * @return PDOStatiment
    */
    public function select($where = null, $order = null, $limit = null, $fields ='*'){

        $where = strlen($where) ? 'WHERE '. $where : '';
        $where = strlen($order) ? 'ORDER BY '. $order : '';
        $where = strlen($limit) ? 'LIMIT '. $limit : '';
        $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit.' ';
        return $this->execute($query);
    }
    /**
  * Atualiza
  * Metodo para Atualizar no banco de dados
  * @param string $where
  * @param array $values
  * @return  boolean    
  */
    public function updateRepair($where, $values){

        $fields = array_keys($values);
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;
        $this->execute($query,array_values($values));
        return true;
    }
      /**
  * Atualiza
  * Metodo para Atualizar no banco de dados
  * @param string $where
  * @return  boolean    
  */
    public function deleteRepair($where){

        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;
        $this->execute($query);
        return true;
    }  
}
