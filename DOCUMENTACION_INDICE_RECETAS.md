# Documentación: Índice de Recetas por Sucursal

## Resumen

Se ha implementado exitosamente la funcionalidad para listar las recetas de una sucursal específica. La implementación incluye:

### Archivos Modificados/Creados:

1. **`/app/Domain/ModeloDevolverReceta.php`** - Actualizado
   - Agregado `SucursalRepository` como dependencia
   - Método `obtenerRecetas($sucursalId, $cadenaId)` actualizado para aceptar parámetros

2. **`/app/Http/Controllers/ControladorDevolverReceta.php`** - Actualizado
   - Agregado método `obtenerRecetas(Request $request)` que:
     - Valida los parámetros `sucursal_id` y `cadena_id`
     - Llama al método del modelo de dominio
     - Retorna la vista con los datos

3. **`/resources/views/receta/indice-recetas.blade.php`** - Creado
   - Vista completa con tabla de recetas
   - Muestra información de la sucursal
   - Lista de medicamentos por receta
   - Estados de las recetas con colores
   - Total por receta
   - Diseño responsivo y moderno

4. **`/routes/web.php`** - Actualizado
   - Agregada nueva ruta: `GET /receta/indice-recetas`
   - Nombre de ruta: `receta.indiceRecetas`

## Uso

### Cómo acceder a la funcionalidad:

La ruta espera dos parámetros en la query string:

```
GET /receta/indice-recetas?sucursal_id=1&cadena_id=1
```

### Ejemplo de uso en una vista Blade:

```html
<a href="{{ route('receta.indiceRecetas', ['sucursal_id' => 1, 'cadena_id' => 1]) }}">
    Ver Recetas de Sucursal
</a>
```

### Ejemplo con un formulario:

```html
<form action="{{ route('receta.indiceRecetas') }}" method="GET">
    <select name="sucursal_id" required>
        <option value="1">Sucursal 1</option>
        <option value="2">Sucursal 2</option>
    </select>
    
    <input type="hidden" name="cadena_id" value="1">
    
    <button type="submit">Ver Recetas</button>
</form>
```

## Flujo de Datos

1. **Usuario** → Envía petición GET con `sucursal_id` y `cadena_id`
2. **ControladorDevolverReceta** → Valida parámetros
3. **ModeloDevolverReceta** → Obtiene la sucursal usando `SucursalRepository`
4. **RecetaRepository** → Busca recetas de la sucursal en la base de datos
5. **Vista** → Muestra las recetas en una tabla formateada

## Características de la Vista

- **Información de Sucursal**: Muestra el ID de sucursal y cadena
- **Tabla de Recetas**: Con columnas para:
  - Fecha (formato dd/mm/yyyy)
  - Nombre del paciente
  - Cédula del doctor
  - Estado (con colores: pendiente, completada, cancelada)
  - Lista de medicamentos con cantidades
  - Total de la receta (formato monetario)
- **Mensaje sin datos**: Cuando no hay recetas
- **Botón de volver**: Para regresar al inicio
- **Diseño responsivo**: Compatible con móviles y desktop

## Próximos Pasos Sugeridos

1. Agregar filtros adicionales (por fecha, estado, etc.)
2. Implementar paginación para grandes cantidades de recetas
3. Agregar funcionalidad de búsqueda
4. Implementar acciones sobre las recetas (ver detalle, cancelar, etc.)

