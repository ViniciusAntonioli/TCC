<?php
include "../conexao.php";
require __DIR__.'/phpmailer/src/PHPMailer.php';
require __DIR__.'/phpmailer/src/SMTP.php';
require __DIR__.'/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagem = '';

date_default_timezone_set('UTC');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));

    $stmt = $conn->prepare("SELECT * FROM tblcliente WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expira = new DateTime('+1 hour');

        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expira)");
            $stmt->execute([
        ':email' => $email,
        ':token' => $token,
        ':expira' => $expira->format('Ymd H:i:s')
    ]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aplicacao715@gmail.com';
            $mail->Password   = 'kxbm kibr yxaa dpxk'; // senha de app (não é a senha do gmail)
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('aplicacao715@gmail.com', 'Brindou.com');
            $mail->addAddress($email);

            $link = "http://localhost/Site%20Principal%20-%20Editado%20Vinicius/Mochilas/login/resetarsenha.php?token=$token";

            $mail->addEmbeddedImage(__DIR__ . '/logo.png', 'logo_brindou');

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar Senha';
            $mail->Body    = "
                <!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8' />
  <title>Ganhe Recompensas em Dinheiro!</title>
</head>
<body style='margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center;'>

  <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>

    <table role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin: auto; text-align: center;'>
    <tr>
        <td>
            <img src='cid:logo_brindou' alt='Brindou.com' style='width: 60%; display: block; margin: auto;'>
        </td>
    </tr>
</table>

<br/> <br/>


    <div style='padding: 20px; color: #1e1e1e;'>

      <!-- Seção 1 -->
      <div style='margin-bottom: 30px;'>
        <div style='font-size: 22px; font-weight: 800; margin-bottom: 15px; text-align: center;'>Redefina sua senha</div>
        <div style='font-size: 16px; line-height: 1.6; margin-bottom: 20px; text-align: center;'>
          Recebemos uma solicitação para redefinir a senha da sua conta.<br><br>
          Clique no botão abaixo para criar uma nova senha:<br>
        </div>
        <div style='text-align: center;'>
          <a href='$link' target='_blank' style='display: inline-block; padding: 14px 28px; background-color: #014060; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 24px;'>Alterar Senha</a>
        </div>
      </div>

      <br>

      <!-- Seção 2 -->
      <div style='margin-bottom: 30px;'>
        <div style='font-size: 16px; line-height: 1.6; margin-bottom: 20px; text-align: center;'>
          Se você não fez essa solicitação, ignore esse e-mail.
        </div>
      </div>

    </div>

    <div style='font-size: 12px; text-align: center; background-color: #1e1e1e; color: #888888; padding: 15px;'>
      Você está recebendo este e-mail porque se cadastrou em nossa plataforma.<br>
    </div>

  </div>

</body>
</html>

            ";
            $mail->send();
            $mensagem = "Um link de recuperação foi enviado para seu e-mail.";
        } catch (Exception $e) {
            $mensagem = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    } else {
        $mensagem = "E-mail não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Brindou.com</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #00111f;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background-color: #fff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        max-width: 500px;
        width: 100%;
        text-align: center;
    }

    h2 {
        font-size: 2em;
        margin-bottom: 20px;
    }

    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin: 1em 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    input[type="submit"] {
        width: 100%;
        background-color: #00111f;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-size: 1.2em;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    p {
        margin-top: 20px;
    }

    a {
        color: #014060;
    }

    a:hover {
        opacity: 0.6;
    }

    .mensagem {
        margin-top: 20px;
        font-weight: bold;
        color: #333;
    }
</style>

<body>
    <div class="container">
        <h2>Recuperar Senha</h2>
        <form method="post">
            <label for="email">Digite seu e-mail:</label><br />
            <input type="email" name="email" id="email" required><br />
            <input type="submit" value="Recuperar senha"><br />
            <p>Voltar para a <a href="../../index.php">Página Inicial</a></p>
        </form>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>
    </div>
</body>
