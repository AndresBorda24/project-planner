import { _modal, _fetch } from "../../extra/utilities.js";

/**
 * Este componente hace referencia a la ventana de edicion y creacion de 
 * proyectos exclusivamente
 */
export default () => ({
    state: {
        due_date: 0,
        updated_at: 0
    },
    addToDates: 0,

    init() {
        setTimeout(() => {
            this.state = Alpine.store("project");
        }, 500);
    },
    /**
     * Determina si el proyecto es `nuevo` dependiendo de si
     * posee un id o no.
     *
     * @returns Boolean
     */
    isNew() {
        return this.state.created_at === null;
    },
    /**
     * Determina si se debe mostrar el input de fecha de inicio
     * de proyecto.
     *
     * @returns Boolean
     */
    allowStartedAt() {
        return this.state.status != "new";
    },
    /**
     * Determina si el registro esta marcado como finalizado.
     */
    hasFinished() {
        return Alpine.store("__control").status != "finished" ? false : true;
    },
    /**
     * Se encarga de calcular "la fecha de entrega" dependiendo
     * de los inputs del usuario.
     */
    setDueDate() {
        let now = new Date();

        switch (this.state.estimated_time) {
            case "days":
                now.setDate(now.getDate() + this.addToDates);
                break;
            case "weeks":
                now.setDate(now.getDate() + this.addToDates * 7);
                break;
            case "months":
                now.setMonth(now.getMonth() + this.addToDates);
                break;
            default:
                now.setFullYear(now.getFullYear() + this.addToDates);
                break;
        }
        this.state.due_date = now.toISOString().substring(0, 10);
    },
    /**
     * Determina si se puede o no establecer una fecha de finalizacion estimada.
     * @returns {boolean}
     */
    allowDueDate() {
        return Alpine.store("__control").due_date ? true : false;
    },
    /**
     * Determina si el proyecto ya se marcó como iniciado o no
     * @returns {boolean}
     */
    isStarted() {
        return Alpine.store("__control").started_at ? true : false;
    },
    /**
     * Actualiza valores luego de que la respuesta es enviada por 
     * el comoponente save-record
     * 
     * @param {*} u El item actualizado
     * @returns {void}
     */
    handleUpdate( u ) {
        if (! Alpine.store('saveProjectRequest')) return; // Aquí se determina si fue este componente quien realizó la peticion

        Alpine.store("__control", {... u.project});
        Alpine.store("project", u.project);
        this.state = Alpine.store("project");

        Alpine.store('saveProjectRequest', false);
    },
});
