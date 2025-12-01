<?php
require 'conexao.php';

$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$usuario_id = $_POST['usuario_id'];

$stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, usuario_id) VALUES (?, ?, ?)");
$stmt->execute([$titulo, $descricao, $usuario_id]);

header('Location: admin.php');
?>