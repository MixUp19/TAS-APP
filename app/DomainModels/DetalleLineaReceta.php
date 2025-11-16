<?php

namespace App\DomainModels;

class DetalleLineaReceta
{
    private Sucursal $sucursal;
    private int $cantidad;

    public function __construct($sucursal, $cantidad)
    {
        $this->sucursal = $sucursal;
        $this->cantidad = $cantidad;
    }
    public function crearNotificacionDevolucion($medicamentoId){}

    public function getSucursal(): Sucursal
    {
        return $this->sucursal;
    }

    public function setSucursal(Sucursal $sucursal): void
    {
        $this->sucursal = $sucursal;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }


}
