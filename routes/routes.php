<?php
use App\App;
use App\Helpers\View;

$router =  new \Bramus\Router\Router();

/* ----------------------------------------------------------------
 | # IMPORTANTE:
 | La documentacion del `Router` se encuentra en:
 |      - https://github.com/bramus/router
 * ----------------------------------------------------------------
*/

/* Establecemos el namespace de los controllers */
$router->setNamespace( App::config('controllers')['namespace'] );

/* ------------------------------------------------------------------------
 | Aquí van las rutas web 
 * ------------------------------------------------------------------------
 */
$router->get("/", "IndexController@index");
$router->get("/sidebar", fn() => View::load('sidebar-test'));
$router->get('/project/{slug}/ver', "ProjectController@index");

$router->get('/config', function() {
    View::load('config', [
        "gema" => (new \App\Models\GemaScope)->select()->get()->fetch_all(MYSQLI_ASSOC),
        "status" => (new \App\Models\Status)->select()->get()->fetch_all(MYSQLI_ASSOC)
    ]);
});

$router->get('/priorizacion-&-solicitudes', function() { 
    View::load('priority-request', [
        "gema" => (new \App\Models\GemaScope)->select()->get()->fetch_all(MYSQLI_ASSOC),
        "status" => (new \App\Models\Status)->select()->get()->fetch_all(MYSQLI_ASSOC),
        "areas" => (new \App\Models\AreaServicios)->select('-id', 'area_servicio_id', 'area_servicio_nombre')->get()->fetch_all(MYSQLI_ASSOC),
    ]);
});

$router->get('/view-activity', function() {
    View::load('view-activity', []);
});

/* ------------------------------------------------------------------------
 | Aquí van las rutas para la `api` 
 * ------------------------------------------------------------------------
 */
$router->mount('/api', function() use($router) {
    foreach ( glob( __DIR__.'/Api/*.php' ) as $r  ) {
        require_once $r;
    }
});

/* ------------------------------------------------------------------------
 * Esto es para ejecutar el router
 * ------------------------------------------------------------------------
 */
$router->run();
