<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\ClientRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\VehicleRepository;

class ClientController extends Controller
{
    private ClientRepository $clients;
    private VehicleRepository $vehicles;

    public function __construct()
    {
        $this->clients  = new ClientRepository();
        $this->vehicles = new VehicleRepository();
    }

    public function index(Request $request): string
    {
        $term    = (string) $request->query('q', '');
        $page    = max(1, (int) $request->query('page', 1));
        $perPage = (int) config('app.per_page', 12);
        $offset  = ($page - 1) * $perPage;

        $total = $this->clients->countSearch($term);

        return $this->render('clients/index', [
            'title'    => 'Clientes',
            'clients'  => $this->clients->search($term, $perPage, $offset),
            'term'     => $term,
            'page'     => $page,
            'perPage'  => $perPage,
            'total'    => $total,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
        ]);
    }

    public function create(Request $request): string
    {
        return $this->render('clients/form', [
            'title'  => 'Novo cliente',
            'client' => null,
        ]);
    }

    public function store(Request $request): string
    {
        $data = $request->only(['name', 'phone', 'contact', 'document', 'notes']);

        $validator = Validator::make($data, [
            'name'  => 'required|max:150',
            'phone' => 'required|max:30',
        ], [
            'name'  => 'Nome',
            'phone' => 'Telefone',
        ]);

        if ($validator->fails()) {
            $this->back('/clientes/criar', $validator->messages(), $data);
        }

        $id = $this->clients->create([
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'contact'    => $data['contact'] ?: null,
            'document'   => $data['document'] ?: null,
            'notes'      => $data['notes'] ?: null,
            'created_by' => Auth::id(),
        ]);

        $this->clearOld();
        Session::flash('success', 'Cliente cadastrado com sucesso.');
        $this->redirect('/clientes/' . $id);
    }

    public function show(Request $request): string
    {
        $client = $this->clients->find((int) $request->param('id'));
        if (!$client) {
            Session::flash('error', 'Cliente não encontrado.');
            $this->redirect('/clientes');
        }

        return $this->render('clients/show', [
            'title'    => $client['name'],
            'client'   => $client,
            'vehicles' => $this->vehicles->forClient((int) $client['id']),
            'quotes'   => (new QuoteRepository())->forClient((int) $client['id']),
        ]);
    }

    public function edit(Request $request): string
    {
        $client = $this->clients->find((int) $request->param('id'));
        if (!$client) {
            Session::flash('error', 'Cliente não encontrado.');
            $this->redirect('/clientes');
        }

        return $this->render('clients/form', [
            'title'  => 'Editar cliente',
            'client' => $client,
        ]);
    }

    public function update(Request $request): string
    {
        $id     = (int) $request->param('id');
        $client = $this->clients->find($id);
        if (!$client) {
            Session::flash('error', 'Cliente não encontrado.');
            $this->redirect('/clientes');
        }

        $data = $request->only(['name', 'phone', 'contact', 'document', 'notes']);

        $validator = Validator::make($data, [
            'name'  => 'required|max:150',
            'phone' => 'required|max:30',
        ], [
            'name'  => 'Nome',
            'phone' => 'Telefone',
        ]);

        if ($validator->fails()) {
            $this->back('/clientes/' . $id . '/editar', $validator->messages(), $data);
        }

        $this->clients->update($id, [
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'contact'  => $data['contact'] ?: null,
            'document' => $data['document'] ?: null,
            'notes'    => $data['notes'] ?: null,
        ]);

        $this->clearOld();
        Session::flash('success', 'Cliente atualizado com sucesso.');
        $this->redirect('/clientes/' . $id);
    }

    public function destroy(Request $request): string
    {
        $id = (int) $request->param('id');
        $this->clients->delete($id);
        Session::flash('success', 'Cliente excluído.');
        $this->redirect('/clientes');
    }
}
