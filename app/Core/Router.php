<?php

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // remove o prefixo /public se existir
        $uri = str_replace('/public', '', $uri);

        $callback = $this->routes[$method][$uri] ?? null;

        if (!$callback) {
            $this->renderView('not-found', ['title' => 'Página não encontrada']);
            return;
        }

        // ✅ Caso o callback seja uma função anônima
        if (is_callable($callback)) {
            echo call_user_func($callback);
            return;
        }

        // ✅ Caso o callback seja um controller do tipo "HomeController@index"
        if (is_string($callback)) {
            [$controller, $method] = explode('@', $callback);
            $controllerPath = __DIR__ . '/../Controllers/' . $controller . '.php';

            if (!file_exists($controllerPath)) {
                $this->renderView('not-found', ['title' => 'Controller não encontrado']);
                return;
            }

            require_once $controllerPath;

            // ✅ Usa o namespace correto
            $controllerClass = "App\\Controllers\\{$controller}";
            $controllerInstance = new $controllerClass();

            $response = call_user_func([$controllerInstance, $method]);

            if (is_array($response) && isset($response['view'])) {
                $this->renderView($response['view'], $response['data'] ?? []);
            } else {
                echo $response;
            }

            return;
        }
    }


    public function renderView($view, $data = [])
    {
        extract($data);
        ob_start();

        $user = \App\Core\Auth::user();
        $isLoggedIn = \App\Core\Auth::check();
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            include __DIR__ . '/../Views/not-found.php';
        }

        $content = ob_get_clean();

        // Layout principal sempre é renderizado no final
        include __DIR__ . '/../Views/layouts/main.php';
    }
}
