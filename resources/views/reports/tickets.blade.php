<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Chamados</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }
        h1, h2, h3 {
            margin-bottom: 5px;
        }
        .header, .summary, .contract {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .header {
            background-color: #f5f5f5;
        }
        .summary {
            background-color: #eef7f1;
        }
        .contract {
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ddd;
        }
        .highlight {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Relatório de Chamados</h1>
    <p><strong>Cliente:</strong> {{ $customer_name }}</p>
    <p><strong>Período do Relatório:</strong> {{ $start_date }} a {{ $end_date }}</p>
</div>

<div class="contract">
    <h2>Dados do Contrato</h2>
    <p><strong>Período do Contrato:</strong> {{ $contract_start }} a {{ $contract_end }}</p>
    <p><strong>Horas Contratadas:</strong> {{ number_format($contract_cost, 2, ',', '.') }}</p>
</div>

<h2>Chamados Resolvidos</h2>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Data da Solução</th>
        <th>Tempo Bruto</th>
        <th>Horas (decimais)</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tickets as $ticket)
        <tr>
            <td>{{ $ticket['id'] }}</td>
            <td>{{ $ticket['description'] }}</td>
            <td>{{ $ticket['solution_date'] }}</td>
            <td>{{ $ticket['raw_response_time'] }}</td>
            <td>{{ number_format($ticket['response_time_hours'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<div class="summary">
    <h2>Resumo de Horas</h2>
    <p class="highlight">Horas Contratuais: {{ number_format($contract_hours, 2, ',', '.') }} h</p>
    <p class="highlight">Horas Utilizadas: {{ number_format($used_hours, 2, ',', '.') }} h</p>
    <p class="highlight">Saldo Restante: {{ number_format($contract_hours - $used_hours, 2, ',', '.') }} h</p>
</div>

</body>
</html>
