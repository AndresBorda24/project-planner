<?php
namespace App\Helpers;

class Response
{
    /**
    * Genera los encabezados e imprime el json del array que se pasa como 
    * parametro. `Esta funcion termina la ejecucion del script`.
    */
    public static function json(array $data): void 
   {
       header("Content-Type: application/json");
       echo json_encode($data);
       exit();
   }

    /**
    * Genera los encabezados e imprime el mensaje que se pasa como parametro.
    * `Esta funcion termina la ejecucion del script`.
    */
    public static function jsonError(string $message = "Ha ocurrido un error!", int $httpErrorCode = 400): void 
   {
       header("Content-Type: application/json");
       http_response_code( $httpErrorCode );

       echo json_encode([
        "status" => "error",
        "message" => $message
       ]);
       exit();
   }
}

