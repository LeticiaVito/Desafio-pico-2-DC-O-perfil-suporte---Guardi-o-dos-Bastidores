<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: ' . $_SESSION['usuario']['perfil'] . '.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistema Marvel</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h2>Login - Sistema de Tarefas VI</h2>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
<style>
  body {
    background-image: url('img/Sem\ título.jpg'); /* Adicione sua imagem de fundo */
    background-size: cover;
    color: white;
    font-family: Arial, sans-serif;
    text-align: center;
}

h2 {
    font-size: 2.5rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #ffcc00; /* Um amarelo vibrante para destaque */
    text-shadow: 3px 3px 10px black;
}

form {
    background: rgba(0, 0, 0, 0.85);
    padding: 25px;
    border-radius: 12px;
    display: inline-block;
    border: 2px solid #ffcc00;
    box-shadow: 0px 0px 15px rgba(255, 0, 0, 0.8);
}

input {
    width: 85%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.9);
    text-align: center;
}

button {
    background: #ff0000; /* Vermelho intenso */
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
    box-shadow: 0px 0px 12px rgba(255, 255, 255, 0.6);
}

button:hover {
    background: #cc0000;
    box-shadow: 0px 0px 18px rgba(255, 255, 255, 1);
}


</style>