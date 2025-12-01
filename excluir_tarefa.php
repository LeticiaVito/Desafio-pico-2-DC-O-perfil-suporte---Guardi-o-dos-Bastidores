<?php
require 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin.php');
?>