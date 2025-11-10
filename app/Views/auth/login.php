<div id="loginModal" class="manual-modal" style="display:none;">
  <div class="manual-modal-backdrop" onclick="window.manualModal.closeModal('loginModal')"></div>

  <div class="manual-modal-dialog d-flex justify-content-center" role="document">
    <div class="card shadow-sm rounded-4 w-50">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4 class="mb-0">Entrar na conta</h4>
              <button type="button" class="btn-close" aria-label="Fechar" onclick="window.manualModal.closeModal('loginModal')"></button>
            </div>
        
            <?php if (isset($_SESSION['error'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
              <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        
            <form action="/login" method="POST">
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
              </div>
        
              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
              </div>
        
              <div class="d-grid">
                <button type="submit" class="btn btn-dark rounded-pill py-2">Entrar</button>
              </div>
        
              <p class="text-center mt-3 mb-0">
                Não tem uma conta? 
                <a href="#" class="text-decoration-none" onclick="window.manualModal.switchModal('loginModal', 'cadastroModal')">Cadastre-se</a>
              </p>
            </form>
        </div>
    </div>
  </div>
</div>
