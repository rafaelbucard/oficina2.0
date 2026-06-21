<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\ClientRepository;
use App\Repositories\QuoteFilter;
use App\Repositories\QuoteRepository;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use App\Services\PdfService;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    private QuoteRepository $quotes;
    private ClientRepository $clients;
    private VehicleRepository $vehicles;
    private QuoteService $service;

    public function __construct()
    {
        $this->quotes   = new QuoteRepository();
        $this->clients  = new ClientRepository();
        $this->vehicles = new VehicleRepository();
        $this->service  = new QuoteService();
    }

    public function index(Request $request): string
    {
        $filter  = QuoteFilter::fromRequest($request);
        $perPage = (int) config('app.per_page', 12);

        $total = $this->quotes->count($filter);

        return $this->render('quotes/index', [
            'title'     => 'Orçamentos',
            'quotes'    => $this->quotes->search($filter, $perPage),
            'filter'    => $filter,
            'mechanics' => (new UserRepository())->mechanics(),
            'total'     => $total,
            'lastPage'  => max(1, (int) ceil($total / $perPage)),
        ]);
    }

    public function create(Request $request): string
    {
        $clientId = (int) $request->query('client_id', 0);

        return $this->render('quotes/form', [
            'title'         => 'Novo orçamento',
            'quote'         => null,
            'items'         => [],
            'clients'       => $this->clients->options(),
            'vehiclesByClient' => $this->vehicles->groupedByClient(),
            'selectedClient'   => $clientId ?: null,
        ]);
    }

    public function store(Request $request): string
    {
        $data = $request->only(['client_id', 'vehicle_id', 'status', 'discount', 'notes']);

        $validator = Validator::make($data, [
            'client_id' => 'required|integer',
            'status'    => 'required|in:rascunho,aguardando,aprovado,em_andamento,concluido,cancelado',
        ], [
            'client_id' => 'Cliente',
            'status'    => 'Status',
        ]);

        if ($validator->fails()) {
            $this->back('/orcamentos/criar', $validator->messages(), $request->all());
        }

        $client = $this->clients->find((int) $data['client_id']);
        if (!$client) {
            $this->back('/orcamentos/criar', ['Cliente inválido.'], $request->all());
        }

        $vehicle = $this->resolveVehicle($data['vehicle_id'] ?? null, (int) $client['id']);

        $items    = $this->service->parseItems($request->all());
        $discount = $this->service->toFloat($data['discount'] ?? 0);
        $total    = $this->service->total($items, $discount);
        $status   = $data['status'];

        $quoteData = array_merge([
            'code'        => $this->quotes->nextCode(),
            'client_id'   => (int) $client['id'],
            'vehicle_id'  => $vehicle['id'] ?? null,
            'created_by'  => Auth::id(),
            'updated_by'  => Auth::id(),
            'status'      => $status,
            'discount'    => $discount,
            'total'       => $total,
            'notes'       => $data['notes'] ?: null,
            'approved_at' => $status === 'aprovado' ? date('Y-m-d H:i:s') : null,
        ], $this->service->snapshot($client, $vehicle));

        Database::beginTransaction();
        try {
            $id = $this->quotes->create($quoteData);
            $this->quotes->replaceItems($id, $items);
            Database::commit();
        } catch (\Throwable $e) {
            Database::rollBack();
            $this->back('/orcamentos/criar', ['Erro ao salvar o orçamento: ' . $e->getMessage()], $request->all());
        }

        $this->clearOld();
        Session::flash('success', 'Orçamento criado com sucesso.');
        $this->redirect('/orcamentos/' . $id);
    }

    public function show(Request $request): string
    {
        $quote = $this->quotes->find((int) $request->param('id'));
        if (!$quote) {
            Session::flash('error', 'Orçamento não encontrado.');
            $this->redirect('/orcamentos');
        }

        $items = $this->quotes->items((int) $quote['id']);

        return $this->render('quotes/show', [
            'title'    => 'Orçamento ' . $quote['code'],
            'quote'    => $quote,
            'items'    => $items,
            'subtotal' => $this->service->itemsSubtotal($items),
        ]);
    }

    public function edit(Request $request): string
    {
        $quote = $this->quotes->find((int) $request->param('id'));
        if (!$quote) {
            Session::flash('error', 'Orçamento não encontrado.');
            $this->redirect('/orcamentos');
        }

        return $this->render('quotes/form', [
            'title'            => 'Editar orçamento ' . $quote['code'],
            'quote'            => $quote,
            'items'            => $this->quotes->items((int) $quote['id']),
            'clients'          => $this->clients->options(),
            'vehiclesByClient' => $this->vehicles->groupedByClient(),
            'selectedClient'   => (int) $quote['client_id'],
        ]);
    }

    public function update(Request $request): string
    {
        $id    = (int) $request->param('id');
        $quote = $this->quotes->find($id);
        if (!$quote) {
            Session::flash('error', 'Orçamento não encontrado.');
            $this->redirect('/orcamentos');
        }

        $data = $request->only(['client_id', 'vehicle_id', 'status', 'discount', 'notes']);

        $validator = Validator::make($data, [
            'client_id' => 'required|integer',
            'status'    => 'required|in:rascunho,aguardando,aprovado,em_andamento,concluido,cancelado',
        ], [
            'client_id' => 'Cliente',
            'status'    => 'Status',
        ]);

        if ($validator->fails()) {
            $this->back('/orcamentos/' . $id . '/editar', $validator->messages(), $request->all());
        }

        $client = $this->clients->find((int) $data['client_id']);
        if (!$client) {
            $this->back('/orcamentos/' . $id . '/editar', ['Cliente inválido.'], $request->all());
        }

        $vehicle  = $this->resolveVehicle($data['vehicle_id'] ?? null, (int) $client['id']);
        $items    = $this->service->parseItems($request->all());
        $discount = $this->service->toFloat($data['discount'] ?? 0);
        $total    = $this->service->total($items, $discount);
        $status   = $data['status'];

        $approvedAt = $quote['approved_at'];
        if ($status === 'aprovado' && !$approvedAt) {
            $approvedAt = date('Y-m-d H:i:s');
        }

        $quoteData = array_merge([
            'client_id'   => (int) $client['id'],
            'vehicle_id'  => $vehicle['id'] ?? null,
            'updated_by'  => Auth::id(),
            'status'      => $status,
            'discount'    => $discount,
            'total'       => $total,
            'notes'       => $data['notes'] ?: null,
            'approved_at' => $approvedAt,
        ], $this->service->snapshot($client, $vehicle));

        Database::beginTransaction();
        try {
            $this->quotes->update($id, $quoteData);
            $this->quotes->replaceItems($id, $items);
            Database::commit();
        } catch (\Throwable $e) {
            Database::rollBack();
            $this->back('/orcamentos/' . $id . '/editar', ['Erro ao atualizar: ' . $e->getMessage()], $request->all());
        }

        $this->clearOld();
        Session::flash('success', 'Orçamento atualizado com sucesso.');
        $this->redirect('/orcamentos/' . $id);
    }

    public function updateStatus(Request $request): string
    {
        $id    = (int) $request->param('id');
        $quote = $this->quotes->find($id);
        if (!$quote) {
            Session::flash('error', 'Orçamento não encontrado.');
            $this->redirect('/orcamentos');
        }

        $status = (string) $request->input('status');
        if (!array_key_exists($status, quote_statuses())) {
            Session::flash('error', 'Status inválido.');
            $this->redirect('/orcamentos/' . $id);
        }

        $update = ['status' => $status, 'updated_by' => Auth::id()];
        if ($status === 'aprovado' && !$quote['approved_at']) {
            $update['approved_at'] = date('Y-m-d H:i:s');
        }

        $this->quotes->update($id, $update);
        Session::flash('success', 'Status atualizado para "' . status_label($status) . '".');
        $this->redirect('/orcamentos/' . $id);
    }

    public function destroy(Request $request): string
    {
        $id = (int) $request->param('id');
        $this->quotes->delete($id);
        Session::flash('success', 'Orçamento excluído.');
        $this->redirect('/orcamentos');
    }

    public function pdf(Request $request): string
    {
        $quote = $this->quotes->find((int) $request->param('id'));
        if (!$quote) {
            Session::flash('error', 'Orçamento não encontrado.');
            $this->redirect('/orcamentos');
        }

        $items = $this->quotes->items((int) $quote['id']);

        (new PdfService())->stream('quotes/pdf', [
            'quote'    => $quote,
            'items'    => $items,
            'subtotal' => $this->service->itemsSubtotal($items),
            'appName'  => config('app.name'),
        ], 'orcamento-' . $quote['code'] . '.pdf');

        exit;
    }

    /** Garante que o veículo informado pertence ao cliente. */
    private function resolveVehicle(mixed $vehicleId, int $clientId): ?array
    {
        if (!$vehicleId) {
            return null;
        }
        $vehicle = $this->vehicles->find((int) $vehicleId);
        if ($vehicle && (int) $vehicle['client_id'] === $clientId) {
            return $vehicle;
        }
        return null;
    }
}
