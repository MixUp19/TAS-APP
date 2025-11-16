<?php

namespace App\DomainModels;

class Paciente
{
    private int $id;
    private string $nombre;
    private string $apellidoMaterno;
    private string $apellidoPaterno;
    private string $email;
    private string $telefono;



    public function __construct()
    {
    }
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getApellidoPaterno(): string
    {
        return $this->apellidoPaterno;
    }

    public function setApellidoPaterno(string $apellidoPaterno): void
    {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function getApellidoMaterno(): string
    {
        return $this->apellidoMaterno;
    }

    public function setApellidoMaterno(string $apellidoMaterno): void
    {
        $this->apellidoMaterno = $apellidoMaterno;
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
