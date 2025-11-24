<?php

namespace App\DomainModels;

class Cadena
{
    private string $cadenaId;
    private string $nombre;

    public function __construct(
        string $cadenaId,
        string $nombre
    ) {
        $this->cadenaId = $cadenaId;
        $this->nombre = $nombre;
    }

    public function getCadenaId(): string
    {
        return $this->cadenaId;
    }

    public function setCadenaId(string $cadenaId): void
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
