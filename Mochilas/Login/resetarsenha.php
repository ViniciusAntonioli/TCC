<?php
include "../conexao.php";

$token = $_GET['token'] ?? '';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novaSenha = htmlspecialchars(trim($_POST['nova_senha']));
    $confirmSenha = htmlspecialchars(trim($_POST['confirma_senha']));
    $token = htmlspecialchars(trim($_POST['token']));

    if ($novaSenha === $confirmSenha) {
        $sql = "SELECT email FROM password_resets WHERE token = :token AND expires_at > GETUTCDATE()";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       // echo "DEBUG - Token: $token <br>";
       // echo $stmt->rowCount();
        if ($row) {
            $email = $row['email'];
            //echo "DEBUG - Token 2: $token <br>";

            $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

            $sqlUpdate = "UPDATE tblcliente SET senha = :senha WHERE email = :email";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':senha' => $senhaCriptografada,
                ':email' => $email
            ]);

            // Remove o token
            $conn->prepare("DELETE FROM password_resets WHERE token = :token")
                 ->execute([':token' => $token]);

            $msg = "Senha redefinida com sucesso!";
            sleep(5);
            header('Location: login.php');
            exit;

        } else {
            $msg = "Token inválido ou expirado.";
        }
    } else {
        $msg = "As senhas não coincidem.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha - Brindou.com</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #00111f;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;          
        flex-direction: column;       
        overflow-x: hidden;
    }

    form {
        width: 400px;
        background-color: #ffffff;
        padding: 2em;
        border-radius: 5px;
        box-sizing: border-box;
        margin-top: 2rem;
    }

    input[type="password"] {
        width: 95%;
        height: 40px;
        border-radius: 5px;
        border: 1px solid #cccccc;
        margin-bottom: 1em;
        padding-left: 1em;
    }

    input[type="submit"] {
        width: 100%;
        height: 40px;
        background-color: #00111f;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        font-size: 1.2em;
        cursor: pointer;

    }

    p {
        color: #ffffff
    }

    a {
        color: #ffffff
    }
</style>
<body>
<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    Nova senha:<br> <input type="password" name="nova_senha" required><br><br>
    Confirmar senha:<br> <input type="password" name="confirma_senha" required><br><br>
    <input type="submit" value="Redefinir senha">
</form>

<br/> <br/>

<div class="voltar">
    <p>Voltar para a <a href='login.php'>Página Inicial</a></p>
</div>    

<p align="center"><?= htmlspecialchars($msg) ?></p>
</body>
</html>
