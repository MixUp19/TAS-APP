<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Receta - Folio #{{ $receta->getFolio() }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
<div class="container mt-4">
    <h1>Detalle de Receta</h1>
    <p class="text-muted">Folio: <strong>#{{ str_pad($receta->getFolio(), 6, '0', STR_PAD_LEFT) }}</strong> | Estado: <span class="badge bg-success">{{ $receta->getEstado() }}</span></p>

    <!-- Información General -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"> Información General</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Paciente:</strong> {{ $receta->getPaciente()->getNombre() }} {{ $receta->getPaciente()->getApellidoPaterno() }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Cédula Doctor:</strong> {{ $receta->getCedulaDoctor() }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($receta->getFecha())->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Sucursal Principal:</strong> {{ $receta->getSucursal()->getCadena()->getNombre() }} - {{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicamentos y Ruta -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"> Medicamentos y Ruta de Recolección</h5>
            
            @php
                $lineas = $receta->getLineasRecetas();
            @endphp
            
            @if(count($lineas) === 0)
                <div class="alert alert-warning">No hay líneas de receta para mostrar.</div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Compuesto Activo</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                            <th>Sucursales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $routeOrder = 1; @endphp
                        @foreach($lineas as $linea)
                        <tr>
                            <td><strong>{{ $linea->getMedicamento()->getNombre() }}</strong></td>
                            <td><small class="text-muted">{{ $linea->getMedicamento()->getCompuestoActivo() }}</small></td>
                            <td>{{ $linea->getCantidad() }}</td>
                            <td>${{ number_format($linea->getMedicamento()->getPrecio(), 2) }}</td>
                            <td><strong>${{ number_format($linea->getSubtotal(), 2) }}</strong></td>
                            <td>
                                @if(count($linea->getDetalleLineaReceta()) > 0)
                                    @foreach($linea->getDetalleLineaReceta() as $detalle)
                                        <span class="badge bg-success me-1">
                                            <span class="badge bg-light text-dark">{{ $routeOrder++ }}</span>
                                            {{ $detalle->getSucursal()->getCadena()->getNombre() }} - {{ $detalle->getSucursal()->getCalle() }}
                                            ({{ $detalle->getCantidad() }} ud.)
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="text-end mt-3">
                    <h5>Total: <strong>${{ number_format($receta->getTotal(), 2) }} MXN</strong></h5>
                </div>
            @endif
        </div>
    </div>

    <!-- Orden de Visita -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"> Orden de Visita a Sucursales</h5>
            <ol class="list-group list-group-numbered">
                @php
                    $visitedBranches = [];
                    $stepNumber = 1;
                @endphp
                
                <!-- Starting point -->
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"> Inicio - {{ $receta->getSucursal()->getCadena()->getNombre() }}</div>
                        <small class="text-muted">{{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}</small>
                    </div>
                </li>

                @foreach($receta->getLineasRecetas() as $linea)
                    @foreach($linea->getDetalleLineaReceta() as $detalle)
                        @php
                            $branchKey = $detalle->getSucursal()->getSucursalId() . '-' . $detalle->getSucursal()->getCadena()->getCadenaId();
                            if (in_array($branchKey, $visitedBranches)) continue;
                            $visitedBranches[] = $branchKey;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $detalle->getSucursal()->getCadena()->getNombre() }}</div>
                                <small class="text-muted">{{ $detalle->getSucursal()->getCalle() }}, {{ $detalle->getSucursal()->getColonia() }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">Parada {{ $stepNumber++ }}</span>
                        </li>
                    @endforeach
                @endforeach
            </ol>
        </div>
    </div>

    <!-- Mapa de Ruta -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"> Mapa de Ruta Optimizada</h5>
            <div id="map" style="height: 500px; border-radius: 8px;"></div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="d-flex gap-3 mb-4">
        <a href="{{ route('receta.indiceRecetas') }}" class="btn btn-secondary">← Volver al Índice</a>
        <a href="{{ route('home') }}" class="btn btn-outline-primary"> Inicio</a>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('map').setView([{{ $receta->getSucursal()->getLatitud() }}, {{ $receta->getSucursal()->getLongitud() }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Collect all unique branches
    const branches = [];
    const branchKeys = new Set();

    // Add starting branch
    branches.push({
        lat: {{ $receta->getSucursal()->getLatitud() }},
        lng: {{ $receta->getSucursal()->getLongitud() }},
        name: '{{ $receta->getSucursal()->getCadena()->getNombre() }}',
        address: '{{ $receta->getSucursal()->getCalle() }}, {{ $receta->getSucursal()->getColonia() }}',
        isStart: true
    });

    // Add branches from line details
    @php $addedBranches = []; @endphp
    @foreach($receta->getLineasRecetas() as $linea)
        @foreach($linea->getDetalleLineaReceta() as $detalle)
            @php
                $branchKey = $detalle->getSucursal()->getCadena()->getNombre() . '-' . $detalle->getSucursal()->getCadena()->getNombre();
            @endphp
            @if(!isset($addedBranches[$branchKey]))
                @php
                    $addedBranches[$branchKey] = true;
                @endphp
                branches.push({
                    lat: {{ $detalle->getSucursal()->getLatitud() }},
                    lng: {{ $detalle->getSucursal()->getLongitud() }},
                    name: '{{ $detalle->getSucursal()->getCadena()->getNombre() }}',
                    address: '{{ $detalle->getSucursal()->getCalle() }}, {{ $detalle->getSucursal()->getColonia() }}',
                    isStart: false
                });
            @endif
        @endforeach
    @endforeach

    // Add markers
    branches.forEach((branch, index) => {
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="background: ${branch.isStart ? '#f59e0b' : '#2563eb'}; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">${branch.isStart ? '🏁' : index}</div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        L.marker([branch.lat, branch.lng], { icon })
            .addTo(map)
            .bindPopup(`<b>${branch.name}</b><br>${branch.address}`);
    });

    // Draw route line
    if (branches.length > 1) {
        const routeCoords = branches.map(b => [b.lat, b.lng]);
        L.polyline(routeCoords, {
            color: '#2563eb',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
    }

    // Fit map to show all markers
    if (branches.length > 0) {
        const bounds = L.latLngBounds(branches.map(b => [b.lat, b.lng]));
        map.fitBounds(bounds, { padding: [50, 50] });
    }
</script>
</body>
</html>
