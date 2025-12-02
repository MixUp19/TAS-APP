<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Receta - Folio #{{ $receta->getFolio() }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { 
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        
        /* Card interna */
        .inner-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
        }
        
        /* Tabla personalizada */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table thead th {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            font-weight: 600;
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
        }
        
        .custom-table thead th:first-child {
            border-radius: 10px 0 0 0;
        }
        
        .custom-table thead th:last-child {
            border-radius: 0 10px 0 0;
        }
        
        .custom-table tbody tr {
            transition: background 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background: #f1f5f9;
        }
        
        .custom-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #374151;
        }
        
        /* Botones de acción */
        .btn-action {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-lista {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-lista:hover:not(:disabled) {
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-recolectada {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-recolectada:hover:not(:disabled) {
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-devolver {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .btn-devolver:hover:not(:disabled) {
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* Badge sucursal */
        .branch-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin: 2px;
        }
        
        .branch-badge .order-num {
            background: white;
            color: #334155;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
        }
        
        /* Lista de orden de visita */
        .visit-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: white;
            border-radius: 12px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .visit-item:hover {
            border-color: #94a3b8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .visit-item.start {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-color: rgba(16, 185, 129, 0.3);
        }
        
        .step-badge {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .step-number {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
        }
        
        /* Mapa */
        #map {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-100 to-slate-200 min-h-screen">

{{-- Contenedor principal --}}
<div class="container mx-auto p-8">
    <div class="bg-white/80 backdrop-blur-xl border border-white/60 shadow-2xl rounded-3xl p-8 fade-in">

        {{-- Mostrar navegación según el tipo de usuario --}}
        @if(Session::has('usuario'))
            @if(Session::get('tipo_usuario') === 'paciente')
                <x-paciente-nav titulo="Detalle de Receta" />
            @else
                <x-farmaceutico-nav titulo="Detalle de Receta" />
            @endif
        @endif

        {{-- Información del folio y estado --}}
        <div class="mt-4 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-gray-500">Folio:</span>
                <span class="text-xl font-bold text-slate-700">#{{ str_pad($receta->getFolio(), 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-500">Estado:</span>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">
                    {{ $receta->getEstado() }}
                </span>
            </div>
        </div>

        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="mt-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Información General --}}
        <div class="inner-card mt-6">
            <h5 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Información General
            </h5>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Paciente</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getPaciente()->getNombre() }} {{ $receta->getPaciente()->getApellidoPaterno() }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Cédula Doctor</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getCedulaDoctor() }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Fecha</p>
                        <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($receta->getFecha())->format('d/m/Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Sucursal Principal</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getSucursal()->getCadena()->getNombre() }}</p>
                        <p class="text-gray-500 text-sm">{{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Medicamentos y Ruta --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mt-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Medicamentos y Ruta de Recolección
                </h5>
            </div>
            
            @php
                $lineas = $receta->getLineasRecetas();
            @endphp
            
            @if(count($lineas) === 0)
                <div class="p-6">
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                        <p class="text-amber-700 font-medium">No hay líneas de receta para mostrar.</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>Compuesto Activo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                                <th>Sucursales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $routeOrder = 1; @endphp
                            @foreach($lineas as $linea)
                            <tr>
                                <td class="font-semibold text-gray-800">{{ $linea->getMedicamento()->getNombre() }}</td>
                                <td class="text-gray-500 text-sm">{{ $linea->getMedicamento()->getCompuestoActivo() }}</td>
                                <td class="text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 rounded-full font-semibold text-slate-700">
                                        {{ $linea->getCantidad() }}
                                    </span>
                                </td>
                                <td class="text-right">${{ number_format($linea->getMedicamento()->getPrecio(), 2) }}</td>
                                <td class="text-right font-semibold text-slate-700">${{ number_format($linea->getSubtotal(), 2) }}</td>
                                <td>
                                    @if(count($linea->getDetalleLineaReceta()) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($linea->getDetalleLineaReceta() as $detalle)
                                                <span class="branch-badge">
                                                    <span class="order-num">{{ $routeOrder++ }}</span>
                                                    {{ $detalle->getSucursal()->getCadena()->getNombre() }} - {{ $detalle->getSucursal()->getCalle() }}
                                                    ({{ $detalle->getCantidad() }} ud.)
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-slate-50 flex justify-end">
                    <div class="text-right">
                        <span class="text-gray-500 text-sm">Total:</span>
                        <span class="text-2xl font-bold ml-2 text-slate-800">${{ number_format($receta->getTotal(), 2) }} <span class="text-sm text-gray-500">MXN</span></span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Orden de Visita --}}
        <div class="inner-card mt-6">
            <h5 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                Orden de Visita a Sucursales
            </h5>
            
            @php
                $visitedBranches = [];
                $stepNumber = 1;
            @endphp
            
            {{-- Punto de inicio --}}
            <div class="visit-item start">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="text-lg">🏁</span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Inicio - {{ $receta->getSucursal()->getCadena()->getNombre() }}</p>
                        <p class="text-gray-500 text-sm">{{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Punto de Inicio</span>
            </div>

            @foreach($receta->getLineasRecetas() as $linea)
                @foreach($linea->getDetalleLineaReceta() as $detalle)
                    @php
                        $branchKey = $detalle->getSucursal()->getSucursalId();
                        if (in_array($branchKey, $visitedBranches)) continue;
                        $visitedBranches[] = $branchKey;
                    @endphp
                    <div class="visit-item">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white step-number">
                                {{ $stepNumber }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $detalle->getSucursal()->getCadena()->getNombre() }}</p>
                                <p class="text-gray-500 text-sm">{{ $detalle->getSucursal()->getCalle() }}, {{ $detalle->getSucursal()->getColonia() }}</p>
                            </div>
                        </div>
                        <span class="step-badge">Parada {{ $stepNumber++ }}</span>
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- Acciones (solo para farmacéutico) --}}
        @if(Session::get('tipo_usuario') !== 'paciente')
            <div class="inner-card mt-6">
                <h5 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Acciones
                </h5>
                
                <div class="flex flex-wrap gap-4">
                    <form action="{{ route('receta.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="folio" value="{{ $receta->getFolio() }}">
                        <input type="hidden" name="estado" value="Lista para recoger">
                        <button type="submit" class="btn-action btn-lista"
                            {{ !($receta->getEstado() !== 'Cancelada por no recoger' && $receta->getEstado() !== 'Recolectada') ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Lista para recoger
                        </button>
                    </form>
                    
                    <form action="{{ route('receta.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="folio" value="{{ $receta->getFolio() }}">
                        <input type="hidden" name="estado" value="Recolectada">
                        <button type="submit" class="btn-action btn-recolectada"
                            {{ !($receta->getEstado() !== 'Cancelada por no recoger' && $receta->getEstado() !== 'Recolectada') ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Recolectada
                        </button>
                    </form>
                    
                    <form action="{{ route('receta.cancelar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="folio" value="{{ $receta->getFolio() }}">
                        <button type="submit" class="btn-action btn-devolver"
                            {{ !($receta->getEstado() !== 'Cancelada por no recoger' && $receta->getEstado() !== 'Recolectada') ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Devolver
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Mapa de Ruta --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mt-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    Mapa de Ruta Optimizada
                </h5>
            </div>
            <div class="p-4">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('map').setView([{{ $receta->getSucursal()->getLatitud() }}, {{ $receta->getSucursal()->getLongitud() }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Collect all unique branches
    const branches = [];

    // Add starting branch
    branches.push({
        lat: {{ $receta->getSucursal()->getLatitud() }},
        lng: {{ $receta->getSucursal()->getLongitud() }},
        name: '{{ $receta->getSucursal()->getCadena()->getNombre() }}',
        address: '{{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}',
        isStart: true
    });

    // Add branches from line details
    @php $addedBranches = []; @endphp
    @foreach($receta->getLineasRecetas() as $linea)
        @foreach($linea->getDetalleLineaReceta() as $detalle)
            @php
                $branchKey = $detalle->getSucursal()->getSucursalId() . '-' . $detalle->getSucursal()->getCadena()->getCadenaId();
            @endphp
            @if(!isset($addedBranches[$branchKey]))
                @php $addedBranches[$branchKey] = true; @endphp
                branches.push({
                    lat: {{ $detalle->getSucursal()->getLatitud() }},
                    lng: {{ $detalle->getSucursal()->getLongitud() }},
                    name: '{{ $detalle->getSucursal()->getCadena()->getNombre() }}',
                    address: '{{ $detalle->getSucursal()->getCalle() }}, {{ $detalle->getSucursal()->getColonia() }}',
                    isStart: false
                });
            @endif
        @endforeach
    @endforeach

    // Add markers with neutral colors
    branches.forEach((branch, index) => {
        const size = branch.isStart ? 48 : 32;
        const color = branch.isStart ? '#10b981' : '#475569';

        const icon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="background: ${color}; color: white; width: ${size}px; height: ${size}px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">${branch.isStart ? '🏁' : index}</div>`,
            iconSize: [size, size],
            iconAnchor: [size / 2, size / 2]
        });

        L.marker([branch.lat, branch.lng], { icon })
            .addTo(map)
            .bindPopup(`<b>${branch.name}</b><br>${branch.address}`);
    });

    // Draw route line with neutral color
    if (branches.length > 1) {
        const routeCoords = branches.map(b => [b.lat, b.lng]);
        routeCoords.push([branches[0].lat, branches[0].lng]);
        
        L.polyline(routeCoords, {
            color: '#475569',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
    }

    // Fit map to show all markers
    if (branches.length > 0) {
        const bounds = L.latLngBounds(branches.map(b => [b.lat, b.lng]));
        map.fitBounds(bounds, { padding: [50, 50] });
    }
</script>
</body>
</html>
