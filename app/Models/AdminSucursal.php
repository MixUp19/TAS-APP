<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSucursal extends Model
{
    use HasFactory;

    protected $table = 'AdminSucursal';
    protected $primaryKey = 'AdminNumeroEmpleado';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'AdminNombre',
        'AdminApellidoPaterno',
        'AdminApellidoMaterno',
        'AdminCorreo',
        'AdminTelefono',
        'AdminContrasena',
        'AdminActivo',
        'AdminIntentosFallidos',
        'AdminFechaUltimoIntento',
        'SucursalID',
        'CadenaID',
    ];

    protected $casts = [
        'AdminNumeroEmpleado' => 'integer',
        'AdminActivo' => 'boolean',
        'AdminIntentosFallidos' => 'integer',
        'AdminFechaUltimoIntento' => 'datetime',
    ];

    public function cadena()
    {
        return $this->belongsTo(Cadena::class, 'CadenaID', 'CadenaID');
    }
}

