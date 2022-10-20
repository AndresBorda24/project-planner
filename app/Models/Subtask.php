<?php
namespace App\Models;

use App\Database\Model;
use App\Contracts\Refreshable;
use App\Database\Traits\HasDetails;
use App\Database\Traits\HasObservations;

class Subtask extends Model implements Refreshable
{
    use HasDetails;
    use HasObservations;

    public $id;
    public $type = 'sub_task';
    protected Task $parent;

    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'pp_sub_tasks';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable = [
        'task_id',
    ];

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types = 'i';

    /**
     * Convierte la sub-tarea actual en una tarea
     */
    public function toTask() 
    {
        $newT = new Task();
        $project_id = ( Task::findById($this->task_id) )->project_id;

        $newT->title        = $this->title;
        $newT->description  = $this->description;
        $newT->status       = 'process';
        $newT->delegate_id  = $this->delegate_id;
        $newT->created_by_id= $this->created_by_id;
        $newT->priority     = $this->priority;
        $newT->started_at   = $this->started_at ?? date('Y-m-d');
        $newT->finished_at  = $this->finished_at;
        $newT->project_id   = $project_id;

        if (! $newT->save() ) throw new \Exception('No se ha podido generar la tarea');
        if (! $this->updateObsToTask( $newT->id )) throw new \Exception('No se han podido convertir las observaciones');
        
        return true;
    }

    protected function updateObsToTask( int $id ): bool
    {
        $stm = "UPDATE pp_observations SET obs_type='task', obs_id=? WHERE obs_type='sub_task' AND obs_id = ?";

        $q = $this->con->prepare($stm);
        $q->bind_param('ii', $id, $this->id);

        if ( $q->execute() ) return true; 

        return false;
    }
}
