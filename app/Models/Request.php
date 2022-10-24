<?php
namespace App\Models;

use App\Models\Status;
use App\Database\Model;
use App\Contracts\Refreshable;
use App\Database\Traits\HasObservations;

class Request extends Model implements Refreshable
{
    use HasObservations;

    /**
     *  Representa los posibles "encargados" que puede tener una solicitud.
     */
    public const DESARROLLO = [
        'DESARROLLO',
        'SOPORTE',
        'ASESORIA'
    ];

    public $id;
    protected array $fillable = [
        "project_id",  
        "pinned",  
        "subject",  
        "area",  
        "desarrollo",  
        "gema",  
        "status",  
        "scope",  
        "importance",  
        "cost",  
        "span",  
        "viability",  
        "frequency",  
        "economy",  
        "normativity",
        "requested_at"
    ];
    private string $type = "request";
    protected string $table = "pp_requests";
    protected string $types = "iisisiiiiiiiiiis";
    private static array $projectsSlugs = [];

    /**
     * Devuelve un array con todas las "notas" empleadas para 
     * sacar el promedio. 
     */
    private function getNotas(): array
    {
        return [
            "scope"         => $this->scope,
            "importance"    => $this->importance,
            "cost"          => $this->cost,
            "span"          => $this->span,
            "viability"     => $this->viability,
            "frequency"     => $this->frequency,
            "economy"       => $this->economy,
            "normativity"   => $this->normativity,
        ];
    }

    /**
     * Obtiene el proyecto al que esta relacionado la 
     * solicitud.
     */
    public function getProject(): ?array
    {
        if (! isset($this->project_id)) {
            return null;
        }

        if (! array_key_exists($this->project_id, static::$projectsSlugs)) {
            static::getProjectsInfo([ $this->project_id ]);
        }

        return [
            "id" => $this->project_id,
            "slug" => static::$projectsSlugs[ "$this->project_id" ]
        ];
    }

    /**
     * Establece los valores para el objecto request con los 
     * valores que vienen en el array {$data}
     */
    public function setRequest(array $data)
    {
        foreach ($this->fillable as $field) {
            if ( isset( $data[ $field ] ) ) {
                $this->{$field} = $data[ $field ];
            }
        }
    }

    /**
     * Retorna un array que sera convertido a json para enviar como 
     * respuesta.
     */
    public function getRequest(): array
    {
        return [
            "id" => $this->id,
            "gema" => $this->gema,
            "area" => $this->area,
            "pinned" => $this->pinned,
            "status" => $this->status,
            "subject" => $this->subject,
            "project" => $this->getProject(),
            "desarrollo" => $this->desarrollo,
            "created_at" => $this->created_at,
            "requested_at" => $this->requested_at,
            "data" => $this->getNotas()
        ];
    }

    /**
     * Refresca las propiedades de la solicitud
     */
    public function refresh(): void
    {
        $this->updateCurrent( 
            $this->select()
            ->where('id', $this->id)
            ->get()
            ->fetch_object() 
        );
    }

    /**
     * Reemplaza los estados de todas las  solicitudes.
     */
    public static function replaceStatus(int $old, int $new): bool 
    {
        $r = new static;

        $stm = "UPDATE {$r->table} SET {$r->table}.`status` = ? WHERE {$r->table}.`status` = ?";
        $q = \App\App::$conn->prepare($stm);
        $q->bind_param("ii", $new, $old);

        if ( $q->execute() ) {
            return true;
        }

        throw new \Exception( \App\App::$conn->error );
    }

    /**
     * Reemplaza los estados de todas las  solicitudes.
     */
    public static function replaceGemaScope(int $old, int $new): bool 
    {
        $r = new static;

        $stm = "UPDATE {$r->table} SET {$r->table}.`gema` = ? WHERE {$r->table}.`gema` = ?";
        $q = \App\App::$conn->prepare($stm);
        $q->bind_param("ii", $new, $old);

        if ( $q->execute() ) {
            return true;
        }

        throw new \Exception( \App\App::$conn->error );
    }


    public static function getRequestObjects(array $requests): array
    {
        static::getProjectsInfo( array_column($requests, "project_id") );

        $newRequests = [];
        foreach ($requests as $request) {
            $r = static::castArrayToRequest($request);
            $newRequests[] = $r->getRequest();
        }

        return $newRequests;
    }

    /**
     * Establece el valor para la propiedad `pinned`.
     */
    public function setPin(array $newOrder, int  $pinValue): bool
    {
        switch ( count($newOrder) ) {
            case 0:
                $updateAll = "UPDATE {$this->table} SET `pinned` = 0 WHERE `pinned` > 0";
                break;
            default:
                $updateAll = "UPDATE {$this->table} SET `pinned` = CASE `id` ";

                foreach ($newOrder as $key => $value) {
                    $updateAll .= "WHEN {$key} THEN {$value} ";
                }

                $updateAll = sprintf(
                    "%s END WHERE `id` IN (%s)", 
                    $updateAll,
                    implode(", ", array_keys($newOrder))
                );
                break;
        }

        if (! \App\App::$conn->query($updateAll) ) {
            throw new \Exception("No se ha podido realizar la actualizacion de los valores.");
        }

        $this->pinned = $pinValue;
        return $this->save();
    }

    /**
     * Recupera y almacena los slugs de los proyectos relacionados a las solicitudes.
     */
    private static function getProjectsInfo(array $ids): void
    {
        $ids = array_filter($ids);

        if (count($ids) === 0) {
            return;
        }

        $projectIds = implode(", ", $ids);

        $pInfo = \App\App::$conn
            ->query("SELECT id, slug FROM pp_projects WHERE id IN ({$projectIds})")
            ->fetch_all(MYSQLI_ASSOC);


        static::$projectsSlugs = static::$projectsSlugs + array_column($pInfo, "slug", "id");
    }

    /**
     * Castea un array a un Objecto `Request`
     * 
     * @param array $r Representa el array a convertir a Request 
     * @return static
     */
    public static function castArrayToRequest(array $r)
    {
        $request = new Request;

        foreach ($r as $key => $value) {
            $request->{$key} = $value;
        }

        return $request;
    }

    /**
     * Establece el id del proyecto que previamente se creó en base a la solicitud.
     */
    public static function setProject(int $requestId, int $projectId): bool
    {
        $request = Request::findById($requestId);
        $request->project_id = $projectId;

        return $request->save();
    }

    /**
     * Selecciona el primer estado que sea `basic` y lo setea a la Solicitud cuyo 
     * project_id sea el pasado por el parameto.
     */
    public static function setBasicStatus(int $projectId): bool
    {
        try {
            $resStatus = (new Status)
                ->select('id')
                ->where("`basic`", "true")
                ->limit(1)
                ->get();
            
            $status = ($resStatus->num_rows == 1) ? 
                $resStatus->fetch_array(MYSQLI_NUM)[0] : 
                (new Status)
                ->select('id')
                ->limit(1)
                ->get()
                ->fetch_array(MYSQLI_NUM)[0];
        
            $up = \App\App::$conn->prepare("UPDATE pp_requests SET `status` = ? WHERE `project_id` = ?");
            $up->bind_param('ii', $status, $projectId);

            if ( $up->execute() ) {
                return true;
            }

            throw new \Exception(\App\App::$conn->error);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}