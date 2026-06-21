<?php

use App\Core\Session;

if (!function_exists('config')) {
    /** Lê um valor de configuração em config/{file}.php usando notação "file.key". */
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        [$file, $item] = array_pad(explode('.', $key, 2), 2, null);

        if (!isset($cache[$file])) {
            $path = dirname(__DIR__) . '/config/' . $file . '.php';
            $cache[$file] = is_file($path) ? require $path : [];
        }

        if ($item === null) {
            return $cache[$file];
        }

        return $cache[$file][$item] ?? $default;
    }
}

if (!function_exists('e')) {
    /** Escapa texto para saída HTML (proteção XSS). */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    /** Monta uma URL absoluta a partir da raiz da aplicação. */
    function url(string $path = ''): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /** Redireciona para um caminho e encerra a execução. */
    function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('old')) {
    /** Recupera o valor anterior de um campo após falha de validação. */
    function old(string $key, mixed $default = ''): mixed
    {
        $old = Session::get('_old', []);
        return $old[$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        $token = Session::get('_csrf');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Session::set('_csrf', $token);
        }
        return $token;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('method_field')) {
    /** Permite simular verbos como PUT/DELETE via campo oculto. */
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
    }
}

if (!function_exists('money')) {
    /** Formata um número como moeda BRL. */
    function money(mixed $value): string
    {
        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }
}

if (!function_exists('quote_statuses')) {
    /** Mapa de status do orçamento => rótulo legível. */
    function quote_statuses(): array
    {
        return [
            'rascunho'     => 'Rascunho',
            'aguardando'   => 'Aguardando aprovação',
            'aprovado'     => 'Aprovado',
            'em_andamento' => 'Em andamento',
            'concluido'    => 'Concluído',
            'cancelado'    => 'Cancelado',
        ];
    }
}

if (!function_exists('status_label')) {
    function status_label(?string $status): string
    {
        return quote_statuses()[$status] ?? (string) $status;
    }
}

if (!function_exists('status_badge')) {
    /** Classe de cor (Bootstrap) para cada status. */
    function status_badge(?string $status): string
    {
        return match ($status) {
            'rascunho'     => 'secondary',
            'aguardando'   => 'warning',
            'aprovado'     => 'info',
            'em_andamento' => 'primary',
            'concluido'    => 'success',
            'cancelado'    => 'danger',
            default        => 'secondary',
        };
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $value, string $format = 'd/m/Y H:i'): string
    {
        if (!$value) {
            return '';
        }
        $ts = strtotime($value);
        return $ts ? date($format, $ts) : '';
    }
}
