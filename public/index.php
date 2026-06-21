<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Router;
use App\Core\Session;

require dirname(__DIR__) . '/vendor/autoload.php';

Session::start();

$request = new Request();

// Proteção CSRF para requisições que alteram estado.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $token = $_POST['_csrf'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        Session::flash('error', 'Sessão expirada. Tente novamente.');
        redirect('/');
    }
}

/** @var Router $router */
$router = require dirname(__DIR__) . '/routes/web.php';

$router->dispatch($request);
