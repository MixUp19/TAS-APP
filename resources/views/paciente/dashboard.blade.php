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

        {{-- Encabezado del Dashboard --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard - Paciente</h1>

            {{-- Formulario para Cerrar Sesión --}}
            <form action="{{ route('cerrar.sesion') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Cerrar Sesión
                </button>
            </form>
        </div>

        {{-- Mensaje de Éxito --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Información del Usuario (solo si hay sesión) --}}
        <div class="space-y-4">
            @if(Session::has('usuario'))
                <p class="text-gray-600">Bienvenido al sistema Te Acerco Salud</p>
                <p class="text-sm text-gray-500">Has iniciado sesión como Paciente</p>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h2 class="font-semibold text-gray-800 mb-2">Información del usuario:</h2>
                    <p class="text-gray-600"><strong>Nombre:</strong> {{ Session::get('usuario')->getNombre() }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ Session::get('usuario')->getEmail() }}</p>
                    <p class="text-gray-600"><strong>Teléfono:</strong> {{ Session::get('usuario')->getTelefono() }}</p>
                </div>
            @endif
        </div> {{-- Cierre de .space-y-4 --}}

    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}
</body>
</html>

