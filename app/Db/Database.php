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
    private $table = 'repair';

    //PDO
    private $connection;
    
    //iniciando conecção ecom banco de dados 
    public function __construct() {
       // $this->$table = $table;
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


    // metodo de execução de query
    public function execute($query,$params = []) {
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch(PDOException $e) {
            die('ERROR:'. $e->getMessage());
        }
    }

    // Método de inserir no banco de dados 
    //parametro array  /  retorna o id 
    public function insert($values) {

        //separa as chaves  do array para mntar querry
        $fields = array_keys($values);

        //pega os valores de acordo com o numero ee chaves
        $binds = array_pad([],count($fields),'?');

        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        
        //chamada do metodo execute
        $this->execute($query, array_values($values));

        return $this ->connection->lastInsertId();
     
    }
    
    // metodo de select no banco de dados 
    // retorna PDOStatiment
    public function select($where = null, $order = null, $limit = null){
        $where = strlen($where) ? 'WHERE '. $where : '';
        $where = strlen($order) ? 'ORDER BY '. $order : '';
        $where = strlen($limit) ? 'LIMIT '. $limit : '';
        
        $query = 'SELECT * FROM '.$this->table.' '.$where.' '.$order.' '.$limit.' ';

        return $this->execute($query);
    }
   
}