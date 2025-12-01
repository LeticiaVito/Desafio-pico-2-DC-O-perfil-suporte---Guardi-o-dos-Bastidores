<?php
require 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$id = $_GET['id'];

// Busca tarefa
$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ?");
$stmt->execute([$id]);
$tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

// Busca usuários
$usuarios = $pdo->query("SELECT id, nome, perfil FROM usuarios WHERE perfil != 'admin'")->fetchAll(PDO::FETCH_ASSOC);

if (!$tarefa) {
    echo "Tarefa não encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="admin.css">
    <title>Editar Tarefa</title>
</head>
<body>
    <h2>Editar Tarefa</h2>
    <form id="editarTarefaForm" action="atualizar_tarefa.php" method="POST">
    <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">
    <input type="text" name="titulo" value="<?= $tarefa['titulo'] ?>" required><br>
    <textarea name="descricao" required><?= $tarefa['descricao'] ?></textarea><br>
    <select name="status" required class="estrutura">
        <option value="pendente" <?= $tarefa['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="concluida" <?= $tarefa['status'] === 'concluida' ? 'selected' : '' ?>>Concluída</option>
    </select><br>
    <select name="usuario_id" required class="estrutura">
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?= $usuario['id'] ?>" <?= $tarefa['usuario_id'] == $usuario['id'] ? 'selected' : '' ?>>
                <?= $usuario['nome'] ?> (<?= $usuario['perfil'] ?>)
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit" id="salvarBtn">Salvar Alterações</button>
</form>

<!-- Mensagem de Sucesso -->
<div id="mensagemSucesso" style="display: none; color: #ff3030; font-size: 18px; font-weight: bold; text-align: center; padding: 10px; margin-top: 15px; border: 2px solid #ff0000; border-radius: 8px; background: rgba(255, 0, 0, 0.1); box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.5);">
    ✅ Suas alterações foram salvas com sucesso!
</div>


<!-- Mensagem de sucesso -->
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