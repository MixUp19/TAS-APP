<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Farmaceutico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-pink-100 min-h-screen">
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
        <x-farmaceutico-nav titulo="Dashboard - Farmaceutico" />

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="space-y-4">
                <p class="text-gray-600">Bienvenido al panel de administración de sucursal</p>
                <p class="text-sm text-gray-500">Has iniciado sesión como Farmaceutico de Sucursal</p>

                @if(Session::has('usuario'))
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h2 class="font-semibold text-gray-800 mb-2">Información del farmaceutico:</h2>
                        <p class="text-gray-600"><strong>Nombre:</strong> {{ Session::get('usuario')->getNombre() }} {{ Session::get('usuario')->getApellidoPaterno() }}</p>
                        <p class="text-gray-600"><strong>Sucursal:</strong> {{ Session::get('usuario')->getSucursal()->getSucursalId() }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</body>
</html>

