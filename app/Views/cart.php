<?php

/** @var string $title */
/** @var array  $items */
/** @var float  $total */
/** @var int    $totalItems */

use App\Core\Auth;

// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// RAMO 1: USUÁRIO NÃO LOGADO
if (!Auth::check()) {

    if (empty($_SESSION['error'])) {
        $_SESSION['error'] = 'Faça login para acessar seu carrinho.';
    }

?>
    <!-- Estilos puros para a página de "login necessário" -->
    <style>
        .login-required-wrapper {
            padding: 2rem 1rem;
            min-height: 60vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .login-required-card {
            max-width: 480px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 14px;
            padding: 1.75rem 2rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
            text-align: center;
        }

        .login-required-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            background: rgba(15, 23, 42, 0.06);
            color: #111827;
        }

        .login-required-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .login-required-subtitle {
            margin: 0.35rem 0 0;
            font-size: 0.95rem;
            color: #6b7280;
        }

        .login-required-message {
            margin-top: 1.25rem;
            font-size: 0.92rem;
            color: #4b5563;
        }

        .login-required-error {
            margin-top: 1rem;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            text-align: left;
        }

        .login-required-actions {
            margin-top: 1.7rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .lr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.65rem 1.2rem;
            font-size: 0.95rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
        }

        .lr-btn-primary {
            background-color: #111827;
            color: #f9fafb;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        }

        .lr-btn-primary:hover {
            background-color: #020617;
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.45);
        }

        .lr-btn-outline {
            background-color: #ffffff;
            color: #111827;
            border-color: #e5e7eb;
        }

        .lr-btn-outline:hover {
            background-color: #f9fafb;
        }

        @media (max-width: 480px) {
            .login-required-card {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>

    <section class="login-required-wrapper">
        <div class="login-required-card">
            <div class="login-required-icon">
                <!-- cadeado -->
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <h1 class="login-required-title">Login necessário</h1>
            <p class="login-required-subtitle">
                Para visualizar seu carrinho, é preciso entrar na sua conta.
            </p>

            <p class="login-required-message">
                Clique em <strong>“Fazer login”</strong> para se autenticar e continuar a compra
                ou utilize o botão abaixo para voltar para a página inicial.
            </p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="login-required-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="login-required-actions">
                <button type="button" id="openLoginFromCart" class="lr-btn lr-btn-primary">
                    <!-- ícone de login -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Fazer login
                </button>

                <a href="/" class="lr-btn lr-btn-outline">
                    <!-- ícone de início -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 11L12 4L20 11" />
                        <path d="M6 11V20H18V11" />
                        <path d="M10 20V15H14V20" />
                    </svg>
                    Voltar para o início
                </a>
            </div>
        </div>
    </section>

    <script>
        (function() {
            function openLoginModal() {
                var modal = document.getElementById('loginModal');

                if (window.manualModal && typeof window.manualModal.openModal === 'function') {
                    window.manualModal.openModal('loginModal');
                } else if (modal) {
                    // usa flex para centralizar no viewport
                    modal.style.display = 'flex';
                }
            }

            // Abre o modal automaticamente ao carregar a página
            window.addEventListener('load', function() {
                openLoginModal();
            });

            // Botão "Fazer login" reaproveita o mesmo modal
            var btn = document.getElementById('openLoginFromCart');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openLoginModal();
                });
            }
        })();
    </script>

<?php
    // IMPORTANTE: não renderiza o carrinho para quem não está logado
    return;
}

// RAMO 2: USUÁRIO LOGADO – CART NORMAL
?>

<section class="container my-5">
    <h1 class="fw-bold mb-4"><?= htmlspecialchars($title) ?></h1>

    <?php if (empty($items)): ?>
        <p class="text-muted">Seu carrinho está vazio.</p>
        <a href="/" class="btn btn-primary">
            Voltar às compras
        </a>
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

                            <!-- QTD COM EDIÇÃO -->
                            <td class="text-center">
                                <form method="post"
                                    action="/carrinho/atualizar"
                                    class="d-inline-flex align-items-center justify-content-center">
                                    <input type="hidden" name="product_id" value="<?= (int) $product->getId() ?>">

                                    <input
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        value="<?= (int) $quantity ?>"
                                        class="form-control form-control-sm text-center"
                                        style="width: 70px;">

                                </form>
                            </td>

                            <td class="text-end">
                                R$<?= number_format((float) $product->getPrice(), 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                R$<?= number_format($subtotal, 2, ',', '.') ?>
                            </td>

                            <!-- REMOVER ITEM -->
                            <td class="text-end">
                                <form method="post" action="/carrinho/remover" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?= (int) $product->getId() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" title="Remover item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5Z" />
                                            <path d="M3.5 3h9a.5.5 0 0 1 0 1h-.5v8.5A1.5 1.5 0 0 1 10.5 14h-5A1.5 1.5 0 0 1 4 12.5V4H3.5a.5.5 0 0 1 0-1Z" />
                                            <path d="M5.5 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0v-6Z" />
                                        </svg>
                                    </button>

                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- RODAPÉ DO CARRINHO: ESVAZIAR, VOLTAR, FINALIZAR -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <p class="mb-0 text-muted">
                    Itens no carrinho: <?= (int) $totalItems ?>
                </p>

                <!-- BOTÃO ESVAZIAR CARRINHO -->
                <form method="post" action="/carrinho/esvaziar" class="mb-0">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Esvaziar carrinho
                    </button>
                </form>
            </div>

            <div class="text-end">
                <p class="fs-5 fw-bold mb-2">
                    Total: R$<?= number_format($total, 2, ',', '.') ?>
                </p>
                <div class="d-flex justify-content-end gap-2">
                    <!-- VOLTAR ÀS COMPRAS -->
                    <a href="/" class="btn btn-outline-secondary">
                        Voltar às compras
                    </a>

                    <!-- FINALIZAR COMPRA -->
                    <a href="/checkout" class="btn btn-success">
                        Finalizar compra
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>