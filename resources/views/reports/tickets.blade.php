<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
<h1>Relatório de Tickets</h1>
<p>Total de Tickets: {{count($data)}}</p>
<table>
    <tr>
        <th>Id</th>
        <th>Funcionário</th>
        <th>Cpf</th>
        <th>Situação</th>
        <th>Quantidade</th>
    </tr>
    @foreach($data as $ticket)
        <tr>
            <td>{{ $ticket['id'] }}</td>
            <td>{{ $ticket['employee']['name'] }}</td>
            <td>{{ $ticket['employee']['cpf'] }}</td>
            <td>{{ $ticket['situation'] === 'A' ? 'Ativo' : 'Inativo' }}</td>
            <td>{{ (string)$ticket['quantity'] }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
