<?php

namespace App\Models;

use App\Database\Model;

class Adjuntos extends Model
{
    public const MAX_SIZE = 5000000;
    public const BASE_PATH = 'storage/adjuntos/';
    public const ATTACHMENT_TYPE = 'adjunto';
    public const MIMES = [
        'text/csv',
        'text/plain',
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/zip',
        'application/msword',
        'application/vnd.rar',
        'application/vnd.ms-excel',
        'application/x-zip-compressed',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /* Id del archivo */
    public $id;

    /* Nombre de la tabla */
    protected string $table = 'pp_project_files';

    /* Campos que se deben setear*/
    protected array $fillable = [
        'project_id',
        'path',
        'name',
        'type'
    ];

    /**
     * Representa los tipos de datos de los campos 
     * establecidos en la propiedad $fillable.
     */
    protected string $types = 'isss';

    /**
     * Almacenas los adjuntos en la base de datos. Aquí se supone que el 
     * tipo debe ser 'adjunto';
     */
    public function saveAttachments(int $projectId, array $values)
    {
        try {
            $stm = sprintf(
                "INSERT INTO {$this->table} (%s) VALUES ",
                implode(", ", $this->fillable)
            );


            foreach ($values as $name => $path) {
                $stm .= sprintf(
                    "(%d, '%s', '%s', '%s'), ",
                    $projectId,
                    $path,
                    $name,
                    self::ATTACHMENT_TYPE
                );
            }

            $this->sql = substr($stm, 0, -2);

            if ( $this->get() ) return true;

            return false;
        } catch (\Throwable $th) {
            $this->errors[] = $th->getMessage();
            return false;
        }
    }

    /**
     * Elimina la carpeta y los archivos relacionados a un proyecto 
     * despues de que este es eliminado. Debe llamarse a la funcion. 
     * Por eso aparece en el controlador.
     */
    public static function removeDir(int $projectId)
    {
        $folder = self::BASE_PATH . "project_{$projectId}";
        if (!file_exists($folder)) return;

        $files = glob($folder . "/*"); // get all file names
        foreach ($files as $file) {
            if (is_file($file)) unlink($file); // delete file            
        }

        rmdir($folder);
        return;
    }
}
