<?php
use App\App;

/* -----------------------------------------------------------------------------
 | Esta función me permite establecer callback general que se ejecuta cada
 | vez que ocurre un error.
 *------------------------------------------------------------------------------ 
*/ 
set_exception_handler(function ($err) {
    \App\Helpers\View::error($err);
    
    return false;
});

/*------------------------------------------------------------------------------
 | Se establece la zona horaria.
 *------------------------------------------------------------------------------ 
 */ 
date_default_timezone_set("America/Bogota");

/*------------------------------------------------------------------------------
 | Se carga la configuracion a App 
 *------------------------------------------------------------------------------ 
 */ 
App::bindConfig( require 'app/config.php' );
