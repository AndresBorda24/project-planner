<?php
namespace App\Models;

use App\Database\Model;

class GemaScope extends Model
{
    public $id;
    protected string $table = "pp_gema_scopes";
    protected string $types = "si";
    protected array $fillable = [
        'scope', 
        'visible'
    ];

    public static function scopeExists(string $scope): bool
    {
        $scope = strtoupper($scope);

        $exists = (new GemaScope)
        ->select('-id', 'COUNT(*)')
        ->where("scope", "'{$scope}'")
        ->get()
        ->fetch_array(MYSQLI_NUM)[0];

        return ($exists == 0) ? false : true;
    }
}
