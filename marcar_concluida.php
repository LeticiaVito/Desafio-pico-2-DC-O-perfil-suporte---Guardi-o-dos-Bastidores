<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$id = $_POST['id'];
$usuario_id = $_SESSION['usuario']['id'];

// Atualiza status da tarefa se for do usuário logado
$stmt = $pdo->prepare("UPDATE tarefas SET status = 'concluida' WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $usuario_id]);

header('Location: heroi.php');
