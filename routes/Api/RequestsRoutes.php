<?php
// --------------< Solicitudes >--------------
$router->get('/requests', 'Api\RequestController@index');
$router->get('/requests/test', 'Api\RequestController@test');
$router->get('/request/(\d+)', 'Api\RequestController@getRequest');
$router->get('/search-requests', 'Api\RequestController@searchBox');

$router->post('/request', 'Api\RequestController@store');

$router->put('/request/(\d+)', 'Api\RequestController@update');
$router->put('/request/(\d+)/set-pin', 'Api\RequestController@updatePinnedValue');
$router->put('/request/(\d+)/set-project', 'Api\RequestController@updateProjectId');

$router->delete('/request/(\d+)', 'Api\RequestController@remove');
// Fin solicitudes

