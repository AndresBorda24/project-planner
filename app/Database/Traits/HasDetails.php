<?php
namespace App\Database\Traits;

use App\Models\User;

trait HasDetails {
    /**
     * Representa los campos que pueden ser seteados o llenados 
     * en la tabla details
     */
    protected $detailFields = [
        'title',
        'description',
        'status',
        'delegate_id',
        'created_by_id',
        'priority',
        'started_at',
        'finished_at',
        'detail_type',
        'detail_id'
    ];

    protected $detail_type;
    protected $detail_id;

    /**
     * Representa los tipos de datos para los campos
     * especificados en $detailFields
     */
    protected $detailsTypes = 'sssiissssi';


    /**
     * Sobreescribimos el metodo estatico findById para
     * que encuentre tambien los campos de la tabla details
     */
    public static function findById(int $id)
    {
        $class = get_called_class();
        $self = new $class;

        $find = $self->detailSelect($self->table)
        ->where("pp_details.`detail_type`", "'$self->type'")
        ->where("pp_details.`detail_id`", $id, '=', 'AND')
        ->get();

        if ($find->num_rows === 1) {
            
            $self->updateCurrent( $find->fetch_object() );
            $p = $self;

            return $p;
        }

        return new $class;
    }


    /**
     * Obtiene todos los registros de la tabla. 
     * 
     * @param array $main Representa los campos a tomar de la tabla principal
     * @param array $detail Representa los campos a tomas de la tabla details
     * @param ?int $l Representa el limite en la consulta
     * @param ?int $o Representa el offset en la consulta
     * @param ?array $order Representa el campo (indice 0) y la direccion (indice 1) en la consulta
     * 
     * @return array
     */
    public static function getAll(array $main = ['*'], array $detail = ['title', 'description'],?int $l = null,?int $o = null,?array $order = null): array
    {
        try {
            $class = get_called_class();
            $self = new $class;

            $all = [];
    
            $find = $self->detailSelect($self->table, $main, $detail)
            ->where("pp_details.`detail_type`", "'$self->type'")
            ->orderBy($order[0], $order[1])
            ->limit($l)
            ->offset($o)
            ->get();
    
            while ( $f = $find->fetch_object($class) ) {
                $all[] = $f;
            }

            return $all;
            
        } catch (\Throwable $e) {
            return [];
        }
    }


    /**
     * Se sobreescribe el metodo delete para que tambien elimine el
     * registro de details asociado.
     */
    public function delete(): bool
    {
        $this->con->begin_transaction();
        try {
            $stm = "DELETE FROM $this->table WHERE id = ?";
            $q = $this->con->prepare($stm);
            $q->bind_param('i', $this->id);
            
            if (! $q->execute() ) {
                throw new \Exception("No se ha podido Eliminar el registro");
            }

            if ($q->affected_rows < 1) {
                throw new \Exception("No se ha eliminado ningun registro");
            }
        
            /* Se Elimina el registro de details asociado con el proyecto | tarea | sub-tarea */
            $stm = "DELETE FROM pp_details WHERE detail_id = ? AND detail_type = ?";
            $q = $this->con->prepare($stm);
            $q->bind_param('is', $this->id, $this->type);

            if (! $q->execute() ) {
                throw new \Exception("No se ha podido Eliminar el registro details");
            }

            if ($q->affected_rows < 1) {
                throw new \Exception("No se ha eliminado ningun registro details");
            }

            if ( ! $this->cleanUp() ) {
                throw new \Exception("No se pudo realizar la limpieza.");
            }

            $this->con->commit();
            return true;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();

            /* Se cancelan TODAS las modificaciones realizadas en el bloque de arriba */
            $this->con->rollback();
            return false;
        }
    }

