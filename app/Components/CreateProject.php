<?php
namespace App\Components;

use App\Helpers\View;

/**
 * Se encarga de cargar el componente para la creación de un nuevo proyecto.
 */
class CreateProject
{
    /**
     * Se encarga de generar el html para el input.
     */
    public static function load(string $xData): string
    {
        ob_start();
        
        View::loadComponent('create-new-project', [
            "xData" => $xData
        ]);

        return ob_get_clean();
    }
}