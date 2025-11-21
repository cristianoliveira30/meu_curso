<?php

namespace App\Core;

class Middleware
{
    public static function auth()
    {
        if (!Auth::check()) {
            // Redireciona se não estiver logado
            header('Location: /');
            exit;
        }
    }

    public static function admin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // 403: Acesso negado
            http_response_code(403);
            echo "<h1>Acesso negado</h1><p>Você não tem permissão para acessar esta página.</p>";
            exit;
        }
    }
}