    /**
     * Guarda un registro junto con su detalle.
     */
    public function save(): bool
    {
        $this->con->begin_transaction();
        try {
            /* Guarda normalmente el registro de Proyectos | tareas | sub-tareas */
            if (! parent::save()) {
                throw new \Exception("No se pudo realizar la insercion");
            }

            /* Si la propiedad $detail_id esta seteada se tomara como una actualizacion si no, como una insercion */
            if ( isset($this->detail_id) ) {
                if (! $this->updateDetails() ) {
                    throw new \Exception("No se pudo realizar la actualizacion details");
                };
            } else {
                if (! $this->storeDetails() ) {
                    throw new \Exception("No se pudo realizar la insercion details");
                }
            }

            if (! $this->checkStatus() ) {
                throw new \Exception("No se pudo modificar el estado.");
            }

            /* Se confirman las modificaciones en la base de datos  */
            $this->con->commit();
        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();
            /* Si ocurre un error: */

            /* Se elimina el valor de la propiedad id */
            unset($this->id);

            /* Se cancelan TODAS las modificaciones realizadas en el bloque de arriba */
            $this->con->rollback();

            /* Retornamos con un false */
            return false;
        }

        /* Si todas las modificaciones se relizaron correctamente se actualizan los valores del objeto actual */
        $this->refresh();
        return true;
    }


    /**
     * Genera un nuevo regustro en la tabla details cuando un 
     * proyecto | tarea | sub-tarea es creado
     */
    protected function storeDetails(): bool
    {
        try {
            $stm = "INSERT INTO pp_details ";
            $data = [];
            $types = '';
            $fields = [];

            /**
             * Se determina cuales campos estan seteados y cuales no, 
             * para aprovechar los campos con valores default y que la 
             * sentencia no de error.
             */
            for ($i=0; $i < count($this->detailFields); $i++) { 
                $field = $this->detailFields[$i];

                if ($field == 'detail_id') {
                    $data[] = $this->id;
                    $types .= $this->detailsTypes[$i];
                    $fields[] = $field;
                    continue;
                }

                if ($field == 'detail_type') {
                    $data[] = $this->type;
                    $types .= $this->detailsTypes[$i];
                    $fields[] = $field;
                    continue;
                }

                if ( isset($this->$field) ) {
                    $data[] = $this->$field;
                    $types .= $this->detailsTypes[$i];
                    $fields[] = $field;
                }
            }

            /**
             * Se generan los signos de interrogacion requeridos por la 
             * sentencia prepaada.
             * 
             * Ej output: "?,?,?,?,?,?"
             */
            $v = substr( str_repeat('?,', count($fields) ), 0, -1);

            /**
             * Completa la sentencia sql al concatenar los campos y los 
             * signos de interrogacion.
             * 
             * Ej output: "(title, description, due_date) VALUES (?,?,?)"
             */
            $stm .= '(' . implode(', ', $fields) . ') VALUES (' . $v . ')';

            $q = $this->con->prepare($stm);
            $q->bind_param($types, ...$data);
            

            if ( $q->execute() ) {
                return ( $q->affected_rows > 0 ) ? true : false;
            } else {
                $this->errors[] = \App\App::$conn->error;

                return false;
            }

        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();

            return false;
        }
    }


