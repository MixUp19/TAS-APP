<?php

namespace App\DomainModels;

use Illuminate\Support\Facades\Date;

class Receta
{
    private string $cedulaDoctor;
    private Date $fecha;
    private string $estado;
    public function __construct(){}

    public function anadirLinea($med, $cantidad){}
    public function getTotal(){}
    public function modificarMedicamento($id, $cantidad){}
    public function anadirLineaLr($lr){}
    public function notificarDevolucion($id){}
}
