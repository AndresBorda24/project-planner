<?php
namespace App\Models;

use App\Database\Model;

class User extends Model {
    /**
     * Determina el nombre de la tabla a la cual se realizarán 
     * las consultas
     */
    protected string $table = 'vista_consultor';
}