<?php

namespace App\DomainModels;

class Autenticador
{
    private string $email;
    private string $telefono;
    private string $contrasena;
    private bool $activo;
    private int $intentosFallidos;
    private ?\DateTime $ultimoIntento;

    public function __construct(
        string $email,
        string $telefono,
        string $contrasena,
        bool $activo,
        int $intentosFallidos,
        ?\DateTime $ultimoIntento
    ){
        $this->email = $email;
        $this->telefono = $telefono;
        $this->contrasena = $contrasena;
        $this->activo = $activo;
        $this->intentosFallidos = $intentosFallidos;
        $this->ultimoIntento = $ultimoIntento;
    }
    public function isSesionActiva(): bool
    {
        return $this->activo;
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
        if($this->activo){
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
        $this->activo = true;
        $this->intentosFallidos = 0;
        $this->ultimoIntento = null;
    }

    private function fallo(){
        $this->intentosFallidos += 1;
        $this->ultimoIntento = new \DateTime();
    }

    public function cerrarSesion(){
        $this->activo=false;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
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
}
