<?php

/** @var string      $title */
/** @var array       $items */
/** @var float       $total */
/** @var int         $totalItems */
/** @var string|null $cep */
/** @var array|null  $endereco */
/** @var float|null  $frete */
/** @var float|null  $totalComFrete */
/** @var string|null $erroCep */
/** @var string|null $numero */
/** @var string|null $erroNumero */
?>
<section class="container my-5">
    <h1 class="fw-bold mb-4"><?= htmlspecialchars($title) ?></h1>

    <?php if (empty($items)): ?>
        <p class="text-muted">Seu carrinho está vazio.</p>
        <a href="/" class="btn btn-primary">Voltar para a loja</a>
    <?php else: ?>

        <!-- Tabela dos produtos -->
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Qtd</th>
                        <th class="text-end">Preço unitário</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php $product = $item['product']; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/<?= htmlspecialchars($product->getImage()) ?>"
                                         alt="<?= htmlspecialchars($product->getTitle()) ?>"
                                         style="width:60px; height:60px; object-fit:contain; background:#fff; border-radius:8px; padding:4px;">
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($product->getTitle()) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><?= (int) $item['quantity'] ?></td>
                            <td class="text-end">
                                R$<?= number_format((float) $product->getPrice(), 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                R$<?= number_format($item['subtotal'], 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total dos produtos -->
        <div class="mb-4">
            <p class="mb-0 text-muted">
                Itens no carrinho: <?= (int) $totalItems ?>
            </p>
            <p class="fs-5 fw-bold">
                Total dos produtos: R$<?= number_format($total, 2, ',', '.') ?>
            </p>
        </div>

        <hr>

        <h2 class="h5 mb-3">Endereço e frete</h2>

        <?php if (!empty($erroCep)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erroCep) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para calcular frete (CEP + Número + Voltar) -->
        <form method="post" action="/checkout/calcular-frete" class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="cep" class="form-label">CEP</label>
                <input
                    type="text"
                    class="form-control"
                    id="cep"
                    name="cep"
                    maxlength="9"
                    placeholder="00000-000"
                    value="<?= isset($cep) ? htmlspecialchars($cep) : '' ?>"
                    required
                >
            </div>

            <div class="col-md-3">
                <label for="numero" class="form-label">Número</label>
                <input
                    type="text"
                    class="form-control"
                    id="numero"
                    name="numero"
                    placeholder="Número da casa"
                    value="<?= htmlspecialchars($numero ?? ($_POST['numero'] ?? '')) ?>"
                    required
                >
                <?php if (!empty($erroNumero)): ?>
                    <div class="text-danger small mt-1">
                        <?= htmlspecialchars($erroNumero) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-5 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    Calcular frete
                </button>

                <!-- Botão de voltar para o carrinho (etapa anterior) -->
                <a href="/carrinho" class="btn btn-outline-secondary">
                    Voltar ao carrinho
                </a>
            </div>
        </form>

        <?php if (!empty($endereco) && $frete !== null): ?>
            <!-- Dados do endereço retornado + frete -->
            <div class="mb-3">
                <p class="mb-1">
                    <strong>Endereço:</strong>
                    <?= htmlspecialchars($endereco['logradouro'] ?? '') ?>
                    <?= !empty($endereco['bairro']) ? ' - ' . htmlspecialchars($endereco['bairro']) : '' ?>
                </p>
                <p class="mb-1">
                    <?= htmlspecialchars($endereco['localidade'] ?? '') ?>
                    <?= isset($endereco['uf']) ? ' - ' . htmlspecialchars($endereco['uf']) : '' ?>
                </p>
                <p class="mb-1">
                    <strong>Frete:</strong>
                    R$<?= number_format($frete, 2, ',', '.') ?>
                </p>
            </div>

            <!-- Resumo final -->
            <div class="mt-3">
                <h3 class="h5">Resumo final</h3>
                <p class="mb-1">
                    Total produtos: R$<?= number_format($total, 2, ',', '.') ?>
                </p>
                <p class="mb-1">
                    Frete: R$<?= number_format($frete, 2, ',', '.') ?>
                </p>
                <p class="fs-5 fw-bold">
                    Total com frete:
                    R$<?= number_format($totalComFrete, 2, ',', '.') ?>
                </p>
            </div>

            <!-- Botão de pagamento (abre modal de forma de pagamento) -->
            <div class="mt-3">
                <button type="button"
                        class="btn btn-success"
                        data-modal-target="paymentModal">
                    Escolher forma de pagamento
                </button>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</section>

<?php
// Se quiser tratar erro de pagamento vindo da sessão:
$erroPagamento = $_SESSION['erro_pagamento'] ?? null;
unset($_SESSION['erro_pagamento']);
?>

<?php if (!empty($erroPagamento)): ?>
    <div class="container mt-3">
        <div class="alert alert-danger">
            <?= htmlspecialchars($erroPagamento) ?>
        </div>
    </div>
<?php endif; ?>

<!-- Modal de forma de pagamento -->
<div id="paymentModal"
     class="manual-modal"
     aria-hidden="true"
     style="display: none;">

    <div class="manual-modal-backdrop" data-modal-close></div>

    <div class="manual-modal-dialog d-flex justify-content-center align-items-center">
        <div class="card w-50 bg-white p-4 rounded-4 shadow-lg position-relative">
            <button type="button"
                    class="btn-close position-absolute top-0 end-0 m-3"
                    data-modal-close></button>

            <h5 class="mb-3">Escolha a forma de pagamento</h5>

            <form id="paymentForm" method="post" action="/checkout/confirmar">
                <div class="form-check mb-2">
                    <input class="form-check-input"
                           type="radio"
                           name="payment_method"
                           id="payPix"
                           value="pix"
                           required>
                    <label class="form-check-label" for="payPix">
                        Pix (à vista)
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input"
                           type="radio"
                           name="payment_method"
                           id="payCard"
                           value="cartao">
                    <label class="form-check-label" for="payCard">
                        Cartão de crédito
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input"
                           type="radio"
                           name="payment_method"
                           id="payBoleto"
                           value="boleto">
                    <label class="form-check-label" for="payBoleto">
                        Boleto bancário
                    </label>
                </div>

                <!-- Total que será cobrado (produtos + frete, se tiver) -->
                <input type="hidden" name="total"
                       value="<?= isset($totalComFrete)
                           ? htmlspecialchars($totalComFrete)
                           : htmlspecialchars($total) ?>">

                <?php if (!empty($cep)): ?>
                    <input type="hidden" name="cep"
                           value="<?= htmlspecialchars($cep) ?>">
                <?php endif; ?>

                <?php if (!empty($numero ?? ($_POST['numero'] ?? ''))): ?>
                    <input type="hidden" name="numero"
                           value="<?= htmlspecialchars($numero ?? ($_POST['numero'] ?? '')) ?>">
                <?php endif; ?>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-modal-close>
                        Cancelar
                    </button>

                    <!-- este botão é interceptado via JS -->
                    <button type="button" class="btn btn-success" id="paymentSubmit">
                        Finalizar pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Estilos específicos dos modais / pagamento com cartão -->
<style>
    /* Container das carteiras (PayPal / Apple Pay / Google Pay) */
    .card-wallet-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .card-wallet-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        color: #111827;
        transition:
            background-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.05s ease,
            border-color 0.15s ease;
    }

    .card-wallet-btn svg {
        display: block;
        height: 18px;
        width: auto;
    }

    .card-wallet-btn:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
        transform: translateY(-1px);
    }

    .card-wallet-btn:focus-visible {
        outline: 2px solid #111827;
        outline-offset: 2px;
    }

    .card-wallet-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.16);
    }

    @media (max-width: 480px) {
        .card-wallet-options {
            flex-direction: column;
        }

        .card-wallet-btn {
            width: 100%;
            justify-content: center;
        }
    }

    .card-payment-container {
        max-width: 480px;
        width: 100%;
        background: #ffffff;
        border-radius: 16px;
        padding: 1.75rem 2rem 2rem;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
        position: relative;
    }

    .card-payment-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.75rem;
    }

    .card-payment-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 1.25rem;
    }

    .card-form {
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }

    .card-input-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .card-input-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
    }

    .card-input-field {
        width: 100%;
        padding: 0.55rem 0.7rem;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .card-input-field:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
    }

    .card-split-row {
        display: flex;
        gap: 0.75rem;
    }

    .card-split-row .card-input-group {
        flex: 1;
    }

    .purchase-btn {
        margin-top: 0.5rem;
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 999px;
        border: none;
        background: #111827;
        color: #f9fafb;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.05s ease,
            box-shadow 0.15s ease;
    }

    .purchase-btn:hover {
        background: #020617;
        transform: translateY(-1px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.4);
    }

    .card-separator {
        display: flex;
        align-items: center;
        margin: 1.25rem 0 1rem;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .card-separator-line {
        flex: 1;
        border: none;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 576px) {
        .card-payment-container {
            padding: 1.5rem 1.25rem 1.75rem;
        }
    }
</style>

<!-- Modal de cartão de crédito -->
<div id="cardPaymentModal"
     class="manual-modal"
     aria-hidden="true"
     style="display: none;">

    <div class="manual-modal-backdrop" data-modal-close></div>

    <div class="manual-modal-dialog d-flex justify-content-center align-items-center">
        <div class="card-payment-container">
            <button type="button"
                    class="btn-close position-absolute top-0 end-0 m-3"
                    data-modal-close></button>

            <h5 class="card-payment-title">Pagamento com cartão de crédito</h5>
            <p class="card-payment-subtitle">
                Informe os dados do cartão para finalizar sua compra com segurança.
            </p>

            <form id="cardPaymentForm" class="card-form" novalidate>
                <div class="card-input-group">
                    <label class="card-input-label" for="card_holder_name">
                        Nome completo do titular
                    </label>
                    <input
                        id="card_holder_name"
                        name="card_holder_name"
                        type="text"
                        class="card-input-field"
                        placeholder="Ex.: João da Silva"
                        required
                    >
                </div>

                <div class="card-input-group">
                    <label class="card-input-label" for="card_number">
                        Número do cartão
                    </label>
                    <input
                        id="card_number"
                        name="card_number"
                        type="text"
                        inputmode="numeric"
                        class="card-input-field"
                        placeholder="0000 0000 0000 0000"
                        maxlength="19"
                        required
                    >
                </div>

                <div class="card-split-row">
                    <div class="card-input-group">
                        <label class="card-input-label" for="card_expiry">
                            Validade (MM/AA)
                        </label>
                        <input
                            id="card_expiry"
                            name="card_expiry"
                            type="text"
                            class="card-input-field"
                            placeholder="01/26"
                            maxlength="5"
                            required
                        >
                    </div>
                    <div class="card-input-group">
                        <label class="card-input-label" for="card_cvv">
                            CVV
                        </label>
                        <input
                            id="card_cvv"
                            name="card_cvv"
                            type="password"
                            class="card-input-field"
                            placeholder="123"
                            maxlength="4"
                            required
                        >
                    </div>
                </div>

                <button type="button" id="cardSubmitBtn" class="purchase-btn">
                    Confirmar pagamento
                </button>

                <div class="card-separator">
                    <hr class="card-separator-line">
                    <span>ou pagar com carteira digital</span>
                    <hr class="card-separator-line">
                </div>

                <div class="card-wallet-options">
                    <!-- PayPal -->
                    <button type="button" class="card-wallet-btn" name="paypal">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="80"
                            height="22"
                            viewBox="0 0 124 33"
                            aria-hidden="true">
                            <path
                                fill="#253B80"
                                d="M46.211,6.749h-6.839c-0.468,0-0.866,0.34-0.939,0.802l-2.766,17.537c-0.055,0.346,0.213,0.658,0.564,0.658h3.265
                c0.468,0,0.866-0.34,0.939-0.803l0.746-4.73c0.072-0.463,0.471-0.803,0.938-0.803h2.165c4.505,0,7.105-2.18,7.784-6.5
                c0.306-1.89,0.013-3.375-0.872-4.415C50.224,7.353,48.5,6.749,46.211,6.749z M47,13.154c-0.374,2.454-2.249,2.454-4.062,2.454
                h-1.032l0.724-4.583c0.043-0.277,0.283-0.481,0.563-0.481h0.473c1.235,0,2.4,0,3.002,0.704C47.027,11.668,47.137,12.292,47,13.154z" />
                            <path
                                fill="#253B80"
                                d="M66.654,13.075h-3.275c-0.279,0-0.52,0.204-0.563,0.481l-0.145,0.916l-0.229-0.332
                c-0.709-1.029-2.29-1.373-3.868-1.373c-3.619,0-6.71,2.741-7.312,6.586c-0.313,1.918,0.132,3.752,1.22,5.031
                c0.998,1.176,2.426,1.666,4.125,1.666c2.916,0,4.533-1.875,4.533-1.875l-0.146,0.91c-0.055,0.348,0.213,0.66,0.562,0.66h2.95
                c0.469,0,0.865-0.34,0.939-0.803l1.77-11.209C67.271,13.388,67.004,13.075,66.654,13.075z M62.089,19.449
                c-0.316,1.871-1.801,3.127-3.695,3.127c-0.951,0-1.711-0.305-2.199-0.883c-0.484-0.574-0.668-1.391-0.514-2.301
                c0.295-1.855,1.805-3.152,3.67-3.152c0.93,0,1.686,0.309,2.184,0.892C62.034,17.721,62.232,18.543,62.089,19.449z" />
                            <path
                                fill="#253B80"
                                d="M84.096,13.075h-3.291c-0.314,0-0.609,0.156-0.787,0.417l-4.539,6.686l-1.924-6.425
                c-0.121-0.402-0.492-0.678-0.912-0.678h-3.234c-0.393,0-0.666,0.384-0.541,0.754l3.625,10.638l-3.408,4.811
                c-0.268,0.379,0.002,0.9,0.465,0.9h3.287c0.312,0,0.604-0.152,0.781-0.408L84.564,13.97C84.826,13.592,84.557,13.075,84.096,13.075z" />
                            <path
                                fill="#179BD7"
                                d="M94.992,6.749h-6.84c-0.467,0-0.865,0.34-0.938,0.802l-2.766,17.537c-0.055,0.346,0.213,0.658,0.562,0.658
                h3.51c0.326,0,0.605-0.238,0.656-0.562l0.785-4.971c0.072-0.463,0.471-0.803,0.938-0.803h2.164c4.506,0,7.105-2.18,7.785-6.5
                c0.307-1.89,0.012-3.375-0.873-4.415C99.004,7.353,97.281,6.749,94.992,6.749z M95.781,13.154c-0.373,2.454-2.248,2.454-4.062,2.454
                h-1.031l0.725-4.583c0.043-0.277,0.281-0.481,0.562-0.481h0.473c1.234,0,2.4,0,3.002,0.704
                C95.809,11.668,95.918,12.292,95.781,13.154z" />
                            <path
                                fill="#179BD7"
                                d="M115.434,13.075h-3.273c-0.281,0-0.52,0.204-0.562,0.481l-0.145,0.916l-0.23-0.332
                c-0.709-1.029-2.289-1.373-3.867-1.373c-3.619,0-6.709,2.741-7.311,6.586c-0.312,1.918,0.131,3.752,1.219,5.031
                c1,1.176,2.426,1.666,4.125,1.666c2.916,0,4.533-1.875,4.533-1.875l-0.146,0.91c-0.055,0.348,0.213,0.66,0.564,0.66h2.949
                c0.467,0,0.865-0.34,0.938-0.803l1.771-11.209C116.053,13.388,115.785,13.075,115.434,13.075z M110.869,19.449
                c-0.314,1.871-1.801,3.127-3.695,3.127c-0.949,0-1.711-0.305-2.199-0.883c-0.484-0.574-0.666-1.391-0.514-2.301
                c0.297-1.855,1.805-3.152,3.67-3.152c0.93,0,1.686,0.309,2.184,0.892C110.816,17.721,111.014,18.543,110.869,19.449z" />
                            <path
                                fill="#179BD7"
                                d="M119.295,7.23l-2.807,17.858c-0.055,0.346,0.213,0.658,0.562,0.658h2.822c0.469,0,0.867-0.34,0.939-0.803
                l2.768-17.536c0.055-0.346-0.213-0.659-0.562-0.659h-3.16C119.578,6.749,119.338,6.953,119.295,7.23z" />
                            <path
                                fill="#253B80"
                                d="M7.266,29.154l0.523-3.322l-1.165-0.027H1.061L4.927,1.292C4.939,1.218,4.978,1.149,5.035,1.1
                c0.057-0.049,0.13-0.076,0.206-0.076h9.38c3.114,0,5.263,0.648,6.385,1.927c0.526,0.6,0.861,1.227,1.023,1.917
                c0.17,0.724,0.173,1.589,0.007,2.644l-0.012,0.077v0.676l0.526,0.298c0.443,0.235,0.795,0.504,1.065,0.812
                c0.45,0.513,0.741,1.165,0.864,1.938c0.127,0.795,0.085,1.741-0.123,2.812c-0.24,1.232-0.628,2.305-1.152,3.183
                c-0.482,0.809-1.096,1.48-1.825,2c-0.696,0.494-1.523,0.869-2.458,1.109c-0.906,0.236-1.939,0.355-3.072,0.355h-0.73
                c-0.522,0-1.029,0.188-1.427,0.525c-0.399,0.344-0.663,0.814-0.744,1.328l-0.055,0.299l-0.924,5.855l-0.042,0.215
                c-0.011,0.068-0.03,0.102-0.058,0.125c-0.025,0.021-0.061,0.035-0.096,0.035H7.266z" />
                        </svg>>
                    </button>

                    <!-- Apple Pay -->
                    <button type="button" class="card-wallet-btn" name="apple-pay">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="70"
                            height="28"
                            viewBox="0 0 512 210.2"
                            aria-hidden="true">
                            <path
                                d="M93.6,27.1C87.6,34.2,78,39.8,68.4,39c-1.2-9.6,3.5-19.8,9-26.1c6-7.3,16.5-12.5,25-12.9
                   C103.4,10,99.5,19.8,93.6,27.1 M102.3,40.9c-13.9-0.8-25.8,7.9-32.4,7.9c-6.7,0-16.8-7.5-27.8-7.3
                   c-14.3,0.2-27.6,8.3-34.9,21.2c-15,25.8-3.9,64,10.6,85c7.1,10.4,15.6,21.8,26.8,21.4c10.6-0.4,14.8-6.9,27.6-6.9
                   c12.9,0,16.6,6.9,27.8,6.7c11.6-0.2,18.9-10.4,26-20.8c8.1-11.8,11.4-23.3,11.6-23.9c-0.2-0.2-22.4-8.7-22.6-34.3
                   c-0.2-21.4,17.5-31.6,18.3-32.2C123.3,42.9,107.7,41.3,102.3,40.9"
                                fill="#000" />
                            <path
                                d="M182.6,11.9v155.9h24.2v-53.3h33.5c30.6,0,52.1-21,52.1-51.4c0-30.4-21.1-51.2-51.3-51.2H182.6z
                   M206.8,32.3h27.9c21,0,33,11.2,33,30.9c0,19.7-12,31-33.1,31h-27.8V32.3z"
                                fill="#000" />
                            <path
                                d="M336.6,13.075c22.9,0,36.6,14.6,36.6,35.3V169h-22.4v-18.7h-0.5c-6.4,12.2-20.5,19.9-35.7,19.9
                   c-22.5,0-38.2-13.4-38.2-34.4c0-20,15.2-31.5,43.3-33.2l30.2-1.8v-8.8c0-12.7-8.3-19.6-23.1-19.6c-12.2,0-21.1,6.3-22.9,15.9
                   h-21.8C291.9,27.775,310.9,13.075,336.6,13.075z"
                                fill="#000" />
                            <path
                                d="M425.1,210.2c23.6,0,34.7-9,44.4-36.3L512,54.7h-24.6l-28.5,92.1h-0.5l-28.5-92.1h-25.3l41,113.5l-2.2,6.9
                   c-3.7,11.7-9.7,16.2-20.4,16.2c-1.9,0-5.6-0.2-7.1-0.4v18.7C417.3,210,423.3,210.2,425.1,210.2z"
                                fill="#000" />
                        </svg>
                    </button>

                    <!-- Google Pay -->
                    <button type="button" class="card-wallet-btn" name="google-pay">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="80"
                            height="28"
                            viewBox="0 0 80 39"
                            fill="none"
                            aria-hidden="true">
                            <g clip-path="url(#clip0_134_34)">
                                <path
                                    d="M37.8 19.7V29H34.8V6H42.6C44.5 6 46.3001 6.7 47.7001 8C49.1001 9.2 49.8 11 49.8 12.9C49.8 14.8 49.1001 16.5 47.7001 17.8C46.3001 19.1 44.6 19.8 42.6 19.8L37.8 19.7ZM37.8 8.8V16.8H42.8C43.9 16.8 45.0001 16.4 45.7001 15.6C47.3001 14.1 47.3 11.6 45.8 10.1L45.7001 10C44.9001 9.2 43.9 8.7 42.8 8.8H37.8Z"
                                    fill="#5F6368" />
                                <path
                                    d="M56.7001 12.8C58.9001 12.8 60.6001 13.4 61.9001 14.6C63.2001 15.8 63.8 17.4 63.8 19.4V29H61V26.8H60.9001C59.7001 28.6 58 29.5 56 29.5C54.3 29.5 52.8 29 51.6 28C50.5 27 49.8 25.6 49.8 24.1C49.8 22.5 50.4 21.2 51.6 20.2C52.8 19.2 54.5 18.8 56.5 18.8C58.3 18.8 59.7 19.1 60.8 19.8V19.1C60.8 18.1 60.4 17.1 59.6 16.5C58.8 15.8 57.8001 15.4 56.7001 15.4C55.0001 15.4 53.7 16.1 52.8 17.5L50.2001 15.9C51.8001 13.8 53.9001 12.8 56.7001 12.8ZM52.9001 24.2C52.9001 25 53.3001 25.7 53.9001 26.1C54.6001 26.6 55.4001 26.9 56.2001 26.9C57.4001 26.9 58.6 26.4 59.5 25.5C60.5 24.6 61 23.5 61 22.3C60.1 21.6 58.8 21.2 57.1 21.2C55.9 21.2 54.9 21.5 54.1 22.1C53.3 22.6 52.9001 23.3 52.9001 24.2Z"
                                    fill="#5F6368" />
                                <path
                                    d="M80 13.3L70.1 36H67.1L70.8 28.1L64.3 13.4H67.5L72.2 24.7H72.3L76.9 13.4H80V13.3Z"
                                    fill="#5F6368" />
                                <path
                                    d="M25.9 17.7C25.9 16.8 25.8 15.9 25.7 15H13.2V20.1H20.3C20 21.7 19.1 23.2 17.7 24.1V27.4H22C24.5 25.1 25.9 21.7 25.9 17.7Z"
                                    fill="#4285F4" />
                                <path
                                    d="M13.1999 30.5999C16.7999 30.5999 19.7999 29.3999 21.9999 27.3999L17.6999 24.0999C16.4999 24.8999 14.9999 25.3999 13.1999 25.3999C9.7999 25.3999 6.7999 23.0999 5.7999 19.8999H1.3999V23.2999C3.6999 27.7999 8.1999 30.5999 13.1999 30.5999Z"
                                    fill="#34A853" />
                                <path
                                    d="M5.8001 19.8999C5.2001 18.2999 5.2001 16.4999 5.8001 14.7999V11.3999H1.4001C-0.499902 15.0999 -0.499902 19.4999 1.4001 23.2999L5.8001 19.8999Z"
                                    fill="#FBBC04" />
                                <path
                                    d="M13.2 9.39996C15.1 9.39996 16.9 10.1 18.3 11.4L22.1 7.59996C19.7 5.39996 16.5 4.09996 13.3 4.19996C8.3 4.19996 3.7 6.99996 1.5 11.5L5.9 14.9C6.8 11.7 9.8 9.39996 13.2 9.39996Z"
                                    fill="#EA4335" />
                            </g>
                            <defs>
                                <clipPath id="clip0_134_34">
                                    <rect width="80" height="38.1" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS máscara de CEP + lógica dos modais de pagamento -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referências dos campos
        var cepInput    = document.getElementById('cep');
        var numeroInput = document.getElementById('numero');

        // Máscara simples de CEP
        if (cepInput) {
            cepInput.addEventListener('input', function() {
                var v = this.value.replace(/\D/g, '').slice(0, 8);
                if (v.length > 5) {
                    this.value = v.slice(0, 5) + '-' + v.slice(5);
                } else {
                    this.value = v;
                }
            });
        }

        // Helpers de modal
        function openModalById(id) {
            if (window.manualModal && typeof window.manualModal.openModal === 'function') {
                window.manualModal.openModal(id);
            } else {
                var m = document.getElementById(id);
                if (m) m.style.display = 'flex';
            }
        }

        function closeModalById(id) {
            if (window.manualModal && typeof window.manualModal.closeModal === 'function') {
                window.manualModal.closeModal(id);
            } else {
                var m = document.getElementById(id);
                if (m) m.style.display = 'none';
            }
        }

        // === EXIGIR CEP + NÚMERO AO CLICAR EM "Escolher forma de pagamento" ===
        var choosePaymentBtn = document.querySelector('[data-modal-target="paymentModal"]');

        if (choosePaymentBtn) {
            // usamos capture = true para interceptar antes de outros scripts
            choosePaymentBtn.addEventListener('click', function(e) {
                if (!cepInput || !numeroInput) {
                    return;
                }

                var cepVal    = cepInput.value.trim();
                var numeroVal = numeroInput.value.trim();
                var digits    = cepVal.replace(/\D/g, '');

                var hasError = false;

                // CEP obrigatório com 8 dígitos
                if (!cepVal || digits.length !== 8) {
                    hasError = true;
                }

                // Número obrigatório
                if (!numeroVal) {
                    hasError = true;
                }

                if (hasError) {
                    // trava qualquer comportamento (inclusive outros listeners)
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof e.stopImmediatePropagation === 'function') {
                        e.stopImmediatePropagation();
                    }

                    alert('Informe o CEP (8 dígitos) e o número da casa para continuar.');

                    if (!cepVal || digits.length !== 8) {
                        cepInput.focus();
                    } else if (!numeroVal) {
                        numeroInput.focus();
                    }

                    return;
                }

                // Tudo OK -> abrimos o modal manualmente e ainda assim bloqueamos outros handlers
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }

                var targetId = this.getAttribute('data-modal-target') || 'paymentModal';
                openModalById(targetId);
            }, true); // <- captura
        }

        // === Lógica de pagamento: Pix/Boleto envia direto, Cartão abre modal de cartão ===
        var paymentForm   = document.getElementById('paymentForm');
        var paymentSubmit = document.getElementById('paymentSubmit');
        var cardModalId   = 'cardPaymentModal';
        var cardSubmitBtn = document.getElementById('cardSubmitBtn');
        var cardForm      = document.getElementById('cardPaymentForm');

        if (paymentForm && paymentSubmit) {
            paymentSubmit.addEventListener('click', function(e) {
                var method = paymentForm.querySelector('input[name="payment_method"]:checked');
                if (!method) {
                    paymentForm.reportValidity();
                    return;
                }

                if (method.value === 'cartao') {
                    e.preventDefault();
                    openModalById(cardModalId);
                } else {
                    paymentForm.submit();
                }
            });
        }

        if (cardSubmitBtn && cardForm && paymentForm) {
            cardSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                if (!cardForm.checkValidity()) {
                    cardForm.reportValidity();
                    return;
                }

                // Copia dados do cartão para campos hidden do paymentForm
                var map = {
                    card_holder_name: 'card_holder_name',
                    card_number:      'card_number',
                    card_expiry:      'card_expiry',
                    card_cvv:         'card_cvv'
                };

                Object.keys(map).forEach(function(name) {
                    var source = document.getElementById(map[name]);
                    if (!source) return;

                    var hidden = paymentForm.querySelector('input[name="' + name + '"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = name;
                        paymentForm.appendChild(hidden);
                    }
                    hidden.value = source.value;
                });

                closeModalById(cardModalId);
                paymentForm.submit();
            });
        }
    });
</script>

