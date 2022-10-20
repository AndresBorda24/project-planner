<?php
namespace App\Controllers;

use App\App;
use App\Helpers\View;
use App\Models\Project;
use App\Models\User;

class ProjectController
{
    public function index(string $slug)
    {
        try {
            $p = Project::findBySlug($slug);

            $users = (new User)->select('-id', 'consultor_id', 'consultor_nombre')
                ->where('consultor_estado', "'A'")
                ->where('area_servicio_nombre', '"SISTEMAS"', "=", "AND")
                ->get()
                ->fetch_all(MYSQLI_ASSOC);
    
            View::load('project', [
                'project' => $p,
                'autor'   => $p->getAuthor(),
                'users'   => $users,
                'script'  => $this->highlight()
            ]);
        } catch (\Throwable $th) {
            View::load('error', [
                'error' => "No se ha encontrado el proyecto... Intenta más tarde" 
            ]);
        }
        
    }

    /**
     * Esta función genera un script que se ejecuta cuando se carga la página 
     * y se encarga (el script) de resaltar una tarea o subtarea.
     */
    protected function highlight(): string 
    {
        $isSetSubtask = ( isset($_GET['sub-task']) && !empty($_GET['sub-task']) ); 
        $isSetTask = ( isset($_GET['task']) && !empty($_GET['task']) ); 
        
        if ( $isSetSubtask && $isSetTask ) {
            return "
                (() => {
                    setTimeout( () => {
                        ( document.getElementById('expand-{$_GET['task']}') ).click();
                        ( document.getElementById('task-{$_GET['task']}') ).scrollIntoView({ behavior: 'smooth', block: 'start' });
                        const sub = document.getElementById('sub-task-{$_GET['sub-task']}');
                        sub.style.cssText = `
                            transition: all 200ms ease;
                            outline: 3px solid #b4ffefd9;
                            background-color: #66ffde45 !important;
                        `;
                        setTimeout(() => sub.style.cssText = '', 10000);
                    }, 2700);
                })()
            ";
        }

        if ( $isSetTask ) {
            return "
                (() => {
                    setTimeout( () => {
                        const task = document.getElementById('task-{$_GET['task']}');
                        task.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        task.style.cssText = `
                            transition: all 200ms ease;
                            outline: 3px solid #b4ffefd9;
                            background-color: #66ffde45 !important;
                        `;
                        setTimeout(() => task.style.cssText = '', 10000);
                    }, 2500);
                })()
            ";
        }

        return '';
    }
}


