<?php

namespace App\Http\Controllers;

use App\Domain\ModeloSesiones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControladorSesiones
{
    private ModeloSesiones $modeloSesiones;

    public function __construct()
    {
        $this->modeloSesiones = new ModeloSesiones();
    }


    public function mostrarLogin()
    {
        return view('login.login');
    }

    public function iniciarSesion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'nip' => 'required|string|min:8',
            'tipo_usuario' => 'required|in:paciente,admin'
        ]);

        $correo = $request->input('correo');
        $nip = $request->input('nip');
        $tipoUsuario = $request->input('tipo_usuario');

        try {
            if ($tipoUsuario === 'paciente') {
                $usuario = $this->modeloSesiones->obtenerPaciente($correo, $nip);
            } else {
                $usuario = $this->modeloSesiones->obtenerAdmin($correo, $nip);
            }
            if($usuario == null){
                throw new \Exception("Credenciales inválidas");
            }
            Session::put('usuario', $usuario);
            Session::put('tipo_usuario', $tipoUsuario);
            Session::put('correo', $correo);

            if ($tipoUsuario === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', '¡Bienvenido Administrador!');
            } else {
                return redirect()->route('paciente.dashboard')->with('success', '¡Bienvenido!');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function registrarUsuario(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'nip' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ], [
            'nip.regex' => 'El NIP debe contener al menos una mayúscula, una minúscula y un número.'
        ]);

        $correo = $request->input('correo');
        $nip = $request->input('nip');

        try {
            $this->modeloSesiones->registrarPaciente($correo, $nip);
            return redirect()->route('login')->with('success', 'Registro exitoso. Por favor complete su perfil.');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function cerrarSesion(Request $request)
    {
        try {
            $usuario = Session::get('usuario');
            $tipoUsuario = Session::get('tipo_usuario');

            if ($usuario) {
                if ($tipoUsuario === 'paciente') {
                    $this->modeloSesiones->cerrarSesionPaciente($usuario);
                } else {
                    $this->modeloSesiones->cerrarSesionAdminSucursal($usuario);
                }
            }
            Session::flush();
            return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error al cerrar sesión: ' . $e->getMessage());
        }
    }
}
