<?php

use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\DashboardController;
use App\Controllers\QuoteController;
use App\Controllers\UserController;
use App\Controllers\VehicleController;
use App\Core\Router;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$router = new Router();

$auth  = [AuthMiddleware::class];
$admin = [AuthMiddleware::class, AdminMiddleware::class];
$guest = [GuestMiddleware::class];

// Autenticação
$router->get('/login', [AuthController::class, 'showLogin'], $guest);
$router->post('/login', [AuthController::class, 'login'], $guest);
$router->post('/logout', [AuthController::class, 'logout'], $auth);

// Dashboard
$router->get('/', [DashboardController::class, 'index'], $auth);

// Usuários (somente admin)
$router->get('/usuarios', [UserController::class, 'index'], $admin);
$router->get('/usuarios/criar', [UserController::class, 'create'], $admin);
$router->post('/usuarios', [UserController::class, 'store'], $admin);
$router->get('/usuarios/{id}/editar', [UserController::class, 'edit'], $admin);
$router->post('/usuarios/{id}', [UserController::class, 'update'], $admin);
$router->post('/usuarios/{id}/status', [UserController::class, 'toggle'], $admin);

// Clientes
$router->get('/clientes', [ClientController::class, 'index'], $auth);
$router->get('/clientes/criar', [ClientController::class, 'create'], $auth);
$router->post('/clientes', [ClientController::class, 'store'], $auth);
$router->get('/clientes/{id}', [ClientController::class, 'show'], $auth);
$router->get('/clientes/{id}/editar', [ClientController::class, 'edit'], $auth);
$router->post('/clientes/{id}', [ClientController::class, 'update'], $auth);
$router->post('/clientes/{id}/excluir', [ClientController::class, 'destroy'], $auth);

// Veículos (gerenciados na tela do cliente)
$router->post('/clientes/{id}/veiculos', [VehicleController::class, 'store'], $auth);
$router->get('/veiculos/{id}/editar', [VehicleController::class, 'edit'], $auth);
$router->post('/veiculos/{id}', [VehicleController::class, 'update'], $auth);
$router->post('/veiculos/{id}/excluir', [VehicleController::class, 'destroy'], $auth);

// Orçamentos
$router->get('/orcamentos', [QuoteController::class, 'index'], $auth);
$router->get('/orcamentos/criar', [QuoteController::class, 'create'], $auth);
$router->post('/orcamentos', [QuoteController::class, 'store'], $auth);
$router->get('/orcamentos/{id}', [QuoteController::class, 'show'], $auth);
$router->get('/orcamentos/{id}/editar', [QuoteController::class, 'edit'], $auth);
$router->post('/orcamentos/{id}', [QuoteController::class, 'update'], $auth);
$router->post('/orcamentos/{id}/status', [QuoteController::class, 'updateStatus'], $auth);
$router->post('/orcamentos/{id}/excluir', [QuoteController::class, 'destroy'], $auth);
$router->get('/orcamentos/{id}/pdf', [QuoteController::class, 'pdf'], $auth);

return $router;
