<?php

namespace App\Providers;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ServicioOCR
{

    public function __construct()
    {
    }

    public function escanearReceta($archivoImagen)
    {
        try {
            if (!file_exists($archivoImagen)) {
                return [];
            }
            $ocr = new TesseractOCR($archivoImagen);
            $texto = $ocr->run();
            $lineas = array_filter(explode("\n", $texto), function($linea) {
                return trim($linea) !== '';
            });
            
            $medicamentos = [];
            foreach ($lineas as $linea) {
                $palabras = explode(" ", $linea);
                foreach ($palabras as $palabra) {
                    if (trim($palabra) !== '') {
                        $medicamentos[] = trim($palabra);
                    }
                }
            }
            
            return $medicamentos;
        } catch (\Exception $e) {
            return [];
        }
    }
}
