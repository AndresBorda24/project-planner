<?php
// --------------< Adjuntos >--------------
$router->get('/attachment/(\d+)/download', 'Api\AdjuntosController@download');
$router->get('/project/(\d+)/attachments', 'Api\AdjuntosController@getAttachments');
$router->get('/project/(\d+)/attachments/delete', 'Api\AdjuntosController@test');
$router->post('/project/(\d+)/attachments', 'Api\AdjuntosController@upload');
$router->delete('/attachment/(\d+)', 'Api\AdjuntosController@remove');
// Fin adjuntos