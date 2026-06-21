<?php

namespace App\Repositories;

use App\Core\Database;

class UserRepository
{
    public function all(): array
    {
        return Database::all('SELECT * FROM users ORDER BY role, name');
    }

    public function find(int $id): ?array
    {
        return Database::first('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return Database::first('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        if ($ignoreId !== null) {
            return (bool) Database::scalar(
                'SELECT 1 FROM users WHERE email = ? AND id <> ?',
                [$email, $ignoreId]
            );
        }
        return (bool) Database::scalar('SELECT 1 FROM users WHERE email = ?', [$email]);
    }

    public function create(array $data): int
    {
        return Database::insert('users', $data);
    }

    public function update(int $id, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        Database::update('users', $data, 'id = ?', [$id]);
    }

    public function toggleActive(int $id): void
    {
        Database::query(
            'UPDATE users SET active = NOT active, updated_at = CURRENT_TIMESTAMP WHERE id = ?',
            [$id]
        );
    }

    /** Lista de mecânicos para preencher filtros. */
    public function mechanics(): array
    {
        return Database::all("SELECT id, name FROM users WHERE role = 'mecanico' ORDER BY name");
    }
}
