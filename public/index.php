<?php
session_start();

require_once __DIR__ . '/../app/Core/autoload.php';
require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

// Rotas básicas
$router->get('/', 'HomeController@index');
$router->get('/contato', function() use ($router) { $router->renderView('contato', ['title' => 'Fale Conosco']); });
$router->get('/cadastro', function() use ($router) { $router->renderView('/auth/cadastro', ['title' => 'Cadastro']); });

// rotas de autenticação
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// rotas de cadastro
$router->get('/cadastro', 'AuthController@showRegister');
$router->post('/cadastro', 'AuthController@register');


$router->dispatch();


