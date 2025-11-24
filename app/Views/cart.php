<?php

/** @var string $title */
/** @var array  $items */
/** @var float  $total */
/** @var int    $totalItems */
?>

<section class="container my-5">
    <h1 class="fw-bold mb-4"><?= htmlspecialchars($title) ?></h1>

    <?php if (empty($items)): ?>
        <p class="text-muted">Seu carrinho está vazio.</p>
        <a href="/" class="btn btn-primary">Voltar para a loja</a>
    <?php else: ?>
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Qtd</th>
                        <th class="text-end">Preço unitário</th>
                        <th class="text-end">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $product  = $item['product'];
                        $quantity = $item['quantity'];
                        $subtotal = $item['subtotal'];
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/<?= htmlspecialchars($product->getImage()) ?>"
                                        alt="<?= htmlspecialchars($product->getTitle()) ?>"
                                        style="width:60px; height:60px; object-fit:contain; background:#fff; border-radius:8px; padding:4px;">
                                    <div>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($product->getTitle()) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><?= (int) $quantity ?></td>
                            <td class="text-end">
                                R$<?= number_format((float) $product->getPrice(), 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                R$<?= number_format($subtotal, 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                <form method="post" action="/carrinho/remover" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?= (int) $product->getId() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted">
                Itens no carrinho: <?= (int) $totalItems ?>
            </p>
            <div class="text-end">
                <p class="fs-5 fw-bold mb-2">
                    Total: R$<?= number_format($total, 2, ',', '.') ?>
                </p>
                <a href="/checkout" class="btn btn-success">
                    Finalizar compra
                </a>

            </div>
        </div>
    <?php endif; ?>
</section>