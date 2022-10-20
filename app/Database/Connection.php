<?php
namespace App\Database;

class Connection {
    public static function connect(array $config): \mysqli
    {
        $con = @new \mysqli(
            $config["host"],
            $config["username"],
            $config["password"],
            $config["db"],
            $config["port"]
        );

        if( $con->connect_error ) {
            die('No se ha podido conectar a la Base de Datos.');
        }
        
        return $con;
    }

}
