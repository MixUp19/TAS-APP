<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta Confirmada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="text-center">
        <div class="alert alert-success" role="alert">
            <h1>¡Receta Confirmada!</h1>
            <p>Tu receta ha sido procesada exitosamente.</p>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Número de Folio</h5>
                <h2 class="text-primary">#{{ str_pad($folio, 6, '0', STR_PAD_LEFT) }}</h2>
            </div>
        </div>

       

        <div class="d-flex flex-column gap-3 mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">Volver al Inicio</a>
            <a href="{{ route('receta.formulario') }}" class="btn btn-secondary">Nueva Receta</a>
        </div>
    </div>
</div>
</body>
</html>
