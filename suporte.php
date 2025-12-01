<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado como suporte
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'suporte') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Suporte</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="suporte-page">
        <nav>
            <div class="nav-container">
                <a href="index.php" class="nav-logo"></a>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="suporte.php" class="nav-link">
                            <i class="fas fa-home"></i> Painel
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <div class="nav-profile">
                            
                            <span class="nav-link"><?= $_SESSION['usuario']['nome'] ?> (<?= $_SESSION['usuario']['perfil'] ?>)</span>
                        </div>
                        <div class="nav-dropdown-content">
                            <a href="logout.php" class="nav-dropdown-link">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

    <main>
        <h3>Gerenciamento de Usuários</h3>
        
        <div class="alert">
            <p>Como suporte, você pode cadastrar novos administradores e editar perfis de usuários, mas não pode gerenciar tarefas.</p>
        </div>

        <h4>Cadastrar Novo Administrador</h4>
        <form action="criar_admin.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="hidden" name="perfil" value="admin">
            <button type="submit">Cadastrar Admin</button>
        </form>

        <hr>

        <h4>Lista de Usuários</h4>
        <?php
        $usuarios = $pdo->query("SELECT * FROM usuarios WHERE perfil != 'suporte'")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($usuarios) > 0): ?>
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= $usuario['perfil'] ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">Editar</a> |
                        <a href="redefinir_senha.php?id=<?= $usuario['id'] ?>">Redefinir Senha</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhum usuário cadastrado.</p>
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
    font-family: 'Bebas Neue', sans-serif; /* Fonte heroica */
    font-family: 'orbitron', sans-serif;
}

.suporte-page {
    background: linear-gradient(135deg, #000000, #1c1c1c); /* Fundo preto moderno */
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start; /* Isso desce o conteúdo */
    padding: 60px 20px; /* Aumentei o espaçamento */
    min-height: 100vh; /* Garante que o fundo cobre toda a tela */
}
nav {
    background: #f5c518;
    padding: 15px 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(215, 31, 40, 0.6);
    width: 100%;
    display: flex;
    justify-content: center; /* Centraliza os elementos */
    align-items: center;
}

.nav-item nav-dropdown{
    margin-left: auto;
}
.nav-menu {
    list-style: none;
    display: flex;
    justify-content: center; /* Centraliza os itens */
    gap: 30px; /* Espaço adequado entre eles */
    padding: 0;
    margin: 0 auto;
    width: 100%;
}

.nav-menu li {
    display: inline-block;
}

.nav-link {
    color: #333;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s ease, background 0.3s ease;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 18px;
    background: rgba(255, 255, 255, 0.2);
    display: inline-block;
    margin-top: 4px;
}

.nav-link:hover {
    color: #f5c518;
    background: rgba(0, 0, 0, 0.1);
}

.profile-section {
    display: flex;
    align-items: center;
    gap: 15px;
    justify-content: center; /* Centraliza o perfil */
    width: auto;
}

.profile-info {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.logout-btn {
    background: #d71f28;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.logout-btn:hover {
    background: #b31720;
}

.nav-dropdown-link {
     color: #333;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s ease, background 0.3s ease;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 18px;
    background: rgba(255, 255, 255, 0.2);
    display: list-item;
    margin-left: 300px;
    margin-top: -20px;
}

.nav-dropdown-link:hover {
    background: #b31720;
    transform: scale(1.05);
}

/* Garantindo que o menu ocupe o espaço corretamente */
.nav-menu {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* Alinha os itens à esquerda */
    gap: 20px;
    width: 100%;
}

/* Adicionando um contêiner para o botão e mantendo no lado direito */
.nav-right {
    margin-left: auto; /* Força o botão a ir para a direita */
}


 
/* HEADER ESTILIZADO */
header {
    background: linear-gradient(90deg, #d71f28,rgb(195, 173, 173));
    width: 100%;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0px 6px 15px rgba(215, 31, 40, 0.6);
}

header h2 {
    font-size: 26px;
    font-weight: bold;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 2px;
}

header a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    font-size: 18px;
    padding: 12px 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    transition: 0.3s;
}

header a:hover {
    background: #f5c518;
    color: black;
    transform: scale(1.1);
}
.nav-profile-img {
    width: 60px; /* Mantém uma largura equilibrada */
    height: 100px; /* Altura aumentada para maior visibilidade */
    border-radius: 8px; /* Bordas menos arredondadas para evitar cortes */
    object-fit: cover; /* Mantém a proporção da imagem */
    border: 2px solid #ffffff; /* Adiciona um contorno para destaque */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); /* Efeito de sombra leve */
    transition: transform 0.2s ease;
}

.nav-profile-img:hover {
    transform: scale(1.08); /* Leve efeito de aumento ao passar o mouse */
}




/* Caixa de gerenciamento */
main {
    width: 85%;
    max-width: 800px;
    background: #1c1c1c;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 6px 15px rgba(255, 0, 0, 0.4);
    margin-top: 50px; /* Espaçamento maior */
}

/* Títulos heroicos */
h3, h4 {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #d71f28;
    margin-bottom: 15px;
    font-family: 'Times New Roman', Times, serif;
}

h2{
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
}

/* Alertas */
.alert {
    background: #f5c518;
    color: black;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
}

/* Formulário minimalista */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

input {
    width: 80%;
    padding: 12px;
    border: 2px solid #d71f28;
    border-radius: 5px;
    background: #121212;
    color: white;
    font-size: 16px;
}

button {
    padding: 12px 16px;
    background: #d71f28;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
}

button:hover {
    background: #a3151b;
}

/* Tabela mais compacta */
table {
    width: 100%;
    border-collapse: collapse;
    background: #181818;
    color: white;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background: #d71f28;
}

tr:hover {
    background: rgba(255, 255, 255, 0.1);
}

</style>