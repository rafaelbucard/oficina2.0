<?php
/**
 * Oficina 2.0
 *
 * @author Rafael Buçard
 */
require __DIR__ . '/vendor/autoload.php';

use App\Entity\Repair;

// Recebendo valores das buscas (FILTER_SANITIZE_STRING foi removido no PHP 8)
$search       = trim((string) filter_input(INPUT_GET, 'search', FILTER_UNSAFE_RAW));
$searchClient = trim((string) filter_input(INPUT_GET, 'searchClient', FILTER_UNSAFE_RAW));
$searchDate   = trim((string) filter_input(INPUT_GET, 'date', FILTER_UNSAFE_RAW));

// Monta a busca usando prepared statements (ILIKE = case-insensitive no PostgreSQL)
if (strlen($search)) {
    $repair = Repair::getSearch('namem ILIKE ?', ['%' . str_replace(' ', '%', $search) . '%']);
} elseif (strlen($searchClient)) {
    $repair = Repair::getSearch('namec ILIKE ?', ['%' . str_replace(' ', '%', $searchClient) . '%']);
} elseif (strlen($searchDate)) {
    // a coluna date é timestamp; precisa de cast para texto no PostgreSQL
    $repair = Repair::getSearch('CAST(date AS TEXT) LIKE ?', ['%' . str_replace('/', '%', $searchDate) . '%']);
} else {
    $repair = Repair::getRepair();
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/list.php';
include __DIR__ . '/includes/footer.php';
