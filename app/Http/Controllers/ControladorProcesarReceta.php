<?php

namespace App\Http\Controllers;

use App\Domain\ModeloProcesarReceta;
use App\DomainModels\Receta;
use App\Models\Paciente;
use Illuminate\Http\Request;

class ControladorProcesarReceta
{
    public function __construct()
    {
    }

    private function obtenerOInicializarModelo(Request $request): ModeloProcesarReceta
    {
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

    public function seleccionarMedicamentos(Request $request)
    {
        $medicamentos = $request->input('medicamentos', []);

        \Log::info('Guardando medicamentos', ['medicamentos' => $medicamentos]);

        if (empty($medicamentos)) {
            return back()->withErrors('No se ha seleccionado ningún medicamento.');
        }

        $modelo = $this->obtenerOInicializarModelo($request);

        if (!$modelo->getReceta()) {
            return redirect()->route('receta.formulario')
                ->withErrors('Debe iniciar una receta primero seleccionando sucursal y doctor.');
        }

        $modelo->limpiarLineas();

        foreach ($medicamentos as $medicamentoData) {
            $modelo->seleccionarMedicamento($medicamentoData['id'], $medicamentoData['cantidad']);
        }

        $total = $modelo->finalizarReceta();
        $receta = $modelo->getReceta();
        $this->guardarModelo($request, $modelo);

        $receta = $modelo->getReceta();
        \Log::info('Medicamentos guardados', [
            'num_lineas' => $receta ? count($receta->getLineasRecetas()) : 0
        ]);

        return view('receta/revisar', ['total' => $total, 'receta'=> $receta]);
    }

    public function confirmarReceta(Request $request)
    {
        try {
            $modelo = $this->obtenerOInicializarModelo($request);

            $receta = $modelo->getReceta();
            if (!$receta) {
                return back()->withErrors('No hay una receta para confirmar. Por favor inicie el proceso nuevamente.');
            }

            if (!$receta->getSucursal()) {
                return back()->withErrors('La receta no tiene una sucursal asignada.');
            }

            if (empty($receta->getLineasRecetas())) {
                return back()->withErrors('La receta no tiene medicamentos seleccionados.');
            }

            \Log::info('Iniciando confirmación de receta', [
                'sucursal' => $receta->getSucursal()->getSucursalId(),
                'num_lineas' => count($receta->getLineasRecetas())
            ]);

            $folio = $modelo->confirmarReceta();

            \Log::info('Receta confirmada exitosamente', ['folio' => $folio]);

            $request->session()->forget('proceso_receta');

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

    public function seleccionarSucursal(Request $request){
        $datos = $request->validate([
            'sucursal_id' => 'required',
            'cedula'      => 'required',
            'fecha'       => 'required|date',
        ]);

        $modelo = $this->obtenerOInicializarModelo($request);

        list($sucursalId, $cadenaId) = explode(',', $datos['sucursal_id']);
        //$paciente = new \App\DomainModels\Paciente(1,"Juan","Perez","García","juan.perez@email.com","6671234567","$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",true, 0, null);
        $paciente = $request->session()->get('usuario');
        $modelo->iniciarPedido($paciente);
        $modelo->seleccionarSucursal($sucursalId, $cadenaId, $datos['cedula'], $datos['fecha']);

        $this->guardarModelo($request, $modelo);

        return redirect()->route('receta.seleccionarMedicamentos');
    }

    public function obtenerSucursales(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $sucursales = $modelo->obtenerSucursales();
        return view('receta.formularioReceta', ['sucursales' => $sucursales]);
    }

    public function revisarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $receta = $modelo->getReceta();

        \Log::info('Revisando receta', [
            'receta_existe' => $receta !== null,
            'num_lineas' => $receta ? count($receta->getLineasRecetas()) : 0,
            'tiene_sucursal' => $receta && $receta->getSucursal() ? 'Si' : 'No'
        ]);

        if (!$receta) {
            return redirect()->route('receta.formulario')
                ->withErrors('No hay una receta en proceso. Por favor inicie una nueva.');
        }


        if (empty($receta->getLineasRecetas())) {
            return redirect()->route('receta.seleccionarMedicamentos')
                ->withErrors('No hay medicamentos seleccionados. Por favor agregue al menos uno.');
        }

        return view('receta.revisar', ['receta' => $receta]);
    }

    public function mostrarConfirmacion($folio)
    {
        return view('receta.confirmacion', ['folio' => $folio]);
    }

    public function buscarReceta(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);
        $receta = $modelo->buscarReceta($request->input('folio'));
        return view('paciente.dashboard', ['receta' => $receta]);
    }

    public function obtenerRecetasPaciente(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);

        $paciente = $request->session()->get('usuario');

        if (!$paciente) {
            return redirect()->route('login')->withErrors('Debe iniciar sesión primero.');
        }

        $recetas = $modelo->obtenerRecetasPaciente($paciente->getId());

        return view('paciente.dashboard', ['recetas' => $recetas]);
    }
}
