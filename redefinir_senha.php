<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado como suporte ou admin
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] !== 'suporte' && $_SESSION['usuario']['perfil'] !== 'admin')) {
    header('Location: index.php');
    exit();
}

// Verifica se foi passado um ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: painel_suporte.php');
    exit();
}

$usuario_id = $_GET['id'];

// Busca os dados básicos do usuário
$stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$usuario) {
    header('Location: painel_suporte.php?error=usuario_nao_encontrado');
    exit();
}

// Processa o formulário de redefinição de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações
    if (empty($nova_senha)) {
        $erro = "A nova senha não pode estar vazia.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        try {
            // Criptografa a nova senha (usando MD5 para compatibilidade com o sistema atual)
            $senha_criptografada = md5($nova_senha);
            
            // Atualiza a senha no banco de dados
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt->execute([$senha_criptografada, $usuario_id]);
            
            header('Location: suporte.php?success=senha_redefinida');
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao redefinir senha: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha | Sistema Marvel</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="suporte-page">
   <header>
    <div class="header-container">
        <h2>🔧 Redefinir Senha</h2>
        <nav class="nav-container">
            <a href="painel.php">Painel</a>
            <a href="suporte.php">Tony Stark (suporte)</a>
           
        </nav>
    </div>
</header>


    <main>
        <h3>Redefinir Senha para <?= htmlspecialchars($usuario['nome']) ?></h3>
        
        <?php if (isset($erro)): ?>
            <div class="alert error">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form action="redefinir_senha.php?id=<?= $usuario_id ?>" method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                <small>A senha deve ter pelo menos 6 caracteres.</small>
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-salvar">Redefinir Senha</button>
                 <a href="index.php" class="btn-excluir">Sair</a>
            </div>
        </form>
    </main>
</body>
</html>

<style>
/* Estilização Geral */
body.suporte-page {
    font-family: Arial, sans-serif;
    background-color: rgb(3, 3, 3);
    color: #333;
    margin: 0;
    padding: 20px;
    text-align: center;
}

/* Cabeçalho */
/* Cabeçalho */
header {
    background: #d71f28;
    color: white;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 98%;
}

/* Container do cabeçalho */
.header-container {
    display: flex;
    width: 100%;
    align-items: center;
}

/* Título da página */
.header-container h2 {
    flex: 1; /* Ocupa espaço e mantém alinhado */
    margin: 0;
}

/* Links de navegação */
.nav-container {
    display: flex;
    gap: 15px;
    flex: 1;
    justify-content: flex-end; /* Garantindo alinhamento à direita */
}

.nav-container a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 10px 15px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.2);
    transition: background 0.3s ease;
}

.nav-container a:hover {
    background: rgba(255, 255, 255, 0.4);
}


/* Formulário */
main {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    max-width: 400px;
    margin: 30px auto;
}

h3 {
    margin-bottom: 20px;
}

/* Campos do Formulário */
.form-group {
    text-align: left;
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Mensagens de Erro */
.alert.error {
    background: #ffcccc;
    color: #d71f28;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
}

/* Botões */
.form-actions {
    display: flex;
    justify-content: space-between;
}

.btn-salvar {
    background: #28a745;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-salvar:hover {
    background: #218838;
}

.btn-excluir {
    background: rgb(195, 9, 9);
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-excluir:hover {
    background: #b31720;
    transform: scale(1.05);
}
</style>
