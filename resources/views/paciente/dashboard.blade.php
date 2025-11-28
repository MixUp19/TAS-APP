<!DOCTYPE html>
</html>
</body>
    </div>
        </div>
            </div>
                @endif
                    </div>
                        <p class="text-gray-600"><strong>Teléfono:</strong> {{ Session::get('usuario')->getTelefono() }}</p>
                        <p class="text-gray-600"><strong>Email:</strong> {{ Session::get('usuario')->getEmail() }}</p>
                        <p class="text-gray-600"><strong>Nombre:</strong> {{ Session::get('usuario')->getNombre() }}</p>
                        <h2 class="font-semibold text-gray-800 mb-2">Información del usuario:</h2>
                    <div class="bg-blue-50 p-4 rounded-lg">
                @if(Session::has('usuario'))

                <p class="text-sm text-gray-500">Has iniciado sesión como Paciente</p>
                <p class="text-gray-600">Bienvenido al sistema Te Acerco Salud</p>
            <div class="space-y-4">

            @endif
                </div>
                    <p class="text-green-700">{{ session('success') }}</p>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            @if(session('success'))

            </div>
                </form>
                    </button>
                        Cerrar Sesión
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    @csrf
                <form action="{{ route('cerrar.sesion') }}" method="POST">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard - Paciente</h1>
            <div class="flex justify-between items-center mb-6">
        <div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="container mx-auto p-8">
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
</head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard - Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
<head>
<html lang="es">

