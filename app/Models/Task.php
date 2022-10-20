<?php
namespace App\Models;

use App\Contracts\Refreshable;
use App\Models\Subtask;
use App\Database\Model;
use App\Database\Traits\HasDetails;
use App\Database\Traits\HasObservations;

class Task extends Model implements Refreshable
{
    use HasDetails;
    use HasObservations;

    public $id;
    public $type = 'task';
    protected Project $parent;
    protected array $subtasks;

    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'pp_tasks';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable = [
        'project_id',
    ];

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types = 'is';


    public function getProgress(): int
    {
        try {
            $finished = $this->detailSelect('pp_sub_tasks', ['count(*)'], [])
            ->where('detail_type', "'sub_task'")
            ->where('pp_sub_tasks.`task_id`', $this->id, '=', 'AND')
            ->where('pp_details.`status`', "'finished'", '=', 'AND')
            ->toSql();
    
            $res = $this->selectFrom('pp_sub_tasks', 'COUNT(*) as `Total`', "($finished) as `Finished`")
            ->where('task_id', $this->id)
            ->get()
            ->fetch_assoc();
            
            return ( $res['Total'] == 0 ) ? 101 : intval($res['Finished']) * 100 / intval($res['Total']);
        } catch (\Throwable $e) {
            echo 'Error: ' . $e->getMessage();
            return 1;
        }
    }

    /**
     * Obtiene todas las sub-tareas relacionadas con la tarea y si no hay 
     * error retorna un array, de otro modo retornará null
     * 
     * @param array $main Campos a obtener de la tabla Tasks
     * @param array $task Campos a obtener de la tabla Details
     * 
     * @return array|null 
     */
    public function getSubtasks(array $main = ['*'], array $task = ['title', 'status']): ?array
    {
        try {
            if ( ! isset($this->subtasks) ) {
                $res = $this->detailSelect('pp_sub_tasks', $main, $task)
                ->where('detail_type', "'sub_task'")
                ->where('pp_sub_tasks.task_id', $this->id, '=', 'AND')
                ->get();

                $this->subtasks = [];
    
                while ($task = $res->fetch_object(Subtask::class) ) {
                    $this->subtasks[] = $task;
                }
            }

            return $this->subtasks;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return null;
        }
    }
}