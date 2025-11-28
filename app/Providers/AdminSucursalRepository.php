<?php

namespace App\Providers;

use App\DomainModels\AdminSucursal;
use App\DomainModels\Cadena;
use App\DomainModels\Sucursal;
use App\Models\AdminSucursal as AdminSucursalModel;

class AdminSucursalRepository
{
    public function obtenerAdminSucursalPorCorreo(string $correo, bool $lock = false): ?AdminSucursal
    {
        $query = AdminSucursalModel::where('AdminCorreo', $correo);

        if ($lock) {
            $query->lockForUpdate();
        }

        $adminModel = $query->first();

        if (!$adminModel) {
            return null;
        }

        // Cargar la cadena directamente
        $adminModel->load('cadena');

        return $this->eloquentToDomain($adminModel);
    }

    public function actualizarAdminSucursal(AdminSucursal $admin): void
    {
        dump($admin);
        $adminModel = AdminSucursalModel::where('AdminCorreo', $admin->getEmail())->first();

        if ($adminModel) {
            $adminModel->AdminActivo = $admin->isSesionActiva();
            $adminModel->AdminIntentosFallidos = $admin->getIntentosFallidos();
            $adminModel->AdminFechaUltimoIntento = $admin->getUltimoIntento()?->format('Y-m-d H:i:s');
            $adminModel->save();
        }
    }

    private function eloquentToDomain(AdminSucursalModel $adminModel): AdminSucursal
    {
        // Cargar sucursal manualmente usando claves compuestas
        $sucursalModel = \App\Models\Sucursal::where('SucursalID', $adminModel->SucursalID)
            ->where('CadenaID', $adminModel->CadenaID)
            ->with('cadena')
            ->first();

        // Crear objeto Cadena del dominio
        $cadena = new Cadena(
            $adminModel->CadenaID,
            $adminModel->cadena->CadenaNombre ?? 'N/A'
        );

        // Crear objeto Sucursal del dominio
        $sucursal = new Sucursal(
            $cadena,
            $adminModel->SucursalID,
            $sucursalModel->SucursalColonia ?? '',
            $sucursalModel->SucursalCalle ?? '',
            (float)($sucursalModel->SucursalLatitud ?? 0),
            (float)($sucursalModel->SucursalLongitud ?? 0),
            (float)($sucursalModel->CiudadID ?? 0)
        );

        return new AdminSucursal(
            $adminModel->AdminNumeroEmpleado,
            $adminModel->AdminNombre,
            $adminModel->AdminApellidoPaterno,
            $adminModel->AdminApellidoMaterno ?? '',
            $sucursal,
            $adminModel->AdminCorreo,
            $adminModel->AdminTelefono ?? '',
            $adminModel->AdminContrasena,
            $adminModel->AdminActivo ?? false,
            $adminModel->AdminIntentosFallidos ?? 0,
            $adminModel->AdminFechaUltimoIntento ? new \DateTime($adminModel->AdminFechaUltimoIntento) : null
        );
    }
}

