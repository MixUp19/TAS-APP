# 🐛 Guía de Debugging - Error en cargarDetalleReceta()

## Problema Identificado y Solucionado

### 🔍 Error Original
El método `obtenerRecetaPorFolio()` en `RecetaRepository` retornaba un modelo Eloquent (`RecetaModel`) en lugar de un objeto de dominio (`Receta`), causando errores al intentar llamar métodos del dominio.

### ✅ Solución Implementada

1. **RecetaRepository.php** - Corregido `obtenerRecetaPorFolio()`:
```php
public function obtenerRecetaPorFolio(int $folio): ?Receta
{
    $recetaModel = RecetaModel::with(['lineas.detalles', 'paciente'])
        ->find($folio);
    
    if (!$recetaModel) {
        return null;
    }
    
    return $this->eloquentToDomain($recetaModel);
}
```

2. **ControladorDevolverReceta.php** - Mejorado con manejo de errores:
```php
- Añadido try-catch completo
- Logs detallados con \Log::info() y \Log::error()
- Validación de receta null
- Respuestas JSON apropiadas con códigos de estado
```

3. **indice-recetas.blade.php** - Mejor debugging en frontend:
```javascript
- Console.log detallado de cada paso
- Muestra errores en pantalla y consola
- Stack trace visible en la UI para desarrollo
```

## 📊 Cómo Debugear

### 1. **Debugging en el Frontend (JavaScript)**

Abre la consola del navegador (F12) y verás:

```javascript
// Al expandir una receta:
Cargando detalle de receta con folio: 1001
Response status: 200
Response headers: Headers { ... }
Datos recibidos: { success: true, receta: {...} }

// Si hay error:
Error completo: Error: Receta no encontrada
Stack trace: Error: Receta no encontrada at cargarDetalleReceta...
```

**Pasos:**
1. Abre DevTools (F12)
2. Ve a la pestaña "Console"
3. Intenta expandir una receta
4. Revisa los mensajes en consola

### 2. **Debugging en el Backend (Laravel)**

#### Ver logs en tiempo real:
```bash
cd "/home/mixup/Documentos/TecNM/Ing Web/ProyectoFinal/TAS-APP2"
tail -f storage/logs/laravel.log
```

#### Logs que verás:
```
[2025-11-26 10:30:00] local.INFO: Obteniendo detalle de receta con folio: 1001
[2025-11-26 10:30:00] local.INFO: Receta encontrada: 1001
[2025-11-26 10:30:00] local.INFO: Líneas procesadas: 3

# Si hay error:
[2025-11-26 10:30:00] local.ERROR: Error al obtener detalle de receta: Call to a member function...
[2025-11-26 10:30:00] local.ERROR: #0 /path/to/file.php(123): ...
```

### 3. **Debugging con Tinker**

Prueba manualmente en la línea de comandos:

```bash
php artisan tinker
```

```php
// Verificar que existe la receta
$recetaModel = App\Models\Receta::find(1);
$recetaModel; // Ver datos

// Verificar relaciones
$recetaModel->lineas;
$recetaModel->paciente;

// Probar el repositorio
$repo = new App\Providers\RecetaRepository(
    new App\Providers\SucursalRepository(
        new App\Providers\CadenaRepository()
    ),
    new App\Providers\PacienteRepository(),
    new App\Providers\MedicamentoRepository()
);

$receta = $repo->obtenerRecetaPorFolio(1);
$receta->getFolio();
$receta->getLineasRecetas();
```

### 4. **Debugging con dd() y dump()**

Añade temporalmente en el controlador:

```php
public function obtenerDetalleReceta(Request $request, $folio)
{
    $modelo = $this->obtenerOInicializarModelo($request);
    
    // Debug: Ver el folio recibido
    dd($folio);
    
    $receta = $modelo->obtenerRecetaPorFolio($folio);
    
    // Debug: Ver la receta obtenida
    dd($receta);
    
    // Debug: Ver las líneas
    dd($receta->getLineasRecetas());
    
    // ... resto del código
}
```

### 5. **Verificar Datos en la Base de Datos**

```bash
php artisan db
```

