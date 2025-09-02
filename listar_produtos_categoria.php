<?php
if (!defined('MY_APP')) {
    define('MY_APP', true);
}

include 'Mochilas/conexao.php';

// Recebe a categoria
$categoria = isset($categoria) ? trim($categoria) : '';

if (!$categoria) {
    echo "<p>Categoria não definida.</p>";
    return;
}



// Consulta os produtos por categoria
$sql = "SELECT TOP 4 p.id_produto, p.descricao_resumida, p.preco, p.imagem_1
        FROM tblproduto p
        JOIN tblcategoria c ON p.id_categoria = c.id_categoria
        WHERE p.ativo = 1 AND c.nome_categoria = ?
        ORDER BY p.id_produto DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$categoria]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$produtos) {
    echo "Nenhum produto encontrado para esta categoria." . $categoria;
}

foreach ($produtos as $produto):
    $imagem = 'Mochilas/listar_img.php?id=' . $produto['id_produto'] . '&img=1' ?: 'imgs/produtos/sem-imagem.jpg';
?>
    <div class="produto">
        <a href="Mochilas/detalhes_produto.php?id=<?= $produto['id_produto']; ?>"><img src="<?=$imagem  ?>" alt="<?= htmlspecialchars($produto['descricao_resumida']); ?>"></a>
        <p class="nome-produto"><?= htmlspecialchars($produto['descricao_resumida']); ?></p>
        <p class="preco-produto"><span>R$</span> <?= number_format($produto['preco'], 2, ',', '.'); ?></p>
    </div>
<?php endforeach; ?>
