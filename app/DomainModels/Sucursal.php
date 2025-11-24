<?php

namespace App\DomainModels;

class Sucursal
{
    private Cadena $cadena;
    private string $sucursalId;
    private string $colonia;
    private string $calle;
    private float $latitud;
    private float $longitud;
    private int $ciudadId;
    public function __construct(
        Cadena $cadena,
        string $sucursalId,
        string $colonia,
        string $calle,
        float $latitud,
        float $longitud,
        float $ciudadId
    )
    {
        $this->cadena = $cadena;
        $this->sucursalId = $sucursalId;
        $this->colonia = $colonia;
        $this->calle = $calle;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->ciudadId = $ciudadId;
    }

    public function getCadena(): Cadena
    {
        return $this->cadena;
    }

    public function setCadena(Cadena $cadena): void
    {
        $this->cadena = $cadena;
    }

    public function getSucursalId(): string
    {
        return $this->sucursalId;
    }

    public function setSucursalId(string $sucursalId): void
    {
        $this->sucursalId = $sucursalId;
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
