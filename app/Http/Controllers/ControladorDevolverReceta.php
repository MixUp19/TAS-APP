<?php

namespace App\Http\Controllers;

use App\Domain\ModeloDevolverReceta;

class ControladorDevolverReceta
{
    public function __construct(private ModeloDevolverReceta $modeloDevolverReceta)
    {
    }
}
