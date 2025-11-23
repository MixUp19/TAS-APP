<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Receta - Paso 1</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1a202c; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 25px; }
        label.section-title { display: block; color: #4a5568; font-weight: 700; margin-bottom: 10px; font-size: 1.1em; }
        input[type="text"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; }
        
        .sucursales-grid {
            display: grid;
            grid-template-columns: 1fr; 
            gap: 15px;
            max-height: 300px; 
            overflow-y: auto;
            padding: 5px;
        }

        .sucursal-card {
            display: block;
            position: relative;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
        }

        .sucursal-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .sucursal-card:has(input:checked) {
            border-color: #3182ce;
            background-color: #ebf8ff;
            box-shadow: 0 0 0 1px #3182ce;
        }

        .card-header { font-weight: bold; color: #2d3748; display: flex; justify-content: space-between; }
        .card-body { font-size: 0.9em; color: #718096; margin-top: 5px; }
        .check-icon { display: none; color: #3182ce; font-weight: bold; }
        
        .sucursal-card:has(input:checked) .check-icon { display: block; }

        .btn-next { width: 100%; background-color: #3182ce; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: background 0.2s; }
        .btn-next:hover { background-color: #2c5282; }
    </style>
</head>
<body>

<div class="container">
    <h2>Datos de la Receta</h2>

    <form action="{{ route('receta.guardarEncabezado') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="section-title">1. Selecciona la Sucursal de Retiro:</label>
            
            <div class="sucursales-grid">
                @foreach($sucursales as $cadenaId => $sucursalesCadena)
                    @php
                        $primeraSucursal = $sucursalesCadena->first();
                        $nombreCadena = $primeraSucursal->cadena->CadenaNombre ?? 'Cadena ' . $cadenaId;
                    @endphp
                    
                    <div style="margin-top: 20px; margin-bottom: 10px;">
                        <h3 style="color: #2d3748; font-size: 1.1em; font-weight: 600; border-bottom: 2px solid #3182ce; padding-bottom: 5px;">
                            🏪 {{ $nombreCadena }}
                        </h3>
                    </div>
                    
                    @foreach($sucursalesCadena as $sucursal)
                    <label class="sucursal-card">
                        <input type="radio" name="sucursal_id" value="{{ $sucursal->SucursalID }}" required>
                        
                        <div class="card-header">
                            <span>Sucursal {{ $sucursal->SucursalID }}</span>
                            <span class="check-icon">✔</span>
                        </div>
                        <div class="card-body">
                            <div>📍 {{ $sucursal->SucursalCalle ?? 'Sin calle' }}</div>
                            <div>🏘️ {{ $sucursal->SucursalColonia ?? 'Sin colonia' }}</div>
                        </div>
                    </label>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Cédula -->
        <div class="form-group">
            <label class="section-title" for="cedula">2. Cédula del Doctor:</label>
            <input type="text" name="cedula" id="cedula" placeholder="Ej: 1755555555" required>
        </div>

        <!-- Fecha -->
        <div class="form-group">
            <label class="section-title" for="fecha">3. Fecha de Emisión:</label>
            <input type="date" name="fecha" id="fecha" value="{{ date('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn-next">Siguiente: Agregar Medicamentos &rarr;</button>
    </form>
</div>

</body>
</html>