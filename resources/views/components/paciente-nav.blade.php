@props(['titulo' => 'Dashboard - Paciente'])

<nav aria-label="Menú principal del dashboard" class="mb-6">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h1 class="text-3xl font-bold text-gray-800">{{ $titulo }}</h1>
        
        {{-- Búsqueda de Recetas por Folio --}}
        <form action="{{ route('receta.buscar') }}" method="GET" class="flex items-center gap-2">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Buscar
            </button>
        </form>
        
        {{-- Botones de Acción --}}
        <div class="flex items-center gap-3">
            <form action="{{ route('receta.mis-recetas') }}" method="GET">
                @csrf
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Mis Recetas
                </button>
            </form>

            {{-- Crear Receta --}}
            <form action="{{ route('receta.formulario') }}" method="GET">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Crear Receta
                </button>
            </form>
            
            {{-- Cerrar Sesión --}}
            <form action="{{ route('cerrar.sesion') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>
