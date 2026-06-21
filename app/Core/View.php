<?php

namespace App\Core;

class View
{
    private const VIEW_PATH = __DIR__ . '/../Views/';

    /**
     * Renderiza uma view dentro de um layout.
     * O conteúdo da view fica disponível como $content no layout.
     */
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/app'): string
    {
        $content = self::renderPartial($view, $data);

        if ($layout === null) {
            return $content;
        }

        return self::renderPartial($layout, array_merge($data, ['content' => $content]));
    }

    /** Renderiza um arquivo de view isolado e devolve o HTML. */
    public static function renderPartial(string $view, array $data = []): string
    {
        $file = self::VIEW_PATH . str_replace('.', '/', $view) . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
