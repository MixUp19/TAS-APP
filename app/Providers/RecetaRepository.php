<?php

namespace App\Providers;

use App\Models\Receta as RecetaModel;
use App\Models\LineaReceta as LineaRecetaModel;
use App\Models\DetalleLineaReceta as DetalleLineaRecetaModel;
use App\DomainModels\Sucursal;
use App\DomainModels\Paciente;
use App\DomainModels\LineaReceta;
use App\DomainModels\DetalleLineaReceta;
use App\DomainModels\Receta;
use Illuminate\Support\Facades\DB;


class RecetaRepository
{
    private SucursalRepository $sucursalRepository;
    private PacienteRepository $pacienteRepository;
    private MedicamentoRepository $medicamentoRepository;

    public function __construct(
        SucursalRepository $sucursalRepository,
        PacienteRepository $pacienteRepository,
        MedicamentoRepository $medicamentoRepository
    ) {
        $this->sucursalRepository = $sucursalRepository;
        $this->pacienteRepository = $pacienteRepository;
        $this->medicamentoRepository = $medicamentoRepository;
    }

    public function obtenerRecetasPorSucursal(Sucursal $sucursal){
        $recetas = RecetaModel::where([
            ['SucursalID', '=', $sucursal->getSucursalId()],
            ['CadenaID', '=', $sucursal->getCadena()->getCadenaId()]
        ])->get();
        $recetasDomain = [];
        foreach ($recetas as $recetaModel) {
            $recetasDomain[] = $this->eloquentToDomainWithSucursal($recetaModel, $sucursal);
        }
        return $recetasDomain;
    }
    public function eloquentToDomain(RecetaModel $recetaModel): Receta
    {
        $sucursal = $this->sucursalRepository->obtenerSucursal(
            $recetaModel->SucursalID,
            $recetaModel->CadenaID
        );

        return $this->eloquentToDomainWithSucursal($recetaModel, $sucursal);
    }

    public function eloquentToDomainWithSucursal(
        RecetaModel $recetaModel,
        Sucursal $sucursal
    ): Receta {
        $paciente = $this->pacienteRepository->obtenerPacientePorId($recetaModel->PacienteID);

        $receta = new Receta($paciente);
        $receta->setSucursal($sucursal);
        $receta->setCedulaDoctor($recetaModel->CedulaDoctor);
        $receta->setFecha($recetaModel->RecetaFecha->format('Y-m-d'));
        $receta->setEstado($recetaModel->RecetaEstado);
        $receta->setFolio($recetaModel->RecetaFolio);


        foreach ($recetaModel->lineas as $lineaModel) {
            $lineaReceta = $this->mapearLineaReceta($lineaModel);
            $receta->anadirLineaLr($lineaReceta);
        }

        return $receta;
    }

    private function mapearLineaReceta(LineaRecetaModel $lineaModel): LineaReceta
    {
        $medicamento = $this->medicamentoRepository->obtenerMedicamentoPorId($lineaModel->MedicamentoID);
        $lineaReceta = new LineaReceta($medicamento, $lineaModel->LRCantidad);

        $detalles = $lineaModel->detalles();
        foreach ($detalles as $detalleModel) {
            $detalleLineaReceta = $this->mapearDetalleLineaReceta($detalleModel);
            $lineaReceta->anadirSucursal(
                $detalleLineaReceta->getSucursal(),
                $detalleLineaReceta->getCantidad()
            );
        }

        return $lineaReceta;
    }

    private function mapearDetalleLineaReceta(DetalleLineaRecetaModel $detalleModel): DetalleLineaReceta
    {
        $sucursal = $this->sucursalRepository->obtenerSucursal(
            $detalleModel->SucursalID,
            $detalleModel->CadenaID
        );

        return new DetalleLineaReceta($sucursal, $detalleModel->DLRCantidad, $detalleModel->DLREstatus);
    }

