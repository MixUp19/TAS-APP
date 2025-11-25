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
    private RecetaRepository $recetaRepository;
    public function __construct() {
        $this->servicioOCR = app(ServicioOCR::class);
        $this->medicamentoRepository = app(MedicamentoRepository::class);
        $this->sucursalRepository = app(SucursalRepository::class);
        $this->localizadorService = app(LocalizadorService::class);
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

    public function finalizarReceta(): Receta
    {
        $this->receta->setEstado("Completa");
        $total = $this->receta->getTotal();
        return $total;
    }


    public function confirmarReceta(): int
    {
        // Obtener líneas de la receta
        $lineas = $this->receta->getLineasRecetas();

        // Obtener sucursal seleccionada
        $sucursal = $this->receta->getSucursal();

        // Obtener sucursales de la misma ciudad
        $sucursales = $this->sucursalRepository->getSucursalesPorCiudad($sucursal->getCiudadId());

        // Ordenar sucursales por cercanía (patrón Fabricación Pura)
        $sucursales = $this->localizadorService->localizarSucursal($sucursal, $sucursales);

        // Buscar medicamentos en sucursales ordenadas (patrón Alta Cohesión)
        // Este método modifica las líneas añadiendo detalles de sucursales
        $lineas = $this->medicamentoRepository->buscarMedicamentosEnSucursales($sucursal, $sucursales, $lineas);

        // Guardar receta en la base de datos (patrón Creador en Repository)
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


    public function obtenerSucursales()
    {
        // Obtiene todas las sucursales desde el repositorio
        return $this->sucursalRepository->listarSucursales();
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
