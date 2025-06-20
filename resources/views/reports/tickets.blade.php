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
            margin: 30px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-left {
            width: 100px;
        }

        .logo-center {
            text-align: center;
        }

        .report-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .report-meta {
            text-align: center;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #ddd;
        }

        .summary {
            margin-top: 25px;
            padding: 10px;
            background-color: #eef7f1;
        }

        .highlight {
            font-weight: bold;
            font-size: 14px;
        }

        .no-break-group {
            page-break-inside: avoid;
        }

        .monthly-table th {
            font-size: 12px;
        }

        .monthly-table td {
            text-align: center;
        }
    </style>
</head>
<body>
<span>Emitido em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>

<table class="header-table">
    <tr>
        <td class="logo-left">
            <img src="{{ public_path('images/Logo.png') }}" alt="VN Logo" height="50">
        </td>
        <td>
            <div class="report-title">RELATÓRIO DE CHAMADOS • VN Solution</div>
            <div class="report-meta">
                <strong>Período de emissão:</strong> {{ \Carbon\Carbon::parse($start_date)->format('m/Y') }} a {{ \Carbon\Carbon::parse($end_date)->format('m/Y') }}<br>
                <strong>Contrato:</strong> {{ $contract_id ?? '---' }} Vigência {{ \Carbon\Carbon::parse($contract_start)->format('m/Y') }} a {{ \Carbon\Carbon::parse($contract_end)->format('m/Y') }}
            </div>
        </td>
        <td class="logo-center">
            <p><strong>{{ $customer_name }}</strong></p>
        </td>
    </tr>
</table>

<h2>Chamados Resolvidos</h2>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Número</th>
        <th>Data de Abertura</th>
        <th>Data da Solução</th>
        <th>Solicitante</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Tempo Bruto</th>
        <th>Horas (decimais)</th>
    </tr>
    </thead>
    <tbody>
    {{$teste = 0}}
    @foreach ($tickets as $ticket)
        {{$teste++}}
        <tr>
            <td>{{$teste}}</td>
            <td>{{ $ticket['id'] }}</td>
            <td>{{ $ticket['date_creation']}}</td>
            <td>{{ $ticket['solution_date'] }}</td>
            <td>{{ $customer_name }}</td>
            <td>{{ $ticket['description'] }}</td>
            <td>{{ $ticket['category'] }}</td>
            <td>{{ $ticket['raw_response_time'] }}</td>
            <td>{{ number_format($ticket['response_time_hours'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="summary">
    <p class="highlight">Horas Contratuais: {{ number_format($contract_hours, 2, ',', '.') }} h</p>
    <p class="highlight">Horas Utilizadas: {{ number_format($used_hours, 2, ',', '.') }} h</p>
    <p class="highlight">Saldo Restante: {{ number_format($contract_hours - $used_hours, 2, ',', '.') }} h</p>
</div>

<h3 style="margin-top: 30px;">Resumo Mensal de Horas Utilizadas</h3>

<table class="monthly-table">
    <thead>
    <tr style="background-color: #f2f2f2;">
        <th style="text-align: center">Horas Utilizadas</th>
        @foreach ($monthly_hours as $month => $value)
            <th style="text-align: center">{{ $month }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Total</td>
        @foreach ($monthly_hours as $value)
            <td>{{ number_format($value, 2, ',', '.') }}</td>
        @endforeach
    </tr>
    </tbody>
</table>

</body>
</html>
