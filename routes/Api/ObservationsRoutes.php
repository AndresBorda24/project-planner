<?php
// --------------< Observaciones >--------------
$router->get('/project/(\d+)/observations', 'Api\ObservationController@loadProjectObs');
$router->post('/project/(\d+)/observations', 'Api\ObservationController@addProjectObs');

$router->get('/task/(\d+)/observations', 'Api\ObservationController@loadTaskObs');
$router->post('/task/(\d+)/observations', 'Api\ObservationController@addTaskObs');

$router->get('/sub-task/(\d+)/observations', 'Api\ObservationController@loadSubTaskObs');
$router->post('/sub-task/(\d+)/observations', 'Api\ObservationController@addSubTaskObs');

$router->get('/request/(\d+)/observations', 'Api\ObservationController@loadRequestObs');
$router->post('/request/(\d+)/observations', 'Api\ObservationController@addRequestObs');

$router->delete('/observation/(\d+)', 'Api\ObservationController@remove');
// Fin Observaciones