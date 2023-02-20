<?php
namespace App\Helpers;

class Assets
{
    /**
     * Genera el `href` de un archivo {$resource} 
     */
    public static function load(string $resource)
    {
        return sprintf(
            "%s/resources/%s?_v=%s",
            \App\App::config('project_path'),
            $resource,
            \App\App::config('version')
        );
    }
}
