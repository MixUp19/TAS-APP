<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Índice de Recetas</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
            margin: 0;
        }
        .container {
            max-width: 1400px;
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
        .recetas-tabla tbody tr.receta-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .recetas-tabla tbody tr.receta-row:hover {
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
            text-transform: capitalize;
        }
        .estado-pendiente {
            background-color: #fef5e7;
            color: #d97706;
        }
        .estado-completada, .estado-recogida {
            background-color: #d1fae5;
            color: #059669;
        }
        .estado-cancelada, .estado-cancelada-por-no-recoger {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .estado-lista-para-recoger {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .total {
            font-weight: 600;
            color: #2d3748;
        }
        .detalle-receta {
            display: none;
            background-color: #f9fafb;
        }
        .detalle-receta.show {
            display: table-row;
        }
        .detalle-contenido {
            padding: 20px;
        }
        .detalle-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        .acciones {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .btn-lista {
            background-color: #3b82f6;
            color: white;
        }
        .btn-recogida {
            background-color: #10b981;
            color: white;
        }
        .btn-devolver {
            background-color: #ef4444;
            color: white;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .lineas-medicamentos {
            margin-top: 15px;
        }
        .linea-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #3182ce;
        }
        .linea-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .medicamento-nombre {
            color: #1a202c;
            font-size: 16px;
        }
        .medicamento-cantidad {
            color: #4a5568;
            font-size: 14px;
        }
        .subtotal {
            color: #059669;
            font-weight: 600;
        }
        .detalles-sucursales {
            margin-top: 10px;
            padding-left: 20px;
        }
        .detalle-sucursal {
            padding: 8px 0;
            color: #4a5568;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
        }
        .detalle-sucursal:last-child {
            border-bottom: none;
        }
        .sucursal-info {
            display: flex;
            gap: 20px;
        }
        .estatus-detalle {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .toggle-icon {
            display: inline-block;
            margin-right: 8px;
            transition: transform 0.3s;
        }
        .toggle-icon.expanded {
            transform: rotate(90deg);
        }
        .loading {
            text-align: center;
            padding: 20px;
            color: #718096;
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
                    <th style="width: 30px;"></th>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Cédula Doctor</th>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recetas as $receta)
                    <tr class="receta-row" onclick="toggleDetalle({{ $receta->getFolio() }})">
                        <td>
                            <span class="toggle-icon" id="icon-{{ $receta->getFolio() }}">▶</span>
                        </td>
                        <td><strong>#{{ $receta->getFolio() }}</strong></td>
                        <td>{{ $receta->getFecha()->format('d/m/Y') }}</td>
                        <td>{{ $receta->getPaciente()->getNombre() }}</td>
                        <td>{{ $receta->getCedulaDoctor() }}</td>
                        <td>
                            @php
                                $estado = $receta->getEstado() ?? 'pendiente';
                                $estadoSlug = strtolower(str_replace(' ', '-', $estado));
                                $claseEstado = 'estado-' . $estadoSlug;
                            @endphp
                            <span class="estado {{ $claseEstado }}" id="estado-{{ $receta->getFolio() }}">
                                {{ ucfirst($estado) }}
                            </span>
                        </td>
                        <td class="total">${{ number_format($receta->getTotal(), 2) }}</td>
                    </tr>
                    <tr class="detalle-receta" id="detalle-{{ $receta->getFolio() }}">
                        <td colspan="7">
                            <div class="detalle-contenido" id="contenido-{{ $receta->getFolio() }}">
                                <div class="loading">Cargando detalles...</div>
                            </div>
                        </td>
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

<script>
    const detallesCache = {};
    const estadosExpandidos = new Set();

    async function toggleDetalle(folio) {
        const detalleRow = document.getElementById(`detalle-${folio}`);
        const icon = document.getElementById(`icon-${folio}`);

        if (estadosExpandidos.has(folio)) {
            // Contraer
            detalleRow.classList.remove('show');
            icon.classList.remove('expanded');
            estadosExpandidos.delete(folio);
        } else {
            // Expandir
            detalleRow.classList.add('show');
            icon.classList.add('expanded');
            estadosExpandidos.add(folio);

            // Cargar detalles si no están en cache
            if (!detallesCache[folio]) {
                await cargarDetalleReceta(folio);
            }
        }
    }

    async function cargarDetalleReceta(folio) {
        const contenido = document.getElementById(`contenido-${folio}`);

        try {
            console.log(`Cargando detalle de receta con folio: ${folio}`);

            const response = await fetch(`/receta/detalle/${folio}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Error desconocido' }));
                console.error('Error response data:', errorData);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Datos recibidos:', data);

            if (!data.success) {
                throw new Error(data.message || 'Error al cargar detalles');
            }

            detallesCache[folio] = data.receta;
            mostrarDetalle(folio, data.receta);
        } catch (error) {
            console.error('Error completo:', error);
            console.error('Stack trace:', error.stack);

            contenido.innerHTML = `
                <div class="loading" style="color: #dc2626;">
                    <p><strong>Error al cargar los detalles</strong></p>
                    <p style="font-size: 12px; margin-top: 10px;">${error.message}</p>
                    <p style="font-size: 11px; color: #718096; margin-top: 5px;">
                        Revisa la consola del navegador (F12) para más detalles
                    </p>
                </div>
            `;
        }
    }

    function mostrarDetalle(folio, receta) {
        const contenido = document.getElementById(`contenido-${folio}`);

        const lineasHTML = receta.lineas.map(linea => `
            <div class="linea-item">
                <div class="linea-header">
                    <div>
                        <span class="medicamento-nombre">${linea.medicamento}</span>
                        <span class="medicamento-cantidad"> (Cantidad: ${linea.cantidad})</span>
                    </div>
                    <span class="subtotal">$${linea.subtotal.toFixed(2)}</span>
                </div>
                <div class="detalles-sucursales">
                    ${linea.detalles.map(detalle => `
                        <div class="detalle-sucursal">
                            <div class="sucursal-info">
                                <span><strong>Sucursal:</strong> ${detalle.sucursal}</span>
                                <span><strong>Cantidad:</strong> ${detalle.cantidad}</span>
                            </div>
                            <span class="estatus-detalle">${detalle.estatus}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');

        const puedeModificar = receta.estado !== 'Cancelada por no recoger' && receta.estado !== 'Recogida';

        contenido.innerHTML = `
            <div class="detalle-header">
                <h3 style="margin: 0; color: #1a202c;">Detalles de Receta #${receta.folio}</h3>
                <div class="acciones">
                    <button class="btn btn-lista" onclick="cambiarEstado(${folio}, 'Lista para recoger')" ${!puedeModificar ? 'disabled' : ''}>
                        Lista para recoger
                    </button>
                    <button class="btn btn-recogida" onclick="cambiarEstado(${folio}, 'Recogida')" ${!puedeModificar ? 'disabled' : ''}>
                        Recogida
                    </button>
                    <button class="btn btn-devolver" onclick="devolverReceta(${folio})" ${!puedeModificar ? 'disabled' : ''}>
                        Devolver
                    </button>
                </div>
            </div>
            <div class="lineas-medicamentos">
                ${lineasHTML}
            </div>
        `;
    }

    async function cambiarEstado(folio, nuevoEstado) {
        if (!confirm(`¿Está seguro de cambiar el estado a "${nuevoEstado}"?`)) {
            return;
        }

        try {
            const response = await fetch('/receta/cambiar-estado', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ folio, estado: nuevoEstado })
            });

            if (!response.ok) throw new Error('Error al cambiar estado');

            const data = await response.json();

            // Actualizar estado en la tabla
            const estadoElement = document.getElementById(`estado-${folio}`);
            const estadoSlug = nuevoEstado.toLowerCase().replace(/ /g, '-');
            estadoElement.className = `estado estado-${estadoSlug}`;
            estadoElement.textContent = nuevoEstado;

            // Actualizar cache
            if (detallesCache[folio]) {
                detallesCache[folio].estado = nuevoEstado;
                mostrarDetalle(folio, detallesCache[folio]);
            }

            alert(data.message);
        } catch (error) {
            alert('Error al cambiar el estado de la receta');
            console.error('Error:', error);
        }
    }

    async function devolverReceta(folio) {
        if (!confirm('¿Está seguro de devolver esta receta? Esta acción cancelará la receta y notificará la devolución.')) {
            return;
        }

        try {
            const response = await fetch('/receta/cancelar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ folio })
            });

            if (!response.ok) throw new Error('Error al devolver receta');

            const data = await response.json();

            // Actualizar estado en la tabla
            const estadoElement = document.getElementById(`estado-${folio}`);
            estadoElement.className = 'estado estado-cancelada-por-no-recoger';
            estadoElement.textContent = 'Cancelada por no recoger';

            // Actualizar cache y recargar detalle
            if (detallesCache[folio]) {
                detallesCache[folio].estado = 'Cancelada por no recoger';
                mostrarDetalle(folio, detallesCache[folio]);
            }

            alert(data.message);
        } catch (error) {
            alert('Error al devolver la receta');
            console.error('Error:', error);
        }
    }
</script>

</body>
</html>

