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
use App\Providers\TSPService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
class ModeloProcesarReceta
{
    private ?Receta $receta = null;
    private SucursalRepository $sucursalRepository;
    private ServicioOCR $servicioOCR;
    private LocalizadorService $localizadorService;
    private TSPService $tspService;
    private MedicamentoRepository $medicamentoRepository;
    private RecetaRepository $recetaRepository;
    public function __construct() {
        $this->servicioOCR = app(ServicioOCR::class);
        $this->medicamentoRepository = app(MedicamentoRepository::class);
        $this->sucursalRepository = app(SucursalRepository::class);
        $this->localizadorService = app(LocalizadorService::class);
        $this->tspService = app(TSPService::class);
        $this->recetaRepository = app(RecetaRepository::class);
    }

    public function __wakeup()
    {
        $this->servicioOCR = app(ServicioOCR::class);
        $this->medicamentoRepository = app(MedicamentoRepository::class);
        $this->sucursalRepository = app(SucursalRepository::class);
        $this->localizadorService = app(LocalizadorService::class);
        $this->tspService = app(TSPService::class);
        $this->recetaRepository = app(RecetaRepository::class);
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
        if ($med) {
            $this->receta->anadirLinea($med, $cantidad);
        }
    }

    public function finalizarReceta(): float
    {
        $this->receta->setEstado("Completa");
        $total = $this->receta->getTotal();
        return $total;
    }


    public function confirmarReceta(): int
    {
        $lineas = $this->receta->getLineasRecetas();

        $sucursal = $this->receta->getSucursal();

        $sucursales = $this->sucursalRepository->getSucursalesPorCiudad($sucursal->getCiudadId());

        $sucursales = $this->localizadorService->localizarSucursal($sucursal, $sucursales);

        $lineas = $this->medicamentoRepository->buscarMedicamentosEnSucursales($sucursal, $sucursales, $lineas);

        $lineas = $this->tspService->optimizarRuta($sucursal, $lineas);
        $this->receta->setLineasRecetas($lineas);
        $folio = $this->recetaRepository->guardarReceta($this->receta);

        return $folio;
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
    public function limpiarLineas():void
    {
        $this->receta->limpiarLineas();
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

    //extra
    public function buscarReceta($folio)
    {
        return $this->recetaRepository->obtenerRecetaPorFolio($folio);
    }

    public function obtenerRecetasPaciente($pacienteId)
    {
        return $this->recetaRepository->obtenerRecetasPorPaciente($pacienteId);
    }
}