```sql
-- Ver recetas
SELECT * FROM RECETA LIMIT 5;

-- Ver líneas de receta
SELECT * FROM LINEA_RECETA WHERE RecetaFolio = 1;

-- Ver detalles de línea
SELECT * FROM DETALLE_LINEA_RECETA WHERE RecetaFolio = 1;

-- Verificar relaciones completas
SELECT 
    r.RecetaFolio,
    r.RecetaEstado,
    lr.MedicamentoID,
    lr.LRCantidad,
    dlr.SucursalID,
    dlr.DLRCantidad,
    dlr.DLREstatus
FROM RECETA r
LEFT JOIN LINEA_RECETA lr ON r.RecetaFolio = lr.RecetaFolio
LEFT JOIN DETALLE_LINEA_RECETA dlr ON lr.RecetaFolio = dlr.RecetaFolio 
    AND lr.MedicamentoID = dlr.MedicamentoID
WHERE r.RecetaFolio = 1;
```

## 🔧 Errores Comunes y Soluciones

### Error 1: "Call to a member function on null"
**Causa:** La receta no existe en la base de datos
**Solución:** Verificar que el folio existe con `SELECT * FROM RECETA WHERE RecetaFolio = X`

### Error 2: "Trying to get property of non-object"
**Causa:** Alguna relación no está cargada (paciente, medicamento, sucursal)
**Solución:** Usar eager loading: `->with(['lineas.detalles', 'paciente'])`

### Error 3: "Response status: 500"
**Causa:** Error en el servidor
**Solución:** Revisar `storage/logs/laravel.log`

### Error 4: "Response status: 404"
**Causa:** Ruta no encontrada o receta no existe
**Solución:** 
- Verificar rutas: `php artisan route:list | grep receta`
- Verificar que la receta existe en la BD

### Error 5: "CSRF token mismatch"
**Causa:** El token CSRF no se está enviando
**Solución:** Verificar que existe `<meta name="csrf-token">` en el HTML

## 🧪 Testing Manual

### Test 1: Ver logs en tiempo real
```bash
# Terminal 1: Ver logs
tail -f storage/logs/laravel.log

# Terminal 2: Hacer petición
curl http://localhost:8000/receta/detalle/1 \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest"
```

### Test 2: Probar en el navegador
1. Ir a `/receta/indice-recetas`
2. Abrir DevTools (F12) → Console
3. Click en una receta para expandir
4. Ver logs en consola

### Test 3: Probar con Postman/Thunder Client
```
GET http://localhost:8000/receta/detalle/1
Headers:
  Accept: application/json
  X-Requested-With: XMLHttpRequest
```

## 📝 Checklist de Debugging

- [ ] ¿La receta existe en la base de datos?
- [ ] ¿Las relaciones están correctamente definidas en los modelos?
- [ ] ¿El eager loading está cargando todas las relaciones necesarias?
- [ ] ¿Los logs de Laravel muestran algún error?
- [ ] ¿La consola del navegador muestra algún error?
- [ ] ¿La respuesta del servidor es JSON válido?
- [ ] ¿El código de estado HTTP es correcto (200, 404, 500)?
- [ ] ¿Los métodos del objeto de dominio existen y funcionan?

## 🚀 Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver rutas
php artisan route:list | grep receta

# Reiniciar servidor (si usas artisan serve)
php artisan serve

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Limpiar logs
> storage/logs/laravel.log

# Ejecutar tinker para pruebas
php artisan tinker
```

## 💡 Mejoras Implementadas

1. ✅ Logging detallado en el backend
2. ✅ Try-catch completo con mensajes descriptivos
3. ✅ Validación de receta null
4. ✅ Respuestas JSON consistentes
5. ✅ Console.log detallado en frontend
6. ✅ Mensajes de error visibles en UI
7. ✅ Stack trace para desarrollo
8. ✅ Códigos de estado HTTP apropiados

## 📞 Si el Error Persiste

1. **Captura la información:**
   - Logs de Laravel (`storage/logs/laravel.log`)
   - Console del navegador (F12)
   - Código de estado HTTP
   - Mensaje de error exacto

2. **Verifica:**
   - ¿Qué folio estás intentando cargar?
   - ¿Existe ese folio en la base de datos?
   - ¿Qué muestra `php artisan tinker` al buscar esa receta?

3. **Prueba:**
   - Intenta con diferentes folios
   - Verifica que el servidor esté corriendo
   - Limpia el cache de Laravel

