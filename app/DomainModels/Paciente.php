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
    private string $contrasena;
    private bool $activo;
    private int $intentosFallidos;
    private ?\DateTime $ultimoIntento;

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
    public function __construct(int $id =null,
                                string $nombre=null,
                                string $apellidoMaterno=null,
                                string $apellidoPaterno=null,
                                string $email = null,
                                string $telefono = null,
                                string $contrasena = null,
                                bool $sesionActiva = null,
                                int $intentosFallidos = null, ?\DateTime $ultimoIntento)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->contrasena = $contrasena;
        $this->sesionActiva = $sesionActiva;
        $this->intentosFallidos = $intentosFallidos;
        $this->ultimoIntento = $ultimoIntento;
    }

    public function isSesionActiva(): bool
    {
        return $this->sesionActiva;
    }

    public function getIntentosFallidos(): int
    {
        return $this->intentosFallidos;
    }

    public function getUltimoIntento(): ?\DateTime
    {
        return $this->ultimoIntento;
    }

    public function autenticar($nip): bool
    {
        if($this->sesionActiva){
            throw new \Exception("La sesión ya está activa para este usuario.");
        }
        if($this->intentosFallidos >= 3){
            $this->verificarTiempo();
        }
        if(password_verify($nip, $this->contrasena)){
            $this->exito();
            return true;
        }
        $this->fallo();
        return false;
    }

    private function verificarTiempo(){
        if($this->ultimoIntento){
            $ahora = new \DateTime();
            $diferencia = $ahora->getTimestamp() - $this->ultimoIntento->getTimestamp();
            if ($diferencia < 30 * 60) {
                throw new \Exception("Cuenta bloqueada. Intente nuevamente más tarde.");
            }
            $this->intentosFallidos = 0;
        }
    }
    private function exito(){
        $this->sesionActiva = true;
        $this->intentosFallidos = 0;
        $this->ultimoIntento = null;
    }

    private function fallo(){
        $this->intentosFallidos += 1;
        $this->ultimoIntento = new \DateTime();
    }

    public function cerrarSesion(){
        $this->sesionActiva=false;
    }

    public function setContrasena(string $contrasena): void
    {
        $this->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
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
