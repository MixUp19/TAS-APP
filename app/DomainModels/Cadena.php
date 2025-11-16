<?php

namespace App\DomainModels;

class Cadena
{
    private int $cadenaId;
    private string $nombre;

    public function __construct() {}

    public function getCadenaId(): int
    {
        return $this->cadenaId;
    }

    public function setCadenaId(int $cadenaId): void
    {
        $this->cadenaId = $cadenaId;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

}
