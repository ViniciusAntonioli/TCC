<?php


$serverName = "localhost\SQLEXPRESS"; 
$database = "CatalogoDeBrindes";
$username = "sa"; 
$password = "1.Aa1234567";
try {
    // Adicionando TrustServerCertificate para ignorar a verificação SSL, para que não ocorram problemas
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database;TrustServerCertificate=true", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>