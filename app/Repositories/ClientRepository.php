<?php

namespace App\Repositories;

use App\Core\Database;

class ClientRepository
{
    /** Busca paginada por nome, telefone ou documento. */
    public function search(string $term = '', int $limit = 12, int $offset = 0): array
    {
        $where  = '';
        $params = [];

        if ($term !== '') {
            $where = 'WHERE c.name ILIKE ? OR c.phone ILIKE ? OR c.document ILIKE ?';
            $like  = '%' . $term . '%';
            $params = [$like, $like, $like];
        }

        $sql = "SELECT c.*, u.name AS created_by_name,
                       (SELECT COUNT(*) FROM vehicles v WHERE v.client_id = c.id) AS vehicles_count
                FROM clients c
                LEFT JOIN users u ON u.id = c.created_by
                {$where}
                ORDER BY c.name
                LIMIT ? OFFSET ?";

        return Database::all($sql, [...$params, $limit, $offset]);
    }

    public function countSearch(string $term = ''): int
    {
        if ($term !== '') {
            $like = '%' . $term . '%';
            return (int) Database::scalar(
                'SELECT COUNT(*) FROM clients WHERE name ILIKE ? OR phone ILIKE ? OR document ILIKE ?',
                [$like, $like, $like]
            );
        }
        return (int) Database::scalar('SELECT COUNT(*) FROM clients');
    }

    public function find(int $id): ?array
    {
        return Database::first('SELECT * FROM clients WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return Database::insert('clients', $data);
    }

    public function update(int $id, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        Database::update('clients', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        Database::delete('clients', 'id = ?', [$id]);
    }

    /** Lista enxuta para selects. */
    public function options(): array
    {
        return Database::all('SELECT id, name, phone, contact FROM clients ORDER BY name');
    }
}
