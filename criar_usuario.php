<?php
require 'conexao.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = md5($_POST['senha']);
$perfil = $_POST['perfil'];

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
$stmt->execute([$nome, $email, $senha, $perfil]);




header('Location: admin.php');
?>