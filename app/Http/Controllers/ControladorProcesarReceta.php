<?php

namespace App\Http\Controllers;

use ModeloProcesarReceta;

class ControladorProcesarReceta
{
    private ModeloProcesarReceta $modeloProcesarReceta;
    public function iniciarPedido($paciente){
        $this->modeloProcesarReceta->iniciarPedido($paciente);
    }
    public function seleccionarSucursal($sucursal){
        $this->modeloProcesarReceta->seleccionarSucursal($sucursal);
    }
    public function seleccionarMedicamento($med, $cantidad){
        $this->modeloProcesarReceta->seleccionarMedicamento($med, $cantidad);
    }
    public function finalizarReceta(){
        $this->modeloProcesarReceta->finalizarReceta();
    }
    public function confirmarReceta(){
        $this->modeloProcesarReceta->confirmarReceta();
    }
    public function cancelarReceta(){}
    public function cambiarSucursal($sucursalId){}
    public function modificarMedicamento($id, $cantidad){}
    public function confirmarMedicamento($lineasDeMedicamento){}
    public function escanearReceta($imagen){}
}
