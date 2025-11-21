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
