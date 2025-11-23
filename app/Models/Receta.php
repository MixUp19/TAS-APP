<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $table = 'Receta';
    protected $primaryKey = 'RecetaFolio';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'CedulaDoctor',
        'RecetaFecha',
        'PacienteID',
        'CadenaID',
        'SucursalID',
    ];

    protected $casts = [
        'RecetaFolio' => 'integer',
        'RecetaFecha' => 'date',
        'PacienteID' => 'integer',
        'CadenaID' => 'string',
        'SucursalID' => 'string',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'PacienteID', 'PacienteID');
    }

    public function cadena()
    {
        return $this->belongsTo(Cadena::class, 'CadenaID', 'CadenaID');
    }
    public function lineas()
    {
        return $this->hasMany(LineaReceta::class, 'RecetaFolio', 'RecetaFolio');
    }

    // Helper para obtener la sucursal asociada (clave compuesta SucursalID+CadenaID)
    public function sucursal()
    {
        return Sucursal::findByKeys($this->SucursalID, $this->CadenaID);
    }
}