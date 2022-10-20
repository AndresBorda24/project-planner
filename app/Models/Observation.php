<?php
namespace App\Models;

use App\Database\Model;

class Observation extends Model {
    /**
     * Id del registro
     */
    public $id;

    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'pp_observations';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable = [
        'body',
        'author_id',
        'created_at',
        'obs_type',
        'obs_id'
    ];
}