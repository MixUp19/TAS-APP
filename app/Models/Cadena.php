<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cadena extends Model
{
    use HasFactory;

    protected $table = 'Cadena';
    protected $primaryKey = 'CadenaID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CadenaID',
        'CadenaNombre',
    ];

    protected $casts = [
        'CadenaID' => 'string',
        'CadenaNombre' => 'string',
    ];

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'CadenaID', 'CadenaID');
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class, 'CadenaID', 'CadenaID');
    }
}

