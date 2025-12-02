@props(['titulo' => 'Dashboard - Farmaceutico'])

<nav aria-label="Menú principal del dashboard" class="mb-6">
    <div class="flex flex-wrap justify-between items-center gap-4">

        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            {{ $titulo }}
        </h1>
        
        <!-- BUSCADOR -->
        <form action="{{ route('farmaceutico.buscar') }}" method="GET" class="flex items-center gap-2">
            <label for="folio-search" class="sr-only">Buscar receta por folio</label>

            <input 
                type="text" 
                id="folio-search"
                name="folio" 
                placeholder="Buscar por folio..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"
                required
            >

            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-150 flex items-center gap-2"
            >
                <!-- ICONO BUSCAR -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.65 5.65a7.5 7.5 0 0 0 10.99 10.99Z" />
                </svg>

                Buscar
            </button>
        </form>

        <!-- BOTONES DERECHA -->
        <div class="flex items-center gap-3">

            <!-- RECETAS PENDIENTES -->
            <form action="{{ route('receta.indiceRecetas') }}" method="GET">
                @csrf
                <button 
                    type="submit" 
                    class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition duration-150 flex items-center gap-2"
                >
                    <!-- ICONO DOCUMENTOS -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                    </svg>

                    Recetas Pendientes
                </button>
            </form>
            
            <!-- CERRAR SESIÓN -->
            <form action="{{ route('cerrar.sesion') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-150 flex items-center gap-2"
                >
                    <!-- ICONO LOGOUT -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>

                    Cerrar Sesión
                </button>
            </form>

        </div>
    </div>
</nav>