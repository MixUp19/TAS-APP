<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleLineaReceta extends Model
{
    use HasFactory;

    protected $table = 'Detalle_Linea_Receta';
    // PK compuesto (RecetaFolio, MedicamentoID, SucursalID, CadenaID)
    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'RecetaFolio',
        'MedicamentoID',
        'SucursalID',
        'CadenaID',
        'DLRCantidad',
        'DLREstatus',
    ];

    protected $casts = [
        'RecetaFolio' => 'integer',
        'MedicamentoID' => 'integer',
        'DLRCantidad' => 'integer',
        'DLREstatus' => 'string',
    ];

    // helper para obtener la línea de receta padre
    public function lineaReceta()
    {
        return LineaReceta::where('RecetaFolio', $this->RecetaFolio)
            ->where('MedicamentoID', $this->MedicamentoID)
            ->first();
    }

    public function sucursal()
    {
        return Sucursal::findByKeys($this->SucursalID, $this->CadenaID);
    }
}

