<?php
/** @var string      $title */
/** @var array       $items */
/** @var float       $total */
/** @var int         $totalItems */
/** @var float       $frete */
/** @var float       $totalComFrete */
/** @var array|null  $endereco */
/** @var string|null $cep */
/** @var string|null $numero */
/** @var string      $paymentMethod */
/** @var string|null $cardLastDigits */

function traduzFormaPagamento(string $method): string {
    switch ($method) {
        case 'pix':    return 'Pix';
        case 'cartao': return 'Cartão de crédito';
        case 'boleto': return 'Boleto bancário';
        default:       return ucfirst($method);
    }
}
?>

<section class="container my-5">
    <h1 class="fw-bold mb-4"><?= htmlspecialchars($title) ?></h1>

    <div class="alert alert-success">
        Pedido confirmado com sucesso! ✅
    </div>

    <h2 class="h5 mt-4">Resumo do pedido</h2>

    <div class="table-responsive mb-3">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th class="text-center">Qtd</th>
                    <th class="text-end">Preço unitário</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $product = $item['product']; ?>
                    <tr>
                        <td><?= htmlspecialchars($product->getTitle()) ?></td>
                        <td class="text-center"><?= (int) $item['quantity'] ?></td>
                        <td class="text-end">
                            R$<?= number_format((float) $product->getPrice(), 2, ',', '.') ?>
                        </td>
                        <td class="text-end">
                            R$<?= number_format($item['subtotal'], 2, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="mb-1">Itens: <?= (int) $totalItems ?></p>
    <p class="mb-1">Total produtos: R$<?= number_format($total, 2, ',', '.') ?></p>
    <p class="mb-1">Frete: R$<?= number_format($frete, 2, ',', '.') ?></p>
    <p class="fs-5 fw-bold">Total a pagar: R$<?= number_format($totalComFrete, 2, ',', '.') ?></p>

    <h2 class="h5 mt-4">Entrega</h2>
    <?php if ($endereco): ?>
        <p class="mb-1">
            <?= htmlspecialchars($endereco['logradouro'] ?? '') ?>
            <?php if (!empty($numero)): ?>
                , Nº <?= htmlspecialchars($numero) ?>
            <?php endif; ?>
            <?= !empty($endereco['bairro']) ? ' - ' . htmlspecialchars($endereco['bairro']) : '' ?>
        </p>
        <p class="mb-1">
            <?= htmlspecialchars($endereco['localidade'] ?? '') ?>
            <?= isset($endereco['uf']) ? ' - ' . htmlspecialchars($endereco['uf']) : '' ?>
        </p>
        <?php if ($cep): ?>
            <p class="mb-1">CEP: <?= htmlspecialchars($cep) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p class="mb-1 text-muted">Endereço não informado.</p>
    <?php endif; ?>

    <h2 class="h5 mt-4">Forma de pagamento</h2>
    <p class="mb-2">
        <?= htmlspecialchars(traduzFormaPagamento($paymentMethod)) ?>
        <?php if ($paymentMethod === 'cartao' && !empty($cardLastDigits)): ?>
            (final <?= htmlspecialchars($cardLastDigits) ?>)
        <?php endif; ?>
    </p>

    <?php if ($paymentMethod === 'pix'): ?>
        <p class="text-muted mb-3">
            Você escolheu pagar com <strong>Pix</strong>.  
            Enviaremos as instruções e o QR Code no próximo passo / por e-mail (exemplo).
        </p>
    <?php elseif ($paymentMethod === 'boleto'): ?>
        <p class="text-muted mb-3">
            Você escolheu pagar com <strong>boleto bancário</strong>.  
            O boleto será disponibilizado para impressão / envio por e-mail (exemplo).
        </p>
    <?php elseif ($paymentMethod === 'cartao'): ?>
        <p class="text-muted mb-3">
            Pagamento no <strong>cartão de crédito</strong> processado com sucesso.
        </p>
    <?php endif; ?>

    <a href="/" class="btn btn-primary">Voltar para a loja</a>
</section>
