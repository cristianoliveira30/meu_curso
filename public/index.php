<?php
session_start();

require_once __DIR__ . '/../app/Core/autoload.php';
require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

// Rotas básicas
$router->get('/', 'HomeController@index');
$router->get('/contato', function() use ($router) { $router->renderView('contato', ['title' => 'Contato']); });
$router->get('/cadastro', function() use ($router) { $router->renderView('/auth/cadastro', ['title' => 'Cadastro']); });

// autenticação
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// cadastro
$router->get('/cadastro', 'AuthController@showRegister');
$router->post('/cadastro', 'AuthController@register');

// Produtos — apenas admin pode adicionar ou editar
$router->get('/produto/adicionar', 'ProductController@indexAdicionar', ['auth', 'admin']);
$router->post('/produto/adicionar', 'ProductController@addProduto', ['auth', 'admin']);
$router->post('/produto/editar', 'ProductController@indexEditar', ['auth', 'admin']);
$router->post('/produto/editar', 'ProductController@editProduto', ['auth', 'admin']);

// Perfil — apenas usuário logado
$router->get('/perfil', 'UserController@index', ['auth']);


$router->dispatch();


