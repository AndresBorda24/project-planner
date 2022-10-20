<?php
// --------------< Proyectos >--------------
$router->get('/projects-search', 'Api\ProjectController@searchBox');
$router->get('/projects', 'Api\ProjectController@index');
$router->get('/project/(\d+)/tests', 'Api\ProjectController@test');
$router->get('/get-project-basic-info/(\d+)', 'Api\ProjectController@getBasicInfo');

$router->post('/project', 'Api\ProjectController@store');
$router->put('/project/(\d+)', 'Api\ProjectController@update');
$router->delete('/project/(\d+)', 'Api\ProjectController@remove');
// Fin Proyectos