# ✅ ERROR SOLUCIONADO: addEagerConstraints

## 🐛 Error Original
```
error: "Method Illuminate\Database\Eloquent\Collection::addEagerConstraints does not exist."
```

## 🔍 Causa del Problema

El error ocurría en la línea de `RecetaRepository.php`:

```php
// ❌ INCORRECTO
$recetaModel = RecetaModel::with(['lineas.detalles', 'paciente'])
    ->find($folio);
```

El problema era que **`detalles` no es una relación Eloquent válida**, sino un método helper que ejecuta una query directamente:

```php
// En LineaReceta.php
public function detalles()  // ← NO es una relación Eloquent
{
    return DetalleLineaReceta::where('RecetaFolio', $this->RecetaFolio)
        ->where('MedicamentoID', $this->MedicamentoID)
        ->get();
}
```

Cuando se intenta usar `with('lineas.detalles')`, Eloquent busca un método de relación válido (hasMany, belongsTo, etc.) pero encuentra un método que retorna una Collection directamente, causando el error.

## ✅ Solución Aplicada

### Cambio en `RecetaRepository.php`

**Línea 226** - Removido `.detalles` del eager loading:

```php
// ✅ CORRECTO
public function obtenerRecetaPorFolio(int $folio): ?Receta
{
    $recetaModel = RecetaModel::with(['lineas.medicamento', 'paciente'])
        ->find($folio);

    if (!$recetaModel) {
        return null;
    }

    return $this->eloquentToDomain($recetaModel);
}
```

### ¿Por qué funciona ahora?

Los detalles se cargan automáticamente en el método `mapearLineaReceta()` (línea 77-88):

```php
private function mapearLineaReceta(LineaRecetaModel $lineaModel): LineaReceta
{
    $medicamento = $this->medicamentoRepository->obtenerMedicamentoPorId($lineaModel->MedicamentoID);
    $lineaReceta = new LineaReceta($medicamento, $lineaModel->LRCantidad);

    $detalles = $lineaModel->detalles();  // ← Aquí se cargan los detalles
    foreach ($detalles as $detalleModel) {
        $detalleLineaReceta = $this->mapearDetalleLineaReceta($detalleModel);
        $lineaReceta->anadirSucursal(
            $detalleLineaReceta->getSucursal(),
            $detalleLineaReceta->getCantidad()
        );
    }

    return $lineaReceta;
}
```

## ✅ Prueba de Funcionamiento

```bash
$ bash test_modelo.sh
=== Probando ModeloDevolverReceta::obtenerRecetaPorFolio() ===

Modelo creado correctamente
✅ Receta obtenida correctamente!
  - Folio: 4
  - Paciente: Juan
  - Estado: Pendiente
  - Total: $25.5
  - Líneas: 1
```

## 📝 Resumen

| Aspecto | Antes | Después |
|---------|-------|---------|
| Eager Loading | `with(['lineas.detalles', ...])` | `with(['lineas.medicamento', ...])` |
| Carga de detalles | Intentaba con Eloquent (fallaba) | Se carga en `mapearLineaReceta()` |
| Resultado | ❌ Error addEagerConstraints | ✅ Funciona correctamente |

## 🎯 Archivos Modificados

- ✅ `/app/Providers/RecetaRepository.php` - Línea 226

## 💡 Lección Aprendida

**No se puede usar eager loading (`with()`) con métodos que no son relaciones Eloquent.**

Si un método retorna directamente una Collection o ejecuta una query, debe llamarse manualmente, no a través de `with()`.

### ✅ Relaciones Eloquent válidas para `with()`:
- `hasMany()`
- `belongsTo()`
- `hasOne()`
- `belongsToMany()`
- `morphMany()`
- etc.

### ❌ NO válidos para `with()`:
- Métodos que retornan `Collection::where()->get()`
- Métodos helper personalizados
- Queries directas

## 🚀 Siguiente Paso

Ahora el sistema debería funcionar correctamente. Puedes:

1. Acceder a `/receta/indice-recetas`
2. Expandir una receta
3. Ver los detalles cargados correctamente

¡El error está completamente solucionado! 🎉

