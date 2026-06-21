<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;

class AuthController extends Controller
{
    public function showLogin(Request $request): string
    {
        return $this->view('auth/login', [
            'title'  => 'Entrar',
            'errors' => Session::getFlash('errors') ?? [],
            'flash'  => ['error' => Session::getFlash('error')],
        ], 'layouts/auth');
    }

    public function login(Request $request): string
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email'    => 'E-mail',
            'password' => 'Senha',
        ]);

        if ($validator->fails()) {
            $this->back('/login', $validator->messages(), ['email' => $data['email']]);
        }

        if (!Auth::attempt($data['email'], $data['password'])) {
            $this->back('/login', ['Credenciais inválidas ou usuário inativo.'], ['email' => $data['email']]);
        }

        $this->clearOld();
        Session::flash('success', 'Bem-vindo de volta!');
        $this->redirect('/');
    }

    public function logout(Request $request): string
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
