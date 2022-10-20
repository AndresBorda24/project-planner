<?php
namespace App\Controllers\Api;

use App\Models\Task;
use App\Models\Subtask;

class SubTaskController
{
    public $request;

    public function __construct() 
    {
        $this->request = json_decode(file_get_contents('php://input'), true);
    }
    /**
     * Obtiene una sub-tarea basado en el id
     */
    public function subTask(int $id)
    {
        $st = Subtask::findById($id);
        $st->author = $st->getAuthor();

        echo json_encode([
            'sub_task' => $st
        ]);
    }
    /**
     * Obtiene las sub tareas de una tarea.
     */
    public function getSubTasks(int $id)
    {
        $main    = $_GET['main'] ?? ['id'];
        $details = $_GET['details'] ?? ['title','status'];
        ! in_array('id', $main) ? array_push($main, 'id') : false; 
    
        $task = Task::findById($id);
        $subTasks = $task->getSubtasks($main, $details);
    
        echo json_encode([
            'sub_tasks' => $subTasks,
            'parent'  => [
                'progress' => $task->getProgress()
            ]
        ]);
    }
    /**
     * Guarda una sub-tarea.
     */
    public function store(int $id)
    {
        // Nueva instancia de la clase Subtask
        $st = new Subtask();
        $st->task_id = $id;
        $data = $this->save($st);
    
        echo json_encode($data);       
    }
    /**
     * Actualiza una sub-tarea
     */
    public function update(int $id)
    {
        $st = Subtask::findById($id);    
        $data = $this->save($st);
    
        echo json_encode($data);
    }
    /**
     * Elimina una sub-tarea
     */
    public function remove(int $id)
    {
        $st = Subtask::findById($id);
        $st->deleteObservations();
    
        if ( $st->delete() ) {
            $data = [
                "status" => "success"
            ];
        } else {
            $data = [
                "status" => "error",
                "message"=> "Ha ocurrido un error durante la operecion."
            ];
        }
    
        echo json_encode($data);
    }
    /**
     * Convierte una sub-tarea en tarea
     */
    public function subTaskToTask(int $id)
    {
        $st = Subtask::findById($id);

        try {
            if ( $st->toTask() ) {
                $data = [
                    'status'  => "success",
                    'message' => "La sub-tarea fue correctamente tranformada en tarea."
                ];
            }
            if (! $st->delete() ) {
                throw new \Exception("Tarea creada. Sub-Tarea se debe eliminar manualmente");
            }
        } catch (\Throwable $th) {
            $data = [
                'status'  => "error",
                'message' => $th->getMessage()
            ];
        }
    
        echo json_encode([
            'data' => $data
        ]);
    }
    /**
     * Esta es la funcion que guarda o actualiza una sub-tarea en la base de datos.
     */
    public function save(&$st)
    {
        $fields  = ['title', 'description', 'status', 'delegate_id', 'created_by_id', 'priority', 'started_at'];

        // Recorremos el request y validamos cuales llaves estan seteadas
        foreach ($fields as $field ) {
            if ( isset($this->request[$field]) ) {
                $st->{$field} = $this->request[$field];   
            }
        }
    
        if ( $st->save() ) {
            if (! isset($st->author)) $st->author = $st->getAuthor();
            
            return [
                'status'  => "success",
                'sub_task' => $st
            ];
        }

        return [
            'status'  => "error",
            'message' => "No se ha podido crear la nueva Tarea. Revisa los Campos."
        ];
    }


}