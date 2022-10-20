<?php
namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Models\GemaScope;

class GemaScopeController
{
    private ?array $request;

    public function __construct()
    {
        $this->request = json_decode( file_get_contents('php://input'), true );    
    }

    public function index(): void
    {
        $scopes = (new GemaScope)
        ->select()
        ->get()
        ->fetch_all(MYSQLI_ASSOC);

        $scopes = array_map(function($el) {
            $el["visible"] = (bool) $el["visible"];
            return $el;
        }, $scopes);

        Response::json($scopes);
    }

    /**
     * Guarda.
     */
    public function save(): void
    {
        if (! array_key_exists('scope', $this->request)) {
            Response::jsonError("No se encontró la información necesaria en la solicitud.");
        }

        if ( GemaScope::scopeExists( $this->request['scope'] ) ) {
            Response::jsonError("Ya se encuentra registrado ese alcance.");
        }

        $gs = new GemaScope;
        $gs->scope = strtoupper( $this->request['scope'] );

        if ( $gs->save() ) {
            Response::json( [
                "id" => $gs->id,
                "status" => "success"
            ]);
        } 
        
        Response::jsonError("No se ha podido guardar el nuevo registro", 500);
    }

    /**
     * Cambia la visibilidad de un alcance , esto lo que permite es impedir que se 
     * creen más solicitudes con ese alcance (en caso que sea false).
     */
    public function changeScopeVisivility(int $id): void
    {
        if (! array_key_exists("visibility", $this->request)) {
            Response::jsonError("No se encontró la información necesaria en la solicitud.");
        }
        
        try {
            $s = GemaScope::findById($id);
            $s->visible = (bool) $this->request["visibility"];
            
            if ( $s->save() ) {
                Response::json([ "status" => "success" ]);
            }

            throw new \Exception("No se ha podido actualizar la visibilidad.");
        } catch (\Throwable $th) {
            Response::jsonError($th->getMessage(), 500);
        }
    }

    /**
     * Elimina.
     */
    public function remove(int $old, int $new): void
    {
        try {
            $s = GemaScope::findById($old);
            \App\Models\Request::replaceGemaScope($old, $new);
            
            if ( $s->delete() ) {
                Response::json([ "status" => "success" ]);
            }

            throw new \Exception("Algo ha salido mal.");
        } catch (\Exception $e) {
            Response::jsonError( $e->getMessage() , 500);
        }
    }
}