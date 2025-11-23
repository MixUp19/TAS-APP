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
        input { width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; }
        input:focus { outline: none; border-color: #3182ce; ring: 2px solid #3182ce; }
        
        .info-box { background-color: #ebf8ff; color: #2b6cb0; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #bee3f8; }
        
        .btn-next { width: 100%; background-color: #3182ce; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-next:hover { background-color: #2c5282; }
    </style>
</head>
<body>

<div class="container">
    <h2>Datos de la Receta</h2>

    <div class="info-box">
        <strong>Sucursal:</strong> {{ $sucursal->nombre }}
    </div>

    <form action="{{ route('receta.guardarEncabezado') }}" method="POST">
        @csrf
        
        <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">

        <div class="form-group">
            <label for="cedula">Cédula del Doctor</label>
            <input type="text" name="cedula" id="cedula" placeholder="Ej: 1755555555" required autofocus>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha de Emisión</label>
            <input type="date" name="fecha" id="fecha" value="{{ date('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn-next">Siguiente: Agregar Medicamentos &rarr;</button>
    </form>
</div>

</body>
</html>