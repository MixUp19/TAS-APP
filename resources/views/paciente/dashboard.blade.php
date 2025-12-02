<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paciente</title>
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
        
        /* Card de receta */
        .receta-card {
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f7fa 50%, #e6fffa 100%);
            border: 1px solid hsla(190, 93%, 41%, 0.2);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .receta-card:hover {
            box-shadow: 0 8px 25px hsla(190, 93%, 41%, 0.15);
            transform: translateY(-2px);
        }
        
        /* Botón primario */
        .btn-primary-custom {
            background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px hsla(190, 93%, 41%, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, hsl(190, 93%, 48%) 0%, hsl(190, 93%, 35%) 100%);
            box-shadow: 0 6px 16px hsla(190, 93%, 41%, 0.4);
            transform: translateY(-2px);
        }
        
        /* Info card interna */
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid hsla(190, 93%, 41%, 0.1);
        }
        
        /* Medicamento item */
        .med-item {
            background: hsla(190, 93%, 41%, 0.05);
            border-radius: 8px;
            padding: 10px 12px;
            transition: background 0.2s ease;
        }
        
        .med-item:hover {
            background: hsla(190, 93%, 41%, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 min-h-screen">
<div class="container mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-xl p-8">

        {{-- Menú de Navegación Principal --}}
        <x-paciente-nav titulo="Dashboard - Paciente" />

        {{-- Mensaje de Éxito --}}
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
        
        {{-- Resultados: Todas las Recetas o Búsqueda Específica --}}
        @if(isset($recetas) && count($recetas) > 0)
            {{-- Mostrar TODAS las recetas del paciente --}}
            <section aria-label="Mis recetas" class="mt-6">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Mis Recetas
                    </h2>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Total: <strong class="text-gray-700">{{ count($recetas) }}</strong> receta(s)
                    </span>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($recetas as $recetaItem)
                        <div class="receta-card">
                            {{-- Encabezado de la Card --}}
                            <div class="flex justify-between items-start mb-3 pb-3 border-b border-gray-200">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Receta</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <span class="font-medium">Folio:</span> 
                                        <span class="font-bold" style="color: hsl(190, 93%, 38%);">{{ $recetaItem->getFolio() }}</span>
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                    Activa
                                </span>
                            </div>

                            {{-- Información Resumida --}}
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span><strong>Fecha:</strong> {{ $recetaItem->getFecha() }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    <span><strong>Medicamentos:</strong> {{ count($recetaItem->getLineasRecetas()) }}</span>
                                </div>
                            </div>

                            {{-- Botón Ver Detalles --}}
                            <a href="{{ route('receta.detalle', ['folio' => $recetaItem->getFolio()]) }}" 
                               class="btn-primary-custom w-full text-center">
                                Ver Detalles
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @elseif(isset($receta))
            {{-- Mostrar UNA SOLA receta (búsqueda por folio) --}}
            <section aria-label="Resultados de búsqueda" class="mt-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Resultados de la Búsqueda
                </h2>
                
                <div class="receta-card p-6">
                    {{-- Encabezado de la Receta --}}
                    <div class="flex justify-between items-start mb-5 pb-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Receta Encontrada</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Folio:</span> 
                                <span class="font-bold" style="color: hsl(190, 93%, 38%);">{{ $receta->getFolio() }}</span>
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                            Activa
                        </span>
                    </div>

                    {{-- Información del Paciente --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-5">
                        <div class="info-card">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información del Paciente
                            </h4>
                            <div class="space-y-1">
                                <p class="text-gray-600 text-sm"><strong>Nombre:</strong> {{ $receta->getPaciente()->getNombre() }}</p>
                                <p class="text-gray-600 text-sm"><strong>Email:</strong> {{ $receta->getPaciente()->getEmail() }}</p>
                                <p class="text-gray-600 text-sm"><strong>Teléfono:</strong> {{ $receta->getPaciente()->getTelefono() }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Detalles de la Receta
                            </h4>
                            <div class="space-y-1">
                                <p class="text-gray-600 text-sm"><strong>Fecha:</strong> {{ $receta->getFecha() }}</p>
                                <p class="text-gray-600 text-sm"><strong>Medicamentos:</strong> {{ count($receta->getLineasRecetas()) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lista de Medicamentos --}}
                    @if(count($receta->getLineasRecetas()) > 0)
                        <div class="info-card mb-5">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                Medicamentos Prescritos
                            </h4>
                            <ul class="space-y-2">
                                @foreach($receta->getLineasRecetas() as $linea)
                                    <li class="med-item flex items-start gap-2 text-sm text-gray-700">
                                        <span class="font-bold" style="color: hsl(190, 93%, 41%);">•</span>
                                        <div>
                                            <span class="font-medium">{{ $linea->getMedicamento()->getNombre() }}</span>
                                            <span class="text-gray-500">- Cantidad: {{ $linea->getCantidad() }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Botón para Ver Detalles Completos --}}
                    <div class="flex justify-end">
                        <a href="{{ route('receta.detalle', ['folio' => $receta->getFolio()]) }}" 
                           class="btn-primary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver Detalles Completos
                        </a>
                    </div>
                </div>
            </section>
        @elseif(isset($recetas) && count($recetas) === 0)
            {{-- Mensaje cuando no hay recetas --}}
            <section aria-label="Sin recetas" class="mt-6">
                <div class="bg-gradient-to-r from-cyan-50 to-teal-50 border-l-4 p-6 rounded-r-xl" style="border-color: hsl(190, 93%, 41%);">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0" style="background: hsla(190, 93%, 41%, 0.15);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" style="color: hsl(190, 93%, 38%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-lg" style="color: hsl(190, 93%, 32%);">No tienes recetas registradas</p>
                            <p class="text-sm mt-1" style="color: hsl(190, 93%, 38%);">Comienza creando tu primera receta usando el botón "Crear Receta"</p>
                        </div>
                    </div>
                </div>
            </section>
        @elseif(request()->has('folio'))
            {{-- Mensaje cuando no se encuentra la receta buscada --}}
            <section aria-label="Sin resultados" class="mt-6">
                <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-amber-800 font-bold">No se encontró ninguna receta</p>
                            <p class="text-amber-600 text-sm mt-1">No existe una receta con el folio: <strong>{{ request('folio') }}</strong></p>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}
</body>
</html>

