<?php
namespace App\Controllers\Api;

use App\Models\Observation;
use App\Helpers\Response;

class ViewActivitiesController
{
    /**
     * Obtiene las ultimas observaciones.
     */
    public function getLog()
    {
        try {
            $before = $_GET["before"] ?? null;
            $after = $_GET["after"] ?? null;
            
            $log = Observation::getLog($before, $after);
            Response::json($log);
        } catch (\Exception $e) {
            Response::jsonError( $e->getMessage() );
        }
    }
}