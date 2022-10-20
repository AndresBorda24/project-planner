<?php
namespace App\Services;

class UploadAttachmentService 
{
    /**
     * Para un facil acceso a la variable $_FILES
     */
    protected $files;

    /**
     * La Carpeta que se creara (si no existe)
     */
    protected $folder;

    /**
     * Ruta en la que se van a guardar los adjuntos.
     */
    protected $rutaBase = \App\Models\Adjuntos::BASE_PATH;
    
    /**
     * Nombre del array con el que se cargan los adjuntos.
     */
    protected $filesPostName = "attachments";

    /**
     * Aqui se van a almacenar errores y las rutas de los nuevos archivos
     */
    public $data = [ 
        "errors" => [],
        "paths" => []  
    ];

    public function __construct($projectId) {
        if (! isset($_FILES[ $this->filesPostName ])) die('No se ha subido ningun archivo!');

        $this->files  = $_FILES[ $this->filesPostName ];
        $this->folder = $this->rutaBase . 'project_' . $projectId;

        /* Si no existe la carpeta entonces la creamos */ 
        if (! file_exists($this->folder) ) mkdir($this->folder);
    }

    /**
     * Aqui se validan los archivos y se almacenan en el servidor.
     */
    public function manageUpload(): array
    {
        foreach ($this->files["error"] as $key => $error) {
            $_name = $this->files['name'][ $key ];

            if ( ! $this->validateUpload($key, $error) ) continue; 

            $newPath = $this->generateNewfilePath($_name);
            $tmpName = $this->files['tmp_name'][ $key ];

            if (! move_uploaded_file($tmpName, $newPath) ) {
                $this->data["errors"][ $_name ] = "No se ha podido subir el archivo.";
                continue;
            }

            $this->data["paths"][$_name] = $newPath;
        }

        return $this->data;
    }

    /**
     * Realzia validaciones a los archivos.
     */
    public function validateUpload(int $key, int $error)
    {
        $_name = $this->files['name'][ $key ];

        if ($error !== UPLOAD_ERR_OK) {
            $this->data["errors"][ $_name ] = $this->getUploadError($error);
            return false;
        };

        if ( ! in_array($this->files['type'][$key], \App\Models\Adjuntos::MIMES ) ) {
            $this->data["errors"][ $_name ] = "Formato de archivo no admitido! :" . $this->files['type'][$key];
            return false;
        }

        if ( $this->files['size'][$key] > \App\Models\Adjuntos::MAX_SIZE ) {
            $this->data["errors"][ $_name ] = "El tamaño del archivo sobrepasa el máximo!";
            return false;
        }

        return true;
    }

    /**
     * Retorna el mensaje de error en caso de que lo haya.
     * Sip, están en ingles. 
     */
    public function getUploadError( int $errorCode )
    {
        $phpFileUploadErrors = [
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        ];

        if (! in_array($errorCode, $phpFileUploadErrors, true)) {
            return "Unknown error...";
        }

        return $phpFileUploadErrors[ $errorCode ];
    }

    /**
     * Se encarga de generar la nueva ruta en la que se va a guardar el
     * archivo subido.
     */
    public function generateNewfilePath(string $_name)
    {
        $ext = pathinfo($_name, PATHINFO_EXTENSION);  
        $name = bin2hex( random_bytes(15) ) . '.' . $ext;

        return "{$this->folder}/{$name}";
    }
    
}
