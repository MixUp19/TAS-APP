<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'Paciente';
    protected $primaryKey = 'PacienteID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'PacienteNombre',
        'PacienteApellidoPaterno',
        'PacienteApellidoMaterno',
        'PacienteTelefono',
        'PacienteCorreo',
        'PacienteFechaRegistro',
        'PacienteContrasena',
        'PacienteActivo',
        'PacienteIntentosFallidos',
        'PacienteFechaUltimoIntento',
    ];

    protected $casts = [
        'PacienteID' => 'integer',
        'PacienteFechaRegistro' => 'date',
    ];

    public function tarjetas()
    {
        return $this->hasMany(Tarjeta::class, 'PacienteID', 'PacienteID');
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class, 'PacienteID', 'PacienteID');
    }
}

