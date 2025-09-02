<?php
include "../conexao.php";
session_start();

if (isset($_SESSION['user_id'])) {
    session_destroy();
    header('Location: ../produtos.php');
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);

    if (!empty($user) && !empty($pass)) {
        // Chama a procedure
        $sql = "EXEC sp_login_cliente @nome_empresa = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($pass, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id_cliente'];
            $_SESSION['name_user'] = $usuario['nome_empresa'];
            $_SESSION['cnpj'] = $usuario['cnpj'];
            $_SESSION['telefone'] = $usuario['telefone'];

            header('Location: ../../index.php');
            exit();
        } else {
            $error = 'Usuário ou senha incorretos.';
        }
    } else {
        $error = 'Preencha todos os campos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login - Brindou.com</title>
</head>
<body>
    <div class="main">
        <div class="container">

            <h1>Login</h1>
            <form method="post" action="login.php">
                <label>Usuário: </label>
                <input type="text" name="user" required /> <br /><br /> 
                <label> Senha: </label>
                <input type="password" name="pass" required /> <br /><br />

                <div class="recuperar-senha">
                <p>Esqueceu a senha? <a href="solicitar_recuperacao.php">Recuperar Senha</a></p>
                </div>

                <?php if (!empty($error)): ?>
                    <p id="wrong" style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <input style="
                width: 100%;
                height: 40px;
                background-color: #00111f;
                color: #ffffff;
                border: none;
                border-radius: 5px;
                font-size: 1.2em;
                cursor: pointer;" type="submit" value="Entrar" /> 
    
    <div class="sem-conta">
    <p>Não tem uma conta? <a href="contactus.php">Contate-nos</a></p>
    </div>
    
    </form>

    <br /><br /><br />

            <div class="voltar">
                <p class="p-voltar-ini">Voltar para a <a href="../../index.php">Página Inicial</a></p>
            </div>

        </div>

    </div>

</body>
</html>