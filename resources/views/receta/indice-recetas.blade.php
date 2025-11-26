<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Índice de Recetas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #1a202c;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .info-sucursal {
            background-color: #edf2f7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-sucursal p {
            margin: 5px 0;
            color: #4a5568;
        }
        .recetas-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .recetas-tabla thead {
            background-color: #3182ce;
            color: white;
        }
        .recetas-tabla th,
        .recetas-tabla td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .recetas-tabla tbody tr:hover {
            background-color: #f7fafc;
        }
        .recetas-tabla th {
            font-weight: 600;
        }
        .sin-recetas {
            text-align: center;
            padding: 40px;
            color: #718096;
            font-size: 16px;
        }
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #718096;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s;
        }
        .btn-volver:hover {
            background-color: #4a5568;
        }
        .estado {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .estado-pendiente {
            background-color: #fef5e7;
            color: #d97706;
        }
        .estado-completada {
            background-color: #d1fae5;
            color: #059669;
        }
        .estado-cancelada {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .total {
            font-weight: 600;
            color: #2d3748;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Índice de Recetas por Sucursal</h1>

    @if(count($recetas) > 0)
        <table class="recetas-tabla">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Cédula Doctor</th>
                    <th>Estado</th>
                    <th>Medicamentos</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recetas as $receta)
                    <tr>
                        <td>{{ $receta->getFecha()->format('d/m/Y') }}</td>
                        <td>{{ $receta->getPaciente()->getNombre() }}</td>
                        <td>{{ $receta->getCedulaDoctor() }}</td>
                        <td>
                            @php
                                $estado = $receta->getEstado() ?? 'pendiente';
                                $claseEstado = 'estado-' . strtolower($estado);
                            @endphp
                            <span class="estado {{ $claseEstado }}">
                                {{ ucfirst($estado) }}
                            </span>
                        </td>
                        <td>
                            @if(count($receta->getLineasRecetas()) > 0)
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($receta->getLineasRecetas() as $linea)
                                        <li>
                                            {{ $linea->getMedicamento()->getNombre() }}
                                            (x{{ $linea->getCantidad() }})
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <em style="color: #a0aec0;">Sin medicamentos</em>
                            @endif
                        </td>
                        <td class="total">${{ number_format($receta->getTotal(), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="sin-recetas">
            <p>No hay recetas registradas para esta sucursal.</p>
        </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('home') }}" class="btn-volver">Volver al inicio</a>
    </div>
</div>

</body>
</html>

