<?php
ob_start();
?>
<form>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">Contato</div>
            <div class="card-body">
                <p class="text-muted">Envie sua mensagem para nós!</p>
                <button class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
