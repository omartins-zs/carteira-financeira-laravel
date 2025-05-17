<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Verifique seu e-mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #6366F1;
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 24px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: #6366F1;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .footer {
            background: #f1f5f9;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Bem-vindo à Carteira Financeira!</h1>
        </div>
        <div class="content">
            <p>Olá, {{ $user->name }} </p>
            <p>Obrigado por se registrar. Para concluir seu cadastro, por favor verifique seu endereço de e-mail
                clicando no botão abaixo:</p>
            <p style="text-align:center;">
                <a href="{{ $url }}" class="btn">Verificar E-mail</a>
            </p>
            <p>Se você não criou esta conta, pode simplesmente ignorar este e-mail.</p>
            <p>Até logo!<br>Equipe Carteira Financeira</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Carteira Financeira. Todos os direitos reservados.
        </div>
    </div>
</body>

</html>
