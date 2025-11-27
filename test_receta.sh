#!/bin/bash
# Script de prueba rápida para verificar que funciona obtenerRecetaPorFolio

cd "/home/mixup/Documentos/TecNM/Ing Web/ProyectoFinal/TAS-APP2"

echo "=== Probando obtenerRecetaPorFolio ==="
echo ""

php artisan tinker --execute="
// Obtener primera receta para prueba
\$recetaModel = App\Models\Receta::first();

if (!\$recetaModel) {
    echo 'No hay recetas en la base de datos\n';
    exit;
}

echo 'Receta encontrada en BD: Folio ' . \$recetaModel->RecetaFolio . '\n';
echo '---\n';

// Probar el repositorio
\$repo = new App\Providers\RecetaRepository(
    new App\Providers\SucursalRepository(
        new App\Providers\CadenaRepository()
    ),
    new App\Providers\PacienteRepository(),
    new App\Providers\MedicamentoRepository()
);

try {
    echo 'Probando obtenerRecetaPorFolio(' . \$recetaModel->RecetaFolio . ')...\n';
    \$receta = \$repo->obtenerRecetaPorFolio(\$recetaModel->RecetaFolio);

    if (\$receta) {
        echo '✓ Receta obtenida correctamente\n';
        echo '  - Folio: ' . \$receta->getFolio() . '\n';
        echo '  - Paciente: ' . \$receta->getPaciente()->getNombre() . '\n';
        echo '  - Estado: ' . \$receta->getEstado() . '\n';
        echo '  - Total: $' . \$receta->getTotal() . '\n';
        echo '  - Líneas: ' . count(\$receta->getLineasRecetas()) . '\n';

        foreach (\$receta->getLineasRecetas() as \$linea) {
            echo '    * ' . \$linea->getMedicamento()->getNombre() . ' (x' . \$linea->getCantidad() . ')\n';
            echo '      Detalles: ' . count(\$linea->getDetalleLineaReceta()) . ' sucursales\n';
        }

        echo '\n✅ TODO FUNCIONA CORRECTAMENTE\n';
    } else {
        echo '❌ Receta retornó null\n';
    }
} catch (Exception \$e) {
    echo '❌ ERROR: ' . \$e->getMessage() . '\n';
    echo \$e->getTraceAsString() . '\n';
}
"

