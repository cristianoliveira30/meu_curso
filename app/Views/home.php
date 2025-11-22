<div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="/img/svg/Camada 1.svg" class="d-block w-100" alt="Banner 1">
      <div class="carousel-caption text-start d-none d-md-block">
        <a href="#" class="btn btn-primary rounded-pill px-4">Ver cursos</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="/img/svg/Camada 2.svg" class="d-block w-100" alt="Banner 2">
      <div class="carousel-caption text-start d-none d-md-block">
        <a href="#" class="btn btn-light rounded-pill px-4">Saiba mais</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="/img/svg/Camada 3.svg" class="d-block w-100" alt="Banner 3">
      <div class="carousel-caption text-start d-none d-md-block">
        <a href="#" class="btn btn-light rounded-pill px-4">Começar agora</a>
      </div>
    </div>
  </div>

  <!-- Controles -->
  <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Próximo</span>
  </button>
</div>

<!-- 🎓 Seção de cursos -->
<section class="container mb-5">
  <h1 class="fw-bold mb-3 text-dark">Produtos em destaque 🔥</h1>
  <p class="text-muted mb-4">Explore os produtos mais procurados!</p>

  <div class="row g-4">
    <?php
    // Agrupa produtos por categoria
    $grouped = [
      'religioso' => [],
      'roupas' => [],
      'comum' => [],
    ];

    foreach ($products as $p) {
      $cat = $p->getCategory();
      if (!isset($grouped[$cat])) $grouped[$cat] = [];
      $grouped[$cat][] = $p;
    }

    // Define nomes bonitos para exibição
    $categoryNames = [
      'religioso' => 'Itens Religiosos',
      'roupas' => 'Roupas e Acessórios',
      'comum' => 'Produtos Diversos'
    ];
    ?>
    <?php foreach ($grouped as $category => $items): ?>
      <?php if (empty($items)) continue; ?>
      <section class="mb-5">
        <h3 class="fw-bold mb-4 text-dark"><?= $categoryNames[$category] ?? ucfirst($category) ?></h3>

        <div id="carousel-<?= $category ?>" class="carousel slide" data-bs-ride="false">
          <div class="carousel-inner">
            <?php foreach (array_chunk($items, 4) as $index => $chunk): ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="row g-4">
                  <?php foreach ($chunk as $product): ?>
                    <div class="col-md-3">
                      <div class="card h-100 shadow-sm border-0">
                        <img src="/<?= htmlspecialchars($product->getImage()) ?>" 
                             class="card-img-top mx-auto d-block"
                             alt="<?= htmlspecialchars($product->getTitle()) ?>"
                             style="width: 100%; height: 250px; object-fit: contain; background-color: #fff; padding: 10px; border-radius: 10px;">
                        <div class="card-body text-center">
                          <h5 class="card-title fw-bold"><?= htmlspecialchars($product->getTitle()) ?></h5>
                          <p class="card-text text-muted"><?= htmlspecialchars($product->getShortDescription()) ?></p>
                          <p class="card-text text-success fw-semibold">R$<?= htmlspecialchars($product->getPrice()) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                          <button class="btn btn-dark w-100 my-1 rounded-pill"
                            data-modal-target="productModal-<?= $product->getId() ?>">
                            Ver detalhes
                          </button>
                          <a href="/produto/<?= htmlspecialchars($product->getSlug()) ?>"
                            class="btn btn-outline-primary w-100 my-1 rounded-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                              <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                            </svg>
                          </a>
                        </div>
                      </div>
                    </div>

                    <?php include __DIR__ . '/products/components/modal-product.php'; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Controles -->
          <button class="carousel-control-prev position-absolute align-self-center d-none d-md-block d-lg-block" type="button" data-bs-target="#carousel-<?= $category ?>" data-bs-slide="prev">
            <span class="text-dark">
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
              </svg>
            </span>
          </button>
          <button class="carousel-control-next position-absolute align-self-center d-none d-md-block d-lg-block" type="button" data-bs-target="#carousel-<?= $category ?>" data-bs-slide="next">
            <span class="text-dark">
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z" />
              </svg>
            </span>
          </button>
        </div>

      </section>
    <?php endforeach; ?>
  </div>
</section>
