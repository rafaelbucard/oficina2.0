<?php

namespace App\Repositories;

use App\Core\Database;

class QuoteRepository
{
    private const BASE_SELECT = "
        SELECT q.*, u.name AS created_by_name, v.type AS current_vehicle_type
        FROM quotes q
        LEFT JOIN users u ON u.id = q.created_by
        LEFT JOIN vehicles v ON v.id = q.vehicle_id
    ";

    /**
     * Busca orçamentos aplicando os filtros combináveis de forma parametrizada.
     */
    public function search(QuoteFilter $filter, int $perPage = 12): array
    {
        [$where, $params] = $this->buildWhere($filter);

        $offset = ($filter->page - 1) * $perPage;

        $sql = self::BASE_SELECT
            . $where
            . ' ORDER BY q.created_at DESC, q.id DESC'
            . ' LIMIT ? OFFSET ?';

        return Database::all($sql, [...$params, $perPage, $offset]);
    }

    public function count(QuoteFilter $filter): int
    {
        [$where, $params] = $this->buildWhere($filter);

        $sql = 'SELECT COUNT(*) FROM quotes q
                LEFT JOIN vehicles v ON v.id = q.vehicle_id'
            . $where;

        return (int) Database::scalar($sql, $params);
    }

    /**
     * Monta a cláusula WHERE acumulando condições e parâmetros em paralelo.
     *
     * @return array{0:string, 1:array}
     */
    private function buildWhere(QuoteFilter $filter): array
    {
        $conditions = [];
        $params     = [];

        if ($filter->search !== '') {
            $conditions[] = '(q.code ILIKE ? OR q.client_name ILIKE ? OR q.vehicle_plate ILIKE ?)';
            $like = '%' . $filter->search . '%';
            array_push($params, $like, $like, $like);
        }

        if ($filter->status !== '') {
            $conditions[] = 'q.status = ?';
            $params[] = $filter->status;
        }

        if ($filter->createdBy !== null) {
            $conditions[] = 'q.created_by = ?';
            $params[] = $filter->createdBy;
        }

        if ($filter->client !== '') {
            $conditions[] = 'q.client_name ILIKE ?';
            $params[] = '%' . $filter->client . '%';
        }

        if ($filter->plate !== '') {
            $conditions[] = 'q.vehicle_plate ILIKE ?';
            $params[] = '%' . $filter->plate . '%';
        }

        if ($filter->vehicleType !== '') {
            $conditions[] = 'q.vehicle_type = ?';
            $params[] = $filter->vehicleType;
        }

        if ($filter->dateFrom !== '') {
            $conditions[] = 'q.created_at >= ?';
            $params[] = $filter->dateFrom . ' 00:00:00';
        }

        if ($filter->dateTo !== '') {
            $conditions[] = 'q.created_at <= ?';
            $params[] = $filter->dateTo . ' 23:59:59';
        }

        $where = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);

        return [$where, $params];
    }

    public function find(int $id): ?array
    {
        return Database::first(self::BASE_SELECT . ' WHERE q.id = ?', [$id]);
    }

    public function forClient(int $clientId): array
    {
        return Database::all(
            self::BASE_SELECT . ' WHERE q.client_id = ? ORDER BY q.created_at DESC',
            [$clientId]
        );
    }

    public function items(int $quoteId): array
    {
        return Database::all(
            'SELECT * FROM quote_items WHERE quote_id = ? ORDER BY id',
            [$quoteId]
        );
    }

    public function create(array $data): int
    {
        return Database::insert('quotes', $data);
    }

    public function update(int $id, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        Database::update('quotes', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        Database::delete('quotes', 'id = ?', [$id]);
    }

    public function replaceItems(int $quoteId, array $items): void
    {
        Database::delete('quote_items', 'quote_id = ?', [$quoteId]);
        foreach ($items as $item) {
            Database::insert('quote_items', array_merge($item, ['quote_id' => $quoteId]));
        }
    }

    /** Gera um código sequencial legível: ORC-2026-0001. */
    public function nextCode(): string
    {
        $prefix = config('app.quote_prefix', 'ORC');
        $year   = date('Y');
        $count  = (int) Database::scalar(
            "SELECT COUNT(*) FROM quotes WHERE code LIKE ?",
            [$prefix . '-' . $year . '-%']
        );
        return sprintf('%s-%s-%04d', $prefix, $year, $count + 1);
    }

    /** Agregados para o dashboard. */
    public function statusSummary(): array
    {
        $rows = Database::all('SELECT status, COUNT(*) AS total FROM quotes GROUP BY status');
        $summary = [];
        foreach ($rows as $row) {
            $summary[$row['status']] = (int) $row['total'];
        }
        return $summary;
    }

    public function totalApproved(): float
    {
        return (float) Database::scalar(
            "SELECT COALESCE(SUM(total), 0) FROM quotes WHERE status IN ('aprovado', 'em_andamento', 'concluido')"
        );
    }

    public function recent(int $limit = 5): array
    {
        return Database::all(
            self::BASE_SELECT . ' ORDER BY q.created_at DESC LIMIT ?',
            [$limit]
        );
    }
}
