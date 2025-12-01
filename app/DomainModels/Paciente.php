<?php

namespace App\DomainModels;

class Paciente extends Autenticador
{
    private int $id;
    private string $nombre;
    private string $apellidoMaterno;
    private string $apellidoPaterno;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $apellidoMaterno
     * @param string $apellidoPaterno
     * @param string $email
     * @param string $telefono
     * @param string $contrasena
     * @param bool $sesionActiva
     * @param int $intentosFallidos
     * @param \DateTime|null $ultimoIntento
     */
    public function __construct(int $id,
                                string $nombre,
                                string $apellidoMaterno,
                                string $apellidoPaterno,
                                string $email,
                                string $telefono,
                                string $contrasena,
                                bool $sesionActiva,
                                int $intentosFallidos, ?\DateTime $ultimoIntento)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->apellidoPaterno = $apellidoPaterno;
        parent::__construct($email, $telefono, $contrasena, $sesionActiva, $intentosFallidos, $ultimoIntento);
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
