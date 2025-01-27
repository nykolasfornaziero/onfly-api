@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Status - Solicitação de Viagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h1 {
            font-size: 24px;
            color: #333333;
        }

        .email-body p {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .status.approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status.canceled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f5f5f5;
            font-size: 14px;
            color: #777777;
        }

        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <img src="https://www.onfly.com.br/wp-content/uploads/2021/10/onfly-header.svg" alt="Logo da Empresa">
        <h2>Status da Viagem</h2>
    </div>

    <div class="email-body">
        <h1>Olá, {{ $user->name }}!</h1>
        <p>
            Estamos entrando em contato para informar que o status da sua viagem para <strong>{{ $travelInfo->destination }}</strong> foi atualizado. Veja abaixo os detalhes:
        </p>

        <div class="status approved">
            Status: <strong>{{ $travelInfo->status }}</strong>
        </div>

        <p>
            Data de partida: <strong>{{ Carbon::parse($travelInfo->departure_date)->format('d/m/Y')}}</strong><br>
            Data de retorno: <strong>{{ Carbon::parse($travelInfo->return_date)->format('d/m/Y') }}</strong>
        </p>
        <p>
            Caso tenha dúvidas ou precise de mais informações, entre em contato com o setor responsável!
        </p>
    </div>

    <div class="email-footer">
        <p>
            <a href="mailto:contato@empresa.com">onfly@onfly.com.br</a>
        </p>
    </div>
</div>
</body>
</html>
