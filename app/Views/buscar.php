<?php
/** @var string $title */
/** @var string $term */
/** @var array  $products */
?>

<section class="container my-5">
    <h1 class="fw-bold mb-3 text-dark">
        <?= htmlspecialchars($title) ?>
    </h1>

    <p class="text-muted mb-4">
        Resultados para: <strong>"<?= htmlspecialchars($term) ?>"</strong>
    </p>

    <?php if (empty($products)): ?>
        <div style="
            padding: 1rem 1.25rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            color: #4b5563;
            margin-bottom: 1rem;
        ">
            Nenhum produto encontrado para esse termo de busca.
        </div>
        <a href="/" style="
            display: inline-block;
            padding: 0.6rem 1.4rem;
            border-radius: 999px;
            background-color: #111827;
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        ">
            Voltar para a página inicial
        </a>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="/<?= htmlspecialchars($product['image']) ?>"
                             class="card-img-top mx-auto d-block"
                             alt="<?= htmlspecialchars($product['title']) ?>"
                             style="width: 100%; height: 250px; object-fit: contain; background-color: #fff; padding: 10px; border-radius: 10px;">

                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold">
                                <?= htmlspecialchars($product['title']) ?>
                            </h5>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars($product['short_description'] ?? '') ?>
                            </p>
                            <p class="card-text text-success fw-semibold">
                                R$<?= number_format((float) $product['price'], 2, ',', '.') ?>
                            </p>
                        </div>

                        <div class="card-footer bg-transparent border-0">
                            <!-- Aqui você pode, se quiser, integrar com o mesmo esquema de modais/carrinho -->
                            <!-- Exemplo simples: botão para detalhes ou adicionar ao carrinho -->

                            <form action="/carrinho/adicionar" method="POST">
                                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                <button type="submit"
                                        class="btn btn-outline-primary w-100 my-1 rounded-pill">
                                    Adicionar ao carrinho
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
