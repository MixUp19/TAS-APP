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

    public function getCadena(): Cadena
    {
        return $this->cadena;
    }

    public function setCadena(Cadena $cadena): void
    {
        $this->cadena = $cadena;
    }

    public function getSucursalId(): int
    {
        return $this->sucursalId;
    }

    public function setSucursalId(int $sucursalId): void
    {
        $this->sucursalId = $sucursalId;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getColonia(): string
    {
        return $this->colonia;
    }

    public function setColonia(string $colonia): void
    {
        $this->colonia = $colonia;
    }

    public function getCalle(): string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): void
    {
        $this->calle = $calle;
    }

    public function getLatitud(): float
    {
        return $this->latitud;
    }

    public function setLatitud(float $latitud): void
    {
        $this->latitud = $latitud;
    }

    public function getLongitud(): float
    {
        return $this->longitud;
    }

    public function setLongitud(float $longitud): void
    {
        $this->longitud = $longitud;
    }

    public function getCiudadId(): int
    {
        return $this->ciudadId;
    }

    public function setCiudadId(int $ciudadId): void
    {
        $this->ciudadId = $ciudadId;
    }


}
