<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    exit("Usuário não logado.");
}

$id_cliente = $_SESSION['user_id'];

$sqlPedidos = "
    SELECT p.id_pedido, p.id_funcionario, p.id_tipo_pedido, t.nome_tipo, p.id_cliente, p.status_pedido
    FROM tblpedido p
    JOIN tbltipo_pedido t ON p.id_tipo_pedido = t.id_tipo_pedido
    WHERE p.id_cliente = ?
    ORDER BY p.id_pedido DESC
";
$stmt = $conn->prepare($sqlPedidos);
$stmt->execute([$id_cliente]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function corStatus($status) {
    switch (strtolower($status)) {
        case 'em andamento': return 'linear-gradient(135deg, #3498db, #2980b9)';
        case 'finalizado': return 'linear-gradient(135deg, #2ecc71, #27ae60)';
        case 'cancelado': return 'linear-gradient(135deg, #e74c3c, #c0392b)';
        default: return 'linear-gradient(135deg, #95a5a6, #7f8c8d)';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - Brindou.com</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f3f6;
            padding: 40px 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: #000000;
        }

        .links {
            text-align: center;
            margin-bottom: 40px;
        }

        .links a {
            color: #014060;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .links a:hover {
            color: #00111f;
        }

        .pedido {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: 0.3s ease;
        }

        .pedido:hover {
            transform: translateY(-3px);
        }

        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .pedido-header h2 {
            font-size: 20px;
            color: #34495e;
        }

        .status {
            font-weight: bold;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            background: gray;
            background-image: var(--status-bg);
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #555;
        }

        .total {
            background-color: #f0f6ff;
            font-weight: bold;
        }

        .linha-subtitulo{
            width: 100px;
            height: 5px;
            background: #014060;
            border-radius: 9999px;
            margin: 8px auto 0 auto;
}

        @media (max-width: 600px) {
            .pedido-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .pedido-header h2 {
                margin-bottom: 10px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            td {
                padding: 10px 0;
                border: none;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                top: 0;
                padding-left: 15px;
                font-weight: bold;
                color: #555;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Meus Pedidos</h1>
    <div class="linha-subtitulo"></div>

    <br> <br>
    <div class="links">
        <a href="../index.php">🏠 Início</a>
        <a href="ver_carrinho.php">🛒 Carrinho</a>
    </div>

    <?php if (count($pedidos) > 0): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="pedido">
                <div class="pedido-header">
                    <h2>Pedido #<?= $pedido['id_pedido'] ?> - <?= htmlspecialchars($pedido['nome_tipo']) ?></h2>
                    <span class="status" style="--status-bg: <?= corStatus($pedido['status_pedido']) ?>;">
                        <?= ucfirst($pedido['status_pedido']) ?>
                    </span>
                </div>

                <table>
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlItens = "
                        SELECT pp.quantidade, pp.valor_unitario, pr.descricao_resumida
                        FROM tblproduto_pedido pp
                        JOIN tblproduto pr ON pp.id_produto = pr.id_produto
                        WHERE pp.id_pedido = ?
                    ";
                    $stmtItens = $conn->prepare($sqlItens);
                    $stmtItens->execute([$pedido['id_pedido']]);
                    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

                    $totalPedido = 0;

                    foreach ($itens as $item):
                        $subtotal = $item['quantidade'] * $item['valor_unitario'];
                        $totalPedido += $subtotal;
                        ?>
                        <tr>
                            <td data-label="Produto"><?= htmlspecialchars($item['descricao_resumida']) ?></td>
                            <td data-label="Quantidade"><?= $item['quantidade'] ?></td>
                            <td data-label="Preço Unitário">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td data-label="Total">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td colspan="3">Total do Pedido</td>
                        <td>R$ <?= number_format($totalPedido, 2, ',', '.') ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; font-size:18px; margin-top:40px;">Você ainda não fez nenhum pedido.</p>
    <?php endif; ?>
</div>
</body>