<?php
namespace App\Controllers\Api;

use App\Models\Task;
use App\Models\Project;

class TaskController
{
    public $request;

    public function __construct() 
    {
        $this->request = json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Obtiene una tarea.
     */
    public function task(int $id)
    {
        $task = Task::findById($id);
        $task->progress = $task->getProgress();
        $task->author = $task->getAuthor();
    
        echo json_encode([
            'task' => $task,
        ]);
    }

    /**
     * Obtiene las tareas relacionadas a un proyecto.
     */
    public function getTasks(int $id)
    {
        $main = explode(',', $_GET['main'] ?? 'id');
        $details = explode(',', $_GET['details'] ?? 'title,status,priority');
        !in_array('id', $main) ? array_push($main, 'id') : false;
    
        $project = Project::findById($id);
        $all = $project->getTasks($main, $details);
    
        $tasks = array_map(function ($t) {
            $t->progress = $t->getProgress();
            if ($t->progress < 101) {
                $t->_subTasks = $t->getSubtasks(['id']);
            }
            return $t;
        }, $all);
    
        echo json_encode([
            "tasks" => $tasks,
            'parent' => [
                'progress' => $project->getProgress(),
            ],
        ]);
    }

    /**
     * Guarda un nuevo proyecto.
     */
    public function store(int $id)
    {
        $t = new Task();
        $t->project_id = $id;
        $data = $this->save($t);

        echo json_encode($data);
    }

    /**
     * Actualiza una tarea.
     */
    public function update(int $id)
    {
        $t = Task::findById($id);
        $t->progress = $t->getProgress();
        $data = $this->save($t);

        echo json_encode($data);
    }

    /**
     * Elimina una tarea.
     */
    public function remove(int $id)
    {
        $task = Task::findById($id);
        $task->deleteObservations();
    
        if ($task->delete()) {
            $task->cleanUpSubTaskObs();
            
            $data = [
                "status" => "success",
            ];
        } else {
            $data = [
                "status" => "error",
                "message" => "Ha ocurrido un error durante la operecion.",
            ];
        }
    
        echo json_encode($data);
    }
    
    /**
     * Guarda o actualiza una tarea en la base de datos.
     */
    public function save(&$t): array
    {
        $fields = ['title', 'description', 'status', 'delegate_id', 'created_by_id', 'priority', 'started_at'];

        // Recorremos el request y validamos cuales llaves estan seteadas
        foreach ($fields as $i) {
            if (isset($this->request[$i])) {
                $t->{$i} = $this->request[$i];
            }
        }

        if (@$t->save()) {
            if (! isset($t->author)) $t->author = $t->getAuthor();
            
            return [
                'status' => "success",
                'task' => $t,
            ];
        } 

        return [
            'status' => "error",
            'message' => "No se ha podido crear la nueva Tarea. Revisa los Campos.",
        ];
    }
}