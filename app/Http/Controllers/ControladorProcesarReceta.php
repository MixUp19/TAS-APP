<?php

namespace App\Http\Controllers;

use App\Domain\ModeloProcesarReceta;
use App\DomainModels\Receta;
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


    private function guardarModelo(Request $request, ModeloProcesarReceta $modelo): void
    {
        $request->session()->put('proceso_receta', $modelo);
    }

    public function iniciarPedido(Request $request, Paciente $paciente)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->iniciarPedido($paciente);
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

        $total = $modelo->finalizarReceta();
        $receta = $modelo->getReceta();
        $this->guardarModelo($request, $modelo);


        return view('receta/confirmar-receta', ['total' => $total, 'receta'=> $receta]);
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
        $modelo->confirmarReceta();
        $request->session()->forget('proceso_receta');
    }
        try {
            $modelo = $this->obtenerOInicializarModelo($request);

            // Verificar que existe una receta en el modelo
            $receta = $modelo->getReceta();
            if (!$receta) {
                return back()->withErrors('No hay una receta para confirmar. Por favor inicie el proceso nuevamente.');
            }

            // Verificar que la receta tenga los datos necesarios
            if (!$receta->getSucursal()) {
                return back()->withErrors('La receta no tiene una sucursal asignada.');
            }

            if (empty($receta->getLineasRecetas())) {
                return back()->withErrors('La receta no tiene medicamentos seleccionados.');
            }

            // Log para debug
            \Log::info('Iniciando confirmación de receta', [
                'sucursal' => $receta->getSucursal()->getSucursalId(),
                'num_lineas' => count($receta->getLineasRecetas())
            ]);

            // Confirmar la receta y obtener el folio
            $folio = $modelo->confirmarReceta();

            \Log::info('Receta confirmada exitosamente', ['folio' => $folio]);

            // Limpiar la sesión después de confirmar
            $request->session()->forget('proceso_receta');

            // Redirigir con mensaje de éxito
            return redirect()->route('receta.confirmacion', ['folio' => $folio])
                ->with('success', "Receta confirmada exitosamente. Folio: {$folio}");

        } catch (\Exception $e) {
            \Log::error('Error al confirmar receta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors('Error al confirmar la receta: ' . $e->getMessage());
        }
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
        $paciente = new \App\DomainModels\Paciente(1,"Usuario","Prueba","Test","test@test.com","1234567890","password",true, 0, null);
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

    /**
     * Muestra la receta para que el usuario la revise antes de confirmar
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function revisarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $receta = $modelo->getReceta();

        // Debug: verificar estado de la receta
        \Log::info('Revisando receta', [
            'receta_existe' => $receta !== null,
            'num_lineas' => $receta ? count($receta->getLineasRecetas()) : 0,
            'tiene_sucursal' => $receta && $receta->getSucursal() ? 'Si' : 'No'
        ]);

        if (!$receta) {
            return redirect()->route('receta.formulario')
                ->withErrors('No hay una receta en proceso. Por favor inicie una nueva.');
        }

        // Verificar que tenga líneas de medicamentos
        if (empty($receta->getLineasRecetas())) {
            return redirect()->route('receta.seleccionarMedicamentos')
                ->withErrors('No hay medicamentos seleccionados. Por favor agregue al menos uno.');
        }

        return view('receta.revisar', ['receta' => $receta]);
    }

    /**
     * Muestra la confirmación de la receta guardada
     *
     * @param int $folio
     * @return \Illuminate\View\View
     */
    public function mostrarConfirmacion($folio)
    {
        return view('receta.confirmacion', ['folio' => $folio]);
    }

}
