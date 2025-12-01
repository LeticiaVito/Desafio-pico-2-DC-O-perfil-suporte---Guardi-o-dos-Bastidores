<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado como vilão
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'vilao') {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

// Busca tarefas do vilão
$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Vilão</title>
    <link rel="stylesheet" href="vilao.css">
</head>
<body>
    <header>
        <h2>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?> (Vilão)</h2>
        <h2><a href="logout.php" class="sair-btn">Sair</a></h2>
    </header>

    <main>
        <h4>Seus Planos... digo, Tarefas</h4>

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
            <p>Nenhuma tarefa maligna atribuída ainda...</p>
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
    font-family: 'Cinzel', serif; /* Fonte com estilo dramático e vilanesco */
}

/* Fundo do vilão - com suporte para imagem */
body {
    background: url('img/viloes\ 1.jpg') center/cover no-repeat fixed; /* Troque pelo caminho correto da imagem */
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

/* Efeito de sombra nas letras */
h2, h3 {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #fff;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
}

/* Caixa de conteúdo */
main {
    width: 85%;
    max-width: 800px;
    background: rgba(0, 0, 0, 0.85); /* Fundo semitransparente para sobrepor à imagem */
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0px 6px 15px rgba(255, 255, 255, 0.2);
}

/* Tabela vilanesca */
table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1); /* Fundo translúcido */
    color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 6px 12px rgba(255, 255, 255, 0.2);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 2px solid rgba(255, 255, 255, 0.3);
}

th {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 18px;
}

/* Sombras e contraste para os textos */
tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.05);
}

tr:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transition: 0.3s ease-in-out;
}

/* Botão vilanesco */
button {
    padding: 12px 18px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(255, 255, 255, 0.5);
    transition: 0.3s ease-in-out;
    border: 2px solid white;
}

button:hover {
    background: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

/* Ícone de concluído */
✅ {
    font-size: 22px;
}

/* Botão de sair - versão sombria */
.sair-btn {
    display: inline-block;
    padding: 14px 24px;
    background: rgba(55, 53, 53, 0.65);
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(13, 12, 12, 0.8);
    transition: 0.3s ease-in-out;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sair-btn:hover {
    background: rgba(7, 7, 7, 0.65);
    transform: scale(1.1);
}
h2{
    color: #000;
}
h4{
    font-size: 40px;
}
</style>