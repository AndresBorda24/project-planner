Eventos
=======
### Index:
- **new-project-info**: Despacha la información del nuevo projecto creado.

#### Projectos:
- **load-obs**: carga todas las observaciones.
- **list-obs**: Después de que las observaciones han sido recuperadas se deben listar. Eso es lo que desencadena este envento. 

- **load-tasks**: Carga todas las tareas con sus sub-tareas.
- **task-list-loaded**: Cuando las tareas con sus subtareas han sido recuperadas exitosamente.

- **load-child**: Realiza la petición para traer la info de la tarea o sub-tarea.
- **child-loaded**: La informacion de la tarea o sub-tarea han sido cargadas correctamente. 

- **save-record**: Se dispara cuando se desea guardar o actualizar un regustro. Se debe enviar el cuerpo junto con el evento.
- **saved-record**: Después de haber realizado la peticón para guardar o actualizar un registro (_y que sea exitosa_) se dispara este evento.

- **add-new-ob**: Abre el modal para crear una nueva observacion.
- **add-attachment**: Abre el modal subir nuevos adjuntos.
- **reload-attachments**: Realiza de nuevo la petición para cargar los adjuntos.

