<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Receta - Paso 1</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 40px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1a202c; margin-bottom: 25px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; }

        input, select {
            width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; background-color: #fff;
        }
        input:focus, select:focus { outline: none; border-color: #3182ce; }

        .btn-next { width: 100%; background-color: #3182ce; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-next:hover { background-color: #2c5282; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="container">
    <x-paciente-nav titulo="Formulario de Receta" />

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
                        <option value="{{ $sucursal->getSucursalId() }},{{ $sucursal->getCadena()->getCadenaID() }}">{{ $texto }}</option>
                @endforeach

            </select>
        </div>

        <!-- 2. Cédula -->
        <div class="form-group">
            <label for="cedula">Cédula del Doctor</label>
            <input type="text" name="cedula" id="cedula" placeholder="Ej: 1755555555" required>
        </div>

        <!-- 3. Fecha -->
        <div class="form-group">
            <label for="fecha">Fecha de Emisión</label>
            <input type="date" name="fecha" id="fecha" value="{{ date('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn-next">Siguiente: Agregar Medicamentos &rarr;</button>
    </form>
</div>

</body>
</html>
