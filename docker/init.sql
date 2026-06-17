-- Schema inicial do banco "mechanic" (PostgreSQL)
-- Executado automaticamente pelo container do Postgres na primeira inicialização.

CREATE TABLE IF NOT EXISTS repair (
    id          SERIAL PRIMARY KEY,
    namem       VARCHAR(100) NOT NULL,
    namec       VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    completed   CHAR(1) NOT NULL CHECK (completed IN ('s', 'n')),
    date        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    price       VARCHAR(100) NOT NULL
);
