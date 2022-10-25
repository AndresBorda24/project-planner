<?php
namespace App\Models;

use App\Database\Model;

class AreaServicios extends Model
{
    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'area_servicio';

    /**
     * Representa todos los campos de la tabla que no sean:
     *  * Default current_date
     *  * id
     */
    protected array $fillable = [ 'area_servicio_nombre', 'area_servicio_correo'];

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types = 'ss';
}
