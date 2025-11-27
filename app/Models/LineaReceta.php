<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaReceta extends Model
{
    use HasFactory;

    protected $table = 'LINEA_RECETA';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RecetaFolio',
        'MedicamentoID',
        'LRCantidad',
        'LRPrecio',
    ];

    protected $casts = [
        'RecetaFolio' => 'integer',
        'MedicamentoID' => 'integer',
        'LRCantidad' => 'integer',
        'LRPrecio' => 'decimal:2',
    ];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'RecetaFolio', 'RecetaFolio');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'MedicamentoID', 'MedicamentoID');
    }

    public function detallesRelacion()
    {
        return $this->hasMany(DetalleLineaReceta::class, ['RecetaFolio', 'MedicamentoID'], ['RecetaFolio', 'MedicamentoID']);
    }

    public function detalles()
    {
        return DetalleLineaReceta::where('RecetaFolio', $this->RecetaFolio)
            ->where('MedicamentoID', $this->MedicamentoID)
            ->get();
    }
}

