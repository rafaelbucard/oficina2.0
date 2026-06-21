<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\ClientRepository;
use App\Repositories\VehicleRepository;

class VehicleController extends Controller
{
    private VehicleRepository $vehicles;
    private ClientRepository $clients;

    public function __construct()
    {
        $this->vehicles = new VehicleRepository();
        $this->clients  = new ClientRepository();
    }

    public function store(Request $request): string
    {
        $clientId = (int) $request->param('id');
        if (!$this->clients->find($clientId)) {
            Session::flash('error', 'Cliente não encontrado.');
            $this->redirect('/clientes');
        }

        $data = $this->validated($request, '/clientes/' . $clientId);

        $this->vehicles->create(array_merge($data, ['client_id' => $clientId]));

        Session::flash('success', 'Veículo cadastrado.');
        $this->redirect('/clientes/' . $clientId);
    }

    public function edit(Request $request): string
    {
        $vehicle = $this->vehicles->find((int) $request->param('id'));
        if (!$vehicle) {
            Session::flash('error', 'Veículo não encontrado.');
            $this->redirect('/clientes');
        }

        return $this->render('vehicles/form', [
            'title'   => 'Editar veículo',
            'vehicle' => $vehicle,
            'client'  => $this->clients->find((int) $vehicle['client_id']),
        ]);
    }

    public function update(Request $request): string
    {
        $vehicle = $this->vehicles->find((int) $request->param('id'));
        if (!$vehicle) {
            Session::flash('error', 'Veículo não encontrado.');
            $this->redirect('/clientes');
        }

        $clientId = (int) $vehicle['client_id'];
        $data = $this->validated($request, '/veiculos/' . $vehicle['id'] . '/editar');

        $this->vehicles->update((int) $vehicle['id'], $data);

        Session::flash('success', 'Veículo atualizado.');
        $this->redirect('/clientes/' . $clientId);
    }

    public function destroy(Request $request): string
    {
        $vehicle = $this->vehicles->find((int) $request->param('id'));
        if (!$vehicle) {
            Session::flash('error', 'Veículo não encontrado.');
            $this->redirect('/clientes');
        }

        $clientId = (int) $vehicle['client_id'];
        $this->vehicles->delete((int) $vehicle['id']);

        Session::flash('success', 'Veículo excluído.');
        $this->redirect('/clientes/' . $clientId);
    }

    /** Valida os campos do veículo e retorna o array pronto para persistir. */
    private function validated(Request $request, string $back): array
    {
        $data = $request->only(['type', 'plate', 'brand', 'model', 'year', 'color', 'mileage']);

        $validator = Validator::make($data, [
            'type'    => 'required|in:carro,moto',
            'plate'   => 'required|max:15',
            'year'    => 'integer',
            'mileage' => 'integer',
        ], [
            'type'    => 'Tipo',
            'plate'   => 'Placa',
            'year'    => 'Ano',
            'mileage' => 'Quilometragem',
        ]);

        if ($validator->fails()) {
            $this->back($back, $validator->messages(), $data);
        }

        return [
            'type'    => $data['type'],
            'plate'   => strtoupper($data['plate']),
            'brand'   => $data['brand'] ?: null,
            'model'   => $data['model'] ?: null,
            'year'    => $data['year'] !== '' ? (int) $data['year'] : null,
            'color'   => $data['color'] ?: null,
            'mileage' => $data['mileage'] !== '' ? (int) $data['mileage'] : null,
        ];
    }
}
