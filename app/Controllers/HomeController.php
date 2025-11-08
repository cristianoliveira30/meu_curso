<?php

namespace App\Controllers;

use App\Services\ProductsService;

class HomeController
{
    public function index()
    {
        $service = new ProductsService();
        $products = $service->getAllProducts();

        $title = 'Produtos em destaque';

        // Retorna os dados para o Router renderizar
        return [
            'view' => 'home',
            'data' => compact('title', 'products')
        ];
    }
}
