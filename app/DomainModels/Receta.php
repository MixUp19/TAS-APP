<?php

namespace App\DomainModels;

use Illuminate\Support\Facades\Date;

class Receta
{
    private ?Paciente $paciente;
    private Sucursal $sucursal;
    private string $cedulaDoctor;
    private Date $fecha;
    private string $estado;
    private array $lineasRecetas;
    public function __construct($paciente){
        $this->paciente = $paciente;
        $this->lineasRecetas = [];
    }
    public function anadirLinea($med, $cantidad){
        $lr = new LineaReceta($med,$cantidad);
        $this->lineasRecetas[] = $lr;
    }
    public function getTotal(){
        $total = 0;
        foreach($this->lineasRecetas as $lr){
            $total += $lr->getSubtotal();
        }
        return $total;
    }

    public function modificarMedicamento($id, $cantidad){
        foreach ($this->lineasRecetas as $index => $linea) {
            if ($linea->getMedicamento()->getId() === $id) {
                if ($cantidad <= 0) {
                    unset($this->lineasRecetas[$index]);
                    $this->lineasRecetas = array_values($this->lineasRecetas);
                } else {
                    $linea->setCantidad($cantidad);
                }
            }
        }
    }
    public function anadirLineaLr($lr){
        $this->lineasRecetas[] = $lr;
    }
    public function notificarDevolucion($id){}


    public function getPaciente(): Paciente
    {
        return $this->paciente;
    }

    public function setPaciente(Paciente $paciente): void
    {
        $this->paciente = $paciente;
    }

    public function getSucursal(): Sucursal
    {
        return $this->sucursal;
    }

    public function setSucursal(Sucursal $sucursal): void
    {
        $this->sucursal = $sucursal;
    }

    public function getCedulaDoctor(): string
    {
        return $this->cedulaDoctor;
    }

    public function setCedulaDoctor(string $cedulaDoctor): void
    {
        $this->cedulaDoctor = $cedulaDoctor;
    }

    public function getFecha(): Date
    {
        return $this->fecha;
    }

    public function setFecha(Date $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getLineasRecetas(): array
    {
        return $this->lineasRecetas;
    }

    public function setLineasRecetas(array $lineasRecetas): void
    {
        $this->lineasRecetas = $lineasRecetas;
    }

}
