<?php
namespace Core\Database\Models;

use Core\App;
use Core\Database\Traits\HasDetails;

class Project
{
    use HasDetails;

    protected static $table = 'pp_projects';
    protected static $type = 'project';

    public static function findBySlug(String $slug)
    {
        $stm = sprintf("SELECT * FROM %s WHERE `slug` = '%s'", self::$table, $slug);
        $q = App::$conn->query($stm)->fetch_object();

        return (object) array_merge(
            (array) $q, 
            (array) self::detailsSelect($q->id, self::$type)
        );
    }
}