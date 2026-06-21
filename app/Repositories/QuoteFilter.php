<?php

namespace App\Repositories;

use App\Core\Request;

/**
 * Normaliza os parâmetros de filtro vindos da listagem de orçamentos.
 */
class QuoteFilter
{
    public string $search = '';
    public ?int $createdBy = null;
    public string $status = '';
    public string $client = '';
    public string $plate = '';
    public string $vehicleType = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $page = 1;

    public static function fromRequest(Request $request): self
    {
        $filter = new self();
        $filter->search      = (string) $request->query('q', '');
        $filter->status      = (string) $request->query('status', '');
        $filter->client      = (string) $request->query('client', '');
        $filter->plate       = (string) $request->query('plate', '');
        $filter->vehicleType = (string) $request->query('vehicle_type', '');
        $filter->dateFrom    = (string) $request->query('date_from', '');
        $filter->dateTo      = (string) $request->query('date_to', '');

        $createdBy = $request->query('created_by', '');
        $filter->createdBy = ($createdBy !== '' && is_numeric($createdBy)) ? (int) $createdBy : null;

        $page = (int) $request->query('page', 1);
        $filter->page = max(1, $page);

        return $filter;
    }

    /** Querystring preservando os filtros (para paginação/links). */
    public function toQuery(array $overrides = []): string
    {
        $params = array_filter([
            'q'            => $this->search,
            'status'       => $this->status,
            'client'       => $this->client,
            'plate'        => $this->plate,
            'vehicle_type' => $this->vehicleType,
            'created_by'   => $this->createdBy,
            'date_from'    => $this->dateFrom,
            'date_to'      => $this->dateTo,
        ], fn ($v) => $v !== '' && $v !== null);

        $params = array_merge($params, $overrides);

        return http_build_query($params);
    }
}
