<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function index(Request $request): string
    {
        return $this->render('users/index', [
            'title' => 'Usuários',
            'users' => $this->users->all(),
        ]);
    }

    public function create(Request $request): string
    {
        return $this->render('users/form', [
            'title' => 'Novo usuário',
            'user'  => null,
        ]);
    }

    public function store(Request $request): string
    {
        $data = $request->only(['name', 'email', 'role', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'name'     => 'required|max:120',
            'email'    => 'required|email|max:160',
            'role'     => 'required|in:admin,mecanico',
            'password' => 'required|min:6|same:password_confirmation',
        ], [
            'name'     => 'Nome',
            'email'    => 'E-mail',
            'role'     => 'Papel',
            'password' => 'Senha',
        ]);

        $errors = $validator->errors();

        if (!isset($errors['email']) && $this->users->emailExists($data['email'])) {
            $errors['email'][] = 'Este e-mail já está cadastrado.';
        }

        if ($errors !== []) {
            $this->back('/usuarios/criar', $this->flatten($errors), $data);
        }

        $this->users->create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'role'          => $data['role'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'active'        => true,
        ]);

        $this->clearOld();
        Session::flash('success', 'Usuário cadastrado com sucesso.');
        $this->redirect('/usuarios');
    }

    public function edit(Request $request): string
    {
        $user = $this->users->find((int) $request->param('id'));
        if (!$user) {
            Session::flash('error', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        return $this->render('users/form', [
            'title' => 'Editar usuário',
            'user'  => $user,
        ]);
    }

    public function update(Request $request): string
    {
        $id   = (int) $request->param('id');
        $user = $this->users->find($id);
        if (!$user) {
            Session::flash('error', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        $data = $request->only(['name', 'email', 'role', 'password', 'password_confirmation']);

        $rules = [
            'name'  => 'required|max:120',
            'email' => 'required|email|max:160',
            'role'  => 'required|in:admin,mecanico',
        ];
        if (($data['password'] ?? '') !== '') {
            $rules['password'] = 'min:6|same:password_confirmation';
        }

        $validator = Validator::make($data, $rules, [
            'name'     => 'Nome',
            'email'    => 'E-mail',
            'role'     => 'Papel',
            'password' => 'Senha',
        ]);

        $errors = $validator->errors();

        if (!isset($errors['email']) && $this->users->emailExists($data['email'], $id)) {
            $errors['email'][] = 'Este e-mail já está cadastrado.';
        }

        if ($errors !== []) {
            $this->back('/usuarios/' . $id . '/editar', $this->flatten($errors), $data);
        }

        $update = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
        ];
        if (($data['password'] ?? '') !== '') {
            $update['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->users->update($id, $update);

        $this->clearOld();
        Session::flash('success', 'Usuário atualizado com sucesso.');
        $this->redirect('/usuarios');
    }

    public function toggle(Request $request): string
    {
        $id = (int) $request->param('id');
        $this->users->toggleActive($id);
        Session::flash('success', 'Situação do usuário atualizada.');
        $this->redirect('/usuarios');
    }

    private function flatten(array $errors): array
    {
        $flat = [];
        foreach ($errors as $messages) {
            foreach ($messages as $message) {
                $flat[] = $message;
            }
        }
        return $flat;
    }
}
