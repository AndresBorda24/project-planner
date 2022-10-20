<?php
namespace App\Models;

use App\Database\Model;

class Status extends Model
{
    public $id;
    protected string $table = "pp_status";
    protected string $types = "si";
    protected array $fillable = [
        'status',
        'visible'
    ];

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
}
