<?php
// --------------< Pendientes >--------------
$router->get('/get-pending/(\d+)', function($delegate) {
    $sql = "
    (
        SELECT `detail_id`, `detail_type` as 'type', `title`, `slug`, null as 'task_id'
        FROM pp_details
        JOIN pp_projects ON pp_details.detail_id = pp_projects.id AND pp_details.detail_type = 'project'
        WHERE pp_details.`status` != 'finished' AND pp_details.delegate_id = $delegate
    )
        UNION ALL
    
    (
        SELECT `detail_id`, `detail_type` as 'type', `title`, `slug`, null as 'task_id'
        FROM pp_details
        JOIN pp_tasks ON pp_details.detail_id = pp_tasks.id AND pp_details.detail_type = 'task'
        JOIN pp_projects ON pp_tasks.project_id = pp_projects.id
        WHERE pp_details.`status` != 'finished' AND pp_details.delegate_id = $delegate
    )
        UNION ALL
    
    (
        SELECT `detail_id`, `detail_type` as 'type', `title`, `slug`, `task_id`
        FROM pp_details
        JOIN pp_sub_tasks ON pp_details.detail_id = pp_sub_tasks.id AND pp_details.detail_type = 'sub_task'
        JOIN pp_tasks ON pp_sub_tasks.task_id = pp_tasks.id
        JOIN pp_projects ON pp_tasks.project_id = pp_projects.id
        WHERE pp_details.`status` != 'finished' AND pp_details.delegate_id = $delegate
    )
    ";

    $pending = \App\App::$conn->query($sql)->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'pending' => $pending
    ]);
});
// Fin Pendientes