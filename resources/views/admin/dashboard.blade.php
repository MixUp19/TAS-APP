<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Farmaceutico</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-100 via-pink-100 to-purple-200 min-h-screen">

    <div class="container mx-auto p-8">

        <!-- CONTENEDOR PRINCIPAL -->
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 shadow-2xl rounded-3xl p-8">

            <!-- NAV — NO SE TOCA -->
            <x-farmaceutico-nav titulo="Dashboard - Farmaceutico" />

            <!-- ALERTA DE ÉXITO -->
            @if(session('success'))
                <div class="mt-6 mb-6 bg-green-50 border-l-4 border-green-600 p-4 rounded-lg shadow-md">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- CONTENIDO DEL DASHBOARD -->
            <div class="mt-4 space-y-6">

                <p class="text-gray-700 text-lg">
                    Bienvenido al panel de administración de sucursal
                </p>

                <p class="text-sm text-gray-500">
                    Has iniciado sesión como Farmacéutico de Sucursal
                </p>

                @if(Session::has('usuario'))
                    <!-- CARD DEL FARMACÉUTICO -->
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-xl 
                                p-6 flex flex-col items-center text-center 
                                hover:shadow-2xl transition-all duration-300">

                        <!-- ICONO -->
                        <div class="w-20 h-20 bg-purple-200 rounded-full flex items-center justify-center 
                                    shadow-md mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-purple-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118A7.5 7.5 0 0 1 12 15a7.5 7.5 0 0 1 7.5 5.118"/>
                            </svg>
                        </div>

                        <!-- NOMBRE -->
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ Session::get('usuario')->getNombre() }}
                            {{ Session::get('usuario')->getApellidoPaterno() }}
                        </h2>

                        <!-- SUCURSAL -->
                        <p class="text-gray-600 mt-1">
                            <strong class="font-medium">Sucursal:</strong>
                            {{ Session::get('usuario')->getSucursal()->getSucursalId() }}
                        </p>

                    </div>
                @endif

            </div>

        </div>
    </div>

</body>
</html>