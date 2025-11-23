<?php

use App\Domain\ModeloProcesarReceta;
use App\DomainModels\Medicamento;
use App\Http\Controllers\ControladorProcesarReceta;
use App\DomainModels\Paciente;
use Illuminate\Http\Request;
use Mockery;

uses(Tests\TestCase::class);

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
});

test('obtener medicamentos subidos',function(){

    $request = new \Illuminate\Http\Request();
    $request->setMethod('POST');
    $request->setLaravelSession(new Illuminate\Session\SessionManager());
    $request->request->add(['medicamentos' => [1,2]]);
    $controlador = new ControladorProcesarReceta();
    $controlador->iniciarPedido($request, new \App\Models\Paciente());

    $receta = $controlador->obtenerReceta($request);

    $lineas = $receta->getLineasRecetas();
    foreach ($lineas as  $linea) {
        $medicamento = $linea->getMedicamento();
        expect($medicamento)->toBeInstanceOf(Medicamento::class);
    }
});

test('ControladorProcesarReceta llama al modelo para seleccionar un medicamento', function () {
    $medicamentoId = 5;
    $cantidad = 2;


    $modeloMock = Mockery::mock(ModeloProcesarReceta::class);
    $modeloMock->shouldReceive('seleccionarMedicamento')
        ->with($medicamentoId, $cantidad)
        ->once();

    $requestMock = Mockery::mock(Request::class);
    $sessionMock = Mockery::mock(Illuminate\Contracts\Session\Session::class);

    $requestMock->shouldReceive('session')->andReturn($sessionMock);
    $sessionMock->shouldReceive('get')->with('proceso_receta', Mockery::any())->andReturn($modeloMock);
    $sessionMock->shouldReceive('put')->with('proceso_receta', $modeloMock);

    $requestMock->shouldReceive('input')->with('medicamento_id')->andReturn($medicamentoId);
    $requestMock->shouldReceive('input')->with('cantidad')->andReturn($cantidad);

    $controlador = new ControladorProcesarReceta();

    $controlador->seleccionarMedicamento($requestMock);
});

test('ModeloProcesarReceta añade correctamente un medicamento a la receta', function () {
    $paciente = new Paciente(1,"a","a","a","a","a","a",false,0,null);
    $medicamento = new Medicamento(1, 'Aspirina', 'Acido acetilsalicilico', 10.0, 'mg', 100);

    $medicamentoRepoMock = Mockery::mock(\App\Providers\MedicamentoRepository::class);
    $medicamentoRepoMock->shouldReceive('obtenerMedicamentoPorId')
        ->with(1)
        ->once()
        ->andReturn($medicamento);

    $this->app->instance(\App\Providers\MedicamentoRepository::class, $medicamentoRepoMock);

    $modelo = new ModeloProcesarReceta();
    $modelo->iniciarPedido($paciente);

    $modelo->seleccionarMedicamento(1, 3);

    $receta = $modelo->getReceta();
    $lineas = $receta->getLineasRecetas();

    expect($lineas)->toHaveCount(1);
    expect($lineas[0]->getMedicamento())->toBe($medicamento);
    expect($lineas[0]->getCantidad())->toBe(3);
});
