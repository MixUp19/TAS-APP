<?php

namespace App\DomainModels;

class LineaReceta
{
    private Medicamento $medicamento;
    private array $detalleLineaReceta;
    private int $cantidad;

    public function __construct($medicamento, $cantidad) {
        $this->medicamento = $medicamento;
        $this->cantidad = $cantidad;
        $this->detalleLineaReceta = [];
    }

    public function getSubtotal(): float
    {
        return $this->cantidad * $this->medicamento->getPrecio();
    }

    public function getMedicamentoId(): int
    {
        return $this->medicamento->getId();
    }

    public function crearNotificaciones(): void{
        foreach ($this->detalleLineaReceta as $dlr) {
            $dlr->setEstatus("En Devolucion");
        }
    }

    public function anadirSucursal($sucursal, $cantidad){
        $dlr = new DetalleLineaReceta($sucursal,$cantidad, "En proceso");
        $this->detalleLineaReceta[] = $dlr;
    }

    public function anadirDetalleLineaReceta(DetalleLineaReceta $detalleLineaReceta){
        $this->detalleLineaReceta[] = $detalleLineaReceta;
    }

    public function getMedicamento(): Medicamento
    {
        return $this->medicamento;
    }

    public function setMedicamento(Medicamento $medicamento): void
    {
        $this->medicamento = $medicamento;
    }

    public function getDetalleLineaReceta(): array
    {
        return $this->detalleLineaReceta;
    }

    public function setDetalleLineaReceta(array $detalleLineaReceta): void
    {
        $this->detalleLineaReceta = $detalleLineaReceta;
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
