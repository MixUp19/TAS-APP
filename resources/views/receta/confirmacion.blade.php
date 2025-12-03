<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta Confirmada</title>
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
        
        /* Animación de check */
        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-check {
            animation: checkmark 0.5s ease-out forwards;
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }
        
        .folio-card {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.5s;
            opacity: 0;
        }
        
        /* Efecto de brillo */
        .shine-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                transparent 0%,
                rgba(255,255,255,0.3) 50%,
                transparent 100%
            );
            transform: rotate(30deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(30deg); }
            100% { transform: translateX(100%) rotate(30deg); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 min-h-screen">

{{-- Contenedor principal --}}
<div class="container mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-xl p-8">

        {{-- Menú de Navegación Principal --}}
        <x-paciente-nav titulo="Receta Confirmada" />

        {{-- Breadcrumbs de progreso --}}
        <x-breadcrumbs 
            :steps="[
                ['name' => 'Datos Generales'],
                ['name' => 'Medicamentos'],
                ['name' => 'Revisar'],
                ['name' => 'Confirmar']
            ]"
            :currentStep="4"
        />

        {{-- Contenido centrado --}}
        <div class="max-w-2xl mx-auto mt-8">
            
            {{-- Mensaje de éxito --}}
            <div class="animate-fade-in flex items-center gap-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-emerald-800 px-6 py-5 rounded-xl shadow-sm">
                <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 animate-check">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-emerald-800">¡Receta procesada exitosamente!</h3>
                    <p class="text-emerald-600 text-sm mt-1">Tu receta ha sido registrada y está lista para ser surtida.</p>
                </div>
            </div>
            
            {{-- Card del folio --}}
            <div class="folio-card mt-8">
                <div class="bg-gradient-to-br from-white to-cyan-50 shadow-lg rounded-2xl p-8 text-center border border-gray-100 shine-effect">
                    <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-5" style="background: linear-gradient(135deg, hsla(190, 93%, 41%, 0.15) 0%, hsla(190, 93%, 41%, 0.25) 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" style="color: hsl(190, 93%, 38%);"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    
                    <p class="text-xs uppercase tracking-widest font-semibold text-gray-500 mb-2">Número de Folio</p>
                    <h2 class="text-4xl font-bold mb-4" style="color: hsl(190, 93%, 38%);">
                        #{{ str_pad($folio, 6, '0', STR_PAD_LEFT) }}
                    </h2>
                    
                    <p class="text-gray-500 text-sm">
                        Guarda este número para consultar el estado de tu receta
                    </p>
                    
                    {{-- Botón para ir a mis recetas --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('receta.mis-recetas') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:transform hover:-translate-y-1"
                           style="background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%); box-shadow: 0 4px 15px hsla(190, 93%, 41%, 0.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            Ver Mis Recetas
                        </a>
                    </div>
                </div>
            </div>
            
        </div>

    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}

</body>
</html>

