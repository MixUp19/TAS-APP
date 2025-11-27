<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'Inventario';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'SucursalID',
        'CadenaID',
        'MedicamentoID',
        'InventarioCantidad',
        'InventarioMaximo',
        'InventarioMinimo',
    ];

    protected $casts = [
        'InventarioCantidad' => 'integer',
        'InventarioMaximo' => 'integer',
        'InventarioMinimo' => 'integer',
        'MedicamentoID' => 'integer',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'MedicamentoID', 'MedicamentoID');
    }

    public function sucursal()
    {
        return Sucursal::findByKeys($this->SucursalID, $this->CadenaID);
    }
}

