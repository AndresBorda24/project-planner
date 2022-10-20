<?php
namespace App\Helpers;
use App\App;

class View
{
    public static function load(string $name, array $vars = [] )
    {
        try {
            extract($vars);
            $r = self::getPath($name);
            
            if ( ! file_exists( $r ) ) {
                throw new \Exception("La vista no existe...");
            }
            
            if ( ! require $r ) {
                throw new \Exception("La vista no se ha podido cargar...");
            }
            
        } catch(\Exception $e) {
            self::error($e);
        }
        
    }

    public static function loadComponent(string $name, array $vars = [] )
    {
        try {
            extract($vars);
            
            $r = sprintf(
                "%s%s",
                self::getDir().'components/',
                $name.'.php'
            );
            
            if ( ! file_exists( $r ) ) {
                throw new \Exception("El componente no existe...");
            }
            
            if ( ! require $r ) {
                throw new \Exception("El componente no se ha podido cargar...");
            }
            
        } catch(\Exception $e) {
            self::error($e);
        }
        
    }
    
    protected static function getPath($name)
    {
        return sprintf(
            "%s%s%s",
            self::getDir(),
            $name,
            App::config('views')['ext']
        );
    }
    
    public static function error($e)
    {
        $error = [
            'Codigo' => $e->getCode(),
            "Error"  => $e->getMessage(),
            "Archivo"=> $e->getFile(),
            "Linea"  => $e->getLine(),
            "Extra"  => error_get_last(),
        ];
        
        include self::getPath( 
            App::config('views')['error_view'] 
        );
    }
    
    protected static function getDir()
    {
        return App::config('views')['dir'];
    }
}


