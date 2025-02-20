<?php
// Incluir o arquivo de configuração do banco de dados
require_once __DIR__ . "/bootstrap.php";

$connectionStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verificar a conexão com o banco de dados
        $stmt = $pdo->query('SELECT 1');
        $stmt->fetch();
        $connectionStatus = 'Conexão bem-sucedida com o banco de dados!';
    } catch (PDOException $e) {
        // Se houver erro, captura e exibe a mensagem
        $connectionStatus = 'Falha na conexão: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão com Banco de Dados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

    <h1>Teste de Conexão com o Banco de Dados</h1>

    <!-- Formulário para testar a conexão -->
    <form method="POST">
        <button type="submit">Testar Conexão</button>
    </form>

    <!-- Exibir o status da conexão -->
    <?php if ($connectionStatus): ?>
        <div class="message <?= $connectionStatus === 'Conexão bem-sucedida com o banco de dados!' ? 'success' : 'error' ?>">
            <?= $connectionStatus ?>
        </div>
    <?php endif; ?>

</body>
</html>
