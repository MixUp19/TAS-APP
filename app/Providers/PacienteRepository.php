<?php

namespace App\Providers;
use App\DomainModels\Paciente;
use App\Models\Paciente as PacienteModel;

class PacienteRepository
{
    public function obtenerPacientePorId(int $pacienteId, bool $lock = false): Paciente
    {
        $query = PacienteModel::find($pacienteId);
        if ($lock) {
            $query->lockForUpdate();
        }
        return $this->eloquentToDomain($query->first());
    }

    private function eloquentToDomain(PacienteModel $paciente):Paciente{
        return new Paciente(
            $paciente->PacienteID,
            $paciente->PacienteNombre,
            $paciente->PacienteApellidoMaterno,
            $paciente->PacienteApellidoPaterno,
            $paciente->PacienteCorreo,
            $paciente->PacienteTelefono,
            $paciente->PacienteContrasena,
            $paciente->PacienteActivo,
            $paciente->PacienteIntentosFallidos,
            $paciente->PacienteFechaUltimoIntento ? new \DateTime($paciente->PacienteFechaUltimoIntento) : null
        );
    }
}
