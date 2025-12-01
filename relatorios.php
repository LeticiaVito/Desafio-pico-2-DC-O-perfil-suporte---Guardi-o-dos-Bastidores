<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Processar filtros
$filtro_usuario = $_GET['usuario_id'] ?? '';
$filtro_status = $_GET['status'] ?? '';

// Construir a consulta SQL com filtros
$sql = "SELECT t.*, u.nome AS usuario_nome FROM tarefas t JOIN usuarios u ON t.usuario_id = u.id WHERE 1=1";
$params = [];

if (!empty($filtro_usuario)) {
    $sql .= " AND t.usuario_id = ?";
    $params[] = $filtro_usuario;
}

if (!empty($filtro_status)) {
    $sql .= " AND t.status = ?";
    $params[] = $filtro_status;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca usuários para o filtro
$usuarios = $pdo->query("SELECT * FROM usuarios WHERE perfil != 'admin' AND perfil != 'suporte'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios | Sistema Marvel</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="admin-page">
    <h2>📊 Relatórios do Sistema
        <a href="admin.php"><- Voltar</a>
    </h2>

    <main>
        <h3>Filtrar Tarefas</h3>
        <form method="GET" action="relatorios.php">
            <select name="usuario_id">
                <option value="">Todos os usuários</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>" <?= ($filtro_usuario == $usuario['id']) ? 'selected' : '' ?>>
                        <?= $usuario['nome'] ?> (<?= $usuario['perfil'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="status">
                <option value="">Todos os status</option>
                <option value="pendente" <?= ($filtro_status == 'pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="concluida" <?= ($filtro_status == 'concluida') ? 'selected' : '' ?>>Concluída</option>
            </select>

            <button type="submit">Filtrar</button>
        </form>

        <hr>

        <h3>Resultados</h3>
        <?php if (count($tarefas) > 0): ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Usuário</th>
                    <th>Perfil</th>
                </tr>
                <?php foreach ($tarefas as $tarefa): ?>
                <tr>
                    <td><?= htmlspecialchars($tarefa['titulo']) ?></td>
                    <td><?= htmlspecialchars($tarefa['descricao']) ?></td>
                    <td class="status-<?= $tarefa['status'] ?>">
                        <?= ucfirst($tarefa['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($tarefa['usuario_nome']) ?></td>
                    <td><?= $tarefa['perfil'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhuma tarefa encontrada com os filtros selecionados.</p>
        <?php endif; ?>
    </main>
</body>
</html>