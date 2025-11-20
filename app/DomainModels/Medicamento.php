<?php

namespace App\DomainModels;

class Medicamento
{
    private int $id;
    private string $nombre;
    private string $compuestoActivo;
    private float $precio;
    private string $unidad;
    private string $contenido;

    public function __construct(int $id, string $nombre, string $compuestoActivo, float $precio, string $unidad, string $contenido)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->compuestoActivo = $compuestoActivo;
        $this->precio = $precio;
        $this->unidad = $unidad;
        $this->contenido = $contenido;
    }

    public function getContenido(): string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): void
    {
        $this->contenido = $contenido;
    }

    public function getUnidad(): string
    {
        return $this->unidad;
    }

    public function setUnidad(string $unidad): void
    {
        $this->unidad = $unidad;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getCompuestoActivo(): string
    {
        return $this->compuestoActivo;
    }

    public function setCompuestoActivo(string $compuestoActivo): void
    {
        $this->compuestoActivo = $compuestoActivo;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


}
