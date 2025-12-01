<?php

namespace App\DomainModels;

class AdminSucursal extends Autenticador
{
    private int $numeroEmpleado;
    private string $nombre;
    private string $apellidoPaterno;
    private string $apellidoMaterno;
    private Sucursal $sucursal;

    /**
     * @param int $numeroEmpleado
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param Sucursal $sucursal
     */
    public function __construct(
        int $numeroEmpleado,
        string $nombre,
        string $apellidoPaterno,
        string $apellidoMaterno,
        Sucursal $sucursal,
        string $email,
        string $telefono,
        string $contrasena,
        bool $activo,
        int $intentosFallidos,
        ?\DateTime $ultimoIntento
    )
    {
        $this->numeroEmpleado = $numeroEmpleado;
        $this->nombre = $nombre;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->sucursal = $sucursal;
        parent::__construct($email, $telefono, $contrasena, $activo, $intentosFallidos, $ultimoIntento);
    }

    public function getNumeroEmpleado(): int
    {
        return $this->numeroEmpleado;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellidoPaterno(): string
    {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno(): string
    {
        return $this->apellidoMaterno;
    }

    public function getSucursal(): Sucursal
    {
        return $this->sucursal;
    }
}
