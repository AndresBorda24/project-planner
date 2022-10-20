import { _modal } from '../extra/loaded.js';
import { _fetch } from "../extra/extra.js";
/**
 * Este componente hace referencia a la ventana de edicion y creacion de 
 * proyectos exclusivamente
 */
export default () => ({
    state: {},
    addToDates: 0,

    init() {
        const data = document.getElementById('project-data'); 
        this.state = JSON.parse( data.innerText );
        Alpine.store("current", {...this.state});
        Alpine.store("state", this.state);
        data.remove();

        this.$watch("state.estimated_time, addToDates", () => {
            if ( ! Alpine.store("current").due_date ) {
                this.setDueDate();
            }
        });
    },
    /**
     * Determina si el proyecto es `nuevo` dependiendo de si
     * posee un id o no.
     *
     * @returns Boolean
     */
    isNew() {
        return this.state.id === null && this.state.created_at === null;
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
        return Alpine.store("current").status != "finished" ? false : true;
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
        return Alpine.store('current').due_date ? true : false;
    },
    /**
     * Determina si el proyecto ya se marcó como iniciado o no
     * @returns {boolean}
     */
    isStarted() {
        return Alpine.store('current').started_at ? true : false;
    },
    /**
     * Actualiza valores luego de que la respuesta es enviada por 
     * el comoponente save-record
     * 
     * @param {*} u El item actualizado
     * @returns {Void}
     */
    handleUpdate( u ) {
        if (! Alpine.store('saveProjectRequest')) return; // Aquí se determina si fue este componente quien realizó la peticion

        Alpine.store("current", u.project);

        Alpine.store('state').created_at  = u.project.created_at;
        Alpine.store('state').updated_at  = u.project.updated_at;
        Alpine.store('state').finished_at = u.project.finished_at;
        Alpine.store('state').started_at  = u.project.started_at;

        Alpine.store('saveProjectRequest', false);
    },
});