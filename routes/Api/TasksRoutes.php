<?php
// --------------< Tareas >--------------
$router->get('/project/(\d+)/tasks', 'Api\TaskController@getTasks');
$router->get('/task/(\d+)', 'Api\TaskController@task');

$router->post('/project/(\d+)/tasks', 'Api\TaskController@store');
$router->put('/task/(\d+)', 'Api\TaskController@update');
$router->delete('/task/(\d+)', 'Api\TaskController@remove');
// Fin Tareas