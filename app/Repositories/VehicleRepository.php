<?php

namespace App\Repositories;

use App\Core\Database;

class VehicleRepository
{
    public function find(int $id): ?array
    {
        return Database::first('SELECT * FROM vehicles WHERE id = ?', [$id]);
    }

    public function forClient(int $clientId): array
    {
        return Database::all(
            'SELECT * FROM vehicles WHERE client_id = ? ORDER BY created_at DESC',
            [$clientId]
        );
    }

    public function create(array $data): int
    {
        return Database::insert('vehicles', $data);
    }

    public function update(int $id, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        Database::update('vehicles', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        Database::delete('vehicles', 'id = ?', [$id]);
    }

    /** Mapa client_id => lista de veículos (para preencher selects dependentes). */
    public function groupedByClient(): array
    {
        $rows = Database::all(
            'SELECT id, client_id, type, plate, brand, model, year FROM vehicles ORDER BY plate'
        );

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[(int) $row['client_id']][] = $row;
        }
        return $grouped;
    }
}
