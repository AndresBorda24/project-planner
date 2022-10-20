<?php
namespace App\Controllers;
use App\Helpers\View;

class IndexController
{
    public function index()
    {
        $script = "";
        
        if ( isset($_GET['requestSubject'])  && isset($_GET['requestId']) ) {
            $script = "window.addEventListener('load', () => {
                setTimeout(() => {
                    const newProjectButton = document.getElementById('new-project-button');
            
                    newProjectButton.dispatchEvent( 
                        new CustomEvent('open-create-modal-from-outside', { bubbles: true }) 
                    );
                }, 1000);
            })";
        }
        
        View::load('index', [
            "script" => $script
        ]);
    }
}