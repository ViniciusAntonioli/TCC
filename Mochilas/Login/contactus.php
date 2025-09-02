<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/contactus_error.log');
error_log("DEBUG: Script contactus.php iniciado em " . date('Y-m-d H:i:s'));

include '../conexao.php';

$errors = [];
$success_message = '';
$pdf_path = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    error_log("DEBUG: Requisição POST recebida.");

    // --- UPLOAD via caminho em disco ---
    if (isset($_FILES['comprovante_pdf']) && $_FILES['comprovante_pdf']['error'] === UPLOAD_ERR_OK) {
        $tmpName   = $_FILES['comprovante_pdf']['tmp_name'];
        $origName  = basename($_FILES['comprovante_pdf']['name']);
        $ext       = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $errors[] = "Somente arquivos PDF são permitidos.";
            error_log("ERRO: extensão inválida: $ext");
        } else {
            $newName = 'contactus_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755)) {
                $errors[] = "Não foi possível criar pasta de uploads.";
                error_log("ERRO: falha ao criar $uploadDir");
            } else {
                $destPath = $uploadDir . $newName;
                if (!move_uploaded_file($tmpName, $destPath)) {
                    $errors[] = "Falha ao mover arquivo para uploads.";
                    error_log("ERRO: move_uploaded_file falhou de $tmpName para $destPath");
                } else {
                    // caminho relativo a salvar no BD
                    $pdf_path = 'uploads/' . $newName;
                }
            }
        }
    } else {
        $errors[] = "Selecione um PDF válido para o comprovante.";
        error_log("ERRO: upload com erro ou nenhum arquivo enviado.");
    }

    // --- SANITIZAÇÃO e VALIDAÇÃO ---
    $nome_empresa  = trim((string)filter_input(INPUT_POST, 'nome_empresa', FILTER_UNSAFE_RAW));
    $razao_social  = trim((string)filter_input(INPUT_POST, 'razao_social', FILTER_UNSAFE_RAW));
    $responsavel   = trim((string)filter_input(INPUT_POST, 'responsavel', FILTER_UNSAFE_RAW));
    $email         = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $cnpj_raw      = (string)filter_input(INPUT_POST, 'cnpj', FILTER_UNSAFE_RAW);
    $cnpj          = preg_replace('/\D/', '', $cnpj_raw);
    $telefone_raw  = (string)filter_input(INPUT_POST, 'telefone', FILTER_UNSAFE_RAW);
    $telefone      = preg_replace('/\D/', '', $telefone_raw);

    if (empty($nome_empresa))  $errors[] = "Nome da empresa é obrigatório.";
    if (empty($razao_social))  $errors[] = "Razão social é obrigatória.";
    if (empty($responsavel))   $errors[] = "Nome do contato é obrigatório.";
    if (empty($email))         $errors[] = "Email é obrigatório.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
                               $errors[] = "Formato de e-mail inválido.";
    if (empty($cnpj))          $errors[] = "CNPJ é obrigatório.";
    elseif (!preg_match('/^\d{14}$/', $cnpj))
                               $errors[] = "CNPJ deve ter 14 dígitos.";
    if (!empty($telefone) && !preg_match('/^\d{10,11}$/', $telefone))
                               $errors[] = "Telefone inválido (10 ou 11 dígitos).";

    // Se tudo OK, insere no BD
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO temp_client
                    (nome_empresa, razao_social, cnpj, contato, email, telefone, comprovante_pdf, Data_envio, status)
                VALUES
                    (:nome_empresa, :razao_social, :cnpj, :responsavel, :email, :telefone, :caminho_pdf, getdate(), 1)";

            if (!isset($conn) || !$conn instanceof PDO) {
                throw new Exception("Conexão não estabelecida.");
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome_empresa',  $nome_empresa);
            $stmt->bindValue(':razao_social',  $razao_social);
            $stmt->bindValue(':cnpj',          $cnpj);
            $stmt->bindValue(':responsavel',   $responsavel);
            $stmt->bindValue(':email',         $email);
            $stmt->bindValue(':telefone',      $telefone);
            $stmt->bindValue(':caminho_pdf',   $pdf_path);

            $stmt->execute();
            error_log("DEBUG: Inserção bem-sucedida, caminho_pdf = $pdf_path");
            $success_message = "Cadastro enviado para análise com sucesso!";
            echo "<script>alert('Cadastro enviado para análise com sucesso!');</script>";
            header("Location: ../../index.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erro no banco de dados: " . $e->getMessage();
            error_log("ERRO PDO: " . $e->getMessage());
        } catch (Exception $e) {
            $errors[] = "Erro interno: " . $e->getMessage();
            error_log("ERRO Geral: " . $e->getMessage());
        }
    } else {
        error_log("DEBUG: Erros de validação: " . implode(" | ", $errors));
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Brindou.com</title>
    <style>
        /* Seu CSS existente */
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #00111f;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            min-height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 3rem;
        }

        .form-container h2 {
            display: block;
            font-size: 2em;
            margin-block-start: 0.67em;
            margin-block-end: 0.67em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            unicode-bidi: isolate;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="password"] { /* Adicionado para compatibilidade com outros campos, se aplicável */
            width: 100%;
            height: 40px;
            border-radius: 5px;
            border: 1px solid #cccccc;
            margin-bottom: 1em;
            padding-left: 1em;
        }

        input[type="file"] {
            padding-top: 8px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
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
            margin-bottom: 1em;
        }

        .error-message {
            color: red;
            font-size: 13px;
            margin-bottom: 15px;
            height: 18px; 
        }

        .php-errors {
            color: red;
            background-color: #ffe0e0;
            border: 1px solid red;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .php-success {
            color: green;
            background-color: #e0ffe0;
            border: 1px solid green;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        a {
            color: #014060;
        }

        a:hover {
            opacity: 0.6;
        }

        .main {
            width: 100%;
            background-color: #00111f;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 1.5rem;
        }

        .voltar {
            padding-bottom: 1rem;
        }

        .voltar a {
            color: #ffffff;
        }



        @media (max-width: 500px) {
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="main">    

    <div class="form-container">
        <h2>Cadastro de Empresa</h2>

        <?php if (!empty($errors)): ?>
            <div class="php-errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="php-success">
                <p><?php echo htmlspecialchars($success_message); ?></p>
            </div>
        <?php endif; ?>

        <form action="contactus.php" method="POST" enctype="multipart/form-data" novalidate>
            <label for="nome_empresa">Nome da Empresa:</label>
            <input type="text" name="nome_empresa" id="nome_empresa" required value="<?php echo isset($_POST['nome_empresa']) ? htmlspecialchars($_POST['nome_empresa']) : ''; ?>">
            <div class="error-message" id="error-nome_empresa"></div>

            <label for="razao_social">Razão Social:</label>
            <input type="text" name="razao_social" id="razao_social" required value="<?php echo isset($_POST['razao_social']) ? htmlspecialchars($_POST['razao_social']) : ''; ?>">
            <div class="error-message" id="error-razao_social"></div>

            <label for="responsavel">Nome do Contato:</label>
            <input type="text" name="responsavel" id="responsavel" required value="<?php echo isset($_POST['responsavel']) ? htmlspecialchars($_POST['responsavel']) : ''; ?>">
            <div class="error-message" id="error-responsavel"></div>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <div class="error-message" id="error-email"></div>

            <label for="cnpj">CNPJ:</label>
            <input type="text" name="cnpj" id="cnpj" maxlength="18" placeholder="00.000.000/0000-00" required value="<?php echo isset($_POST['cnpj']) ? htmlspecialchars($_POST['cnpj']) : ''; ?>">
            <div class="error-message" id="error-cnpj"></div>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" placeholder="(00) 00000-0000" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
            <div class="error-message" id="error-telefone"></div>

            <input type="hidden" name="tipo_usuario" value="empresa">
            <input type="hidden" name="status" value="ativo">

            <label for="comprovante_pdf">Comprovante (PDF):</label>
            <input type="file" name="comprovante_pdf" id="comprovante_pdf" accept="application/pdf" required>
            <div class="error-message" id="error-comprovante_pdf"></div>

            <input type="submit" value="Cadastrar">

            <p style="text-align: center; margin-top: 20px;">Já possui cadastro? <a href="../../index.php">Faça login</a></p>

        </form>

    </div>    

    <br /><br /><br />        


            </div>

    <div class="voltar">     
        <p style="text-align: center; margin-top: 20px; color: #fff;">Voltar para a <a href="../mochilas.php">Página Inicial</a></p>
    </div>
    

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let valid = true;

            // Limpa as mensagens de erro anteriores
            document.querySelectorAll(".error-message").forEach(el => el.textContent = "");

            const nomeEmpresa = document.getElementById("nome_empresa").value.trim();
            const razaoSocial = document.getElementById("razao_social").value.trim();
            const responsavel = document.getElementById("responsavel").value.trim();
            const email = document.getElementById("email").value.trim();

            // *** CRÍTICO: Limpa CNPJ e Telefone removendo todos os caracteres não numéricos ANTES da validação ***
            const cnpjInput = document.getElementById("cnpj");
            const cnpj = cnpjInput.value.replace(/\D/g, ''); // Remove tudo que não for dígito (incluindo a máscara utilizada)

            const telefoneInput = document.getElementById("telefone");
            const telefone = telefoneInput.value.replace(/\D/g, ''); // Remove tudo que não for dígito
            // ***************************************************************************************************

            const comprovantePdf = document.getElementById("comprovante_pdf").files.length;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const cnpjRegex = /^\d{14}$/; 
            
            const telefoneRegex = /^\d{10,11}$/; 

            if (!nomeEmpresa) {
                document.getElementById("error-nome_empresa").textContent = "Nome da empresa é obrigatório.";
                valid = false;
            }

            if (!razaoSocial) {
                document.getElementById("error-razao_social").textContent = "Razão social é obrigatória.";
                valid = false;
            }

            if (!responsavel) {
                document.getElementById("error-responsavel").textContent = "Nome do contato é obrigatório.";
                valid = false;
            }

            if (!email) {
                document.getElementById("error-email").textContent = "Email é obrigatório.";
                valid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById("error-email").textContent = "Formato de e-mail inválido.";
                valid = false;
            }

            // Validação do CNPJ (agora usando a variável 'cnpj' limpa)
            if (!cnpj) {
                document.getElementById("error-cnpj").textContent = "CNPJ é obrigatório.";
                valid = false;
            } else if (!cnpjRegex.test(cnpj)) { // Testa se são EXATAMENTE 14 dígitos
                document.getElementById("error-cnpj").textContent = "CNPJ deve ter 14 dígitos numéricos.";
                valid = false;
            }

            // Validação do Telefone (agora usando a variável 'telefone' limpa)
            // O telefone é opcional, só valida se houver algo digitado
            if (telefone && !telefoneRegex.test(telefone)) {
                document.getElementById("error-telefone").textContent = "Telefone inválido (mínimo 10 ou 11 dígitos numéricos).";
                valid = false;
            }

            // Checa se o arquivo PDF foi selecionado
            if (comprovantePdf === 0) {
                document.getElementById("error-comprovante_pdf").textContent = "Comprovante PDF é obrigatório.";
                valid = false;
            }

            if (!valid) {
                event.preventDefault(); // Impede o envio do formulário se a validação falhar
            }
        });

        // --- Lógica de Mascaramento (UX) para CNPJ e Telefone ---
        document.addEventListener('DOMContentLoaded', (event) => {
            const cnpjInput = document.getElementById('cnpj');
            if (cnpjInput) {
                cnpjInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, ''); // Remove não-dígitos para aplicar a máscara
                    if (value.length > 14) value = value.slice(0, 14); // Limita a 14 dígitos

                    // Aplica a máscara de CNPJ: XX.XXX.XXX/XXXX-XX
                    if (value.length > 12) {
                        value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
                    } else if (value.length > 8) {
                        value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4}).*/, '$1.$2.$3/$4');
                    } else if (value.length > 5) {
                        value = value.replace(/^(\d{2})(\d{3})(\d{3}).*/, '$1.$2.$3');
                    } else if (value.length > 2) {
                        value = value.replace(/^(\d{2})(\d{3}).*/, '$1.$2');
                    } else {
                        value = value.replace(/^(\d{0,2})/, '$1');
                    }
                    e.target.value = value;
                });
            }

            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, ''); // Remove não-dígitos para aplicar a máscara
                    if (value.length > 11) value = value.slice(0, 11); // Limita ao máximo para telefone brasileiro (11 dígitos)

                    // Aplica máscara de telefone: (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
                    if (value.length > 10) { // Celular (com 9º dígito)
                        value = value.replace(/^(\d\d)(\d{5})(\d{4}).*/, '($1) $2-$3');
                    } else if (value.length > 6) { // Fixo ou celular antigo
                        value = value.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                    } else if (value.length > 2) { // Apenas DDD
                        value = value.replace(/^(\d\d)(\d{0,5})/, '($1) $2');
                    } else { // Início
                        value = value.replace(/^(\d*)/, '($1');
                    }
                    e.target.value = value;
                });
            }
        });
    </script>
</body>
</html>