    /**
     * Actualiza la tabla details del actual 
     * proyecto | tarea | sub-tarea
     */
    protected function updateDetails(): bool
    {
        try {
            $data = [];
            $types = '';
            $sql  = "UPDATE pp_details SET ";

            /**
             * Recorre las propiedades establecidas en $detailFields
             * y determina si agregarlas a la consulta o no dependiendo de si
             * es null o no lo es. 
             */
            for ($i=0; $i < count($this->detailFields); $i++) { 
                $field = $this->detailFields[$i];

                /* Si $filed es alguno de estos campos no los agrega a la consulta aunque no sean nulos */
                if ($field == 'detail_id' || $field == 'detail_type') {
                    continue;
                }

                if ( isset($this->$field) ) {
                    $sql .= "$field = ?, ";
                    $data[] = $this->$field;
                    $types .= $this->detailsTypes[$i];
                }
            }

            /* Continuacion de la consulta */
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE `detail_id` = ? and `detail_type` = ?";
            $types .= 'is';
            $data[] =  $this->id;
            $data[] =  $this->type;

            $q = $this->con->prepare($sql);
            $q->bind_param($types, ...$data);
            
            if( !$q->execute() ) {
                $this->errors[] = \App\App::$conn->error;
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();

            return false;
        }
    }


    /**
     * Verifica el estado de Proyecto | tarea | sub-tarea 
     * y modifica finished_at.
     */
    protected function checkStatus(): bool
    {
        if ( isset($this->status) ) {
            if ($this->status == 'finished') {
                $now = date('Y-m-d H:i:s');
    
                $sql = "UPDATE pp_details 
                    SET finished_at = '$now' 
                    WHERE 
                        `detail_type` = '$this->type' AND 
                        `detail_id` = $this->id";
                        
                return $this->con->query($sql);
    
            } else if ( $this->status != 'finished' && isset($this->finished_at) ) {
                $sql = "UPDATE pp_details 
                SET finished_at = DEFAULT 
                WHERE 
                    `detail_type` = '$this->type' AND 
                    `detail_id` = $this->id";
                    
                return $this->con->query($sql);
            }
        }
        
        return true;
    }


    /**
     * Prepara una sentencia sql en la que trae los campos de la 
     * tabla principal (ej Projects) junto con sus campos relacionados en la 
     * tabla details.
     * 
     */
    public function detailSelect(string $from, array $main = ["*"], array $detail = [
        'title','description','status','delegate_id','created_by_id','priority',
        'created_at','started_at','updated_at','finished_at','detail_type','detail_id'
    ])
    {
        $select = '';
        $type = substr($from, 3, -1);

        foreach ($main as $field) {
            if ($field == '*') {
                $select .= "{$from}.*, ";
                break;
            }
            if ($field == 'id') {
                $select .= "{$from}.id, ";
                continue;
            }

            $select .= "{$field}, ";
        }

        foreach ($detail as $field) {
            $select .= "pp_details.{$field}, ";
        }

        $select = substr($select, 0, -2);

        $this->sql = "SELECT {$select} 
        FROM {$from} 
        JOIN pp_details 
        ON pp_details.detail_id = {$from}.id AND pp_details.detail_type = '{$type}'";

        return $this;
    }


    /**
     * Cuando se elimina un proyecto, sus tareas y subtareas se eliminan por cascada. Sin
     * embargo, esto no afecta la tabla details ya que en esta no hay restricciones de llave 
     * foranea. Por eso, lo que hace esta funcion es eliminar de la tabla details los registros de 
     * tareas y subtareas que hayan quedado resagados.
     * 
     * @return void
     */
    protected function cleanUp(): bool
    {
        $cleanTasks     = "DELETE FROM pp_details WHERE pp_details.`detail_type` = 'task' AND pp_details.`detail_id` NOT IN (SELECT `id` FROM pp_tasks)";
        $cleanSubTasks  = "DELETE FROM pp_details WHERE pp_details.`detail_type` = 'sub_task' AND pp_details.`detail_id` NOT IN (SELECT `id` FROM pp_sub_tasks)";

        if ( ! $this->con->query($cleanTasks) ) {
            $this->errors[] = \App\App::$conn->error;
            return false;
        } 

        if ( ! $this->con->query($cleanSubTasks) ) {
            $this->errors[] = \App\App::$conn->error;
            return false;
        } 

        return true;
    }

    public function getAuthor(): string
    {
        return (new User)->select('-id', 'consultor_nombre')
        ->where('consultor_id', $this->created_by_id)
        ->get()
        ->fetch_array()[0];
    }

    public function getObjetcs(\mysqli_result $res)
    {
        $all = [];
        $class = get_called_class();

        while ( $row = $res->fetch_object($class) ) {
            $all[] = $row;
        }

        return $all;
    }

    public function refresh(): void
    {
        $this->updateCurrent(
            $this->detailSelect($this->table)
            ->where('pp_details.`detail_type`', "'$this->type'")
            ->where('pp_details.`detail_id`', $this->id, '=', 'AND')
            ->get()
            ->fetch_object()
        );
    }
}
