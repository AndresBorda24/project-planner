<?php
return [
    /*-------------------------------------------------------------------------
     * base_path se utiliza para determinar mejor la uri. representa el 
     * directiorio raiz del proyecto.
     * ------------------------------------------------------------------------
     */
    "base_path" => '/',

    /*-------------------------------------------------------------------------
     * project_path se utiliza para cargar los assets. Representa la ruta 
     * en la que se encuentra el proyecto. Ej:
     *  - Dado que en local se trabaja en el puerto 8000 no es necesario. Sin 
     * embargo una vez subido el proyecto al host se debe poner en una carpeta
     * por lo que la ruta cambia a /project-planner -> {ruta actual donde se 
     * sube el proyecto actualmente}  
     * ------------------------------------------------------------------------
     */
    "project_path" => '',

    /*-------------------------------------------------------------------------
     * Configuracíon para la carga de las visatas.
     * ------------------------------------------------------------------------
     */ 
    "views" => [
        "dir" => "resources/views/",
        "ext" => ".view.php",
        "error_view" => "error",
    ],

    /*-------------------------------------------------------------------------
     * Configuracíon para la carga de los controladores.
     * ------------------------------------------------------------------------
     */ 
    "controllers" => [
        "dir" => 'app/controllers/', 
        "namespace" => "App\Controllers"
    ],

    /*-------------------------------------------------------------------------
     * Informacion para la conexion de la base de datos.
     * ------------------------------------------------------------------------
     */
    "database" => [
        "host"     => '127.0.0.1',
        "username" => 'root',
        "password" => '',
        "db"       => 'project_planner',
        "port"     => 3309
    ],
    
    /*-------------------------------------------------------------------------
     * Opciones para ver los errores.
     * ------------------------------------------------------------------------
     */
    "error" => [
        "show" => true,
        "default_message" => "No se ha podidio cargar correctamente la página. Intenta nuevamente más tarde"
    ]
];
