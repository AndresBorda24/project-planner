<?php
// --------------< Sub-tareas >--------------
$router->get('/task/(\d+)/sub-tasks', 'Api\SubTaskController@getSubTasks');
$router->get('/sub-task/(\d+)', 'Api\SubTaskController@subTask');
$router->get('/sub-task/(\d+)/to-task', 'Api\SubTaskController@subTaskToTask');

$router->post('/task/(\d+)/sub-tasks', 'Api\SubTaskController@store');
$router->put('/sub-task/(\d+)', 'Api\SubTaskController@update');
$router->delete('/sub-task/(\d+)', 'Api\SubTaskController@remove');
// Fin Sub-tareas