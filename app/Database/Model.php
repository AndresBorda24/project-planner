<?php
namespace App\Database;

use App\App;
use Exception;

class Model
{
    protected $con;

    /**
     * Representa el id del objecto. El id es el que está en la 
     * base de datos.
     */
    protected $id;

    /**
     * Almacena los errores
     */
    protected array $errors = [];

    /**
     * Representa la sentencia sql que se esta construyendo.
     */
    protected string $sql = '';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable;

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types;

    public function __construct() {
        $this->con = App::$conn;
    }

    /**
     * Encuentra un Proyecto basado en su ID
     * 
     * @return static
     */
    public static function findById(int $id)
    {
        $class = get_called_class();
        $self = new $class;

        $find = $self->select()->where('id', $id)->get();

        if ($find->num_rows === 1) {
            $self->updateCurrent( $find->fetch_object() );
            return $self;
        }

        throw new Exception("No se ha encontrado el modelo.");
    }


    /**
     * Construye un SELECT basado en los campos pasados por paramentros.
     */
    public function select(...$fields)
    {
        $this->sql = 'SELECT';

        if (count($fields)) {
            !in_array('id', $fields) && !in_array('-id', $fields) ?  array_unshift($fields, 'id') : false;
            in_array('-id', $fields) ? array_shift($fields) : false;

            foreach ($fields as $field) {
                $this->sql .= " $field,";
            }

            $this->sql = substr($this->sql, 0, -1);
        } else {
            $this->sql .= ' *';
        }

        $this->sql .= " FROM " . $this->table;

        return $this;
    }


    /**
     * Construye un SELECTa una tabla especifica
     * basado en los campos pasados por paramentros.
     */
    public function selectFrom(string $table, string ...$fields)
    {
        $this->sql = 'SELECT ';
        $this->sql .= ( count($fields) > 0 ) ? implode(' ,', $fields) : '*' ;
        $this->sql .= " FROM " . $table;

        return $this;
    }

    /**
     * Construye un WHERE basado en los campos pasados por paramentros.
     */
    public function where($field, $value, $op = '=', $comp = false)
    {
        if ($comp) {
            $this->sql .= " $comp $field $op $value";
        } else {
            $this->sql .= " WHERE $field $op $value";
        }

        return $this;
    }


    /**
     * Establece un limit en la consulta 
     */
    public function limit(int $limit = 0)
    {
        $this->sql .= " LIMIT $limit";
        return $this;
    }


    /**
     * Establece el offset de la consulta
     */
    public function offset(int $offset = 0)
    {
        $this->sql .= " OFFSET $offset";
        return $this;
    }


    /**
     * Order By SQL 
     */
    public function orderBy(string $field = 'id', string $dir = 'asc')
    {
        $this->sql .= " ORDER BY $field $dir";
        return $this;
    }


    /**
     * Completa la sentencia sql y retorna el objecto mysqli_result.
     */
    public function get()
    {
        return $this->con->query($this->sql);
    }


    /**
     * Devuelve la sentencia sql que se esta preparando.
     */
    public function toSql() 
    {
        return $this->sql;
    }


    /**
     * Guarda un nuevo registo en la base de datos.
     */
    protected function store(): bool
    {
        try {
            $stm  = "INSERT INTO $this->table ";

            $data = []; // Se guardan los valores de los campos.
            $types = ''; // Para bindear los parametros, es una cadena que especifica el tipo de los valores.
            $fields = []; // Un arrat con los campos que se van a insertar.

            /* Se recorre la propiedad $fillable para saber que campos deben estar seteados*/
            for ($i=0; $i < count($this->fillable); $i++) { 
                $field = $this->fillable[$i];

                /* Si el campo está seteado se añade */ 
                if ( isset($this->{$field}) ) {
                    $data[] = $this->{$field};
                    $types .= $this->types[$i];
                    $fields[] = $field;
                }
            }

            /* Aquí se generan los placeholders. $v seria ?,?,?,?... */
            $v = substr( str_repeat('?,', count($fields) ), 0, -1);
            /* Se concreta el statement */
            $stm .= '(' . implode(', ', $fields) . ') VALUES (' . $v . ')';
            /* Preparamos la consulta */
            $q = $this->con->prepare($stm);

            /* Si la longitud de {$types} es mayor a 0 se bindean los parametros   */ 
            if (strlen($types)) {
                $q->bind_param($types, ...$data);
            }

            /* Aquí se ejecuita la consulta */
            if ( $q->execute() ) {
                $this->id = $q->insert_id;
                return true;
            }
            
            throw new Exception(\App\App::$conn->error);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }


    /**
     * Actualiza un registro en la base de datos
     */
    protected function update(): bool
    {
        try {
            $sql  = "UPDATE $this->table SET ";
            $data = []; // Se guardan los valores de los campos.
            $types = ''; // Para bindear los parametros, es una cadena que especifica el tipo de los valores.

            /* Se recorre la propiedad $fillable para saber que campos deben estar seteados*/
            for ($i=0; $i < count($this->fillable) ; $i++) { 
                $field = $this->fillable[$i];

                if ( isset($this->{$field}) ) {
                    $sql .= "{$field} = ?, "; // Se generan los placeholders ( ? ) en conjunto.
                    $data[] = $this->{$field};
                    $types .= $this->types[$i];
                } 
            }
    
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE `id` = ?"; // Especificamos el id .
            $data[] =  $this->id; // Añadimos el id a la lista de valores.

            $q = $this->con->prepare($sql);
            $q->bind_param($types.'i', ...$data); // Concatenamos el tipo del id (i por integer)
            
            return $q->execute(); // Se retorna true o false dependiendo del estado de la ejecucion
        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }


    /**
     * Guarda o actualiza un registro
     */
    public function save(): bool
    {
        // Si el id está seteado se tomará como un Update y no un insert.
        if ( isset($this->id) ) {
            return ( $this->update() ) ?  true : false;
        }

        return ( $this->store() ) ? true : false;
    }


    /**
     * Elimina un regirtro.
     */
    public function delete(): bool
    {
        try {
            $stm = "DELETE FROM $this->table WHERE id = ?";
            $q = $this->con->prepare($stm);
            $q->bind_param('i', $this->id);
            $q->execute();

            return $q->affected_rows === 1 ? true : false;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }


    /**
     * Mapea el resultado de una consulta y lo convierte en un objeto 
     * de la clase que lo invoca
     */
    public function toObject(\mysqli_result $mysqliResult)
    {
        return $mysqliResult->fetch_object( get_called_class() );
    }


    /**
     * Usado para realizar sentencias SQL 'crudas'
     */
    public function _sql( $sql ) 
    {
        $this->sql = $sql;
        return $this;
    }


    /**
     * Despues de realizar una consulta de actualizacion o insercion 
     * actualiza las propiedades del objecto actual en base a $stmObject.
     *  
     * Mapea las propiedades del resultado a las propiedades del modelo 
     * correspondiente
     * 
     * @param object $stmObject Objecto devuelto por una consulta sql mediante el metodo
     *          fetch_object().
     */
    public function updateCurrent( object $stmObject ): void
    {
        $values = get_object_vars( $stmObject );

        foreach ($values as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Devuelve el array con los errores jeje
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
