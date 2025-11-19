<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'Medicamentos';
    protected $primaryKey = 'MedicamentoID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'MedicamentoNombre',
        'MedicamentoPrecio',
        'MedicamentoCompuestoActivo',
        'MedicamentoUnidad',
        'MedicamentoContenido',
    ];

    protected $casts = [
        'MedicamentoID' => 'integer',
        'MedicamentoPrecio' => 'decimal:2',
    ];

    public function lineas()
    {
        return $this->hasMany(LineaReceta::class, 'MedicamentoID', 'MedicamentoID');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'MedicamentoID', 'MedicamentoID');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleLineaReceta::class, 'MedicamentoID', 'MedicamentoID');
    }
}

