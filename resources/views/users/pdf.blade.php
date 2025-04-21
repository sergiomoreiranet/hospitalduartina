<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 2px solid #004358;
        }
        .header h1 {
            color: #004358;
            margin: 0;
            padding: 0;
            font-size: 24px;
        }
        .header h2 {
            color: #1a7894;
            margin: 10px 0;
            font-size: 18px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .report-info {
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 11px;
            color: #666;
        }
        .report-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            color: #004358;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        .type-admin {
            color: #9333ea;
            font-weight: bold;
        }
        .type-sector-admin {
            color: #2563eb;
            font-weight: bold;
        }
        .type-regular {
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hospital Duartina</h1>
        <h2>Lista de Usuários</h2>
    </div>

    <div class="report-info">
        <p><strong>Relatório gerado por:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Cargo:</strong> 
            @if(Auth::user()->is_admin)
                Administrador Geral
            @elseif(Auth::user()->is_sector_admin)
                Administrador de Setor
            @else
                Usuário Comum
            @endif
        </p>
        <p><strong>Data e hora:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Filtros aplicados:</strong> 
            @if(request('search'))
                Busca: "{{ request('search') }}" |
            @endif
            @if(request('sector'))
                Setor: "{{ App\Models\Sector::find(request('sector'))?->name }}" |
            @endif
            @if(request('type'))
                Tipo: "{{ request('type') === 'admin' ? 'Administrador Geral' : (request('type') === 'sector_admin' ? 'Admin. de Setor' : 'Usuário Comum') }}"
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Setor</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->cpf }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->sector ? $user->sector->name : 'Sem setor' }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="type-admin">Administrador Geral</span>
                        @elseif($user->is_sector_admin)
                            <span class="type-sector-admin">Admin. de Setor</span>
                        @else
                            <span class="type-regular">Usuário Comum</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer" style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; padding: 10px 0; border-top: 1px solid #ddd;">
        <script type="text/php">
            if (isset($pdf)) {
                // Configurações comuns
                $font = $fontMetrics->getFont("Arial");
                $size = 10;
                $color = array(0.6, 0.6, 0.6);
                $y = $pdf->get_height() - 35;

                // Texto da data e página
                $date = "Documento gerado automaticamente pelo sistema em {{ now()->format('d/m/Y H:i:s') }}";
                $pageText = "| Página {PAGE_NUM} de {PAGE_COUNT}";
                
                // Calcula a largura total para centralizar
                $dateWidth = $fontMetrics->getTextWidth($date, $font, $size);
                $pageWidth = $fontMetrics->getTextWidth(" | Página X de Y", $font, $size); // Texto exemplo para cálculo
                $totalWidth = $dateWidth + $pageWidth;
                
                // Calcula a posição inicial para centralizar
                $pageWidth = $pdf->get_width();
                $startX = ($pageWidth - $totalWidth) / 2;
                
                // Renderiza o texto da data
                $pdf->text($startX, $y, $date, $font, $size, $color);
                
                // Renderiza o texto da página logo após o texto da data
                $pdf->page_text($startX + $dateWidth, $y, $pageText, $font, $size, $color);
            }
        </script>
    </div>
</body>
</html> 