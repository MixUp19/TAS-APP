<?php

namespace App\Providers;

use App\DomainModels\Paciente;
use App\Models\Paciente as PacienteModel;

class PacienteRepository
{
    public function obtenerPacientePorId(int $pacienteId, bool $lock = false): ?Paciente
    {
        $query = PacienteModel::where('PacienteID', $pacienteId);

        if ($lock) {
            $query->lockForUpdate();
        }

        $pacienteModel = $query->first();

        if (!$pacienteModel) {
            return null;
        }

        return $this->eloquentToDomain($pacienteModel);
    }

    public function obtenerPacientePorCorreo(string $correo, bool $lock = false): ?Paciente
    {
        $query = PacienteModel::where('PacienteCorreo', $correo);

        if ($lock) {
            $query->lockForUpdate();
        }

        $pacienteModel = $query->first();

        if (!$pacienteModel) {
            return null;
        }

        return $this->eloquentToDomain($pacienteModel);
    }

    public function actualizarPaciente(Paciente $paciente): void
    {
        $pacienteModel = PacienteModel::find($paciente->getId());

        if ($pacienteModel) {
            $pacienteModel->PacienteActivo = $paciente->isSesionActiva();
            $pacienteModel->PacienteIntentosFallidos = $paciente->getIntentosFallidos();
            $pacienteModel->PacienteFechaUltimoIntento = $paciente->getUltimoIntento() ?
                $paciente->getUltimoIntento()->format('Y-m-d H:i:s') : null;
            $pacienteModel->save();
        }
    }

    public function crearPaciente(Paciente $paciente): void
    {
        $pacienteModel = new PacienteModel();
        $pacienteModel->PacienteCorreo = $paciente->getEmail();
        $pacienteModel->PacienteContrasena = $paciente->getContrasena();
        $pacienteModel->PacienteNombre = $paciente->getNombre();
        $pacienteModel->PacienteApellidoPaterno = $paciente->getApellidoPaterno();
        $pacienteModel->PacienteApellidoMaterno = $paciente->getApellidoMaterno();
        $pacienteModel->PacienteTelefono = $paciente->getTelefono();
        $pacienteModel->PacienteFechaRegistro = date('Y-m-d');
        $pacienteModel->PacienteActivo = $paciente->isSesionActiva();
        $pacienteModel->PacienteIntentosFallidos = $paciente->getIntentosFallidos();
        $pacienteModel->save();
    }

    private function eloquentToDomain(PacienteModel $paciente): Paciente
    {
        return new Paciente(
            $paciente->PacienteID,
            $paciente->PacienteNombre,
            $paciente->PacienteApellidoMaterno ?? '',
            $paciente->PacienteApellidoPaterno,
            $paciente->PacienteCorreo,
            $paciente->PacienteTelefono ?? '',
            $paciente->PacienteContrasena,
            $paciente->PacienteActivo ?? false,
            $paciente->PacienteIntentosFallidos ?? 0,
            $paciente->PacienteFechaUltimoIntento ? new \DateTime($paciente->PacienteFechaUltimoIntento) : null
        );
    }
}
