<?php
namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Models\Project;

class ProjectController 
{
    public ?array $request;

    public function __construct()
    {
        $this->request = json_decode(file_get_contents('php://input'), true);
    }
    
    /**
     * Metodo Para la caja de busqueda en el index del proyecto. 
     */
    public function searchBox() 
    {
        $p = new Project;
        $data = $p
            ->detailSelect('pp_projects', ['id', "slug", "'project' as `type`"], ['title'])
            ->where('title', "'%$_GET[search]%'", 'LIKE', 'AND')
            ->get()
            ->fetch_all(MYSQLI_ASSOC);
    
        echo json_encode([
            'projects' => $data,
        ]);
    }

    /**
     * Obtiene los proyectos para el index del proyecto.
     * - Nota: Por diversos temas los arrays enviador en la url 
     * no se pueden parsear asi que por eso se emplea el explode
     */
    public function index()
    {
        $perPage = $_GET['per-page'] ?? 10; // Cantidad de proyectos a cargar
        $status  = $_GET['by-status'] ?? '%%';
        $order   = explode(',', $_GET['order']   ?? 'id,desc');
        $details = explode(',', $_GET['details'] ?? 'title,description');
        $main    = explode(',', $_GET['main']    ?? 'id');
        !in_array('id', $main) ? array_push($main, 'id') : false; // Se carga si o si el id

        $p = new Project;

        /* Se obtiene el total para manejar la paginacion */
        $total = $p
            ->detailSelect('pp_projects', ['COUNT(*)'], [])
            ->where("pp_details.`status`", "'$status'", 'LIKE', 'AND')
            ->get()
            ->fetch_array()[0];

        // Datos extra para paginacion
        $totalPages = ceil($total / $perPage);
        $current = (!isset($_GET['page']) || $_GET['page'] > $totalPages) ? 1 : $_GET['page'];
        $page = (($current) - 1) * $perPage; // Representa el offset en la query se calcula con la pagina actual de la paginacion

        /* Se mapean todos las filas del resultado a la clase Project */
        $all = $p->getObjetcs(
            $p->detailSelect('pp_projects', $main, $details)
            ->where("pp_details.`status`", "'$status'", 'LIKE', 'AND')
            ->orderBy($order[0], $order[1])
            ->limit($perPage)
            ->offset($page)
            ->get()
        );

        /* Para cada proyecto se carga su progreso y se especifica el tipo (Se puede hacer con un foreach) */
        foreach ($all as $pr) {
            $pr->progress = $pr->getProgress();
        }

        /* Aquí se imprime el json con los resultados */
        echo json_encode([
            "projects" => $all,
            "meta" => [
                "pagination" => [
                    'total' => $total,
                    'count' => count($all),
                    'total_pages' => $totalPages,
                    'current_page' => intval($current),
                ],
            ],
        ]);
    }

    /**
     * Guarda un nuevo proyecto en la base de datos
     */
    public function store()
    {
        $p = new Project(); // -> Nueva instancia de la clase Project
        $p->setProject($this->request, true);

        if ( $p->save() ) {
            $data = [
                'status' => "success",
                'project' => $p,
            ];

            if ( array_key_exists("requestId", $this->request) ) {
                $data["request"] = \App\Models\Request::setProject($this->request["requestId"], $p->id); 
            }
        } else {
            $data = [
                'status' => "error",
                'message' => "Ha surgido un error en la operacion. Revisa los Campos.",
            ];
        }

        echo json_encode($data);
    }
    
    /**
     * Actualiza un nuevo proyecto ya existente
    */
    public function update($id)
    {
        $p = Project::findById($id);
        $p->setProject($this->request);

        if ( $p->save() ) {
            Response::json([
                'status' => "success",
                'project' => $p->projectToArray(),
            ]);
        }
        
        Response::jsonError("Ha surgido un error en la operacion. Revisa los Campos.");
    }
    /**
     * Elimina un proyecto junto a todo lo relacionado a él.
     */
    public function remove($id): void
    {
        try {
            $p = Project::findById($id);
            \App\Models\Request::setBasicStatus($id);
        
            if ( $p->delete() ) {
                $p->cleanUpAll();
                Response::json([ "status" => "success" ]);
            } 

            throw new \Exception("Ha ocurrido un error durante la operecion.");
        } catch (\Exception $e) {
            Response::jsonError( $e->getMessage() );
        }
    }

    public function getBasicInfo(int $id)
    {
        try {
            $project = Project::findById($id);
            $data = [
                "status" => "success",
                "info" => [
                    "title" => $project->title,
                    "status" => $project->status,
                    "progress" =>$project->getProgress(),
                    "created_at" => $project->created_at,
                    "priority" => $project->priority
                ]
            ];
        } catch(\Exception $e) {
            $data = [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }

        echo json_encode($data);
    }
}