<?php

namespace App\Domain;

use App\DomainModels\LineaReceta;
use App\DomainModels\Receta;
use App\DomainModels\Sucursal;
use App\Providers\LocalizadorService;
use App\Providers\MedicamentoRepository;
use App\Providers\RecetaRepository;
use App\Providers\ServicioOCR;
use App\Providers\SucursalRepository;
use Illuminate\Database\Eloquent\Collection;

class ModeloProcesarReceta
{
    private Receta $receta;
    private SucursalRepository $sucursalRepository;
    private ServicioOCR $servicioOCR;
    private LocalizadorService $localizadorService;
    private MedicamentoRepository $medicamentoRepository;
    private RecetaRepository $recetaRepository;

    // El constructor ya no recibe dependencias para poder serializarlo en la sesión.
    public function __construct() {
        // Las dependencias se resuelven desde el contenedor de servicios de Laravel.
        $this->sucursalRepository = app(SucursalRepository::class);
        $this->servicioOCR = app(ServicioOCR::class);
        $this->localizadorService = app(LocalizadorService::class);
        $this->medicamentoRepository = app(MedicamentoRepository::class);
        $this->recetaRepository = app(RecetaRepository::class);
    }

    public function obtenerMedicamentos()
    {
        return $this->medicamentoRepository->obtenerMedicamentos();
    }

    public function iniciarPedido($paciente)
    {
        $this->receta = new Receta($paciente);
    }

    public function seleccionarSucursal($sucursal)
    {
        $this->receta->setSucursal($sucursal);
    }

    public function seleccionarMedicamento($id, $cantidad)
    {
        $med = $this->medicamentoRepository->obtenerMedicamentoPorId($id);
        $this->receta->anadirLinea($med, $cantidad);
    }

    public function finalizarReceta(): int
    {
        $this->receta->setEstado("Completa");
        $total = $this->receta->getTotal();
        return $total;
    }

    public function confirmarReceta()
    {
        $lineas = $this->receta->getLineasRecetas();
        $sucursal = $this->receta->getSucursal();
        $sucursales = $this->sucursalRepository->getSucursalesPorCiudad($sucursal->getCiudadId());
        $sucursales = $this->localizadorService->localizarSucursal($sucursal, $sucursales);
        $lineas = $this->medicamentoRepository->buscarMedicamentosEnSucursales($sucursal, $sucursales, $lineas);
        $this->recetaRepository->guardarReceta($this->receta);
    }

    public function cancelarReceta()
    {
        $this->receta = null;
    }

    public function cambiarSucursal($sucursal)
    {
        $this->receta->setSucursal($sucursal);
    }

    public function modificarMedicamento($id, $cantidad)
    {
        $this->receta->modificarMedicamento($id, $cantidad);
    }

    public function confirmarMedicamento($lineasDeMedicamento)
    {
        foreach ($lineasDeMedicamento as $linea) {
            $this->receta->anadirLineaLr($linea);
        }
        
    }

    public function escanearReceta($imagen)
    {
        $nombresMedicamentos = $this->servicioOCR->escanearReceta($imagen);
        $lineasDeMedicamentos = [];
        foreach ($nombresMedicamentos as $nombreMedicamento) {
            $medicamento = $this->medicamentoRepository->obtenerMedicamentoPorNombre($nombreMedicamento);
            $lineasDeMedicamentos[] = new LineaReceta($medicamento, 1);
        }
        return $lineasDeMedicamentos;
    }


    public function obtenerSucursales()
    {
        // Obtiene todas las sucursales desde el repositorio
        return $this->sucursalRepository->listarSucursales();
    }

    
}
