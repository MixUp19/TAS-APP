<?php

namespace App\Domain;

use App\Providers\RecetaRepository;
use App\Providers\SucursalRepository;
use App\DomainModels\Sucursal;

class ModeloDevolverReceta
{
    private RecetaRepository $recetaRepository;
    private SucursalRepository $sucursalRepository;

    public function __construct()
    {
        $this->recetaRepository = app(RecetaRepository::class);
        $this->sucursalRepository = app(SucursalRepository::class);
    }

    public function cancelarPedido($Receta)
    {
    }

    public function confirmarCancelacion($receta)
    {
    }

    public function obtenerRecetas(){
        $sucursal = $this->sucursalRepository->obtenerSucursal( "SUC001", "FAR001");
        return $this->recetaRepository->obtenerRecetasPorSucursal($sucursal);
    }
}
