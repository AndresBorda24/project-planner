<?php
namespace App\Controllers\Api;

use App\Models\Project;

class ReportsController
{
    /**
     * Un reporte de proyectos. 
     */
    public function projects()
    {
        $res = (new Project)->selectFrom('pp_vista_proyectos', '*', 'REPLACE(ROUND((`N. Tareas Finalizadas` / `N. Tareas`),2), ".", ",") as Avance')->get();

        echo json_encode([
            'projects' => $res->fetch_all(MYSQLI_ASSOC)
        ]);
    }
    /**
     * Un reporte de proyectos con sus tareas. 
     */
    public function projectWithTasks()
    {
        $p = new Project;
        $pro = $p->getObjetcs( 
            $p 
            ->detailSelect('pp_projects', ['id', 'due_date'], ['title', 'created_at', 'status', 'priority'])
            ->where("pp_details.`detail_type`", "'$p->type'")
            ->get() 
        );
    
        foreach ($pro as $pr) {
            $pr;
            $pr->progress_ = $pr->getProgress();
            $pr->tasks_ = array_map( function($task) {
                $task->progress_ = $task->getProgress();
                return $task; 
            }, $pr->getTasks(['id'], ['title', 'created_at', 'status', 'priority']) ); 
        }
    
        echo json_encode([
            'projects' => $pro
        ]);
    }
}