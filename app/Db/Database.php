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
    public function __construct($table = null) {
        $this->$table = $table;
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
    // Método de inserir no banco de dados 
    //parametro array  /  retorna o id 
    public function insert($values) {

        //separa as chaves  do array para mntar querry
        $fields = array_keys($values);

        //pega os valores de acordo com o numero ee chaves
        $binds = array_pad([],count($fields),'?');

        $query = 'INSERT INTO'.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        echo "<pre>";print_r($q); echo "</pre>"; exit;
    }
   
}