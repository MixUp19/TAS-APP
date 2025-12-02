<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta Confirmada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

<div class="container mx-auto mt-8">
    <x-paciente-nav titulo="Receta Confirmada" />
    <div class="max-w-3xl mx-auto mt-8">
        <div class="flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-lg">Tu receta ha sido procesada exitosamente.</p>
        </div>
    </div>
    <div class="max-w-xl mx-auto mt-6">
        <div class="bg-white shadow-md rounded-xl p-6 text-center border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-blue-500 mb-3"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-6a2 2 0 012-2h6" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 7h6m0 0v6m0-6l-8 8" />
            </svg>
            <h5 class="text-xl font-semibold mb-2 text-gray-700">Número de Folio</h5>
            <h2 class="text-3xl font-bold text-blue-600">
                #{{ str_pad($folio, 6, '0', STR_PAD_LEFT) }}
            </h2>
        </div>
    </div>
</div>
</body>
</html>

