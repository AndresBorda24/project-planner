<?php
namespace App\Models;

use App\Models\Task;
use App\Database\Model;
use App\Models\Adjuntos;
use App\Contracts\Refreshable;
use App\Database\Traits\HasDetails;
use App\Database\Traits\HasObservations;

class Project extends Model implements Refreshable
{
    use HasDetails;
    use HasObservations;

    public $id;
    public $type = "project";
    protected array $tasks;

    
    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'pp_projects';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable = [
        'estimated_time',
        'due_date', 
        'slug'
    ];

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types = 'sss';


    /**
     * Obtiene todas las tareas relacionadas con el proyecto y si no hay 
     * error retorna un array, de otro modo retornará null
     * 
     * @param array $main Campos a obtener de la tabla Tasks
     * @param array $task Campos a obtener de la tabla Details
     * 
     * @return array|null 
     */
    public function getTasks(array $main = ['*'], array $task = ['title', 'description', 'status']): ?array
    {
        try {
            if ( ! isset($this->tasks) ) {
                $res = $this->detailSelect('pp_tasks', $main, $task)
                ->where('detail_type', "'task'")
                ->where('pp_tasks.project_id', $this->id, '=', 'AND')
                ->get();

                $this->tasks = [];
    
                while ($task = $res->fetch_object(Task::class) ) {
                    $this->tasks[] = $task;
                }
            }

            return $this->tasks;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return null;
        }
    }

    /**
     * Calcula el progreso del proyecto basado en las tareas que esten 
     * marcadas como finalizadas. Si no hay tareas relacionadas 
     * retornará 101 
     * 
     * @return int
     */
    public function getProgress(): float
    {
        try {
            $finished = $this->detailSelect('pp_tasks', [], ['status', 'priority'])
            ->where('pp_tasks.`project_id`', $this->id)
            ->get()
            ->fetch_all(MYSQLI_NUM);
            
            $data = ["steps" => 0, "stepsDone" => 0];
            
            $sumValue = function (&$data, $status, $add)  {
                $data["steps"] += $add;
                if ($status == 'finished') $data["stepsDone"] += $add;
            };

            foreach ($finished as $task) {
                switch ($task[1]) {                       
                    case 'normal':
                        $sumValue($data, $task[0], 2);
                        break;
                    case 'high':
                        $sumValue($data, $task[0], 3);
                        break;
                    case 'low':
                    default:
                        $sumValue($data, $task[0], 1);
                        break;
                }
            }

            if ($data["steps"] === 0) return 101;
            
            return round(
                $data["stepsDone"] * 100 / $data['steps'], 
                1
            );
        } catch (\Throwable $e) {
            // echo 'Error: ' . $e->getMessage();
            return 1;
        }
    }

    /**
     * Obtiene todas las observaciones relacionadas con el proyecto.
     */
    public function getAllObs()
    {
        $stm = "SELECT 
                    pp_observations.id, title, pp_observations.body, author_id, pp_observations.created_at, obs_type, obs_id
                FROM pp_observations
                JOIN pp_details ON pp_details.detail_type = pp_observations.obs_type AND pp_details.detail_id = pp_observations.obs_id
                WHERE pp_observations.project_id = $this->id ORDER BY created_at DESC";
        
        $obs = $this->con->query($stm)->fetch_all(MYSQLI_ASSOC);

        return $obs;
    }

    /**
     * Genera un slug para el proyecto basado en su titulo.
     */
    public function generateSlug( $text ) 
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);  
        $text = trim($text, '-');
        $text = iconv('utf-8', 'ASCII//IGNORE//TRANSLIT', $text);   
        $text = strtolower(trim($text));
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = $text . '-' .substr( md5( time() ), 0, 6 );
    
        return $text;
    }

    /**
     * Sobreescribimos el metodo estatico findById para
     * que encuentre tambien los campos de la tabla details
     */
    public static function findBySlug(string $slug)
    {
        $self = new self;

        $find = $self->detailSelect($self->table)
        ->where("{$self->table}.slug", "'{$slug}'")
        ->get();

        if ($find->num_rows === 1) {
            $self->updateCurrent( $find->fetch_object() );
            $p = $self;

            return $p;
        }

        throw new \Exception("No se ha encontrado el proyecto...");
    }

    /**
     * Da valor a las propiedades del proyecto dependiendo de la info en `$data`
     * 
     * Se emplea en las solicitudes POST y PUT
     */
    public function setProject(array $data, $isPost = false): void
    {
        $fields = ['due_date', 'estimated_time', 'title', 'description', 'status', 'delegate_id', 'created_by_id', 'priority', 'started_at'];

        foreach ($fields as $field) {
            $this->{$field} = $data[$field] ?? null;
        }

        if ( $isPost ) {
            $this->slug = $this->generateSlug($data['title']);
        }
    }

    /**
     * Obtiene los enlaces de los adjuntos del Proyecto
     */
    public function getAttachments()
    {
        $adjuntos = (new Adjuntos)
            ->select('project_id', 'path', 'name')
            ->where('type', "'".Adjuntos::ATTACHMENT_TYPE."'")
            ->where('project_id', $this->id, "=", 'AND')
            ->get()
            ->fetch_all(MYSQLI_ASSOC);
        
        return $adjuntos;
    }

    /**
     * Almacena los adjuntos del proyecto.
     */
    public function saveAttachments(array $values)
    {
        $at = new Adjuntos;

        /* Aunque tengan el mismo nombre las funciones son distintas, lo siento. */
        return $at->saveAttachments($this->id, $values);
    }

    /**
     * Elimina observaciones del proyecto, tareas y subtareas realacionadas y, ademas,
     * tambien elimina los archivos adjuntos.
     */
    public function cleanUpAll()
    {
        $this->deleteObservations();
        $this->cleanUpTaskObs(); 
        $this->cleanUpSubTaskObs();
        \App\Models\Adjuntos::removeDir($this->id); //Elimina los archivos relacionados 
    }

    /**
     * Devuelve el proyecto en forma de array.
     */
    public function projectToArray(): array
    {
        return [
            "id" => (int) $this->id,
            "slug" => $this->slug,
            "title" => $this->title,
            "status" => $this->status,
            "priority" => $this->priority,
            "delegate_id" => $this->delegate_id ? (int) $this->delegate_id : null,
            "author" => $this->getAuthor(),
            "description" => $this->description,
            "due_date" => $this->due_date,
            "started_at" => $this->started_at,
            "finished_at" => $this->finished_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "estimated_time" => $this->estimated_time,
        ];
    }

    /**
     * Este método mágico se emplea para tener una representacion del projecto en 
     * forma de string
     */
    public function __toString(): string
    {
        return json_encode($this->projectToArray());
    }
}


