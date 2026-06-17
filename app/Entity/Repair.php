<?php

namespace App\Entity;

use App\Db\Database;
use PDO;

class Repair
{
    /** Identificador primário */
    public $id;

    /** Nome do mecânico */
    public $namem;

    /** Nome do cliente */
    public $namec;

    /** Descrição do serviço */
    public $description;

    /** Status: 's' (concluído) ou 'n' (em andamento) */
    public $completed;

    /** Data e hora */
    public $date;

    /** Preço */
    public $price;

    /** Insere o orçamento no banco de dados */
    public function register(): bool
    {
        $this->date = date('Y-m-d H:i:s');

        $obDatabase = new Database('repair');
        $this->id = $obDatabase->insert([
            'namem' => $this->namem,
            'namec' => $this->namec,
            'description' => $this->description,
            'completed' => $this->completed,
            'price' => $this->price,
            'date' => $this->date,
        ]);

        return true;
    }

    /** Atualiza o orçamento no banco de dados */
    public function update(): bool
    {
        return (new Database('repair'))->updateRepair('id = ' . (int) $this->id, [
            'namem' => $this->namem,
            'namec' => $this->namec,
            'description' => $this->description,
            'completed' => $this->completed,
            'price' => $this->price,
            'date' => $this->date,
        ]);
    }

    /** Remove o orçamento do banco de dados */
    public function delete(): bool
    {
        return (new Database('repair'))->deleteRepair('id = ' . (int) $this->id);
    }

    /** Retorna a listagem de orçamentos */
    public static function getRepair(?string $where = null, ?string $order = null, ?string $limit = null): array
    {
        return (new Database('repair'))
            ->select($where, $order, $limit)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /** Retorna um único orçamento pelo id (prepared statement) */
    public static function getEdit(int $id): ?Repair
    {
        $repair = (new Database('repair'))
            ->execute('SELECT * FROM repair WHERE id = ?', [$id])
            ->fetchObject(self::class);

        return $repair instanceof Repair ? $repair : null;
    }

    /**
     * Busca orçamentos por uma cláusula com parâmetros vinculados.
     *
     * @param string $where  Cláusula com placeholders (ex.: 'namem ILIKE ?')
     * @param array  $params Valores vinculados aos placeholders
     */
    public static function getSearch(string $where, array $params = []): array
    {
        $query = 'SELECT * FROM repair WHERE ' . $where;
        return (new Database('repair'))
            ->execute($query, $params)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
