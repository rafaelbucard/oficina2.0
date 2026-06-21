<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Repositories\ClientRepository;
use App\Repositories\QuoteRepository;

class DashboardController extends Controller
{
    public function index(Request $request): string
    {
        $quotes  = new QuoteRepository();
        $clients = new ClientRepository();

        return $this->render('dashboard/index', [
            'title'         => 'Painel',
            'statusSummary' => $quotes->statusSummary(),
            'totalApproved' => $quotes->totalApproved(),
            'recent'        => $quotes->recent(6),
            'clientsCount'  => $clients->countSearch(),
        ]);
    }
}
