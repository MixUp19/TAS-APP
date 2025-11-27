#!/bin/bash
# Prueba del modelo directamente

cd "/home/mixup/Documentos/TecNM/Ing Web/ProyectoFinal/TAS-APP2"

echo "=== Probando ModeloDevolverReceta::obtenerRecetaPorFolio() ==="
echo ""

php artisan tinker --execute="
try {
    \$modelo = new App\Domain\ModeloDevolverReceta();
    echo 'Modelo creado correctamente\n';

    \$receta = \$modelo->obtenerRecetaPorFolio(4);

    if (\$receta) {
        echo '✅ Receta obtenida correctamente!\n';
        echo '  - Folio: ' . \$receta->getFolio() . '\n';
        echo '  - Paciente: ' . \$receta->getPaciente()->getNombre() . '\n';
        echo '  - Estado: ' . \$receta->getEstado() . '\n';
        echo '  - Total: $' . \$receta->getTotal() . '\n';
        echo '  - Líneas: ' . count(\$receta->getLineasRecetas()) . '\n';
    } else {
        echo '⚠️  Receta retornó null\n';
    }
} catch (Exception \$e) {
    echo '❌ ERROR: ' . \$e->getMessage() . '\n';
    echo '\n--- Stack Trace ---\n';
    echo \$e->getTraceAsString() . '\n';
}
"

