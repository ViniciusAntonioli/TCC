<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/htdocs/php-error.log'); 
error_log("DEBUG: Script started at " . date('Y-m-d H:i:s')); 
session_start();

require_once '../conexao.php'; // Verifique se o caminho para o arquivo de conexão está correto

$conteudoArquivo = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Lógica de Upload do Arquivo PDF ---
    if (isset($_FILES['comprovante_pdf']) && $_FILES['comprovante_pdf']['error'] === UPLOAD_ERR_OK) {
        $arquivoTmp = $_FILES['comprovante_pdf']['tmp_name'];
        $nomeArquivo = $_FILES['comprovante_pdf']['name'];

        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        if (strtolower($extensao) !== 'pdf') {
            echo "<script>alert('Somente arquivos PDF são permitidos.');</script>";
            exit;
        }

        $conteudoArquivo = file_get_contents($arquivoTmp);

        if ($conteudoArquivo === false) {
            echo "<script>alert('Erro ao ler o conteúdo do arquivo PDF. Tente novamente.');</script>";
            exit;
        }

    } else {
        if ($_FILES['comprovante_pdf']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "<script>alert('Erro no envio do arquivo PDF: " . $_FILES['comprovante_pdf']['error'] . ". Por favor, tente novamente.');</script>";
            exit;
        }
        echo "<script>alert('Por favor, selecione um arquivo PDF para o comprovante.');</script>";
        exit;
    }

    try {
        $nome_empresa = (string) filter_input(INPUT_POST, 'nome_empresa');
        $razao_social = (string) filter_input(INPUT_POST, 'razao_social');
        $responsavel = (string) filter_input(INPUT_POST, 'responsavel');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); 

        $cnpj_raw = (string) filter_input(INPUT_POST, 'cnpj'); 
        $cnpj = preg_replace('/\D/', '', $cnpj_raw);
        $telefone_raw = (string) filter_input(INPUT_POST, 'telefone'); 
        $telefone = preg_replace('/\D/', '', $telefone_raw); 
        // --- Fim da Sanitização ---

        // --- Validação Server-Side ---
        $errors = [];

        if (empty($nome_empresa)) { $errors[] = "Nome da empresa é obrigatório."; }
        if (empty($razao_social)) { $errors[] = "Razão social é obrigatória."; }
        if (empty($responsavel)) { $errors[] = "Nome do contato é obrigatório."; }

        if (empty($email)) {
            $errors[] = "Email é obrigatório.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Formato de e-mail inválido.";
        }

        if (empty($cnpj)) {
            $errors[] = "CNPJ é obrigatório.";
        } elseif (!preg_match('/^\d{14}$/', $cnpj)) {
            $errors[] = "CNPJ deve ter exatamente 14 dígitos numéricos.";
        }

        if (!empty($telefone) && !preg_match('/^\d{10,}$/', $telefone)) {
            $errors[] = "Telefone inválido (mínimo 10 dígitos numéricos, apenas números).";
        }

        if (!empty($errors)) {
            echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
            exit;
        }

        // --- Preparação e Execução da Inserção no Banco de Dados ---
        $sql = "INSERT INTO temp_client
                (nome_empresa, razao_social, cnpj, contato, email, telefone, comprovante_pdf)
                VALUES
                (:nome_empresa, :razao_social, :cnpj, :responsavel, :email, :telefone, :arquivo)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':nome_empresa', $nome_empresa);
        $stmt->bindValue(':razao_social', $razao_social);
        $stmt->bindValue(':cnpj', $cnpj);
        $stmt->bindValue(':responsavel', $responsavel);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':arquivo', $conteudoArquivo, PDO::PARAM_LOB);

        $stmt->execute();

        echo "<script>alert('O cadastro foi enviado para o administrador para análise!');"
        header("Location: ../produtos.php");
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Erro no banco de dados: " . $e->getMessage() . "');</script>";
    }
}
?>