<?php

namespace App\DomainModels;

class Medicamento
{
    private int $id;
    private string $nombre;
    private string $compuestoActivo;
    private double $precio;
    private string $unidad;
    private double $contenido;

    public function __construct()
    {

    }
}
