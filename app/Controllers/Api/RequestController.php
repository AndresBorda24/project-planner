<?php
namespace App\Controllers\Api;
use App\Models\Request;
use App\Helpers\Response;

class RequestController
{
    /**
     * Aqui se almacenan los paramentros enviados por POST & PUT
     */
    protected ?array $request;

    public function __construct()
    {
        $this->request = json_decode( file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Devuelve el listado de las Solicitudes
     */
    public function index(): void 
    {
        try {
            $r = new Request;
            $all = $r
                ->select()
                ->orderBy('pinned', 'desc')
                ->get()
                ->fetch_all(MYSQLI_ASSOC);

            $requests = Request::getRequestObjects($all);

            Response::json([
                'status' => "success",
                'requests' => $requests
            ]);
        } catch (\Exception $e) {
            Response::json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }    
    }

    /**
     * Almacena una solicitud en la base de datos
     */
    public function store(): void
    {
        $request = new Request;
        $request->setRequest( $this->request );

        if ( $request->save() ) {
            $request->refresh();
            Response::json([
                'status'  => 'success',
                'request' => $request->getRequest()
            ]);
        }

        Response::json([
            'status' => 'error',
            'message' => 'No se pudo crear la Solicitud'
        ]);
    }

    /**
     * Almacena una solicitud en la base de datos
     */
    public function update(int $id): void
    {
        $request = Request::findById($id);
        $request->setRequest( $this->request );

        if ( $request->save() ) {
            Response::json([ 'status'  => 'success' ]);
        }

        Response::json([
            'status' => 'error',
            'message' => 'No se pudo actualizar la Solicitud',
        ]);
    }

    /**
     * Pues eso, elimina una solicitud.
     */
    public function remove(int $id): void
    {
        $request = Request::findById($id);
        $request->deleteObservations();

        if ( $request->delete() ) {
            Response::json([ 'status'  => 'success' ]);
        }

        Response::json([
            'status' => 'error',
            'message' => 'No se pudo actualizar la Solicitud'
        ]);
    }

    /**
     * Almacena una solicitud en la base de datos
     */
    public function updatePinnedValue(int $id): void
    {
        if (! array_key_exists('pinnedValue', $this->request ) || ! array_key_exists('newOrder', $this->request )) {
            Response::json([
                "status" => "error",
                "message"=> "No se ha encontrado la información necesaria para realizar la actualización"
            ]);
        }

        try {
            $request = Request::findById($id);
            /* Si no hay errores en la actualización */
            if ( $request->setPin( $this->request["newOrder"],$this->request['pinnedValue'] ) ) {
                Response::json([ "status" => "success" ]);
            }

            Response::json([
                "status" => "error",
                "message"=> "No se ha actualizar la solicitud"
            ]);

        } catch (\Exception $e) {
            Response::json([
                "status" => "error",
                "message"=> "No se ha actualizar la solicitud",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function updateProjectId(int $id): void
    {
        try {
            if (! array_key_exists("projectId", $this->request) ) {
                Response::jsonError("No se encontró la información necesaria.");
            }

            $request = Request::findById($id);
            $request->project_id = $this->request["projectId"];
            $request->status = \App\Models\Status::getEnDesarrolloId();

            if (! $request->save()) {
                Response::jsonError("No se ha podido actualizar la solicitud.", 500);
            }

            Response::json([ "status" => "success", "dId" => $request->status ]);
        } catch (\Throwable $th) {
            Response::jsonError( $th->getMessage() );
        }
    }

    /**
     * Realiza la consulta para la caja de busqueda 
     */
    public function searchBox(): void
    {
        try {
            $query = urldecode($_GET["search"]);
            $ids = urldecode($_GET["ids"]);

            $res = (new Request)
                ->select('LEFT(subject, 100) as subject')
                ->where('subject', "'%{$query}%'", 'LIKE')
                ->where('id', "({$ids})", 'NOT IN', 'AND')
                ->get()
                ->fetch_all(MYSQLI_ASSOC);

            Response::json([
                'status' => "success",
                'requests' => $res
            ]);
        } catch (\Exception $e) {
            Response::json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Devuelve la informacion de una solicitud en especifico.
     */
    public function getRequest(int $id): void
    {
        $r = Request::findById($id);

        echo json_encode([
            "request" => $r->getRequest()
        ]);
    }

    public function test(): void
    {
        try {
            $a = \App\Models\Status::getEnDesarrolloId();

            Response::json([ "id" => $a ]);
        } catch (\Exception $e) {
            Response::jsonError("No se ha podido recuperar el id", 500);
        }
        // $request = new Request;
        // $pinnedRequests = $request->select('pinned')->where('pinned', '0', '>')->get()->fetch_all();

        // Response::json([ "status" => "success", "requests" => $pinnedRequests ]);
    }
}
