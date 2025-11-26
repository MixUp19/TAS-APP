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
    private ?Receta $receta = null;
    private SucursalRepository $sucursalRepository;
    private ServicioOCR $servicioOCR;
    private LocalizadorService $localizadorService;
    private MedicamentoRepository $medicamentoRepository;
    private \App\Providers\RecetaRepository $recetaRepository;
    public function __construct() {
        $this->servicioOCR = new ServicioOCR();
        $this->medicamentoRepository = new MedicamentoRepository(); 
        $this->sucursalRepository = new SucursalRepository();
        $this->localizadorService = new LocalizadorService();
        $this->recetaRepository = new \App\Providers\RecetaRepository();
    }
    public function iniciarPedido($paciente){
        $this->receta = new Receta($paciente);
    }

    public function seleccionarSucursal($sucursalId, $cadenaId, $doctorCedula, $fecha)
    {
        $sucursal = $this->sucursalRepository->obtenerSucursal($sucursalId,$cadenaId);
        $this->receta->setSucursal($sucursal);
        $this->receta->setCedulaDoctor($doctorCedula);
        $this->receta->setFecha($fecha);
    }

    public function seleccionarMedicamento($id, $cantidad)
    {
        $med = $this->medicamentoRepository->obtenerMedicamentoPorId($id);
        $this->receta->anadirLinea($med, $cantidad);
        dump($this->receta);
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
            $this->receta->anadirLinea($linea->getMedicamento(), $linea->getCantidad());
        }

    }

    public function escanearReceta($imagen)
    {
        $medicamentos = $this->medicamentoRepository->obtenerMedicamentos();
        $mapaMedicamentos = [];

        foreach ($medicamentos as $med) {
            $mapaMedicamentos[strtolower($med->getNombre())] = $med;
        }

        $palabras = $this->servicioOCR->escanearReceta($imagen);

        $lineasReceta  = [];
        foreach ($palabras as $palabra) {
            $palabraLimpia = strtolower(trim($palabra));
            if (isset($mapaMedicamentos[$palabraLimpia])) {
                $medicamento = $mapaMedicamentos[$palabraLimpia];
                $lineaReceta = new LineaReceta($medicamento, 1);
                $lineasReceta[] = $lineaReceta;
            }
        }
        return $lineasReceta;
    }
    public function getReceta(): Receta{
        return $this->receta;
    }
    
    public function obtenerSucursales(): array
    {
        return $this->sucursalRepository->listarSucursales();
    }

    public function obtenerMedicamentos()
    {
        return $this->medicamentoRepository->obtenerMedicamentos();
    }


}
