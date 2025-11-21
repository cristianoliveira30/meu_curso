<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Product;
use PDO;

class ProductsRepository
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $this->pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['user'],
            $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function create(Product $product)
    {
        $pdo = Database::getConnection();
        $stmt = $this->pdo->prepare("
            INSERT INTO products (
                title, slug, category, short_description, description, 
                price, rating, image, created_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $product->getTitle(),
            $product->getSlug(),
            $product->getCategory(),
            $product->getShortDescription(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getRating(),
            $product->getImage(),
        ]);

        $product->setId($pdo->lastInsertId());
        return $product;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(string $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
