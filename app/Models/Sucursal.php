<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'Sucursal';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'SucursalID',
        'SucursalColonia',
        'SucursalCalle',
        'SucursalLatitud',
        'SucursalLongitud',
        'CiudadID',
        'CadenaID',
    ];

    protected $casts = [
        'SucursalLatitud' => 'decimal:6',
        'SucursalLongitud' => 'decimal:6',
        'CiudadID' => 'integer',
        'SucursalID' => 'string',
        'CadenaID' => 'string',
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'CiudadID', 'CiudadID');
    }

    public function cadena()
    {
        return $this->belongsTo(Cadena::class, 'CadenaID', 'CadenaID');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'SucursalID', 'SucursalID');
    }

    public static function findByKeys($sucursalId, $cadenaId)
    {
        return self::where('SucursalID', $sucursalId)
            ->where('CadenaID', $cadenaId)
            ->first();
    }
}

