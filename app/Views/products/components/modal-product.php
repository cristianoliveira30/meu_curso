<?php

/** @var \App\Models\Product $product */

use App\Repositories\ProductsRepository;

if (!isset($product)) {
    if (!isset($productId)) {
        throw new Exception("Parâmetro 'product' ou 'productId' é obrigatório na modal de produto.");
    }

    // Buscar produto diretamente, caso só o ID tenha sido passado
    $repo = new ProductsRepository();
    $product = $repo->findById($productId);

    if (!$product) {
        echo "<div class='text-center text-danger p-3'>Produto não encontrado.</div>";
        return;
    }
}
?>

<div id="productModal-<?= htmlspecialchars($product->getId()) ?>"
     class="manual-modal"
     aria-hidden="true"
     style="display: none;">

    <div class="manual-modal-backdrop"></div>

    <div class="manual-modal-dialog d-flex justify-content-center align-items-center">
        <div class="card w-50 bg-white p-4 rounded-4 shadow-lg position-relative">
            <button data-modal-close class="btn-close position-absolute top-0 end-0 m-3"></button>

            <div class="text-center">
                <img src="/<?= htmlspecialchars($product->getImage()) ?>"
                     class="img-fluid rounded-3 mb-3"
                     style="max-height: 250px; object-fit: cover;"
                     alt="<?= htmlspecialchars($product->getTitle()) ?>">

                <h4 class="fw-bold"><?= htmlspecialchars($product->getTitle()) ?></h4>
                <p class="text-muted"><?= nl2br(htmlspecialchars($product->getDescription())) ?></p>
                <p class="text-success fs-5 fw-semibold mb-4">
                    R$<?= htmlspecialchars($product->getPrice()) ?>
                </p>

                <!-- Botão ajustado para integrar com o carrinho -->
                <button type="button"
                        class="btn btn-dark w-100 rounded-pill js-open-cart-modal"
                        data-id="<?= $product->getId() ?>"
                        data-title="<?= htmlspecialchars($product->getTitle()) ?>"
                        data-price="<?= htmlspecialchars($product->getPrice()) ?>"
                        data-image="/<?= htmlspecialchars($product->getImage()) ?>">
                    Adicionar ao carrinho
                </button>
            </div>
        </div>
    </div>
</div>
