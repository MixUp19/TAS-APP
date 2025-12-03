<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Índice de Recetas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Tabla personalizada */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .custom-table thead th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: white;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .custom-table thead th:first-child {
            border-radius: 12px 0 0 0;
        }

        .custom-table thead th:last-child {
            border-radius: 0 12px 0 0;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .custom-table tbody tr:hover {
            background-color: #fef2f2;
            transform: scale(1.005);
        }

        .custom-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #fecaca;
            color: #374151;
        }

        .custom-table tbody tr:last-child td:first-child {
            border-radius: 0 0 0 12px;
        }

        .custom-table tbody tr:last-child td:last-child {
            border-radius: 0 0 12px 0;
        }

        /* Estados */
        .estado-badge {
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .estado-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .estado-pendiente {
            background-color: #fef3c7;
            color: #d97706;
        }

        .estado-completada, .estado-recolectada {
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

        /* Botones */
        .btn-action {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-action:active {
            transform: translateY(0);
        }

        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-lista {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-recolectada {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-devolver {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* Cards de detalles */
        .detalle-card {
            background: white;
            border-radius: 12px;
            border-left: 4px solid #dc2626;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .detalle-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-rose-50 to-red-100 min-h-screen">

<div class="container mx-auto p-8">
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="bg-white/80 backdrop-blur-xl border border-white/60 shadow-2xl rounded-3xl p-8">

        <!-- NAV -->
        <x-farmaceutico-nav titulo="Recetas de la Sucursal {{ Session::get('usuario')->getSucursal()->getSucursalId() }}" />

        <!-- CONTENIDO -->
        <div class="mt-6">
            @if(count($recetas) > 0)
                <!-- Contador de recetas -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total de recetas</p>
                            <p class="text-2xl font-bold text-gray-800">{{ count($recetas) }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">
                        Haz clic en una receta para ver más detalles
                    </p>
                </div>

                <!-- Tabla de recetas -->
                <div class="overflow-hidden rounded-xl border border-red-100 shadow-lg">
                    <table class="custom-table">
                        <thead>
                            <tr>
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
                                <tr onclick="window.location='{{ route('receta.devolver', ['folio' => $receta->getFolio()]) }}'">
                                    <td>
                                        <span class="font-semibold text-red-600">#{{ $receta->getFolio() }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $receta->getFecha()->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <span class="text-red-600 font-semibold text-sm">
                                                    {{ strtoupper(substr($receta->getPaciente()->getNombre(), 0, 1)) }}
                                                </span>
                                            </div>
                                            {{ $receta->getPaciente()->getNombre() }}
                                        </div>
                                    </td>
                                    <td>{{ $receta->getCedulaDoctor() }}</td>
                                    <td>
                                        @php
                                            $estado = $receta->getEstado() ?? 'pendiente';
                                            $estadoSlug = strtolower(str_replace(' ', '-', $estado));
                                            $claseEstado = 'estado-' . $estadoSlug;
                                        @endphp
                                        <span class="estado-badge {{ $claseEstado }}" id="estado-{{ $receta->getFolio() }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-lg font-bold text-gray-800">${{ number_format($receta->getTotal(), 2) }}</span>
                                    </td>
                                </tr>
                                <tr class="detalle-receta hidden" id="detalle-{{ $receta->getFolio() }}">
                                    <td colspan="6" class="bg-red-50/50 p-0">
                                        <div class="p-6 fade-in" id="contenido-{{ $receta->getFolio() }}">
                                            <div class="loading-pulse text-center py-8 text-gray-500">
                                                <svg class="w-8 h-8 mx-auto mb-3 text-red-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Cargando detalles...
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay recetas pendientes</h3>
                    <p class="text-gray-500">No se encontraron recetas asignadas a esta sucursal.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const detallesCache = {};
    const estadosExpandidos = new Set();

    async function toggleDetalle(folio) {
        const detalleRow = document.getElementById(`detalle-${folio}`);
        const icon = document.getElementById(`icon-${folio}`);

        if (estadosExpandidos.has(folio)) {
            detalleRow.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-90');
            estadosExpandidos.delete(folio);
        } else {
            detalleRow.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-90');
            estadosExpandidos.add(folio);

            if (!detallesCache[folio]) {
                await cargarDetalleReceta(folio);
            }
        }
    }

    async function cargarDetalleReceta(folio) {
        const contenido = document.getElementById(`contenido-${folio}`);

        try {
            const response = await fetch(`/receta/detalle/${folio}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Error desconocido' }));
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Error al cargar detalles');
            }

            detallesCache[folio] = data.receta;
            mostrarDetalle(folio, data.receta);
        } catch (error) {
            console.error('Error:', error);
            contenido.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-red-600 font-medium">Error al cargar los detalles</p>
                    <p class="text-sm text-gray-500 mt-1">${error.message}</p>
                </div>
            `;
        }
    }

    function mostrarDetalle(folio, receta) {
        const contenido = document.getElementById(`contenido-${folio}`);

        const lineasHTML = receta.lineas.map(linea => `
            <div class="detalle-card">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-800 text-lg">${linea.medicamento}</h4>
                        <p class="text-sm text-gray-500">Cantidad: ${linea.cantidad} unidades</p>
                    </div>
                    <span class="text-xl font-bold text-green-600">$${linea.subtotal.toFixed(2)}</span>
                </div>
                <div class="space-y-2 mt-4">
                    ${linea.detalles.map(detalle => `
                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <span class="text-sm"><strong class="text-gray-700">Sucursal:</strong> ${detalle.sucursal}</span>
                                <span class="text-sm"><strong class="text-gray-700">Cantidad:</strong> ${detalle.cantidad}</span>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">${detalle.estatus}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');

        const puedeModificar = receta.estado !== 'Cancelada' && receta.estado !== 'Recolectada';

        contenido.innerHTML = `
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-red-200">
                <h3 class="text-xl font-bold text-gray-800">Detalles de Receta #${receta.folio}</h3>
                <div class="flex gap-3">
                    <button class="btn-action btn-lista" onclick="event.stopPropagation(); cambiarEstado(${folio}, 'Lista para recoger')" ${!puedeModificar ? 'disabled' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Lista para recoger
                    </button>
                    <button class="btn-action btn-recolectada" onclick="event.stopPropagation(); cambiarEstado(${folio}, 'Recolectada')" ${!puedeModificar ? 'disabled' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Recolectada
                    </button>
                    <button class="btn-action btn-devolver" onclick="event.stopPropagation(); devolverReceta(${folio})" ${!puedeModificar ? 'disabled' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Devolver
                    </button>
                </div>
            </div>
            <div class="space-y-4">
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

            const estadoElement = document.getElementById(`estado-${folio}`);
            const estadoSlug = nuevoEstado.toLowerCase().replace(/ /g, '-');
            estadoElement.className = `estado-badge estado-${estadoSlug}`;
            estadoElement.textContent = nuevoEstado;

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

            const estadoElement = document.getElementById(`estado-${folio}`);
            estadoElement.className = 'estado-badge estado-cancelada-por-no-recoger';
            estadoElement.textContent = 'Cancelada por no recoger';

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

