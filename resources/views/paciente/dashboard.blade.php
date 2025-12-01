<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paciente</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
<div class="container mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-xl p-8">

        {{-- Menú de Navegación Principal --}}
        <x-paciente-nav titulo="Dashboard - Paciente" />

        {{-- Mensaje de Éxito --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif
        
        {{-- Resultados: Todas las Recetas o Búsqueda Específica --}}
        @if(isset($recetas) && count($recetas) > 0)
            {{-- Mostrar TODAS las recetas del paciente --}}
            <section aria-label="Mis recetas" class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Mis Recetas</h2>
                    <span class="text-sm text-gray-600">Total: <strong>{{ count($recetas) }}</strong> receta(s)</span>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recetas as $recetaItem)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
                            {{-- Encabezado de la Card --}}
                            <div class="flex justify-between items-start mb-3 pb-3 border-b border-blue-200">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Receta</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <span class="font-medium">Folio:</span> 
                                        <span class="text-blue-600 font-bold">{{ $recetaItem->getFolio() }}</span>
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                    Activa
                                </span>
                            </div>

                            {{-- Información Resumida --}}
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span><strong>Fecha:</strong> {{ $recetaItem->getFecha() }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    <span><strong>Medicamentos:</strong> {{ count($recetaItem->getLineasRecetas()) }}</span>
                                </div>
                            </div>

                            {{-- Botón Ver Detalles --}}
                            <a href="{{ route('receta.detalle', ['folio' => $recetaItem->getFolio()]) }}" 
                               class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition duration-150 text-sm font-medium">
                                Ver Detalles
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @elseif(isset($receta))
            {{-- Mostrar UNA SOLA receta (búsqueda por folio) --}}
            <section aria-label="Resultados de búsqueda" class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Resultados de la Búsqueda</h2>
                
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-md">
                    {{-- Encabezado de la Receta --}}
                    <div class="flex justify-between items-start mb-4 pb-4 border-b border-blue-200">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Receta Encontrada</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Folio:</span> 
                                <span class="text-blue-600 font-bold">{{ $receta->getFolio() }}</span>
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            Activa
                        </span>
                    </div>

                    {{-- Información del Paciente --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información del Paciente
                            </h4>
                            <p class="text-gray-600 text-sm"><strong>Nombre:</strong> {{ $receta->getPaciente()->getNombre() }}</p>
                            <p class="text-gray-600 text-sm"><strong>Email:</strong> {{ $receta->getPaciente()->getEmail() }}</p>
                            <p class="text-gray-600 text-sm"><strong>Teléfono:</strong> {{ $receta->getPaciente()->getTelefono() }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Detalles de la Receta
                            </h4>
                            <p class="text-gray-600 text-sm"><strong>Fecha:</strong> {{ $receta->getFecha() }}</p>
                            <p class="text-gray-600 text-sm"><strong>Medicamentos:</strong> {{ count($receta->getLineasRecetas()) }}</p>
                        </div>
                    </div>

                    {{-- Lista de Medicamentos --}}
                    @if(count($receta->getLineasRecetas()) > 0)
                        <div class="bg-white rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                Medicamentos Prescritos
                            </h4>
                            <ul class="space-y-2">
                                @foreach($receta->getLineasRecetas() as $linea)
                                    <li class="flex items-start gap-2 text-sm text-gray-700 bg-gray-50 p-2 rounded">
                                        <span class="text-blue-500 font-bold">•</span>
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
                           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-150 flex items-center gap-2">
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
            <section aria-label="Sin recetas" class="mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="text-blue-700 font-semibold text-lg">No tienes recetas registradas</p>
                            <p class="text-blue-600 text-sm">Comienza creando tu primera receta usando el botón "Crear Receta"</p>
                        </div>
                    </div>
                </div>
            </section>
        @elseif(request()->has('folio'))
            {{-- Mensaje cuando no se encuentra la receta buscada --}}
            <section aria-label="Sin resultados" class="mb-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-yellow-700 font-semibold">No se encontró ninguna receta</p>
                            <p class="text-yellow-600 text-sm">No existe una receta con el folio: <strong>{{ request('folio') }}</strong></p>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}
</body>
</html>

