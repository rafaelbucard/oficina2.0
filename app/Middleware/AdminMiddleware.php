<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Acesso restrito ao administrador.');
            redirect('/');
        }
    }
}
