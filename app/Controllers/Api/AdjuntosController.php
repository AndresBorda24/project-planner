<?php
namespace App\Controllers\Api;

use App\Models\Project;
use App\Models\Adjuntos;
use App\Services\UploadAttachmentService;

class AdjuntosController
{
    /**
     * Obtiene un adjunto...
     */
    public function getAttachments(int $projectId) 
    {
        $p = Project::findById($projectId);

        echo json_encode( $p->getAttachments() );
    }

    /**
     * Se encarga de manejar la subida de los archivos. 
     */
    public function upload($projectId)
    { 
        $p = Project::findById($projectId);

        /* Guardamos los archivos en el directorio creado */
        $upload = new UploadAttachmentService($p->id);
        $data = $upload->manageUpload();

        echo json_encode([
            "upload_errors" => $data["errors"],
            "save_errors"   => $p->saveAttachments( $data["paths"] )
        ]);
    }

    /**
     * Elimina un archivo.
     */
    public function remove(int $id)
    {
        $a = Adjuntos::findById($id);

        if (! unlink($a->path) ) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se ha podidio eliminar el adjunto.'
            ]);

            return;
        }

        if ( $a->delete() ) {
            $data = [ 'status' => 'success' ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'No se ha podidio eliminar el adjunto de la base de datos.'
            ];
        }

        echo json_encode($data);
    }

    public function download($id)
    {
        $a = Adjuntos::findById($id);

        header('Content-Description: File Transfer');
        header("Content-Type: " . mime_content_type($a->path));        
        header("Content-disposition: inline; filename=\"".basename($a->path)."\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($a->path));
        readfile($a->path);
        exit;
    }
}
