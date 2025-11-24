// modal.js — versão universal
(function () {
  function openModal(modal) {
    if (!modal) return;
    modal.classList.add("show");
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("manual-modal-open");
    document.body.style.overflow = "hidden";

    modal._trigger = document.activeElement;

    const first = modal.querySelector(
      'input, button, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    if (first) first.focus();

    modal.addEventListener("keydown", trapFocus);
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove("show");
    modal.style.display = "none";
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("manual-modal-open");
    document.body.style.overflow = "";

    modal.removeEventListener("keydown", trapFocus);
    if (modal._trigger) modal._trigger.focus();
  }

  function trapFocus(e) {
    if (e.key !== "Tab") return;
    const modal = e.currentTarget;
    const focusables = modal.querySelectorAll(
      'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
    );
    if (!focusables.length) return;
    const first = focusables[0];
    const last = focusables[focusables.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  // 🔹 Handler global
  document.addEventListener("click", function (e) {
    // ✅ Universal: qualquer elemento com data-modal-target
    const target = e.target.closest("[data-modal-target]");
    if (target) {
      e.preventDefault();
      const id = target.getAttribute("data-modal-target");
      const modal = document.getElementById(id);
      openModal(modal);
      return;
    }

    // Compatibilidade antiga (login/cadastro)
    const openCadastro = e.target.closest("#openCadastroBtn");
    const openLogin = e.target.closest("#openLoginBtn");

    if (openCadastro) {
      e.preventDefault();
      openModal(document.getElementById("cadastroModal"));
      return;
    }

    if (openLogin) {
      e.preventDefault();
      openModal(document.getElementById("loginModal"));
      return;
    }

    // Fechar manualmente
    if (e.target.closest("[data-modal-close]")) {
      const modal = e.target.closest(".manual-modal");
      closeModal(modal);
      return;
    }

    // Fechar ao clicar fora (backdrop)
    if (
      e.target.classList &&
      e.target.classList.contains("manual-modal-backdrop")
    ) {
      const modal = e.target.closest(".manual-modal");
      closeModal(modal);
      return;
    }
  });

  // Fechar com ESC
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      const modal = document.querySelector(".manual-modal.show");
      if (modal) closeModal(modal);
    }
  });

  // 🔹 Acesso global manual
  window.manualModal = {
    openModal(id) {
      const modal = document.getElementById(id);
      openModal(modal);
    },
    closeModal(id) {
      const modal = document.getElementById(id);
      closeModal(modal);
    },
    switchModal(fromId, toId) {
      this.closeModal(fromId);
      this.openModal(toId);
    },
  };
})();

// 🔹 Comportamento específico do modal de carrinho
document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("cartModal");
  if (!modal) return;

  var titleEl = modal.querySelector(".cart-product-title");
  var priceEl = modal.querySelector(".cart-product-price");
  var imageEl = modal.querySelector(".cart-product-image");

  // Botões de "adicionar ao carrinho"
  var buttons = document.querySelectorAll(".js-open-cart-modal");
  buttons.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();

      var productId = btn.getAttribute("data-id");
      var title     = btn.getAttribute("data-title");
      var price     = btn.getAttribute("data-price");
      var image     = btn.getAttribute("data-image");

      if (!productId) {
        console.error("Botão de carrinho sem data-id");
        return;
      }

      // 1) Preenche o modal de confirmação
      titleEl.textContent = title || "";
      priceEl.textContent = price ? "R$ " + price : "";
      imageEl.src = image || "";
      imageEl.alt = title || "";

      // 2) Se estiver dentro de outro modal (ex: productModal-X), fecha ele
      var parentManualModal = btn.closest(".manual-modal");
      if (
        parentManualModal &&
        parentManualModal.id &&
        parentManualModal.id !== "cartModal" &&
        window.manualModal
      ) {
        window.manualModal.closeModal(parentManualModal.id);
      }

      // 3) Envia pro backend (PHP) adicionar na sessão do carrinho
      //    Rota: POST /carrinho/adicionar
      var formData = new FormData();
      formData.append("product_id", productId);
      formData.append("quantity", 1);

      fetch("/carrinho/adicionar", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then(function (response) {
          if (!response.ok) {
            throw new Error("Erro ao adicionar ao carrinho");
          }
          // Se seu controller retornar JSON, dá pra usar:
          return response.json().catch(function () {
            return null;
          });
        })
        .then(function (data) {
          // Aqui você pode atualizar um badge de carrinho, se quiser
          // if (data && typeof data.totalItems !== "undefined") {
          //   var badge = document.getElementById("cartCount");
          //   if (badge) badge.textContent = data.totalItems;
          // }
        })
        .catch(function (error) {
          console.error(error);
        });

      // 4) Abre o modal "Produto adicionado"
      if (window.manualModal) {
        window.manualModal.openModal("cartModal");
      } else {
        modal.classList.add("show");
        modal.style.display = "flex";
      }
    });
  });
});
