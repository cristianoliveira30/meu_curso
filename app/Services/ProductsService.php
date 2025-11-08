<?php

namespace App\Services;

use App\Repositories\ProductsRepository;
use App\Factories\ProductsFactory;
use App\Models\Product;

class ProductsService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ProductsRepository();
    }

    public function register(array $product)
    {
        $errors = [];

        // Validações simples
        if (empty($product['title'])) {
            $errors['title'] = 'O nome é obrigatório.';
        }
        if (empty($product['price'])) {
            $errors['price'] = 'O preço é obrigatório.';
        }
        if (empty($product['category'])) {
            $errors['category'] = 'A categoria é obrigatória.';
        }
        if (empty($product['description'])) {
            $errors['description'] = 'A categoria é obrigatória.';
        }
        if (empty($product['shortDescription'])) {
            $errors['shortDescription'] = 'A categoria é obrigatória.';
        }

        // Se houver erros, lançamos todos de uma vez
        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

        $productDone = new Product([
            'title'             => $product['title'],
            'slug'              => $product['slug'],
            'category'          => $product['category'],
            'shortDescription'  => $product['short_description'] ?? null,
            'description'       => $product['description'] ?? null,
            'price'             => $product['price'],
            'stock'             => $product['stock'] ?? null,
            'rating'            => $product['rating'] ?? null,
            'image'             => $product['image'] ?? null,
            'supplier'          => $product['supplier'] ?? null,
        ]);

        return $this->repository->create($productDone);
    }

    public function getAllProducts(): array
    {
        $rows = $this->repository->findAll();
        $products = [];

        foreach ($rows as $row) {
            $products[] = ProductsFactory::createFromArray($row);
        }

        return $products;
    }

    public function getCourseBySlug(string $slug)
    {
        $data = $this->repository->findBySlug($slug);
        return $data ? ProductsFactory::createFromArray($data) : null;
    }
}
