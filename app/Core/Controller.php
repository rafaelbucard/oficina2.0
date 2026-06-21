<?php

namespace App\Core;

abstract class Controller
{
    /** Renderiza uma view com layout e envia ao navegador. */
    protected function view(string $view, array $data = [], ?string $layout = 'layouts/app'): string
    {
        return View::render($view, $data, $layout);
    }

    /** Renderiza guardando dados padrão (usuário logado, flash). */
    protected function render(string $view, array $data = []): string
    {
        $shared = [
            'authUser' => Auth::user(),
            'flash'    => [
                'success' => Session::getFlash('success'),
                'error'   => Session::getFlash('error'),
            ],
            'errors'   => Session::getFlash('errors') ?? [],
            'title'    => $data['title'] ?? config('app.name'),
        ];

        return $this->view($view, array_merge($shared, $data));
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    /** Redireciona de volta repovoando o formulário e os erros. */
    protected function back(string $fallback, array $errors = [], array $old = []): never
    {
        if ($errors !== []) {
            Session::flash('errors', $errors);
        }
        if ($old !== []) {
            Session::set('_old', $old);
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? $fallback;
        $path = parse_url($referer, PHP_URL_PATH) ?: $fallback;
        redirect($path);
    }

    protected function clearOld(): void
    {
        Session::remove('_old');
    }
}
