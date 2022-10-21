<?php
namespace App\Models;

use App\Database\Model;

class Status extends Model
{
    public $id;
    protected string $table = "pp_status";
    protected string $types = "sii";
    protected array $fillable = [
        'status',
        'visible',
        'basic'
    ];
    /**
     * Representa los Estados que se deben mostrar cuando aún no se ha creado 
     * un proyecto. Ya estando creado el proyecto (relacionado a la solicitud) se 
     * deben mostrar todos los Estados.
     */
    public const BASIC_STATUS = [
        'DESEABLE',
        'EN PRUEBAS',
        'EN ESPERA',
        'NO AUTORIZADO',
        'NO GEMA',
        'NO VIABLE'
    ];

    /**
     * Representa el id del Estado "EN DESARROLLO". Si cambia se debe modificar manualmente
     */
    public const EN_DESARROLLO = "EN DESARROLLO";

    public static function statusExists(string $status): bool
    {
        $status = strtoupper($status);

        $exists = (new Status)
        ->select('-id', 'COUNT(*)')
        ->where("status", "'{$status}'")
        ->get()
        ->fetch_array(MYSQLI_NUM)[0];

        return ($exists == 0) ? false : true;
    }

    public function changeVisivility(bool $visible) 
    {
        try {
            $this->visible = $visible;
            return $this->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    final public static function getEnDesarrolloId()
    {
        try {
            $x = sprintf("'%s'", Status::EN_DESARROLLO);
            $res = (new Status)
            ->select("id")
            ->where("status", $x)
            ->get()
            ->fetch_array(MYSQLI_NUM)[0];

            return $res;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
