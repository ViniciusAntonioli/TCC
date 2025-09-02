<?php
if (!defined('MY_APP')) {
    header('Location: mochilas.php');
    die();
}

include 'conexao.php'; 

$sql = "SELECT p.* FROM tblproduto p WHERE p.ativo = 1";
$condicoes = [];
$params = [];

// Categoria
if (!empty($_GET['categoria']) && is_array($_GET['categoria'])) {
    $placeholders = implode(',', array_fill(0, count($_GET['categoria']), '?'));
    $condicoes[] = "p.id_categoria IN ($placeholders)";
    $params = array_merge($params, $_GET['categoria']);
}

// Cor
if (!empty($_GET['cor']) && is_array($_GET['cor'])) {
    $placeholders = implode(',', array_fill(0, count($_GET['cor']), '?'));
    $condicoes[] = "p.cor IN ($placeholders)";
    $params = array_merge($params, $_GET['cor']);
}

// Material
if (!empty($_GET['material']) && is_array($_GET['material'])) {
    $placeholders = implode(',', array_fill(0, count($_GET['material']), '?'));
    $condicoes[] = "p.id_tipo_material IN ($placeholders)";
    $params = array_merge($params, $_GET['material']);
}

// Texto de busca
if (!empty($_GET['search'])) {
    $condicoes[] = "p.descricao_resumida LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
}

// Filtro por preço
if (isset($_GET['preco_min']) && isset($_GET['preco_max'])) {
    $preco_min = floatval($_GET['preco_min']);
    $preco_max = floatval($_GET['preco_max']);

    if($preco_min < 0 or is_string($preco_min)) {
        $preco_min = 0; // Garante que o valor mínimo não seja negativo
    }

    if($preco_max > 1000 or is_string($preco_max)) {
        $preco_max = 1000; // Garante que o valor mínimo não seja maior que 1000
    }

    // Garante que o valor mínimo seja menor ou igual ao máximo
    if ($preco_min <= $preco_max) {
        $condicoes[] = "p.preco BETWEEN ? AND ?";
        $params[] = $preco_min;
        $params[] = $preco_max;
    }
}

// Concatena condições na SQL
if ($condicoes) {
    $sql .= " AND " . implode(" AND ", $condicoes);
}

$stmt = $conn->prepare($sql);
$params = array_map('strval', $params); // Converte todos os parâmetros para string
$stmt->execute($params);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_produtos = $stmt->rowCount();
?>

<p>Total de produtos encontrados: <?= $total_produtos; ?></p>

<div class="products">
    <?php foreach ($produtos as $produto): ?>
        <a href="detalhes_produto.php?id=<?= $produto['id_produto']; ?>">
        <div class="product">
            <img 
                class="hover-img" 
                src="listar_img.php?id=<?= $produto['id_produto']; ?>&img=1" 
                alt="<?= htmlspecialchars($produto['descricao_resumida']); ?>" 
                data-hover="listar_img.php?id=<?= $produto['id_produto']; ?>&img=2" 
                data-original="listar_img.php?id=<?= $produto['id_produto']; ?>&img=1" 
                id="img-<?= $produto['id_produto']; ?>" 
            >
            <div class="product-info">
                <h1 class="product-title"><?= $produto['descricao_resumida']; ?></h1>
                <p class="product-price">R$<?= number_format($produto['preco'], 2, ',', '.'); ?></p>
            </div>
        </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imagens = document.querySelectorAll('.hover-img');

    imagens.forEach(img => {
        const originalSrc = img.getAttribute('data-original');
        const hoverSrc = img.getAttribute('data-hover');

        img.addEventListener("mouseenter", () => {
            img.src = hoverSrc;
        });

        img.addEventListener("mouseleave", () => {
            img.src = originalSrc;
        });
    });
});
</script>
