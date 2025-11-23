<?php

namespace App\Http\Controllers;

use App\Domain\ModeloProcesarReceta;
use App\Models\Paciente; // Usamos el modelo de Eloquent
use Illuminate\Http\Request;

class ControladorProcesarReceta
{
    // El constructor ya no necesita inyectar el modelo, ya que se gestionará por sesión.
    public function __construct()
    {
    }

    /**
     * Obtiene el modelo del proceso desde la sesión del usuario.
     * Si no existe, crea uno nuevo y lo retorna.
     */
    private function obtenerOInicializarModelo(Request $request): ModeloProcesarReceta
    {
        // Usamos el helper session() para obtener/guardar datos en la sesión.
        // 'proceso_receta' es la clave única para este proceso.
        return $request->session()->get('proceso_receta', new ModeloProcesarReceta());
    }

    /**
     * Guarda el estado actual del modelo en la sesión.
     */
    private function guardarModelo(Request $request, ModeloProcesarReceta $modelo): void
    {
        $request->session()->put('proceso_receta', $modelo);
    }

    public function iniciarPedido(Request $request, Paciente $paciente)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->iniciarPedido($paciente);
        $this->guardarModelo($request, $modelo);
        // Aquí podrías redirigir a la siguiente vista, ej: seleccionar sucursal
    }

    public function seleccionarSucursal(Request $request, $sucursal)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->seleccionarSucursal($sucursal);
        $this->guardarModelo($request, $modelo);
    }

    public function seleccionarMedicamento(Request $request)
    {
        $request->validate([
            'medicamento_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $medicamentoId = $request->input('medicamento_id');
        $cantidad = $request->input('cantidad');

        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->seleccionarMedicamento($medicamentoId, $cantidad);
        $this->guardarModelo($request, $modelo);
        return back()->with('success', 'Medicamento añadido a la receta.');
    }

    public function guardarMedicamentos(Request $request)
    {
        $medicamentos = $request->input('medicamentos', []);

        if (empty($medicamentos)) {
            return back()->withErrors('No se ha seleccionado ningún medicamento.');
        }

        $modelo = $this->obtenerOInicializarModelo($request);


        foreach ($medicamentos as $medicamentoData) {
            $modelo->seleccionarMedicamento($medicamentoData['id'], $medicamentoData['cantidad']);
        }

        $this->guardarModelo($request, $modelo);


        return redirect()->route('receta.seleccionarMedicamentos')->with('success', 'Receta guardada correctamente.');
    }


    public function finalizarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $total = $modelo->finalizarReceta();
        // Al finalizar, guardamos en la BD (hecho dentro de confirmarReceta) y limpiamos la sesión.
        $request->session()->forget('proceso_receta');
        return $total;
    }

    public function confirmarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->confirmarReceta(); // Este método guarda en la base de datos
        $request->session()->forget('proceso_receta'); // Limpiamos la sesión después de confirmar
    }

    public function cancelarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cancelarReceta();
        $request->session()->forget('proceso_receta'); // Limpiamos la sesión
    }

    public function cambiarSucursal(Request $request, $sucursal)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cambiarSucursal($sucursal);
        $this->guardarModelo($request, $modelo);
    }

    public function modificarMedicamento(Request $request, $id, $cantidad)
    {
        $request->validate([
            'medicamento_id'=> 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);
        $id = $request->input('medicamento_id');
        $cantidad = $request->input('cantidad');
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->modificarMedicamento($id, $cantidad);
        $this->guardarModelo($request, $modelo);
    }

    public function escanearReceta(Request $request, $imagen)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->escanearReceta($imagen);
        $this->guardarModelo($request, $modelo);
    }

    public function obtenerMedicamentos(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $medicamentos =  $modelo->obtenerMedicamentos();
        $modelo->iniciarPedido(null);
        $this->guardarModelo($request, $modelo);
        return view('receta/seleccionar-medicamentos', ['medicamentos' => $medicamentos]);
    }

    public function confirmarMedicamento(Request $request, $id)
    {   
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->confirmarMedicamento($id);
        $this->guardarModelo($request, $modelo);
    }



    //funcion en dev para guardar el encabezado de la receta (NO final)
    public function guardarEncabezado(Request $request){
    $datos = $request->validate([
        'sucursal_id' => 'required',
        'cedula'      => 'required',
        'fecha'       => 'required|date',
    ]);

    $modelo = $this->obtenerOInicializarModelo($request);

    
    $modelo->seleccionarSucursal($datos['sucursal_id']);
    $modelo->cedula = $datos['cedula']; 
    $modelo->fecha  = $datos['fecha'];

    $this->guardarModelo($request, $modelo);

    return redirect()->route('receta.seleccionarMedicamentos');
    }

    public function obtenerSucursales(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $sucursales = $modelo->obtenerSucursales();
        return view('receta.formularioReceta', ['sucursales' => $sucursales]);
    }

}
