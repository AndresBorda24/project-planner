<?php
namespace App\Components;

use App\Helpers\View;

/**
 * Genera un input tipo select `dinamico` que permite realizar una busqueda entre las 
 * opciones y crear nuevas de forma rápida.
 */
class DynamicSelect
{
    public static $ref = 0;
    private static array $types = [
        "gema" => "gema-scope",
        "status" => ""
    ];

    /**
     * Se encarga de generar el html para el input.
     * 
     * @param string $title Es un titulo que se mostrará como label del input
     * @param string $model Ya que se está trabajando con `Alpinejs` este argumento 
     *  representa el modelo al que va vinculado el input.
     * @param string $type Representa el tipo del select, es usado para generar la url a 
     *  la cual hacer las peticiones POST 
     * @param string $oninput Representa la accion a ejecutar una vez haya cambiado 
     *  el valor del input, especialmente util para (en el uso que se tiene pensado) realizar 
     *  peticiones http con `fetch` desde js
     */
    public static function load(string $title, string $model, string $type, string $oninput = ""): void
    {
        self::$ref++;

        return View::loadComponent('dynamic-select', [
            'title' => $title, 
            'model' => $model,
            'ref'   => "d-select-" . self::$ref,
            'type'  => static::$types[ $type ],
            'oninput' => $oninput
        ]);
    }
}