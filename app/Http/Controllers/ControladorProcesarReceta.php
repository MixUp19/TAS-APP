<?php

namespace App\Http\Controllers;

use App\Domain\ModeloProcesarReceta;
use Illuminate\Http\Request;

class ControladorProcesarReceta
{
    private function obtenerOInicializarModelo(Request $request): ModeloProcesarReceta
    {
        return $request->session()->get('proceso_receta', new ModeloProcesarReceta());
    }

    private function guardarModelo(Request $request, ModeloProcesarReceta $modelo): void
    {
        $request->session()->put('proceso_receta', $modelo);
    }

    public function iniciarPedido(Request $request, $paciente){
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->iniciarPedido($paciente);
        $this->guardarModelo($request, $modelo);
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
    public function confirmarReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->confirmarReceta();
        $request->session()->forget('proceso_receta');
    }
    public function cancelarReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cancelarReceta();
        $request->session()->forget('proceso_receta');
    }
    public function cambiarSucursal(Request $request, $sucursal){
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


    public function obtenerMedicamentos(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $medicamentos =  $modelo->obtenerMedicamentos();
        $this->guardarModelo($request, $modelo);
        return view('receta/seleccionar-medicamentos', ['medicamentos' => $medicamentos]);
    }

    public function obtenerReceta(Request $request): Receta{
        $modelo = $this->obtenerOInicializarModelo($request);
        return $modelo->getReceta();
    }

    public function confirmarMedicamento(Request $request, $id)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->confirmarMedicamento($id);
        $this->guardarModelo($request, $modelo);
    }
    //funcion en dev para guardar el encabezado de la receta (NO final)
    public function seleccionarSucursal(Request $request){
        $datos = $request->validate([
            'sucursal_id' => 'required',
            'cedula'      => 'required',
            'fecha'       => 'required|date',
        ]);

        $modelo = $this->obtenerOInicializarModelo($request);

        // Partir el valor de sucursal_id en $sucursalId y $cadenaId
        list($sucursalId, $cadenaId) = explode(',', $datos['sucursal_id']);
        $paciente = new \App\DomainModels\Paciente(0,"a","a","a","a","a","a",false, 0, null);
        $modelo->iniciarPedido($paciente);
        dump($sucursalId,$cadenaId);
        $modelo->seleccionarSucursal($sucursalId, $cadenaId, $datos['cedula'], $datos['fecha']);

        $this->guardarModelo($request, $modelo);

        return redirect()->route('receta.seleccionarMedicamentos');
    }

    public function obtenerSucursales(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $sucursales = $modelo->obtenerSucursales();
        return view('receta.formularioReceta', ['sucursales' => $sucursales]);
    }

    public function escanearReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $request->validate([
            'recipe_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('recipe_image')) {
            $image = $request->file('recipe_image');
            $path = $image->store('recipes', 'public'); 

            $fullPath = storage_path('app/public/' . $path);
            
            $lineas = $modelo->escanearReceta($fullPath);
            $this->guardarModelo($request, $modelo);
            
            $lineasArray = array_map(function($linea) {
                $med = $linea->getMedicamento();
                return [
                    'medicamento' => [
                        'id' => $med->getId(),
                        'nombre' => $med->getNombre(),
                        'compuesto' => $med->getCompuestoActivo(),
                        'precio' => $med->getPrecio(),
                        'contenido' => $med->getContenido(),
                        'unidad' => $med->getUnidad(),
                    ],
                    'cantidad' => $linea->getCantidad(),
                ];
            }, $lineas);

            return response()->json([
                'success' => true,
                'medicamentos_detectados' => $lineasArray
            ]);
        }

        $this->guardarModelo($request, $modelo);

        return back()->withErrors(['recipe_image' => 'Error uploading file']);
    }

}
