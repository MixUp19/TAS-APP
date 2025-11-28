<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Te Acerco Salud</h1>
            <p class="text-gray-600 mt-2">Bienvenido {{ $usuario->getCorreo() }}</p>
            <a href="{{ route('logout') }}" class="text-sm text-red-600 hover:underline mt-2 inline-block">Cerrar Sesión</a>
        </div>
</body>
</html>
