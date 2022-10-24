<?php
// Rutas para manegar los alcances de GEMA
$router->get('/gema-scopes', 'Api\GemaScopeController@index');
$router->post('/gema-scope', 'Api\GemaScopeController@save');
$router->put('/gema-scope/(\d+)/change-visibility', 'Api\GemaScopeController@changeScopeVisivility');
$router->delete('/gema-scope/(\d+)/replacement/(\d+)', 'Api\GemaScopeController@remove');

// Rutas para los status
$router->get('/status', 'Api\StatusController@index');
$router->post('/add-status', 'Api\StatusController@save');
$router->put('/status/(\d+)', 'Api\StatusController@update');
$router->delete('/status/(\d+)/replacement/(\d+)', 'Api\StatusController@remove');
// Fin xd 