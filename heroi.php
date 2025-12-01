<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado como herói
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'heroi') {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

// Busca tarefas do herói
$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Herói</title>
    <link rel="stylesheet" href="heroi.css">
</head>
<body>
    <header>
        <h2> Olá, <?= $_SESSION['usuario']['nome'] ?> (Herói)</h2>
        <h2><a href="logout.php" class="sair-btn"> Sair</a></h2>

    </header>

    <main>
        <h3>Suas Tarefas</h3>

        <?php if (count($tarefas) > 0): ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($tarefas as $tarefa): ?>
                <tr>
                    <td><?= htmlspecialchars($tarefa['titulo']) ?></td>
                    <td><?= htmlspecialchars($tarefa['descricao']) ?></td>
                    <td><?= $tarefa['status'] ?></td>
                    <td>
                        <?php if ($tarefa['status'] === 'pendente'): ?>
                            <form action="marcar_concluida.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">
                                <button type="submit">Concluir</button>
                            </form>
                        <?php else: ?>
                            ✅
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Você não possui tarefas atribuídas ainda.</p>
        <?php endif; ?>
    </main>
</body>
</html>
<style>
    /* Reset geral */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Orbitron', sans-serif; /* Fonte futurista */
}

/* Fundo tecnológico */
body {
    background: linear-gradient(135deg, #0a0a0a, #1c1c1c); /* Preto profundo */
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

/* Cabeçalho futurista */
header {
    background: linear-gradient(90deg, #ff0000, #b30000);
    width: 100%;
    text-align: center;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 5px 20px rgba(255, 0, 0, 0.8); /* Efeito neon vermelho */
    font-size: 24px;
    font-weight: bold;
}

header h2 {
    display: inline-block;
    margin: 10px;
}

header a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    background: #222;
    padding: 12px 18px;
    border-radius: 8px;
    transition: 0.3s;
    box-shadow: 0px 4px 6px rgba(255, 0, 0, 0.5);
}

header a:hover {
    background: #000;
    transform: scale(1.1);
}

/* Caixa principal */
main {
    width: 80%;
    max-width: 850px;
    margin-top: 20px;
    background: #121212;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0px 6px 15px rgba(255, 0, 0, 0.4);
}

/* Título */
h3 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 26px;
    font-weight: bold;
    color: #ff0000;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Tabela tecnológica */
table {
    width: 100%;
    border-collapse: collapse;
    background: #1c1c1c;
    color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 6px 12px rgba(255, 0, 0, 0.3);
}

th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 2px solid #ff0000;
}

th {
    background: rgba(255, 0, 0, 0.9);
    color: white;
    font-size: 20px;
}

tr:nth-child(even) {
    background-color: #262626;
}

tr:hover {
    background-color:rgb(107, 10, 10);
    transition: 0.3s ease-in-out;
}

/* Botão de ação */
button {
    padding: 10px 16px;
    background: linear-gradient(90deg, #ff0000, #b30000);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    transition: 0.3s ease-in-out;
    box-shadow: 0px 4px 8px rgba(255, 0, 0, 0.5);
}

button:hover {
    background:rgb(114, 4, 4);
    transform: scale(1.1);
}

/* Ícone de concluído */
✅ {
    font-size: 22px;
}.sair-btn-container {
    position: absolute;
    top: 20px;
    right: 20px;
}

.sair-btn {
    padding: 14px 24px;
    background: linear-gradient(90deg, #ff0000, #b30000);
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(255, 0, 0, 0.8);
    transition: 0.3s ease-in-out;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 2px solid #ff0000;
}

/* Efeito neon dinâmico */
.sair-btn:hover {
    background: linear-gradient(90deg, #ff3030, #b32222);
    transform: scale(1.1);
    box-shadow: 0px 6px 20px rgba(255, 0, 0, 0.9);
}

/* Animação ao clicar */
.sair-btn:active {
    transform: scale(0.95);
}

h2{
    font-family: 'Times New Roman', Times, serif;
}

</style>