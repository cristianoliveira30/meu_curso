<?php

namespace App\Controllers;

use App\Services\ProductsService;

class CheckoutController
{
    /** @var ProductsService */
    private $productsService;

    public function __construct()
    {
        $this->productsService = new ProductsService();
    }

    /**
     * Monta um resumo do carrinho (reaproveitado nos dois métodos)
     */
    private function buildCartSummary(): array
    {
        $cart = $_SESSION['cart'] ?? [];

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->productsService->getById((int) $productId);
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

        return compact('items', 'total', 'totalItems');
    }

    /**
     * GET /checkout
     * Mostra a página de pagamento sem frete calculado ainda
     */
    public function index()
    {
        $title         = 'Finalizar compra';
        $summary       = $this->buildCartSummary();

        // Valores "vazios" para a primeira vez
        $cep           = null;
        $numero        = null;
        $endereco      = null;
        $frete         = null;
        $totalComFrete = null;
        $erroCep       = null;

        return [
            'view' => 'checkout', // app/Views/checkout.php
            'data' => array_merge(
                $summary,
                compact('title', 'cep', 'numero', 'endereco', 'frete', 'totalComFrete', 'erroCep')
            ),
        ];
    }

    /**
     * POST /checkout/calcular-frete
     * Recebe o CEP, consulta o endereço e calcula o valor do frete
     */
    public function calcularFrete()
    {
        $title   = 'Finalizar compra';
        $summary = $this->buildCartSummary();

        $cep           = preg_replace('/\D/', '', $_POST['cep'] ?? '');
        $numero        = trim($_POST['numero'] ?? '');
        $erroCep       = null;
        $endereco      = null;
        $frete         = null;
        $totalComFrete = null;

        if (strlen($cep) !== 8) {
            $erroCep = 'CEP inválido. Informe 8 dígitos.';
        } else {
            // Consulta ViaCEP (API pública gratuita)
            $url  = "https://viacep.com.br/ws/{$cep}/json/";
            $json = @file_get_contents($url);

            if ($json === false) {
                $erroCep = 'Não foi possível consultar o CEP. Tente novamente.';
            } else {
                $data = json_decode($json, true);

                if (isset($data['erro']) && $data['erro']) {
                    $erroCep = 'CEP não encontrado.';
                } else {
                    $endereco = [
                        'cep'        => $data['cep'] ?? $cep,
                        'logradouro' => $data['logradouro'] ?? '',
                        'bairro'     => $data['bairro'] ?? '',
                        'localidade' => $data['localidade'] ?? '',
                        'uf'         => $data['uf'] ?? '',
                    ];

                    // Calcula o valor do frete pela UF
                    $frete         = $this->calcularValorFretePorUF($endereco['uf']);
                    $totalComFrete = $summary['total'] + $frete;

                    // Guardar na sessão para a etapa de confirmação
                    $_SESSION['checkout'] = [
                        'cep'           => $cep,
                        'numero'        => $numero,
                        'endereco'      => $endereco,
                        'frete'         => $frete,
                        'totalComFrete' => $totalComFrete,
                    ];
                }
            }
        }

        return [
            'view' => 'checkout',
            'data' => array_merge(
                $summary,
                compact('title', 'cep', 'numero', 'endereco', 'frete', 'totalComFrete', 'erroCep')
            ),
        ];
    }

    /**
     * Tabela de frete bem simples por UF/região
     */
    private function calcularValorFretePorUF(string $uf): float
    {
        $uf = strtoupper($uf);

        $sudeste      = ['SP', 'RJ', 'MG', 'ES'];
        $sul          = ['PR', 'SC', 'RS'];
        $centroOeste  = ['DF', 'GO', 'MT', 'MS'];
        $nordeste     = ['BA', 'SE', 'AL', 'PE', 'PB', 'RN', 'CE', 'PI', 'MA'];
        $norte        = ['AM', 'RR', 'AP', 'PA', 'TO', 'RO', 'AC'];

        if (in_array($uf, $sudeste, true))     return 20.00;
        if (in_array($uf, $sul, true))         return 25.00;
        if (in_array($uf, $centroOeste, true)) return 30.00;
        if (in_array($uf, $nordeste, true))    return 35.00;
        if (in_array($uf, $norte, true))       return 40.00;

        return 50.00; // fallback se não identificar a UF
    }

    /**
     * POST /checkout/confirmar
     * Recebe forma de pagamento e mostra tela de pedido confirmado
     */
    public function confirmar()
    {
        $summary    = $this->buildCartSummary();
        $items      = $summary['items'];
        $total      = $summary['total'];
        $totalItems = $summary['totalItems'];

        $checkoutData  = $_SESSION['checkout'] ?? null;
        $frete         = $checkoutData['frete'] ?? 0.0;
        $totalComFrete = $checkoutData['totalComFrete'] ?? ($total + $frete);
        $endereco      = $checkoutData['endereco'] ?? null;
        $cep           = $checkoutData['cep'] ?? null;
        $numero        = $checkoutData['numero'] ?? ($_POST['numero'] ?? null);

        $paymentMethod = $_POST['payment_method'] ?? null;

        if (!$paymentMethod) {
            // se não escolheu nada, volta pro checkout mostrando erro
            $_SESSION['erro_pagamento'] = 'Escolha uma forma de pagamento.';
            header('Location: /checkout');
            exit;
        }

        // Se for cartão, validamos os dados básicos
        $cardLastDigits = null;

        if ($paymentMethod === 'cartao') {
            $cardHolder = trim($_POST['card_holder_name'] ?? '');
            $cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
            $cardExpiry = trim($_POST['card_expiry'] ?? '');
            $cardCvv    = trim($_POST['card_cvv'] ?? '');

            if (
                $cardHolder === '' ||
                $cardNumber === '' ||
                $cardExpiry === '' ||
                $cardCvv === ''
            ) {
                $_SESSION['erro_pagamento'] = 'Preencha todos os dados do cartão de crédito.';
                header('Location: /checkout');
                exit;
            }

            // Apenas últimos 4 dígitos para exibir em tela (não guardar cartão inteiro!)
            $cardLastDigits = substr($cardNumber, -4);
        }

        $title = 'Pedido confirmado';

        // (Opcional) limpar carrinho e dados de checkout:
        // $_SESSION['cart'] = [];
        // unset($_SESSION['checkout']);

        return [
            'view' => 'order-confirmation',
            'data' => compact(
                'title',
                'items',
                'total',
                'totalItems',
                'frete',
                'totalComFrete',
                'endereco',
                'cep',
                'numero',
                'paymentMethod',
                'cardLastDigits'
            ),
        ];
    }
}
