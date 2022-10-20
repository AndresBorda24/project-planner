<?php
// --------------< Reportes >--------------
$router->get('/reports/projects', 'Api\ReportsController@projects');
$router->get('/reports/projects-with-tasks', 'Api\ReportsController@projectWithTasks');
// Fin reportes