<?php
namespace App;
use App\Database\Connection;

class App
{
    public static \mysqli $conn;
    protected static $reg = [];
    
    public static function bind(string $key, $value): void
    {
        self::$reg[ $key ] = $value;
    }
    
    public static function bindConfig(array $value)
    {
        self::$reg[ 'config' ] = $value;
        self::$conn = Connection::connect(
            self::config('database')    
        );
    }
    
    public static function get(string $key)
    {
        if (!array_key_exists( $key, self::$reg )) {
            throw new \Exception("No hay un valor para la llave suministrada {$key}");
        }
        
        return self::$reg[ $key ];
    }
    
    public static function config(string $key)
    {
        if (!array_key_exists( $key, self::$reg[ 'config' ] )) {
            throw new \Exception("No existe la configuracion {$key}");
        }
        
        return self::$reg['config'][ $key ];
    }
    
    public static function connection()
    {
        return self::$conn;
    }
}
