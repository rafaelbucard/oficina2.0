-- Usuario administrador inicial.
-- Login: admin@oficina.local  |  Senha: admin123
-- O hash usa bcrypt (pgcrypto/gen_salt('bf')), compativel com password_verify() do PHP.
INSERT INTO users (name, email, password_hash, role, active)
VALUES (
    'Administrador',
    'admin@oficina.local',
    crypt('admin123', gen_salt('bf')),
    'admin',
    TRUE
);

-- Mecanico de exemplo.
-- Login: mecanico@oficina.local  |  Senha: mecanico123
INSERT INTO users (name, email, password_hash, role, active)
VALUES (
    'Mecanico Exemplo',
    'mecanico@oficina.local',
    crypt('mecanico123', gen_salt('bf')),
    'mecanico',
    TRUE
);
