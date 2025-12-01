<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);

    if ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['usuario'] = $usuario;
        header('Location: ' . $usuario['perfil'] . '.php');
        exit();
    } else {
        echo "Email ou senha inválidos.";
    }
}
?>