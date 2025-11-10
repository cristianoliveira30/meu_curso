<?php

namespace App\Factories;

use App\Models\Product;

class ProductsFactory
{
    public static function createFromArray(array $data): Product
    {
        $product = new Product();
        $product->setId($data['id'] ?? null);
        $product->setTitle($data['title'] ?? '');
        $product->setSlug($data['slug'] ?? '');
        $product->setShortDescription($data['short_description'] ?? '');
        $product->setDescription($data['description'] ?? '');
        $product->setCategory($data['category'] ?? '');
        $product->setPrice($data['price'] ?? 0);
        $product->setRating($data['rating'] ?? 0);
        $product->setImage($data['image'] ?? '');
        return $product;
        
    }
}
