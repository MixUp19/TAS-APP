<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Medicamentos</title>
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
                            50: 'hsl(190, 93%, 97%)',
                            100: 'hsl(190, 93%, 92%)',
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
        
        /* Estilos para inputs y selects */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background-color: #fff;
            font-size: 15px;
            transition: all 0.25s ease;
        }
        
        .form-input:hover {
            border-color: hsla(190, 93%, 41%, 0.4);
        }
        
        .form-input:focus {
            outline: none;
            border-color: hsl(190, 93%, 41%);
            box-shadow: 0 0 0 4px hsla(190, 93%, 41%, 0.12);
        }
        
        /* Botón primario */
        .btn-primary-custom {
            background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px hsla(190, 93%, 41%, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, hsl(190, 93%, 48%) 0%, hsl(190, 93%, 35%) 100%);
            box-shadow: 0 6px 16px hsla(190, 93%, 41%, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-primary-custom:active {
            transform: translateY(0);
        }
        
        .btn-primary-custom:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Botón secundario (escanear) */
        .btn-secondary-custom {
            background: white;
            color: hsl(190, 93%, 38%);
            padding: 12px 20px;
            border: 2px solid hsl(190, 93%, 41%);
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-secondary-custom:hover {
            background: hsla(190, 93%, 41%, 0.08);
            border-color: hsl(190, 93%, 35%);
        }
        
        /* Botón success */
        .btn-success-custom {
            background: linear-gradient(135deg, hsl(160, 84%, 39%) 0%, hsl(160, 84%, 32%) 100%);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px hsla(160, 84%, 39%, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-success-custom:hover {
            background: linear-gradient(135deg, hsl(160, 84%, 44%) 0%, hsl(160, 84%, 28%) 100%);
            box-shadow: 0 6px 16px hsla(160, 84%, 39%, 0.4);
            transform: translateY(-2px);
        }
        
        /* Botón danger */
        .btn-danger-custom {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-danger-custom:hover {
            background: #fecaca;
        }
        
        /* Tabla personalizada */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table thead th {
            background: linear-gradient(135deg, hsl(190, 93%, 97%) 0%, hsl(190, 50%, 95%) 100%);
            color: hsl(190, 93%, 30%);
            font-weight: 600;
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
            border-bottom: 2px solid hsla(190, 93%, 41%, 0.2);
        }
        
        .custom-table thead th:first-child {
            border-radius: 10px 0 0 0;
        }
        
        .custom-table thead th:last-child {
            border-radius: 0 10px 0 0;
        }
        
        .custom-table tbody tr {
            transition: background 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background: hsla(190, 93%, 41%, 0.04);
        }
        
        .custom-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }
        
        /* Card interna */
        .inner-card {
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f7fa 50%, #e6fffa 100%);
            border: 1px solid hsla(190, 93%, 41%, 0.2);
            border-radius: 16px;
            padding: 24px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 min-h-screen">

{{-- Contenedor principal --}}
<div class="container mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-xl p-8">

        {{-- Menú de Navegación Principal --}}
        <x-paciente-nav titulo="Seleccionar Medicamentos" />

        {{-- Breadcrumbs de progreso --}}
        <x-breadcrumbs 
            :steps="[
                ['name' => 'Datos Generales'],
                ['name' => 'Medicamentos'],
                ['name' => 'Revisar'],
                ['name' => 'Confirmar']
            ]"
            :currentStep="2"
        />

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="mt-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Card: Añadir Medicamento --}}
        <div class="inner-card mt-6">
            <h5 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Añadir Medicamento
            </h5>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label for="medicamento-select" class="block text-sm font-semibold text-gray-700 mb-2">Medicamento</label>
                    <select id="medicamento-select" class="form-input">
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
                
                <div class="md:col-span-2">
                    <label for="cantidad-input" class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                    <input type="number" id="cantidad-input" class="form-input" value="1" min="1">
                </div>
                
                <div class="md:col-span-3">
                    <form id="scan-form" enctype="multipart/form-data">
                        <input type="file" name="recipe_image" id="recipe_image" accept="image/*" style="display: none;">
                        <button type="button" id="escanear-medicamento-btn" class="btn-secondary-custom w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Escanear receta
                        </button>
                    </form>
                </div>
                
                <div class="md:col-span-2">
                    <button type="button" id="add-medicamento-btn" class="btn-primary-custom w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Añadir
                    </button>
                </div>
            </div>
        </div>

        {{-- Form y tabla de medicamentos seleccionados --}}
        <form action="{{ route('medicamentos.add') }}" method="POST" id="receta-form">
            @csrf
            
            <div class="mt-6 bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h5 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: hsl(190, 93%, 41%);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Medicamentos Seleccionados
                    </h5>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="custom-table">
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
                
                {{-- Mensaje cuando no hay medicamentos --}}
                <div id="empty-state" class="py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <p class="text-gray-400 text-sm">No hay medicamentos seleccionados</p>
                    <p class="text-gray-300 text-xs mt-1">Añade medicamentos usando el formulario de arriba</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-success-custom">
                    <span>Continuar a Revisar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </form>

    </div> {{-- Cierre de .bg-white --}}
</div> {{-- Cierre de .container --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('add-medicamento-btn');
        const select = document.getElementById('medicamento-select');
        const cantidadInput = document.getElementById('cantidad-input');
        const tbody = document.getElementById('medicamentos-seleccionados-tbody');
        const form = document.getElementById('receta-form');
        const scanBtn = document.getElementById('escanear-medicamento-btn');
        const fileInput = document.getElementById('recipe_image');
        const emptyState = document.getElementById('empty-state');

        function updateEmptyState() {
            if (tbody.children.length === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        function addMedicamentoRow(id, nombre, compuesto, precio, contenido, cantidad) {
            // Evitar duplicados
            if (form.querySelector(`input[name="medicamentos[${id}][id]"]`)) {
                return false; 
            }

            const newRow = tbody.insertRow();
            newRow.setAttribute('data-medicamento-id', id);
            newRow.innerHTML = `
                <td class="font-medium text-gray-800">${nombre}</td>
                <td>${compuesto}</td>
                <td class="font-semibold" style="color: hsl(190, 93%, 35%);">$${parseFloat(precio).toFixed(2)}</td>
                <td>${contenido}</td>
                <td><span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full font-semibold text-gray-700">${cantidad}</span></td>
                <td><button type="button" class="btn-danger-custom remove-medicamento-btn">Eliminar</button></td>
            `;

            // Añadir inputs ocultos para el envío del formulario
            const hiddenInputsContainer = document.createElement('div');
            hiddenInputsContainer.setAttribute('data-medicamento-id', id);
            hiddenInputsContainer.innerHTML = `
                <input type="hidden" name="medicamentos[${id}][id]" value="${id}">
                <input type="hidden" name="medicamentos[${id}][cantidad]" value="${cantidad}">
            `;
            form.appendChild(hiddenInputsContainer);
            updateEmptyState();
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

                const originalHTML = scanBtn.innerHTML;
                scanBtn.disabled = true;
                scanBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Escaneando...
                `;

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
                    scanBtn.innerHTML = originalHTML;
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
                updateEmptyState();
            }
        });

        // Estado inicial
        updateEmptyState();
    });
</script>

</body>
</html>
