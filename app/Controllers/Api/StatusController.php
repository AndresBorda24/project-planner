<?php
namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Models\Status;

class StatusController
{
    private ?array $request;

    public function __construct()
    {
        $this->request = json_decode( file_get_contents('php://input'), true );    
    }

    public function index(): void 
    {
        $status = (new Status)
        ->select()
        ->get()
        ->fetch_all(MYSQLI_ASSOC);

        $status = array_map( function( $el ) {
            $el['visible'] = (bool) $el['visible']; 
            $el['basic'] = (bool) $el['basic'];
            return $el;
        }, $status);
        
        Response::json($status);
    }

    /**
     * Realiza algunas validaciones antes de registrar el nuevo estado en la base de datos.
     */
    public function save(): void
    {
        if (! array_key_exists('status', $this->request)) {
            Response::jsonError("No se encontró la información necesaria en la solicitud.");
        };

        if ( Status::statusExists( $this->request['status'] ) ) {
            Response::jsonError("Ya se encuentra registrado ese Estado.");
        }

        $gs = new Status;
        $gs->status = strtoupper( $this->request['status'] );

        if ( $gs->save() ) {
            Response::json([
                "status" => "success",
                "id"     => $gs->id
            ]);
        }
        
        Response::jsonError("No se ha podido guardar el nuevo registro", 500);
    }

    /**
     * Cambia la visibilidad de un estado, esto lo que permite es impedir que se 
     * creen más solicitudes (en caso que sea false).
     */
    public function changeStatusVisivility(int $id): void
    {
        if (! array_key_exists("visibility", $this->request)) {
            Response::jsonError("No se encontró la información necesaria en la solicitud.");
        }
        
        try {
            $s = Status::findById($id);
            $visible = (bool) $this->request["visibility"];
            
            if ( $s->changeVisivility($visible) ) {
                Response::json([ "status" => "success" ]);
            }

            throw new \Exception("No se ha podido actualizar la visibilidad del estado.");
        } catch (\Throwable $th) {
            Response::jsonError($th->getMessage(), 500);
        }
    }

    /**
     * Elimina un Status
     */
    public function remove(int $old, int $new)
    {
        try {
            $s = Status::findById($old);
            \App\Models\Request::replaceStatus($old, $new);
            
            if ( $s->delete() ) {
                Response::json([ "status" => "success" ]);
            } 

            throw new \Exception("No se ha podido eliminar el Status");
        } catch (\Exception $e) {
            Response::jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un estado, esta pensado para actualizar solamente las 
     * propiedades `visible` & `basic` 
     */
    public function update(int $id): void
    {
        try {
            if (
                ! array_key_exists("visible", $this->request) ||
                ! array_key_exists("basic", $this->request)
            ) {
                throw new \Exception("No se ha encontrado la información suficiente en la solicitud");
            }

            $st = Status::findById($id);
            $st->visible = $this->request["visible"];
            $st->basic = $this->request["basic"];

            if(! $st->save()) {
                throw new \Exception("No se ha podido actualizar.");
            }
            
            Response::json([ "status" => "success" ]);
        } catch (\Exception $e) {
            Response::jsonError( $e->getMessage() );
        }
    }
}
