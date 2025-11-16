<?php

class ModeloProcesarReceta {
    public function __construct() {}
    public function iniciarPedido($paciente){}
    public function seleccionarSucursal($sucursalId){}
    public function seleccionarMedicamento($id, $cantidad){}
    public function finalizarReceta($pedidoId){}
    public function cancelarReceta(){}
    public function cambiarSucursal($sucursalId){}
    public function modificarMedicamento($id, $cantidad){}
    public function confirmarMedicamento($lineasDeMedicamento){}
    public function escanearReceta($imagen){}
}
