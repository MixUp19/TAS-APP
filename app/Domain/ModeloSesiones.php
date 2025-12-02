<?php

namespace App\Domain;

use App\DomainModels\Autenticador;
use App\DomainModels\Paciente as DomainPaciente;
use App\DomainModels\AdminSucursal as DomainAdminSucursal;
use App\Providers\PacienteRepository;
use App\Providers\AdminSucursalRepository;
use Illuminate\Support\Facades\DB;

class ModeloSesiones
{
    private PacienteRepository $pacienteRepository;
    private AdminSucursalRepository $adminRepository;

    public function __construct()
    {
        $this->pacienteRepository = new PacienteRepository();
        $this->adminRepository = new AdminSucursalRepository();
    }

    public function obtenerPaciente(string $correo, string $nip): ?DomainPaciente
    {
        try {
            DB::beginTransaction();

            $paciente = $this->pacienteRepository->obtenerPacientePorCorreo($correo, true);
            $exito = $this->validarUsuario($paciente, $nip);

            $this->pacienteRepository->actualizarPaciente($paciente);
            DB::commit();

            if(!$exito){
                $paciente = null;
            }

            return $paciente;
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function obtenerAdmin(string $correo, string $nip): ?DomainAdminSucursal
    {
        try {
            DB::beginTransaction();

            $admin = $this->adminRepository->obtenerAdminSucursalPorCorreo($correo, true);
            $exito = $this->validarUsuario($admin, $nip);

            $this->adminRepository->actualizarAdminSucursal($admin);
            DB::commit();

            if (!$exito) {
                $admin = null;
            }
            return $admin;
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function validarUsuario(?Autenticador $usuario, string $nip): bool
    {
        if (!$usuario) {
            throw new \Exception("Credenciales invalidas.");
        }

        return $usuario->autenticar($nip);
    }

    public function registrarPaciente(string $correo, string $nip): void
    {
        try {
            DB::beginTransaction();

            $existente = $this->pacienteRepository->obtenerPacientePorCorreo($correo);
            if ($existente) {
                throw new \Exception("El correo electrónico ya está registrado.");
            }
            $paciente = new DomainPaciente(
                0,
                'Nombre',
                '',
                'Apellido',
                $correo,
                '',
                '', // Contraseña temporal
                false,
                0,
                null
            );

            $paciente->setContrasena($nip);

            $this->pacienteRepository->crearPaciente($paciente);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function cerrarSesionPaciente(DomainPaciente $paciente): void
    {
        try {
            DB::beginTransaction();

            $paciente->cerrarSesion();
            $this->pacienteRepository->actualizarPaciente($paciente);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function cerrarSesionAdminSucursal(DomainAdminSucursal $admin): void
    {
        try {
            DB::beginTransaction();

            $admin->cerrarSesion();
            $this->adminRepository->actualizarAdminSucursal($admin);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
