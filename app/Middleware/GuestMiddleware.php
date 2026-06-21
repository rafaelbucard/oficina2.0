<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;

class GuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::check()) {
            redirect('/');
        }
    }
}
