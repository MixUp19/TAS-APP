<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'Ciudad';
    protected $primaryKey = 'CiudadID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'CiudadNombre',
        'EstadoID',
    ];

    protected $casts = [
        'CiudadID' => 'integer',
        'EstadoID' => 'integer',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'EstadoID', 'EstadoID');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'CiudadID', 'CiudadID');
    }
}

