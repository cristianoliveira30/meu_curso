<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Minha Loja' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Seu CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/modal.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- header -->
    <?php include_once __DIR__ . '/header.php'; ?>

    <!-- Conteúdo -->
    <main class="container-fluid p-0" style="min-height: 83vh;">
        <?= $content ?? '' ?>
    </main>

    <!-- footer -->
    <?php include_once __DIR__ . '/footer.php'; ?>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/modal.js"></script>

    <!-- modais -->
    <?php include __DIR__ . '/../auth/cadastro.php'; ?>
    <?php include __DIR__ . '/../auth/login.php'; ?>

    <!-- tratamento de sessao de erros / auto-open modal -->
    <?php
        $hasErrors = !empty($_SESSION['errors']) || !empty($_SESSION['error']);
        $hasLoginError = !empty($_SESSION['login_error']); // string com mensagem de login, ou true flag
        $hasSuccess = !empty($_SESSION['success']);
    ?>
    <?php if ($hasErrors || $hasLoginError): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Decide qual modal abrir: login tem prioridade se existir login_error
                <?php if ($hasLoginError): ?>
                    if (window.manualModal) window.manualModal.openModal('loginModal');
                    <?php if (!empty($_SESSION['login_error'])): ?>
                        alert(<?= json_encode($_SESSION['login_error']) ?>);
                    <?php endif; ?>
                <?php else: ?>
                    if (window.manualModal) window.manualModal.openModal('cadastroModal');
                    <?php if (!empty($_SESSION['errors'])): ?>
                        const errors = <?= json_encode($_SESSION['errors']) ?>;
                        let msg = "Ocorreram erros no formulário:\n\n";
                        for (const [field, text] of Object.entries(errors)) {
                            msg += `${text}\n`;
                        }
                        alert(msg);
                    <?php elseif (!empty($_SESSION['error'])): ?>
                        alert(<?= json_encode($_SESSION['error']) ?>);
                    <?php endif; ?>
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>
    <?php if ($hasSuccess): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert(<?= json_encode($_SESSION['success']) ?>);
            });
        </script>
    <?php endif; ?>
    <?php unset($_SESSION['errors'], $_SESSION['error'], $_SESSION['success'], $_SESSION['old'], $_SESSION['login_error']); ?>
</body>

</html>