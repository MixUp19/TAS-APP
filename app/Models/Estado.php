<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Estado extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'int';
    public $incrementing = true;
    protected $primaryKey = 'EstadoID';
    protected $table = 'Estado';

    protected $casts = [
        'EstadoNombre' => 'string',
        'EstadoID' => 'integer',
    ];

    protected $fillable = [
        'EstadoNombre',
        ];
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'EstadoID', 'EstadoID');
    }
}


















