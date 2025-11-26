<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Medicamentos</title>
    {{-- Incluye aquí tus estilos CSS, como Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Seleccionar Medicamentos</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Añadir Medicamento</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="medicamento-select" class="form-label">Medicamento</label>
                    <select id="medicamento-select" class="form-select">
                        <option selected disabled>Seleccione un medicamento</option>
                        @foreach ($medicamentos as $medicamento)
                            <option value="{{ $medicamento->getId() }}"
                                    data-nombre="{{ $medicamento->getNombre() }}"
                                    data-compuesto="{{ $medicamento->getCompuestoActivo() }}"
                                    data-precio="{{ $medicamento->getPrecio() }}"
                                    data-contenido="{{ $medicamento->getContenido() }} {{ $medicamento->getUnidad() }}">
                                {{ $medicamento->getNombre() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="cantidad-input" class="form-label">Cantidad</label>
                    <input type="number" id="cantidad-input" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-3">
                    <form id="scan-form" enctype="multipart/form-data">
                        <input type="file" name="recipe_image" id="recipe_image" accept="image/*" style="display: none;">
                        <button type="button" id="escanear-medicamento-btn" class="btn btn-primary w-100">Escanear receta</button>
                    </form>
                </div>
                <div class="col-md-2">
                    <button type="button" id="add-medicamento-btn" class="btn btn-primary w-100">Añadir</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('medicamentos.add') }}" method="POST" id="receta-form">
        @csrf
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Medicamentos Seleccionados</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Compuesto</th>
                        <th>Precio</th>
                        <th>Contenido</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="medicamentos-seleccionados-tbody">
                    {{-- Las filas se añadirán aquí dinámicamente --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Guardar Receta</button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('add-medicamento-btn');
        const select = document.getElementById('medicamento-select');
        const cantidadInput = document.getElementById('cantidad-input');
        const tbody = document.getElementById('medicamentos-seleccionados-tbody');
        const form = document.getElementById('receta-form');
        const scanBtn = document.getElementById('escanear-medicamento-btn');
        const fileInput = document.getElementById('recipe_image');

        function addMedicamentoRow(id, nombre, compuesto, precio, contenido, cantidad) {
            // Evitar duplicados
            if (form.querySelector(`input[name="medicamentos[${id}][id]"]`)) {
                return false; 
            }

            const newRow = tbody.insertRow();
            newRow.setAttribute('data-medicamento-id', id);
            newRow.innerHTML = `
                <td>${nombre}</td>
                <td>${compuesto}</td>
                <td>$${parseFloat(precio).toFixed(2)}</td>
                <td>${contenido}</td>
                <td>${cantidad}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-medicamento-btn">Eliminar</button></td>
            `;

            // Añadir inputs ocultos para el envío del formulario
            const hiddenInputsContainer = document.createElement('div');
            hiddenInputsContainer.setAttribute('data-medicamento-id', id);
            hiddenInputsContainer.innerHTML = `
                <input type="hidden" name="medicamentos[${id}][id]" value="${id}">
                <input type="hidden" name="medicamentos[${id}][cantidad]" value="${cantidad}">
            `;
            form.appendChild(hiddenInputsContainer);
            return true;
        }

        addBtn.addEventListener('click', function () {
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || selectedOption.disabled) {
                alert('Por favor, seleccione un medicamento.');
                return;
            }

            const cantidad = cantidadInput.value;
            if (cantidad < 1) {
                alert('La cantidad debe ser al menos 1.');
                return;
            }

            const medicineId = selectedOption.value;
            
            if (!addMedicamentoRow(
                medicineId, 
                selectedOption.dataset.nombre, 
                selectedOption.dataset.compuesto, 
                selectedOption.dataset.precio, 
                selectedOption.dataset.contenido, 
                cantidad
            )) {
                alert('Este medicamento ya ha sido añadido.');
                return;
            }

            select.selectedIndex = 0;
            cantidadInput.value = 1;
        });

        // Escanear receta
        scanBtn.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const formData = new FormData();
                formData.append('recipe_image', this.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                const originalText = scanBtn.textContent;
                scanBtn.disabled = true;
                scanBtn.textContent = 'Escaneando...';

                fetch('{{ route('receta.escanear') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let addedCount = 0;
                        data.medicamentos_detectados.forEach(item => {
                            const med = item.medicamento;
                            const cantidad = item.cantidad;
                            const contenidoStr = `${med.contenido} ${med.unidad}`;
                            
                            if (addMedicamentoRow(med.id, med.nombre, med.compuesto, med.precio, contenidoStr, cantidad)) {
                                addedCount++;
                            }
                        });
                        
                        if (addedCount > 0) {
                            alert(`Se han añadido ${addedCount} medicamentos detectados.`);
                        } else {
                            alert('No se añadieron nuevos medicamentos (posibles duplicados).');
                        }
                    } else {
                        alert('Error al escanear la receta.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al procesar la imagen.');
                })
                .finally(() => {
                    scanBtn.disabled = false;
                    scanBtn.textContent = originalText;
                    fileInput.value = ''; 
                });
            }
        });

        tbody.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-medicamento-btn')) {
                const row = e.target.closest('tr');
                const medicineId = row.getAttribute('data-medicamento-id');

                // Eliminar fila de la tabla
                row.remove();

                // Eliminar inputs ocultos del formulario
                const hiddenInputs = form.querySelector(`div[data-medicamento-id="${medicineId}"]`);
                if (hiddenInputs) {
                    hiddenInputs.remove();
                }
            }
        });
    });
</script>

</body>
</html>
