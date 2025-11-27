# Documentación: Índice de Recetas Mejorado

## Descripción General
Se ha modificado el sistema de índice de recetas para que funcione como un índice expandible, donde los detalles de cada receta (medicamentos, sucursales y cantidades) se cargan dinámicamente al hacer clic en la fila.

## Cambios Realizados

### 1. ModeloDevolverReceta.php
Se añadieron los siguientes métodos:

- `cambiarEstadoReceta($folio, $nuevoEstado)`: Cambia el estado de una receta específica
- `obtenerRecetaPorFolio($folio)`: Obtiene una receta por su folio

### 2. ControladorDevolverReceta.php
Se añadieron los siguientes métodos:

- `obtenerDetalleReceta(Request $request, $folio)`: Retorna un JSON con los detalles completos de una receta, incluyendo:
  - Información básica de la receta
  - Líneas de medicamentos con cantidades y subtotales
  - Detalles por sucursal con cantidades y estatus

- `cambiarEstado(Request $request)`: Cambia el estado de una receta a "Lista para recoger" o "Recogida"

- `cancelarReceta(Request $request)`: Ejecuta la devolución de una receta (llama a `cancelarPedido()` y `confirmarCancelacion()`)

### 3. routes/web.php
Se añadieron las siguientes rutas:

```php
// Obtener detalle de una receta específica
GET /receta/detalle/{folio}

// Cambiar estado de una receta
POST /receta/cambiar-estado

// Cancelar/devolver receta
POST /receta/cancelar
```

### 4. indice-recetas.blade.php
Se modificó completamente la vista para implementar:

#### Características principales:
- **Tabla expandible**: Cada fila de receta puede expandirse/contraerse con un clic
- **Ícono de expansión**: Indicador visual (▶) que rota al expandir
- **Carga dinámica**: Los detalles se cargan solo cuando se expande la receta
- **Caché de detalles**: Los detalles cargados se almacenan para no volver a cargarlos
- **Botones de acción**: Tres botones para gestionar cada receta:
  - **Lista para recoger**: Marca la receta como lista
  - **Recogida**: Marca la receta como recogida
  - **Devolver**: Cancela la receta y notifica devolución

#### Estructura de detalles:
Cuando se expande una receta, se muestra:
- Encabezado con folio y botones de acción
- Lista de líneas de medicamentos:
  - Nombre del medicamento y cantidad
  - Subtotal por línea
  - Detalles por sucursal:
    - Nombre de la sucursal
    - Cantidad asignada
    - Estatus (Por recoger, Por devolver, etc.)

#### Estados de las recetas:
- **Pendiente**: Color amarillo
- **Lista para recoger**: Color azul
- **Recogida**: Color verde
- **Cancelada/Cancelada por no recoger**: Color rojo

#### Lógica de deshabilitación:
Los botones se deshabilitan automáticamente si la receta está en estado:
- "Cancelada por no recoger"
- "Recogida"

## Flujo de Uso

1. **Ver índice**: El usuario accede a `/receta/indice-recetas` y ve una tabla con todas las recetas
2. **Expandir receta**: Click en cualquier fila para ver los detalles
3. **Modificar estado**:
   - Click en "Lista para recoger" para marcarla como disponible
   - Click en "Recogida" cuando el paciente la recoja
   - Click en "Devolver" si no se recoge y debe cancelarse
4. **Actualización dinámica**: El estado se actualiza en la tabla sin recargar la página

## Tecnologías Utilizadas
- **Laravel Blade**: Templates del frontend
- **JavaScript Vanilla**: Manejo de la interactividad
- **Fetch API**: Llamadas AJAX para cargar detalles y actualizar estados
- **CSS3**: Estilos y animaciones

## Métodos JavaScript Principales

### `toggleDetalle(folio)`
Expande/contrae el detalle de una receta

### `cargarDetalleReceta(folio)`
Realiza una petición AJAX para obtener los detalles de una receta

### `mostrarDetalle(folio, receta)`
Renderiza los detalles de una receta en el DOM

### `cambiarEstado(folio, nuevoEstado)`
Cambia el estado de una receta y actualiza la UI

### `devolverReceta(folio)`
Ejecuta la devolución/cancelación de una receta

## Seguridad
- Todas las peticiones POST incluyen el token CSRF
- Confirmaciones del usuario antes de acciones destructivas
- Validación de estados antes de permitir modificaciones

