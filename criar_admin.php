<?php
session_start();
require 'conexao.php';

// Verifica se o usuário é suporte
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'suporte') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Criptografia MD5 (considerar usar password_hash() em produção)
    $perfil = 'admin'; // Forçando o perfil como admin

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha, $perfil]);
        
        header('Location: suporte.php?success=1');
        exit();
    } catch (PDOException $e) {
        // Verifica se é erro de email duplicado
        if ($e->errorInfo[1] == 1062) {
            header('Location: suporte.php?error=email_duplicado');
        } else {
            header('Location: suporte.php?error=1');
        }
        exit();
    }
} else {
    header('Location: suporte.php');
    exit();
}