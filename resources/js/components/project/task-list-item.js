export default () => ({
    showSubtasks: false,
    /**
     * Da formato al progreso de la tarea.
     *
     * @param {Nuber} progress represent el progreso de la tarea
     * @returns {String}
     */
    progress(progress) {
        return progress == 101 ? "No Aplica" : `${progress}%`;
    },
    /**
     * Determina si se muestra el botón ( + ) de crear nueva sub-tarea.
     *
     * @param {Object} c Es el 'child' recuperado del listado
     * @returns {Boolean}
     */
    showAddSubTask(c) {
        return (
            c.status == "process" &&
            Alpine.store("__control").status == "process" &&
            c.type == "task"
        );
    },
    /**
     * Simplemente da una clase especial (fondo rojo) si la tarea
     * esta marcada como fincalizada.
     *
     * @param {String} status El estado de la tarea
     * @returns
     */
    isFinished(status) {
        return status == "finished" ? "list-group-item-danger" : "list-group-item-light";
    },
    /**
     * Imprime el estado en español
     */
    statusSpanish( st ) {
        switch (st) {
            case "new":
                return 'Nueva';
            case "process":
                return 'En proceso';
            case "paused":
                return "Pausado";
            case "finished":
                return "Finalizado";
            default:
                return 'En proceso';
        }
    },
    /**
     * Imprime el estado en español
     */
    prioritySpanish( pr ) {
        switch (pr) {
            case "low":
                return 'Baja';
            case "normal":
                return 'Normal';
            default:
                return 'Alta';
        }
    },
});
