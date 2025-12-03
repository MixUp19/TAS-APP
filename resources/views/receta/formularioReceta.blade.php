<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Receta - Paso 1</title>
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

        .form-card {
            border: 1px solid hsla(190, 93%, 41%, 0.2);
            padding: 32px 36px;
            border-radius: 16px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            box-sizing: border-box;
            background-color: #fff;
            font-size: 15px;
            transition: all 0.25s ease;
        }

        .form-group input:hover,
        .form-group select:hover {
            border-color: hsla(190, 93%, 41%, 0.4);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: hsl(190, 93%, 41%);
            background-color: #fff;
            box-shadow: 0 0 0 4px hsla(190, 93%, 41%, 0.12);
        }

        .btn-next {
            width: 100%;
            background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%);
            color: white;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px hsla(190, 93%, 41%, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .btn-next:hover {
            background: linear-gradient(135deg, hsl(190, 93%, 48%) 0%, hsl(190, 93%, 35%) 100%);
            box-shadow: 0 6px 20px hsla(190, 93%, 41%, 0.45);
            transform: translateY(-2px);
        }

        .btn-next:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px hsla(190, 93%, 41%, 0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 min-h-screen">

    <div class="container mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <x-paciente-nav titulo="Formulario de Receta" />

            {{-- Breadcrumbs de progreso --}}
            <x-breadcrumbs 
                :steps="[
                    ['name' => 'Datos Generales'],
                    ['name' => 'Medicamentos'],
                    ['name' => 'Revisar'],
                    ['name' => 'Confirmar']
                ]"
                :currentStep="1"
            />

            <div class="flex justify-center mt-6">
                <div class="form-card w-full max-w-xl">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">
                        <span class="inline-flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: hsl(190, 93%, 41%);"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Nueva Receta
                        </span>
                    </h2>

                    <form action="{{ route('receta.guardarEncabezado') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="sucursal_id">Seleccionar Sucursal de Retiro</label>
                            <select name="sucursal_id" id="sucursal_id" required>
                                <option value="">-- Seleccione una opción --</option>
                                @foreach($sucursales as $sucursal)
                                    @php
                                        $nombreCadena = $sucursal->getCadena()->getNombre() ?? 'Cadena ' . $sucursal->getCadena()->getCadenaID();
                                        $direccion = $sucursal->getCalle() ?? '';
                                        if ($sucursal->getColonia()) {
                                            $direccion .= ($direccion ? ', ' : '') . $sucursal->getColonia();
                                        }
                                        $texto = "Cadena: {$nombreCadena} - Sucursal {$direccion}";
                                    @endphp
                                    <option
                                        value="{{ $sucursal->getSucursalId() }},{{ $sucursal->getCadena()->getCadenaID() }}">
                                        {{ $texto }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="cedula">Cédula del Doctor</label>
                            <input type="text" name="cedula" id="cedula" placeholder="Ej: 1755555555" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha de Emisión</label>
                            <input type="date" name="fecha" id="fecha" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <button type="submit" class="btn-next">
                            <span>Siguiente: Agregar Medicamentos</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div> 
    </div> 

</body>

</html>