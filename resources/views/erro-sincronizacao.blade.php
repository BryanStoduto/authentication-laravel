<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na sincronização</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #901212, #000000);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: whitesmoke;
            padding: 35px;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 2px 5px 5px rgba(247, 247, 247, 0.1);
            p color: #ffffff;
        }

        h1 {
            color: #ff0000;
        }

        .detalhes {
            background: #transparent;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin: 20px 0;
            overflow-wrap: anywhere;
        }

        .botao {
            display: inline-block;
            background: linear-gradient(to right, #dc3545, #721c24);
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .botao:hover {
            background: #901212;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Erro na sincronização</h1>

        <p>
            {{ session('mensagem', 'Registro retornado da API com problema.') }}
        </p>

        <div class="detalhes">
            <p>
                <strong>Indicador:</strong>
                {{ session('indicador', 'Não identificado') }}
            </p>

            <p>
                <strong>Código:</strong>
                {{ session('codigo', '-') }}
            </p>

            <p>
                <strong>Detalhes:</strong>
                {{ session('detalhes', 'Nenhum detalhe disponível.') }}
            </p>
        </div>

        <a href="{{ route('indicadores.index') }}" class="botao">
            Voltar para Dashboard
        </a>

    </div>

</body>
</html>
