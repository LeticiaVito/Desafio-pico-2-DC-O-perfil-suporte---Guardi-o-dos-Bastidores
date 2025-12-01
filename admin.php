<?php
session_start();
require 'conexao.php';

// Verifica se é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Consulta usuários e tarefas
$usuarios = $pdo->query("SELECT * FROM usuarios WHERE perfil != 'admin'")->fetchAll(PDO::FETCH_ASSOC);
$tarefas = $pdo->query("SELECT t.*, u.nome AS usuario_nome FROM tarefas t JOIN usuarios u ON t.usuario_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h2 id="welcome-message">Bem-vindo, Admin!</h2>

<script>


</script>

   <a href="logout.php" class="botao-sair">Sair</a>


    <hr>
<div class="container-table">




    <h3>📋 Cadastrar Novo Usuário</h3>
    <form action="criar_usuario.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <select name="perfil" required>
            <option value="heroi">Herói</option>
            <option value="vilao">Vilão</option>
        </select>
        <button type="submit">Cadastrar</button>
    </form>

    <hr>

    <h3>🧾 Cadastrar Nova Tarefa</h3>
    <form action="criar_tarefa.php" method="POST">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descricao" placeholder="Descrição da tarefa" required></textarea>
        <select name="usuario_id" required>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id'] ?>"><?= $usuario['nome'] ?> (<?= $usuario['perfil'] ?>)</option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Criar Tarefa</button>
    </form>

    <hr>

    <h3>📊 Tarefas Atribuídas</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Usuário</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($tarefas as $tarefa): ?>
        <tr>
            <td><?= $tarefa['titulo'] ?></td>
            <td><?= $tarefa['descricao'] ?></td>
            <td><?= $tarefa['status'] ?></td>
            <td><?= $tarefa['usuario_nome'] ?></td>
            <td>
                <a href="editar_tarefa.php?id=<?= $tarefa['id'] ?>">Editar</a> |
                <a href="excluir_tarefa.php?id=<?= $tarefa['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
<style>
 /* Configuração geral do corpo */
body {
    background-image: url('img/1 t.webp'); /* Substitua pelo nome correto do arquivo */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    position: relative;
    padding: 20px;
}

/* Fundo escurecido */
body::before {
    content: "";
    position: absolute;
    top: -8px;
    left: -10px;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: -1;
}

/* Estilização dos títulos */
h2 {
    font-size: 2.5rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #ffcc00;
    text-shadow: 3px 3px 10px black;
    text-align: center;
    margin-bottom: 20px;
}

h3 {
    font-size: 2rem;
    color: #ffcc00;
    text-shadow: 2px 2px 8px black;
    margin-bottom: 15px;
    text-align: center;
}

/* Contêiner para organização */
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    max-width: 800px; /* Define um tamanho máximo */
}

/* Estilização dos formulários */
form {
    background: #222;
    padding: 20px;
    border-radius: 12px;
    border: 2px solid #ffcc00;
    box-shadow: 0px 0px 15px rgba(255, 0, 0, 0.8);
    width: 80%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
}

input, textarea, select {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.9);
    text-align: center;
}

/* Botão geral */
button {
    background: #ff0000;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: 0.3s ease-in-out;
    font-weight: bold;
    box-shadow: 0px 4px 8px rgba(255, 0, 0, 0.4);
}

button:hover {
    background: #cc0000;
    box-shadow: 0px 6px 12px rgba(255, 0, 0, 0.6);
}

/* Botão "Sair" */
.botao-sair {
    display: inline-block;
    background: #ff0000;
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    font-size: 1.2rem;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0px 4px 8px rgba(255, 0, 0, 0.4);
    transition: 0.3s ease-in-out;
    text-align: center;
    margin-bottom: 20px;
}

.botao-sair:hover {
    background: #cc0000;
    box-shadow: 0px 6px 12px rgba(255, 0, 0, 0.6);
}

/* Contêiner da tabela */
.container-table {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

/* Estilização da tabela */
table {
    width: 80%;
    border-collapse: collapse;
    background: #222;
    border: 2px solid #ffcc00;
    box-shadow: 0px 0px 10px rgba(255, 0, 0, 0.8);
    text-align: center;
    margin: auto;
    font-size: 20px;
}

th, td {
    padding: 12px;
    border: 1px solid #ffcc00;
    color: white;
}

/* Estilização dos cabeçalhos da tabela */
th {
    background: #ff0000;
    text-shadow: 2px 2px 5px black;
    font-size: 1.0rem;
}

/* Estilização dos links da tabela */
td a {
    color: #ffcc00;
    font-weight: bold;
    text-decoration: none;
}

td a:hover {
    color: #ffffff;
    text-shadow: 0px 0px 5px #ffcc00;
}

</style>