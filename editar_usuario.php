<?php
session_start();
require 'conexao.php';

// Apenas suporte e admin podem editar usuários
if ($_SESSION['usuario']['perfil'] !== 'suporte' && $_SESSION['usuario']['perfil'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Verifica se foi passado um ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: suporte.php');
    exit();
}

$usuario_id = $_GET['id'];

// Busca os dados do usuário a ser editado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$usuario) {
    header('Location: suporte.php?error=usuario_nao_encontrado');
    exit();
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se é uma solicitação de exclusão
    if (isset($_POST['excluir'])) {
        try {
            // Primeiro, verifica se o usuário tem tarefas associadas
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $tem_tarefas = $stmt->fetchColumn();
            
            if ($tem_tarefas > 0) {
                $erro = "Este usuário possui tarefas associadas. Não é possível excluir.";
            } else {
                // Exclui o usuário
                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$usuario_id]);
                
                header('Location: suporte.php?success=usuario_excluido');
                exit();
            }
        } catch (PDOException $e) {
            $erro = "Erro ao excluir usuário: " . $e->getMessage();
        }
    } else {
        // Processa a edição normal
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $perfil = $_POST['perfil'];
        
        // Verifica se o email já existe em outro usuário
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $usuario_id]);
        
        if ($stmt->fetch()) {
            $erro = "Este email já está sendo usado por outro usuário.";
        } else {
            try {
                // Atualiza os dados do usuário
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, perfil = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $perfil, $usuario_id]);
                
                header('Location: suporte.php?success=usuario_editado');
                exit();
            } catch (PDOException $e) {
                $erro = "Erro ao atualizar usuário: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário | Sistema Marvel</title>
    <link rel="stylesheet" href="./css/style.css">
    <script>
        function confirmarExclusao() {
            return confirm('Tem certeza que deseja excluir este usuário?\nEsta ação não pode ser desfeita.');
        }
    </script>
</head>
<body class="suporte-page">
    <header>
        <h2>🔧 Editar Usuário</h2>
        <h2><a href="suporte.php">Voltar</a></h2>
    </header>

    <main>
        <h3>Editar Informações do Usuário</h3>
        
        <?php if (isset($erro)): ?>
            <div class="alert error">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form action="editar_usuario.php?id=<?= $usuario_id ?>" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            
            <div class="forms-group">
                <label for="perfil">Perfil:</label>
                <select id="perfil" name="perfil" required>
                    <option value="heroi" <?= $usuario['perfil'] === 'heroi' ? 'selected' : '' ?>>Herói</option>
                    <option value="vilao" <?= $usuario['perfil'] === 'vilao' ? 'selected' : '' ?>>Vilão</option>
                    <?php if ($_SESSION['usuario']['perfil'] === 'suporte'): ?>
                        <option value="admin" <?= $usuario['perfil'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-salvar">Salvar Alterações</button>
                <button type="submit" name="excluir" value="1" class="btn-excluir" onclick="return confirmarExclusao()">
                    Excluir Usuário
                </button>
            </div>
        </form>
    </main>
</body>
</html>
<div id="mensagemSucesso">✅ Suas alterações foram salvas com sucesso!</div>

   <h2><a href="admin.php" class="voltar-btn">← Voltar</a></h2>
<script>
    document.getElementById("editarTarefaForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Evita recarregar a página

        const formData = new FormData(this);

        fetch("atualizar_tarefa.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Espera uma resposta JSON do servidor
        .then(data => {
            if (data.sucesso) { // Verifica se o backend confirmou a alteração
                document.getElementById("mensagemSucesso").style.display = "block";

                // Oculta a mensagem após 3 segundos
                setTimeout(() => {
                    document.getElementById("mensagemSucesso").style.display = "none";
                }, 3000);
            } else {
                alert("Erro ao salvar. Tente novamente.");
            }
        })
        .catch(error => console.error("Erro ao salvar:", error));
    });
</script>



</body>
</html>
<style>
    /* Mensagem de sucesso */
#mensagemSucesso {
    display: none;
    color: #ff3030;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    margin-top: 15px;
    border: 2px solid #ff0000;
    border-radius: 8px;
    background: rgba(255, 0, 0, 0.1);
    box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.5);
    /* Reset geral */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Orbitron', sans-serif; /* Fonte futurista */
}
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

/* Títulos estilizados */
h2 {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #ff0000;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

/* Caixa de edição */
form {
    width: 90%;
    max-width: 700px;
    background: #121212;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0px 6px 15px rgba(255, 0, 0, 0.5);
}

.forms-group{
   width: 104.7%;
   
   
  
}
.estrutura {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
}

/* Campos de entrada */
input, textarea, select {
    width: 96%;
    padding: 12px;
    margin-bottom: 15px;
    border: 2px solid #ff0000;
    border-radius: 8px;
    background: #1c1c1c;
    color: white;
    font-size: 16px;
    transition: 0.3s;
}

input:focus, textarea:focus, select:focus {
    border-color: #ff3030;
    outline: none;
}

/* Botão de salvar alterações */
button {
    padding: 14px 20px;
    background: linear-gradient(90deg, #ff0000, #b30000);
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(255, 0, 0, 0.8);
    transition: 0.3s ease-in-out;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 2px solid #ff0000;
    width: 100%;
}

button:hover {
    background: #ff3030;
    transform: scale(1.1);
    box-shadow: 0px 6px 20px rgba(255, 0, 0, 0.9);
}

/* Botão de voltar */
.voltar-btn {
    display: inline-block;
    padding: 12px 18px;
    background: linear-gradient(90deg, #ff0000, #b30000);
    color: white;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 8px;
    box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.6);
    transition: 0.3s ease-in-out;
    margin-top: 15px;
}

.voltar-btn:hover {
    background: #ff3030;
    transform: scale(1.1);
}

</style>