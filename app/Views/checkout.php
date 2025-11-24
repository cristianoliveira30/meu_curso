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

        <!-- Formulário para calcular frete -->
        <form method="post" action="/checkout/calcular-frete" class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="cep" class="form-label">CEP</label>
                <input type="text"
                    class="form-control"
                    id="cep"
                    name="cep"
                    maxlength="9"
                    placeholder="00000-000"
                    value="<?= isset($cep) ? htmlspecialchars($cep) : '' ?>"
                    required>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">
                    Calcular frete
                </button>
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

            <!-- Botão de pagamento (simulação) -->
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

    <div class="manual-modal-backdrop"></div>

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

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-modal-close>
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        Finalizar pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- JS simples para máscara de CEP -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var cepInput = document.getElementById('cep');
        if (!cepInput) return;

        cepInput.addEventListener('input', function() {
            var v = this.value.replace(/\D/g, '').slice(0, 8);
            if (v.length > 5) {
                this.value = v.slice(0, 5) + '-' + v.slice(5);
            } else {
                this.value = v;
            }
        });
    });
</script>