<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$id_produto = $_POST['id_produto'] ?? null;
$quantidade = isset($_POST['quantidade_remover']) ? (int)$_POST['quantidade_remover'] : null;
$acao = $_POST['acao'] ?? '';

/* =========== TRATAMENTO PARA O "REMOVER TUDO" ============ */
if ($acao === 'remover_tudo' && $id_produto) {
    if (isset($_SESSION['carrinho'][$id_produto])) {
        unset($_SESSION['carrinho'][$id_produto]);
    }

    // Se o carrinho ficar vazio, limpa a sessão
    if (empty($_SESSION['carrinho'])) {
        unset($_SESSION['carrinho']);
    }

    header("Location: ver_carrinho.php");
    exit;
}

/* =========== AÇÕES EXISTENTES: ADICIONAR / REMOVER ============ */
if (!$id_produto || $quantidade === null || $quantidade <= 0) {
    header("Location: produtos.php");
    exit;
}

// Adicionar produto ao carrinho
if ($acao === 'adicionar') {
    if (isset($_SESSION['carrinho'][$id_produto])) {
        $_SESSION['carrinho'][$id_produto] += $quantidade;
    } else {
        $_SESSION['carrinho'][$id_produto] = $quantidade;
    }
}

// Remover quantidade do carrinho
elseif ($acao === 'remover') {
    if (isset($_SESSION['carrinho'][$id_produto])) {
        $_SESSION['carrinho'][$id_produto] -= $quantidade;

        if ($_SESSION['carrinho'][$id_produto] <= 0) {
            unset($_SESSION['carrinho'][$id_produto]);
        }
    }
}

// Se o carrinho ficar vazio, limpa a sessão
if (empty($_SESSION['carrinho'])) {
    unset($_SESSION['carrinho']);
}

// Redireciona de volta ao carrinho
header("Location: ver_carrinho.php");
exit;
?>
