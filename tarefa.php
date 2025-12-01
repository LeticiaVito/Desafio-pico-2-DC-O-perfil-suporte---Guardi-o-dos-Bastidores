<?php
require 'conexao.php';

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$status = $_POST['status'];
$usuario_id = $_POST['usuario_id'];

$stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, usuario_id = ? WHERE id = ?");
$stmt->execute([$titulo, $descricao, $status, $usuario_id, $id]);

header('Location: admin.php');
?>
