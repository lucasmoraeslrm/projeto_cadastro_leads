<?php
// Inicia a sessão e gera um token CSRF
session_start();
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-control:disabled {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>

    <div class="container">
        <h3 class="text-center mb-4">Formulário de Cadastro</h3>
        <form id="form-cadastro" action="model/cadastro.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="celular" class="form-label">Celular:</label>
                <input type="tel" class="form-control" id="celular" name="celular" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" required>
            </div>

            <div class="mb-3">
                <label for="razao" class="form-label">Razão Social:</label>
                <input type="text" class="form-control" id="razao" name="razao">
            </div>

            <div class="mb-3">
                <label for="atividade" class="form-label">Ramo de Atividade:</label>
                <input type="text" class="form-control" id="atividade" name="atividade">
            </div>

            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade:</label>
                <input type="text" class="form-control" id="cidade" name="cidade">
            </div>

            <!-- Adiciona o token CSRF gerado na sessão -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">Registrar</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('cnpj').addEventListener('blur', function() {
            var cnpj = this.value;

            if (cnpj.length === 14) {
                // Faz a requisição para o PHP que chama a API do CNPJ
                fetch('model/cnpj.php?cnpj=' + cnpj)
                    .then(response => response.json()) // Converte a resposta para JSON
                    .then(data => {
                        if (data.status === 'OK') {
                            // Preenche os campos do formulário com os dados retornados pela API

                            // Nome ou razão social
                            document.getElementById('razao').value = data.nome;

                            // Atividade principal (apenas o texto da primeira atividade)
                            if (data.atividade_principal && data.atividade_principal.length > 0) {
                                document.getElementById('atividade').value = data.atividade_principal[0].text;
                            }

                            // Cidade
                            document.getElementById('cidade').value = data.municipio;

                        } else {
                            alert('Erro ao consultar CNPJ');
                        }
                    })
                    .catch(error => console.error('Erro na consulta:', error));
            } else {
                alert('CNPJ inválido');
            }
        });
    </script>

</body>

</html>