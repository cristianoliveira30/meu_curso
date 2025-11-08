<?php

namespace App\Controllers;

use App\Services\ProductsService;

class ProductController
{
    private $productyService;

    public function __construct() {
        $this->productyService = new ProductsService();
    }

    public function indexAdicionar()
    {
        return [
            'view' => 'products/add-product',
            'data' => ['title' => 'Adicionar Produto']
        ];
    }

    public function addProduto()
    {
        try {
            $data = [
                'title'             => $_POST['title'],
                'slug'              => $_POST['slug'],
                'category'          => $_POST['category'],
                'shortDescription'  => $_POST['short_description'],
                'description'       => $_POST['description'],
                'price'             => $_POST['price'],
                'rating'            => $_POST['rating'],
                'image'             => $_POST['image'],
            ];

            $this->productyService->register($data);

            $_SESSION['success'] = 'Produto cadastrado com sucesso!';
            header('Location: /produto/adicionar');
            exit;
        } catch (\Exception $e) {
            // Guarda os valores preenchidos
            $_SESSION['old'] = $data;

            $decoded = json_decode($e->getMessage(), true);
            if (is_array($decoded)) {
                $_SESSION['errors'] = $decoded;
            } else {
                $_SESSION['error'] = $e->getMessage();
            }

            header('Location: /produto/adicionar');
            exit;
        }
    }
}
