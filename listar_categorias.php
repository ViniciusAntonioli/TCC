<?php
if (!defined('MY_APP')) {
    define('MY_APP', true);
}

include 'Mochilas/conexao.php'; 

// Consulta as categorias
$sql = "SELECT id_categoria, nome_categoria FROM tblcategoria ORDER BY nome_categoria ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}

// Exibe as categorias
foreach ($categorias as $categoria):
    $nome = $categoria['nome_categoria'];
    
    // Formata o nome para slug 
    $slug = strtolower(str_replace([' ', '/', '&'], ['_', '', ''], $nome));

    // Caminho da imagem
    $imagem = "imgs/produtos/{$slug}/default.jpg";

    // Link para a categoria
    $link = "{$slug}.php";
    ?>
    <div class="categoria">
        <a href="<?= htmlspecialchars($link); ?>">
            <img src="<?= htmlspecialchars($imagem); ?>" alt="<?= htmlspecialchars($nome); ?>">
            <div class="nome-categoria">
                <p class="txt-categoria"><?= htmlspecialchars($nome); ?></p>
            </div>
        </a>
    </div>
<?php endforeach; ?>
