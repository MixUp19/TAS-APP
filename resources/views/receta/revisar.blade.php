<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Receta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Revisar Receta</h1>
    <p class="text-muted">Verifica que toda la información sea correcta antes de confirmar</p>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Información General</h5>
            <p><strong>Paciente:</strong> {{ $receta->getPaciente()->getNombre() }} {{ $receta->getPaciente()->getApellidoPaterno() }}</p>
            <p><strong>Cédula Doctor:</strong> {{ $receta->getCedulaDoctor() }}</p>
            <p><strong>Fecha:</strong> {{ $receta->getFecha()->format('d/m/Y') }}</p>
            <p><strong>Sucursal:</strong> {{ $receta->getSucursal()->getCadena()->getNombre() }} - {{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Medicamentos Solicitados</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Compuesto Activo</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receta->getLineasRecetas() as $linea)
                    <tr>
                        <td>{{ $linea->getMedicamento()->getNombre() }}</td>
                        <td>{{ $linea->getMedicamento()->getCompuestoActivo() }}</td>
                        <td>{{ $linea->getCantidad() }}</td>
                        <td>${{ number_format($linea->getMedicamento()->getPrecio(), 2) }}</td>
                        <td>${{ number_format($linea->getSubtotal(), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <h5>Total: ${{ number_format($receta->getTotal(), 2) }} MXN</h5>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route('receta.seleccionarMedicamentos') }}" class="btn btn-secondary">← Modificar Medicamentos</a>
        <form action="{{ route('receta.confirmar') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">Confirmar Receta</button>
        </form>
    </div>
</div>
</body>
</html>
