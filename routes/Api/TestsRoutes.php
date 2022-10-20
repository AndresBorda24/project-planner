<?php
$router->post('/test', function() {
    $request = json_decode(file_get_contents('php://input'), true);
    sleep(2);

    $key = $request['newStatus'] . substr( md5( time() - rand(3000, 10000) ), 0, 6 );

    echo json_encode([
        'key' => $key
    ]);
});