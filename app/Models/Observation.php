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

    /**
     * Obtiene las observaciones entre las fechas establecidas.
     */
    public static function getLog(?string $before, ?string $after ): array
    {
        $before = $before ?? date("Y-m-d", strtotime("+1 day"));
        $after =  $after  ?? date("Y-m-d", strtotime("-30 days"));

        $query = "SELECT 
           (SELECT title FROM pp_details WHERE detail_id = O.project_id AND detail_type = 'project' LIMIT 1) AS 'project',
            O.body, O.obs_type AS `type`, D.title, O.author_id, O.created_at, 
           D.detail_id, ST.task_id, O.project_id, slug
        FROM pp_observations AS O
        JOIN pp_details AS D ON 
           O.obs_id = D.detail_id AND 
           D.detail_type = O.obs_type
        LEFt JOIN pp_sub_tasks AS ST 
           ON D.detail_id = ST.id AND 
           D.detail_type = 'sub_task'
        LEFt JOIN pp_projects AS P 
           ON O.project_id = P.id 
        WHERE 
           O.project_id IS NOT NULL AND
           O.created_at BETWEEN '{$after}' AND '{$before}' 
        ORDER BY O.created_at DESC";

        try {
            $res = (new static)
                ->_sql($query)
                ->get()
                ->fetch_all(MYSQLI_ASSOC);

            return $res; 
        } catch(\Exception $e) {
            throw $e;
        }
    }
}