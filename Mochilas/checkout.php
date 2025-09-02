<?php
session_start();

require_once 'dompdf/autoload.inc.php';
include 'conexao.php';

date_default_timezone_set('America/Sao_Paulo');

use Dompdf\Dompdf;
use Dompdf\Options;

// Verificação
if (!isset($_SESSION['user_id']) || empty($_SESSION['carrinho'])) {
    exit('Usuário não logado ou carrinho vazio.');
}

$ids = array_keys($_SESSION['carrinho']);

// Buscar produtos no banco
$sql = "SELECT * FROM tblproduto WHERE id_produto IN (" . implode(',', array_map('intval', $ids)) . ")";
$stmt = $conn->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inserir pedido
$id_cliente = $_SESSION['user_id'];
$id_funcionario = 1;
$id_tipo_pedido = 1;

$stmtPedido = $conn->prepare("INSERT INTO tblpedido (id_cliente, id_funcionario, id_tipo_pedido) VALUES (?, ?, ?)");
$stmtPedido->execute([$id_cliente, $id_funcionario, $id_tipo_pedido]);
$id_pedido = $conn->lastInsertId();

// Prepara os inserts 
$stmtProdutoPedido = $conn->prepare("INSERT INTO tblproduto_pedido (id_produto, id_pedido, quantidade, valor_unitario) VALUES (?, ?, ?, ?)");
$stmtAtualizaEstoque = $conn->prepare("UPDATE tblproduto SET quantidade_estoque = quantidade_estoque - ? WHERE id_produto = ?");

foreach ($produtos as $produto) {
    $id_produto = $produto['id_produto'];
    $quantidade = $_SESSION['carrinho'][$id_produto] ?? 0;

    if ($quantidade < 20) {
        // Cancela o processo e também redireciona com uma mensagem de erro
        $_SESSION['erro_checkout'] = "Não é permitido gerar orçamento com itens abaixo de 20 unidades.";
        header('Location: ver_carrinho.php');
        exit;
    }
    if (headers_sent()) {
    die("Erro: headers já foram enviados.");
}
}

// Limpa o carrinho
$carrinho = $_SESSION['carrinho'];

unset($_SESSION['carrinho']);

// Gerar o PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$html = "
<style>
    body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
    h1, h2 { text-align: center; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background-color: #f2f2f2; font-weight: bold; text-align: left; padding: 8px; border: 1px solid #ddd; }
    td { padding: 16px; border: 1px solid #ddd; }
    .total { background-color: #f9f9f9; font-weight: bold; }
</style>

<img src='http://localhost/Site%20Principal%20-%20Editado%20Vinicius/imgs/Brindouazul.png' alt='Logo' style='width: 100px; height: auto; display: block; margin: 0 auto;'>

<table>
    <tr><td colspan='5' style='text-align: center;'><h1>Orçamento</h1></td></tr>
    <tr>
        <td>ID: " . htmlspecialchars($_SESSION['user_id']) . "</td>
        <td>Nome empresa: " . htmlspecialchars($_SESSION['name_user']) . "</td>
        <td>Telefone: " . htmlspecialchars($_SESSION['telefone']) . "</td>
        <td>Data de emissão: " . date('d/m/Y H:i:s') . "</td>
        <td>Validade: " . date('d/m/Y H:i:s', strtotime('+60 days')) . "</td>
    </tr>
    <tr>
        <td colspan='4'>CNPJ: " . htmlspecialchars($_SESSION['cnpj']) . "</td>
        <td>Transportadora: </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>";

$totalGeral = 0;
foreach ($produtos as $produto) {
   $qtd = $carrinho[$produto['id_produto']] ?? 0;
    $total = $qtd * $produto['preco'];
    $totalGeral += $total;

    $html .= "
        <tr>
            <td>" . htmlspecialchars($produto['descricao_resumida']) . "</td>
            <td>$qtd</td>
            <td>R$ " . number_format($produto['preco'], 2, ',', '.') . "</td>
            <td>R$ " . number_format($total, 2, ',', '.') . "</td>
        </tr>";
}

$html .= "
        <tr class='total'>
            <td colspan='3'>Total:</td>
            <td>R$ " . number_format($totalGeral, 2, ',', '.') . "</td>
        </tr>
    </tbody>
</table>

<p style='margin-top: 20px; text-align: center;'>* Este orçamento é válido por 60 dias a partir da data de emissão.</p>
<p style='margin-top: 20px; text-align: center;'>* O pagamento deve ser realizado antes da entrega.</p>

<div style='text-align: center; margin-top: 70px;'>
    <span style='text-decoration: overline; margin-left: 40px;'>Data de saída</span>
    <span style='text-decoration: overline; margin-left: 40px;'>Depósito</span>
    <span style='text-decoration: overline; margin-left: 40px;'>Data de entrega</span>
    <div style='margin-left: 180px; border-top: 2px solid black; width: 200px; margin-top: 80px;'>
        <span style='margin-left: 12px;'>Assinatura do cliente</span>
    </div>
</div>";

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("orcamento.pdf", ["Attachment" => false]);
exit;
?>