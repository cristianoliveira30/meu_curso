<?php

namespace App\Controllers;

use App\Services\ProductsService;

class CartController
{
    /** @var ProductsService */
    private $productsService;

    public function __construct()
    {
        $this->productsService = new ProductsService();
    }

    /**
     * GET /carrinho
     * Mostra a página do carrinho
     */
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->productsService->getById((int) $productId); // precisa ter esse método no service
            if (!$product) {
                continue;
            }

            $price    = (float) $product->getPrice();
            $subtotal = $price * $quantity;
            $total   += $subtotal;

            $items[] = [
                'product'  => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        $totalItems = array_sum($cart);
        $title      = 'Seu carrinho';

        return [
            'view' => 'cart',   // app/Views/cart.php
            'data' => compact('title', 'items', 'total', 'totalItems'),
        ];
    }

    /**
     * POST /carrinho/adicionar
     * Adiciona item na sessão
     */
    public function add()
    {
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity  = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        if (!$quantity || $quantity < 1) {
            $quantity = 1;
        }

        if (!$productId) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Produto inválido']);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId] += $quantity;

        $totalItems = array_sum($_SESSION['cart']);

        // Se veio via fetch (AJAX), respondemos JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

            header('Content-Type: application/json');
            echo json_encode([
                'success'    => true,
                'totalItems' => $totalItems,
            ]);
            exit;
        }

        // fallback se não for AJAX
        header('Location: /carrinho');
        exit;
    }

    /**
     * POST /carrinho/remover
     * Remove um item do carrinho
     */
    public function remove()
    {
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        header('Location: /carrinho');
        exit;
    }
}
