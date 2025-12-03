<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Receta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: 'hsl(190, 93%, 41%)',
                            dark: 'hsl(190, 93%, 32%)',
                            light: 'hsl(190, 93%, 50%)',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        
        /* Card interna */
        .inner-card {
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f7fa 50%, #e6fffa 100%);
            border: 1px solid hsla(190, 93%, 41%, 0.2);
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
            background: linear-gradient(135deg, hsl(190, 93%, 97%) 0%, hsl(190, 50%, 95%) 100%);
            color: hsl(190, 93%, 30%);
            font-weight: 600;
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
            border-bottom: 2px solid hsla(190, 93%, 41%, 0.2);
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
            background: hsla(190, 93%, 41%, 0.04);
        }
        
        .custom-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }
        
        .custom-table tfoot td {
            padding: 16px;
            font-weight: 600;
            background: linear-gradient(135deg, hsl(190, 93%, 97%) 0%, hsl(190, 50%, 95%) 100%);
        }
        
        .custom-table tfoot td:last-child {
            border-radius: 0 0 10px 0;
        }
        
        .custom-table tfoot td:first-child {
            border-radius: 0 0 0 10px;
        }
        
        /* Botón primario */
        .btn-primary-custom {
            background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px hsla(190, 93%, 41%, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, hsl(190, 93%, 48%) 0%, hsl(190, 93%, 35%) 100%);
            box-shadow: 0 6px 20px hsla(190, 93%, 41%, 0.45);
            transform: translateY(-2px);
        }
        
        .btn-primary-custom:active {
            transform: translateY(0);
        }
        
        /* Info item */
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid hsla(190, 93%, 41%, 0.1);
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-icon {
            width: 36px;
            height: 36px;
            background: hsla(190, 93%, 41%, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .info-icon svg {
            width: 20px;
            height: 20px;
            color: hsl(190, 93%, 38%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 min-h-screen">

{{-- Contenedor principal --}}
<div class="container mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-xl p-8">

        {{-- Menú de Navegación Principal --}}
        <x-paciente-nav titulo="Revisar Receta" />

        {{-- Breadcrumbs de progreso --}}
        <x-breadcrumbs 
            :steps="[
                ['name' => 'Datos Generales'],
                ['name' => 'Medicamentos'],
                ['name' => 'Revisar'],
                ['name' => 'Confirmar']
            ]"
            :currentStep="3"
        />

        {{-- Subtítulo --}}
        <p class="text-gray-500 mt-2 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Verifica que toda la información sea correcta antes de confirmar
        </p>

        {{-- Grid de información --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            {{-- Card: Información General --}}
            <div class="inner-card">
                <h5 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Información General
                </h5>
                
                <div class="info-item">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Paciente</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getPaciente()->getNombre() }} {{ $receta->getPaciente()->getApellidoPaterno() }}</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Cédula Doctor</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getCedulaDoctor() }}</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Fecha</p>
                        <p class="text-gray-800 font-semibold">{{ $receta->getFecha()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Card: Sucursal de Retiro --}}
            <div class="inner-card">
                <h5 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Sucursal de Retiro
                </h5>
                
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background: hsla(190, 93%, 41%, 0.15);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 38%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">{{ $receta->getSucursal()->getCadena()->getNombre() }}</p>
                            <p class="text-gray-600 mt-1">{{ $receta->getSucursal()->getCalle() }}</p>
                            <p class="text-gray-500 text-sm">{{ $receta->getSucursal()->getColonia() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Medicamentos Solicitados --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Medicamentos Solicitados
                </h5>
            </div>
            
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Compuesto Activo</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-right">Precio Unit.</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receta->getLineasRecetas() as $linea)
                        <tr>
                            <td class="font-medium text-gray-800">{{ $linea->getMedicamento()->getNombre() }}</td>
                            <td>{{ $linea->getMedicamento()->getCompuestoActivo() }}</td>
                            <td class="text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full font-semibold text-gray-700">
                                    {{ $linea->getCantidad() }}
                                </span>
                            </td>
                            <td class="text-right">${{ number_format($linea->getMedicamento()->getPrecio(), 2) }}</td>
                            <td class="text-right font-semibold" style="color: hsl(190, 93%, 35%);">${{ number_format($linea->getSubtotal(), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right text-gray-700">Total a Pagar:</td>
                            <td class="text-right text-xl" style="color: hsl(190, 93%, 35%);">
                                ${{ number_format($receta->getTotal(), 2) }} <span class="text-sm text-gray-500">MXN</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Botón de confirmar --}}
        <div class="flex justify-end">
            <form action="{{ route('receta.confirmar') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Confirmar Receta</span>
                </button>
            </form>
        </div>

    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}

</body>
</html>
