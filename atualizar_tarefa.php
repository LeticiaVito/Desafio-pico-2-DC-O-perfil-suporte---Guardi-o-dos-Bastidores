<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $usuario_id = $_POST['usuario_id'];

    $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, usuario_id = ? WHERE id = ?");
    $resultado = $stmt->execute([$titulo, $descricao, $status, $usuario_id, $id]);

    // Retorna resposta em JSON
    echo json_encode(["sucesso" => $resultado]);
}
?>
