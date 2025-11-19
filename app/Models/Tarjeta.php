<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $table = 'Tarjeta';
    protected $primaryKey = 'Tarjeta';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Tarjeta',
        'TarjetaNombreTitular',
        'TarjetaTipoTarjeta',
        'TarjetaFechaVencimiento',
        'TarjetaCVV',
        'PacienteID',
    ];

    protected $casts = [
        'Tarjeta' => 'string',
        'TarjetaFechaVencimiento' => 'date',
        'TarjetaCVV' => 'string',
        'PacienteID' => 'integer',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'PacienteID', 'PacienteID');
    }
}

