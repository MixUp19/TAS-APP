<?php

use App\Providers\ServicioOCR;
use App\Providers\SucursalRepository;
use \App\Providers\LocalizadorService;
use \App\Providers\MedicamentoRepository;
use App\DomainModels\Receta;

class ModeloProcesarReceta {
    private Receta $receta;
    private SucursalRepository $sucursalRepository;
    private ServicioOCR $servicioOCR;
    private LocalizadorService $localizadorService;
    private MedicamentoRepository $medicamentoRepository;
    private \App\Providers\RecetaRepository $recetaRepository;
    public function __construct() {}
    public function iniciarPedido($paciente){
        $this->receta = new Receta($paciente);
    }
    public function seleccionarSucursal($sucursal){
        $this->receta->setSucursal($sucursal);
    }
    public function seleccionarMedicamento($med, $cantidad){
        $this->receta->anadirLinea($med, $cantidad);
    }
    public function finalizarReceta():int{
        $this->receta->setEstado("Completa");
        $total = $this->receta->getTotal();
        return $total;
    }
    public function confirmarReceta(){
        $lineas = $this->receta->getLineasRecetas();
        $sucursal = $this->receta->getSucursal();
        $sucursales = $this->sucursalRepository->getSucursalesPorCiudad($sucursal->getCiudadId());
        $sucursales = $this->localizadorService->localizarSucursal($sucursal, $sucursales);
        $lineas = $this->medicamentoRepository->buscarMedicamentosEnSucursales($sucursal, $sucursales, $lineas);
        $this->recetaRepository->guardarReceta($this->receta);
    }
    public function cancelarReceta(){}
    public function cambiarSucursal($sucursalId){}
    public function modificarMedicamento($id, $cantidad){}
    public function confirmarMedicamento($lineasDeMedicamento){}
    public function escanearReceta($imagen){}
}
