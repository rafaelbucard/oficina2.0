<?php

namespace App\Services;

use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    /** Renderiza uma view como PDF e envia ao navegador (inline). */
    public function stream(string $view, array $data, string $filename): void
    {
        $html = View::render($view, $data, null);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
}
