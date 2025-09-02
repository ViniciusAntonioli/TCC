<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id_produto = intval($_GET['id']);
    $img_num;

    switch (intval($_GET['img'])) {
        case 1:
            $img_num = 'imagem_1';
            break;
        case 2:
            $img_num = 'imagem_2';
            break;
        case 3:
            $img_num = 'imagem_3';
            break;
        case 4:
            $img_num = 'imagem_4';
            break;
        case 5:
            $img_num = 'imagem_5';
            break;
        default:
            $img_num = 'imagem_1'; // Padrão para imagem 1
    }
    
    $sql = "SELECT $img_num FROM tblproduto WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && !empty($produto[$img_num])) {
        // Garantir o tipo correto para a resposta
        header("Content-Type: image/jpeg"); // Define o tipo da imagem (pode ser alterado se for outro formato também)
        echo $produto[$img_num]; // Exibe a imagem
    } else {
        // Caso não tenha imagem, exibe uma imagem padrão :)
        header("Content-Type: image/jpeg");
        readfile("imgs/image.png"); // Imagem padrão
    }
} else {
    header("Content-Type: image/jpeg");
    readfile("imgs/image.png"); // Imagem padrão

}
?>