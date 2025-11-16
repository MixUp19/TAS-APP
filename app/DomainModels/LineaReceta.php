<?php

namespace App\DomainModels;

class LineaReceta
{
    private Medicamento $medicamento;
    private array $detalleLineaReceta;
    private int $cantidad;

    public function __construct()
    {

    }
    public function getSubtotal(){}
    public function crearNotificaciones(){}
}
