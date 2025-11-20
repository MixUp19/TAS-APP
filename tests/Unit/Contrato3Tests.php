<?php

use App\Domain\ModeloProcesarReceta;
use App\DomainModels\Medicamento;
use App\Http\Controllers\ControladorProcesarReceta;
use Mockery;

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('obtenerMedicamentos devuelve un array de objetos Medicamento', function () {
    $medicamentosEsperados = [
        new Medicamento(1, 'Paracetamol', 'Paracetamol', 25.50, 'mg', 500),
        new Medicamento(2, 'Ibuprofeno', 'Ibuprofeno', 30.00, 'mg', 400),
    ];
    $modeloMock = Mockery::mock(ModeloProcesarReceta::class);
    $modeloMock->shouldReceive('obtenerMedicamentos')
        ->once()
        ->andReturn($medicamentosEsperados);

    $controlador = new ControladorProcesarReceta($modeloMock);

    $medicamentos = $controlador->obtenerMedicamentos();

    expect($medicamentos)->toBe($medicamentosEsperados);
    expect($medicamentos)->toBeArray();
    foreach ($medicamentos as $medicamento) {
        expect($medicamento)->toBeInstanceOf(Medicamento::class);
    }
});
