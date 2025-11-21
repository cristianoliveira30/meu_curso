<?php
// Exibe mensagens de sucesso ou erro (se existirem)
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

// Limpa as mensagens após exibir
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['errors'], $_SESSION['old']);
?>

<div class="container my-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-4 text-dark">Cadastrar Novo Produto</h2>

            <!-- Mensagens de feedback -->
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/produto/adicionar" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Título</label>
                        <input type="text" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['title'] ?? '') ?>">
                        <?php if (isset($errors['title'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">URL</label>
                        <input type="text" name="slug" class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['slug'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Categoria</label>
                        <select name="category" class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>">
                            <option value="" disabled>Selecione uma categoria</option>
                            <option value="religious" <?= (isset($old['category']) && $old['category'] === 'religious') ? 'selected' : '' ?>>Religioso</option>
                            <option value="common" <?= (isset($old['category']) && $old['category'] === 'common') ? 'selected' : '' ?>>Comum</option>
                            <option value="clothing" <?= (isset($old['category']) && $old['category'] === 'clothing') ? 'selected' : '' ?>>Vestuário</option>
                        </select>
                        <?php if (isset($errors['category'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['category']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fornecedor</label>
                        <input type="text" name="supplier" class="form-control"
                            value="<?= htmlspecialchars($old['supplier'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Descrição Curta</label>
                        <input type="text" require name="short_description" class="form-control <?= isset($errors['shortDescription']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['short-description'] ?? '') ?>">
                        <?php if (isset($errors['shortDescription'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['shortDescription']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Descrição Completa</label>
                        <textarea name="description" required rows="4" class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Preço (R$)</label>
                        <input type="number" step="0.01" name="price" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['price'] ?? '') ?>">
                        <?php if (isset($errors['price'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['price']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Estoque</label>
                        <input type="number" name="stock" class="form-control <?= isset($errors['stock']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['stock'] ?? '') ?>">
                        <?php if (isset($errors['stock'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['stock']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Avaliação (0 a 5)</label>
                        <input type="number" name="rating" step="0.1" min="0" max="5" class="form-control"
                            value="<?= htmlspecialchars($old['rating'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Imagem (URL ou nome do arquivo)</label>
                        <input type="text" name="image" class="form-control"
                            value="<?= htmlspecialchars($old['image'] ?? '') ?>">
                    </div>

                    <div class="col-12 d-flex justify-content-between mt-4">
                        <a href="/" class="btn btn-outline-secondary">Voltar</a>
                        <button type="submit" class="btn btn-dark px-4">Cadastrar Produto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>