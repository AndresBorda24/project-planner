<?php
namespace App\Database\Traits;

trait HasObservations {
    public function getObs()
    {
        try {
            $obs = $this
            ->selectFrom('pp_observations', 'id', 'body', 'created_at', 'author_id')
            ->where('obs_type', "'$this->type'")
            ->where('obs_id', $this->id, '=', 'AND')
            ->orderBy('created_at', 'desc')
            ->get();

            return $obs->fetch_all(MYSQLI_ASSOC);
        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();

            return [];
        }
    }

    public function saveObs(string $body, int $author, ?int $project_id = null)
    {
        $stm = "INSERT INTO pp_observations (`body`, `author_id`, `obs_type`, `obs_id`, `project_id`) VALUES(?,?,?,?,?)";
        $q = $this->con->prepare($stm);
        $q->bind_param('sisii', $body, $author, $this->type, $this->id, $project_id);

        if (! $q->execute() ) {
            $this->errors[] = \App\App::$conn->error;    
            
            return [
                'status'  => 'error',
                'message' => 'Ocurrio un error, no se ha podido insertar la observacion'
            ];
        }

        return  [
            'status'  => 'success',
            'message' => 'Observacion agregada correctamente!'
        ];
    }

    public function deleteObservations()
    {
        $stm = "DELETE FROM pp_observations WHERE `obs_type` = ? AND `obs_id` = ?";
        $q = $this->con->prepare($stm);
        $q->bind_param('si', $this->type, $this->id);

        if (! $q->execute() ) {
            $this->errors[] = \App\App::$conn->error;
            return false;
        }
        
        return true;
    }
    /**
     * Se eliminan todas las observaciones de tareas que hayan sido eliminadas
     */
    public function cleanUpTaskObs() {
        $stm = "DELETE FROM pp_observations WHERE pp_observations.obs_type = 'task' AND  pp_observations.obs_id NOT IN (SELECT id FROM pp_tasks)";

        if ( ! $this->con->query($stm) ) {
            $this->errors[] = \App\App::$conn->error;
            return false;
        }
        
        return true;
    }
    /**
     * Se eliminan todas las observaciones de sub-tareas que hayan sido eliminadas
     */
    public function cleanUpSubTaskObs() {
        $stm = "DELETE FROM pp_observations WHERE pp_observations.obs_type = 'sub_task' AND  pp_observations.obs_id NOT IN (SELECT id FROM pp_sub_tasks)";

        if ( ! $this->con->query($stm) ) {
            $this->errors[] = \App\App::$conn->error;
            return false;
        }

        return true;
    }
}