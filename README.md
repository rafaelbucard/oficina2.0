# Oficina 2.0 :wrench:

Sistema de **gestão de orçamentos** para oficina mecânica que atende **carros e motos**.

Autor: Rafael Buçard

Aplicação web em **PHP 8.3** com arquitetura **MVC própria** (sem framework pesado), banco **PostgreSQL**, autenticação por papéis, orçamentos itemizados, filtros avançados e exportação em PDF. Executa **100% via Docker**.

## Funcionalidades

- **Autenticação por sessão** com papéis: `admin` e `mecanico`.
- **Usuários** (somente admin): cadastra mecânicos, edita e ativa/desativa.
- **Clientes**: cadastro mínimo (nome, telefone, contato), lista com busca e tela de detalhe.
- **Veículos**: vinculados ao cliente (carro ou moto), gerenciados na tela do cliente.
- **Orçamentos**: itemizados (peças/serviços com quantidade e valor), cálculo automático de total, snapshot dos dados de cliente/veículo, fluxo de status e rastreio de autoria (quem criou / quem atualizou).
- **Filtros** na listagem de orçamentos: mecânico, status, cliente, placa, tipo de veículo, período e busca rápida — com paginação.
- **PDF / impressão** do orçamento (dompdf).
- **UI dashboard** com tema azul em degradê e preto, responsivo.

## Stack

- PHP 8.3 (Apache)
- PostgreSQL 16
- Composer (PSR-4) + `dompdf/dompdf`
- Docker + Docker Compose

## Executando (exclusivamente via Docker)

Não é necessário instalar PHP ou PostgreSQL na máquina.

```bash
docker compose up --build
```

Acesse: **http://localhost:8000**

O schema e um usuário inicial são criados automaticamente (via `database/migrations/`).

### Acesso padrão

- Administrador: `admin@oficina.local` / `admin123`
- Mecânico (exemplo): `mecanico@oficina.local` / `mecanico123`

### Comandos úteis

```bash
docker compose down       # para os containers (mantém os dados)
docker compose down -v    # para e apaga também o banco (volume)
```

## Arquitetura (MVC)

```
oficina2.0/
├── public/                 # raiz servida pelo Apache
│   ├── index.php           # front controller
│   ├── .htaccess           # rewrite -> index.php
│   └── assets/css/app.css  # tema dashboard
├── app/
│   ├── Core/               # Router, Controller, Request, Validator, View, Database, Auth, Session
│   ├── Middleware/         # Auth, Admin, Guest
│   ├── Controllers/        # Auth, Dashboard, User, Client, Vehicle, Quote
│   ├── Repositories/       # acesso a dados + filtros parametrizados
│   ├── Services/           # QuoteService, PdfService
│   ├── Views/              # templates (layouts, auth, dashboard, clients, vehicles, quotes, users)
│   └── helpers.php
├── routes/web.php          # mapa de rotas
├── config/app.php
├── database/migrations/    # schema (001) + seed (002)
├── docker/000-default.conf # vhost Apache (DocumentRoot=public, rewrite)
├── Dockerfile
└── docker-compose.yml
```

Responsabilidades: controllers orquestram (sem SQL), repositories executam queries com prepared statements, services concentram regra de negócio (cálculo de totais, snapshot, PDF) e views só apresentam (com escape de saída).

## Modelo de dados

Tabelas: `users`, `clients`, `vehicles`, `quotes`, `quote_items`. O orçamento guarda um **snapshot** do cliente/veículo e os itens ficam em `quote_items` (peça/serviço, quantidade, valor unitário, subtotal). Veja `database/migrations/001_schema.sql`.

## Segurança

- Senhas com `password_hash` (bcrypt).
- Prepared statements em todas as queries.
- Escape de saída nas views (proteção XSS).
- Token CSRF em todos os formulários POST.
- Autorização por papel via middlewares (`/usuarios/*` restrito ao admin).
