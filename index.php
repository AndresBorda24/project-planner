<?php
/**
 * Auto-load con composer jeje
 */
require_once 'vendor/autoload.php';

/**
 * DotEnv
 */ 
$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();

/**
 * Bootstrap jeje
 */
require_once __DIR__ . '/app/bootstrap.php';

/* 
 * Son las rutas del proyecto.
*/
require_once 'routes/routes.php';

