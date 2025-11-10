<div id="cadastroModal" class="manual-modal" aria-hidden="true" aria-labelledby="cadastroTitle" role="dialog">
  <div class="manual-modal-backdrop" data-modal-close></div>

  <div class="manual-modal-dialog" role="document">
    <div class="card shadow-sm rounded-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h5 id="cadastroTitle" class="modal-title mb-0">Criar Conta</h5>
          <button type="button" class="btn-close" aria-label="Fechar" data-modal-close></button>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="/cadastro" method="POST" id="cadastroForm" novalidate>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">Nome completo</label>
              <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>">
              <?php if (!empty($_SESSION['errors']['name'])): ?>
                <div class="text-danger small"><?= $_SESSION['errors']['name'] ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>">
              <?php if (!empty($_SESSION['errors']['email'])): ?>
                <div class="text-danger small"><?= $_SESSION['errors']['email'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Senha</label>
              <input type="password" name="password" id="password" class="form-control" required>
              <?php if (!empty($_SESSION['errors']['password'])): ?>
                <div class="text-danger small"><?= $_SESSION['errors']['password'] ?></div>
              <?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
              <label for="password_confirm" class="form-label">Confirmar Senha</label>
              <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
              <?php if (!empty($_SESSION['errors']['password_confirm'])): ?>
                <div class="text-danger small"><?= $_SESSION['errors']['password_confirm'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- restante dos campos -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label">Telefone</label>
              <input type="text" name="phone" id="phone" class="form-control" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($_SESSION['old']['phone'] ?? '') ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="address" class="form-label">Endereço</label>
              <input type="text" name="address" id="address" class="form-control" value="<?= htmlspecialchars($_SESSION['old']['address'] ?? '') ?>">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="city" class="form-label">Cidade</label>
              <input type="text" name="city" id="city" class="form-control" value="<?= htmlspecialchars($_SESSION['old']['city'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
              <label for="state" class="form-label">Estado</label>
              <input type="text" name="state" id="state" class="form-control" value="<?= htmlspecialchars($_SESSION['old']['state'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
              <label for="zip_code" class="form-label">CEP</label>
              <input type="text" name="zip_code" id="zip_code" class="form-control" value="<?= htmlspecialchars($_SESSION['old']['zip_code'] ?? '') ?>">
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-dark rounded-pill py-2">Cadastrar</button>
          </div>
        </form>

        <p class="text-center mt-3 mb-0">
          Já tem uma conta? <a href="#" onclick="window.manualModal.switchModal('cadastroModal', 'loginModal')" class="text-decoration-none">Entrar</a>
        </p>
      </div>
    </div>
  </div>
</div>