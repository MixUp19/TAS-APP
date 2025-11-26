<?php

namespace App\DomainModels;

class DetalleLineaReceta
{
    private Sucursal $sucursal;
    private int $cantidad;
    private string $estatus;

    public function __construct($sucursal, $cantidad, $estatus)
    {
        $this->sucursal = $sucursal;
        $this->cantidad = $cantidad;
        $this->estatus = $estatus;
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

    public function getEstatus(): string
    {
        return $this->estatus;
    }

    public function setEstatus(string $estatus): void
    {
        $this->estatus = $estatus;
    }

}
