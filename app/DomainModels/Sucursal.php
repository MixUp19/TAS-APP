<?php

namespace App\DomainModels;

class Sucursal
{
    private Cadena $cadena;
    private int $sucursalId;
    private string $nombre;
    private string $colonia;
    private string $calle;
    private double $latitud;
    private double $longitud;
    private int $ciudadId;
    public function __construct()
    {

    }
}
