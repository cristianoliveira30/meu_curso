<!-- app/Views/products/components/cart-modal.php -->

<div id="cartModal" class="manual-modal cart-modal" aria-hidden="true">
  <!-- Backdrop -->
  <div class="manual-modal-backdrop"></div>

  <!-- Conteúdo do modal -->
  <div class="cart-modal-content">
    <div class="cart-modal-header">
      <h5>Produto adicionado ao carrinho 🛒</h5>

      <!-- Botão de fechar -->
      <button type="button" class="cart-modal-close" data-modal-close>
        &times;
      </button>
    </div>

    <div class="cart-modal-body">
      <img src="" alt="" class="cart-product-image">

      <div class="cart-product-info">
        <h6 class="cart-product-title"></h6>
        <p class="cart-product-price"></p>
      </div>
    </div>

    <div class="cart-modal-footer">
      <a href="/carrinho" class="btn btn-primary">
        Ir para o carrinho
      </a>

      <button type="button" class="btn btn-outline-secondary" data-modal-close>
        Continuar comprando
      </button>
    </div>
  </div>
</div>