    public function guardarReceta(Receta $receta): int
    {
        return DB::transaction(function () use ($receta) {
            // 1. Guardar el encabezado de la receta
            $recetaModel = RecetaModel::create([
                'CedulaDoctor' => $receta->getCedulaDoctor(),
                'RecetaFecha' => $receta->getFecha()->format('Y-m-d'),
                'PacienteID' => $receta->getPaciente()->getId(),
                'CadenaID' => $receta->getSucursal()->getCadena()->getCadenaId(),
                'SucursalID' => $receta->getSucursal()->getSucursalId(),
                'RecetaEstado' => $receta->getEstado(),
            ]);

            $folio = $recetaModel->RecetaFolio;

            // 2. Guardar las líneas de medicamentos
            foreach ($receta->getLineasRecetas() as $lineaReceta) {
                $medicamento = $lineaReceta->getMedicamento();

                // Crear línea de receta
                LineaRecetaModel::create([
                    'RecetaFolio' => $folio,
                    'MedicamentoID' => $medicamento->getId(),
                    'LRCantidad' => $lineaReceta->getCantidad(),
                    'LRPrecio' => $medicamento->getPrecio(),
                ]);

                // 3. Guardar los detalles de la línea (distribución por sucursales)
                foreach ($lineaReceta->getDetalleLineaReceta() as $detalle) {
                    $sucursal = $detalle->getSucursal();

                    DetalleLineaRecetaModel::create([
                        'RecetaFolio' => $folio,
                        'MedicamentoID' => $medicamento->getId(),
                        'SucursalID' => $sucursal->getSucursalId(),
                        'CadenaID' => $sucursal->getCadena()->getCadenaId(),
                        'DLRCantidad' => $detalle->getCantidad(),
                        'DLREstatus' => $detalle->getEstatus(), // Estado inicial
                    ]);
                }
            }

            return $folio;
        });
    }

    /**
     * Actualiza una receta existente
     *
     * @param Receta $receta
     * @return int
     */
    public function actualizarReceta(Receta $receta): int
    {
        return DB::transaction(function () use ($receta) {
            $folio = $receta->getFolio();

            if (!$folio) {
                throw new \InvalidArgumentException('La receta debe tener un folio para poder actualizarla');
            }

            // 1. Actualizar el encabezado de la receta
            $recetaModel = RecetaModel::findOrFail($folio);
            $recetaModel->update([
                'CedulaDoctor' => $receta->getCedulaDoctor(),
                'RecetaFecha' => $receta->getFecha()->format('Y-m-d'),
                'PacienteID' => $receta->getPaciente()->getId(),
                'CadenaID' => $receta->getSucursal()->getCadena()->getCadenaId(),
                'SucursalID' => $receta->getSucursal()->getSucursalId(),
                'RecetaEstado' => $receta->getEstado(),
            ]);

            // 2. Actualizar las líneas de medicamentos
            foreach ($receta->getLineasRecetas() as $lineaReceta) {
                $medicamento = $lineaReceta->getMedicamento();

                // Actualizar o crear línea de receta
                LineaRecetaModel::updateOrCreate(
                    [
                        'RecetaFolio' => $folio,
                        'MedicamentoID' => $medicamento->getId(),
                    ],
                    [
                        'LRCantidad' => $lineaReceta->getCantidad(),
                        'LRPrecio' => $medicamento->getPrecio(),
                    ]
                );

                // 3. Actualizar los detalles de la línea (distribución por sucursales)
                foreach ($lineaReceta->getDetalleLineaReceta() as $detalle) {
                    $sucursal = $detalle->getSucursal();

                    DetalleLineaRecetaModel::updateOrCreate(
                        [
                            'RecetaFolio' => $folio,
                            'MedicamentoID' => $medicamento->getId(),
                            'SucursalID' => $sucursal->getSucursalId(),
                            'CadenaID' => $sucursal->getCadena()->getCadenaId(),
                        ],
                        [
                            'DLRCantidad' => $detalle->getCantidad(),
                            'DLREstatus' => $detalle->getEstatus(),
                        ]
                    );
                }
            }

            return $folio;
        });
    }

    /**
     * Obtiene una receta por su folio (para futuras consultas)
     *
     * @param int $folio
     * @return RecetaModel|null
     */
    public function obtenerRecetaPorFolio(int $folio): ?RecetaModel
    {
        return RecetaModel::with(['lineas.medicamento', 'paciente'])
            ->find($folio);
    }
}
