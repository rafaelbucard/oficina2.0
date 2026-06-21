-- Schema do sistema de gestao de orcamentos (PostgreSQL)
-- Executado automaticamente pelo container do Postgres na primeira inicializacao.

CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Usuarios (admin e mecanicos)
CREATE TABLE users (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(10) NOT NULL DEFAULT 'mecanico' CHECK (role IN ('admin', 'mecanico')),
    active        BOOLEAN NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Clientes
CREATE TABLE clients (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    phone       VARCHAR(30) NOT NULL,
    contact     VARCHAR(150),
    document    VARCHAR(30),
    notes       TEXT,
    created_by  INTEGER REFERENCES users(id) ON DELETE SET NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Veiculos vinculados ao cliente
CREATE TABLE vehicles (
    id         SERIAL PRIMARY KEY,
    client_id  INTEGER NOT NULL REFERENCES clients(id) ON DELETE CASCADE,
    type       VARCHAR(10) NOT NULL CHECK (type IN ('carro', 'moto')),
    plate      VARCHAR(15) NOT NULL,
    brand      VARCHAR(60),
    model      VARCHAR(60),
    year       INTEGER,
    color      VARCHAR(40),
    mileage    INTEGER,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Orcamentos
CREATE TABLE quotes (
    id              SERIAL PRIMARY KEY,
    code            VARCHAR(20) NOT NULL UNIQUE,
    client_id       INTEGER REFERENCES clients(id) ON DELETE SET NULL,
    vehicle_id      INTEGER REFERENCES vehicles(id) ON DELETE SET NULL,
    created_by      INTEGER REFERENCES users(id) ON DELETE SET NULL,
    updated_by      INTEGER REFERENCES users(id) ON DELETE SET NULL,
    status          VARCHAR(20) NOT NULL DEFAULT 'rascunho'
                    CHECK (status IN ('rascunho', 'aguardando', 'aprovado', 'em_andamento', 'concluido', 'cancelado')),
    discount        NUMERIC(10,2) NOT NULL DEFAULT 0,
    total           NUMERIC(10,2) NOT NULL DEFAULT 0,
    notes           TEXT,
    -- Snapshot do cliente/veiculo no momento da criacao
    client_name     VARCHAR(150) NOT NULL,
    client_phone    VARCHAR(30) NOT NULL,
    client_contact  VARCHAR(150),
    vehicle_plate   VARCHAR(15),
    vehicle_type    VARCHAR(10),
    vehicle_brand   VARCHAR(60),
    vehicle_model   VARCHAR(60),
    vehicle_year    INTEGER,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    approved_at     TIMESTAMP
);

-- Itens do orcamento (pecas e servicos)
CREATE TABLE quote_items (
    id          SERIAL PRIMARY KEY,
    quote_id    INTEGER NOT NULL REFERENCES quotes(id) ON DELETE CASCADE,
    item_type   VARCHAR(10) NOT NULL CHECK (item_type IN ('peca', 'servico')),
    description VARCHAR(255) NOT NULL,
    quantity    NUMERIC(10,2) NOT NULL DEFAULT 1,
    unit_price  NUMERIC(10,2) NOT NULL DEFAULT 0,
    line_total  NUMERIC(10,2) NOT NULL DEFAULT 0
);

-- Indices para os filtros da listagem de orcamentos
CREATE INDEX idx_quotes_status     ON quotes(status);
CREATE INDEX idx_quotes_created_by ON quotes(created_by);
CREATE INDEX idx_quotes_created_at ON quotes(created_at);
CREATE INDEX idx_quotes_client     ON quotes(client_id);
CREATE INDEX idx_vehicles_plate    ON vehicles(plate);
CREATE INDEX idx_vehicles_type     ON vehicles(type);
CREATE INDEX idx_vehicles_client   ON vehicles(client_id);
