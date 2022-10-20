<?php
namespace App\Controllers\Api;

use App\Models\Task;
use App\Models\Project;
use App\Models\Observation;
use App\Models\Request;
use App\Models\Subtask;

class ObservationController
{
    public $request;

    public function __construct() {
        $this->request = json_decode(file_get_contents('php://input'), true);
    }
    /**
     * Carga las observaciones de un proyecto.
     */
    public function loadProjectObs(int $id)
    {
        $p = Project::findById($id);
        $res = $p->getAllObs();
    
        echo json_encode(['obs' => $res]);
    }
    /**
     * 
     * Añade una nueva observacion a un proyecto.
     */
    public function addProjectObs(int $id)
    {
        $p = Project::findById($id);

        $res = @$p->saveObs(
            $this->request['body'], 
            $this->request['author'], 
            $this->request['project_id']
        );
    
        echo json_encode($res);
    }
    /**
     * Carga las observaciones de una tarea.
     */
    public function loadTaskObs(int $id)
    {
        $t = Task::findById($id);
        $res = $t->getObs();
    
        echo json_encode(['obs' => $res]);
    }
    /**
     * 
     * Añade una nueva observacion a una tarea.
     */
    public function addTaskObs(int $id)
    {
        $t = Task::findById($id);

        $res = @$t->saveObs(
            $this->request['body'], 
            $this->request['author'], 
            $this->request['project_id']
        );
    
        echo json_encode($res);
    }
    /**
     * Carga las observaciones de una sub-tarea.
     */
    public function loadSubTaskObs(int $id)
    {
        $st = Subtask::findById($id);
        $res = $st->getObs();
    
        echo json_encode(['obs' => $res]);
    }
    /**
     * 
     * Añade una nueva observacion a una sub-tarea.
     */
    public function addSubTaskObs(int $id)
    {
        $st = Subtask::findById($id);

        $res = @$st->saveObs(
            $this->request['body'], 
            $this->request['author'], 
            $this->request['project_id']
        );
    
        echo json_encode($res);
    }
    /**
     * Carga las observaciones de una tarea.
     */
    public function loadRequestObs(int $id)
    {
        $r = Request::findById($id);
        $res = $r->getObs();
    
        echo json_encode(['obs' => $res]);
    }
    /**
     * 
     * Añade una nueva observacion a una tarea.
     */
    public function addRequestObs(int $id)
    {
        $r = Request::findById($id);

        $res = @$r->saveObs(
            $this->request['body'], 
            $this->request['author']
        );

        $res['obs'] = $r->getObs();
    
        echo json_encode($res);
    }
    /**
     * Elimina una observacion. 
    */
    public function remove(int $id)
    {
        $o = Observation::findById($id);

        if ( $o->delete() ) {
            $data = [
                "status" => "success",
                "message"=> "Observacion eliminada!"
            ];
        } else {
            $data = [
                "status" => "error",
                "message"=> "Ha ocurrido un error durante la operecion."
            ];
        }
    
        echo json_encode($data);
    }
}