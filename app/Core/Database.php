<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Provedor de conexão PDO (PostgreSQL) e helper de queries com prepared statements.
 * As credenciais vêm das variáveis de ambiente definidas no docker-compose.
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST') ?: 'db';
            $port = getenv('DB_PORT') ?: '5432';
            $name = getenv('DB_NAME') ?: 'mechanic';
            $user = getenv('DB_USER') ?: 'postgres';
            $pass = getenv('DB_PASS') ?: 'postgres';

            try {
                $dsn = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $name;
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                die('Erro de conexão com o banco de dados: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    /** Executa uma query parametrizada e retorna o statement. */
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    /** Retorna todas as linhas. */
    public static function all(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /** Retorna uma única linha (ou null). */
    public static function first(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /** Retorna o valor da primeira coluna da primeira linha. */
    public static function scalar(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    /** Insere e retorna o id gerado (usa RETURNING do PostgreSQL). */
    public static function insert(string $table, array $values): int
    {
        $fields = array_keys($values);
        $binds  = array_pad([], count($fields), '?');

        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') '
            . 'VALUES (' . implode(', ', $binds) . ') RETURNING id';

        return (int) self::query($sql, array_values($values))->fetchColumn();
    }

    /** Atualiza registros pela condição informada (com parâmetros). */
    public static function update(string $table, array $values, string $where, array $whereParams = []): void
    {
        $sets = implode(', ', array_map(fn ($f) => $f . ' = ?', array_keys($values)));
        $sql  = 'UPDATE ' . $table . ' SET ' . $sets . ' WHERE ' . $where;

        self::query($sql, [...array_values($values), ...$whereParams]);
    }

    public static function delete(string $table, string $where, array $params = []): void
    {
        self::query('DELETE FROM ' . $table . ' WHERE ' . $where, $params);
    }

    public static function beginTransaction(): void
    {
        self::connection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::connection()->commit();
    }

    public static function rollBack(): void
    {
        if (self::connection()->inTransaction()) {
            self::connection()->rollBack();
        }
    }
}
