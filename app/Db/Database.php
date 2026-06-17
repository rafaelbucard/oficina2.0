<?php

namespace App\Db;

use PDO;
use PDOException;

class Database
{
    /** Nome da tabela */
    private ?string $table;

    /** Conexão PDO */
    private PDO $connection;

    public function __construct(?string $table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Monta a conexão PDO com PostgreSQL lendo as variáveis de ambiente
     * definidas no container (docker-compose). Os fallbacks apontam para
     * o serviço "db" do Docker.
     */
    private function setConnection(): void
    {
        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '5432';
        $name = getenv('DB_NAME') ?: 'mechanic';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: 'postgres';

        try {
            $dsn = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $name;
            $this->connection = new PDO($dsn, $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('error: ' . $e->getMessage());
        }
    }

    /**
     * Executa uma query com parâmetros (prepared statement)
     */
    public function execute(string $query, array $params = []): \PDOStatement
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Insere um registro e retorna o id gerado (usa RETURNING do PostgreSQL)
     */
    public function insert(array $values): int
    {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') '
            . 'VALUES (' . implode(',', $binds) . ') RETURNING id';

        $statement = $this->execute($query, array_values($values));

        return (int) $statement->fetchColumn();
    }

    /**
     * Seleciona registros da tabela
     */
    public function select(?string $where = null, ?string $order = null, ?string $limit = null, string $fields = '*'): \PDOStatement
    {
        $where = strlen((string) $where) ? 'WHERE ' . $where : '';
        $order = strlen((string) $order) ? 'ORDER BY ' . $order : '';
        $limit = strlen((string) $limit) ? 'LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->execute($query);
    }

    /**
     * Atualiza registros que atendem à condição
     */
    public function updateRepair(string $where, array $values): bool
    {
        $fields = array_keys($values);
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;
        $this->execute($query, array_values($values));
        return true;
    }

    /**
     * Remove registros que atendem à condição
     */
    public function deleteRepair(string $where): bool
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
        $this->execute($query);
        return true;
    }
}
