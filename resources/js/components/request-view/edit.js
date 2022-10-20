import { toastError, url as URL, _fetch } from "../../extra/utilities.js";

export default () => ({
    text: ["Muy Bajo", "Bajo", "Medio", "Alto", "Muy Alto"],
    state: undefined,

    init() {
        this.$watch("$store.currentRequest", () => {
            this.state = Alpine.store("currentRequest");
        });
    },

    /** Determina si cargar o no el html del menu */
    loadMenu() {
        if (
            typeof this.state == "undefined" ||
            typeof Alpine.store("currentRequest") == "undefined"
        ) {
            return false;
        }
        if (
            !Object.prototype.hasOwnProperty.call(
                Alpine.store("currentRequest"),
                "id"
            )
        ) {
            return false;
        }

        return true;
    },

    /** Obtiene el texto para las etiquetas en los inputs range */
    getText(value) {
        return this.text[value - 1];
    },

    /** Prepara el cuerpo para la petición */
    setBody() {
        return {
            id: this.state.id,
            subject: this.state.subject,
            area: this.state.area,
            desarrollo: this.state.desarrollo,
            gema: this.state.gema,
            status: this.state.status,
            scope: this.state.data.scope,
            importance: this.state.data.importance,
            cost: this.state.data.cost,
            span: this.state.data.span,
            viability: this.state.data.viability,
            frequency: this.state.data.frequency,
            economy: this.state.data.economy,
            normativity: this.state.data.normativity,
        };
    },

    /** Realiza la peticion de actualizacion */
    async save() {
        if (this.state.subject.length < 10) {
            return;
        }
        
        const body = this.setBody();
        const res = await Alpine.store("saveRequest").save(body, "PUT");

        if (res.status == "error") {
            toastError(res.message);
        }
    },

    /**
     * Devuelve true si esta seteado el project_id de la solicitud.
     */
    hasProject() {
        return (typeof this.state.project == 'object' &&  this.state.project !== null);
    },

    /**
     * Abre una nueva ventana para que se cree el proyecto con la informacion de
     * la solicitud.
     */
    createProject() {
        const title = this.state.subject.substring(0, 80);
        const desc = this.state.subject.substring(80);

        this.$dispatch('open-create-modal', { 
            title: title,
            desc: desc,
            requestId: this.state.id
        });
    },

    /**
     * Abre el proyecto relacionado en una pestaña.
     */
     goToProject() {
        const _url = `${URL.substring(0, URL.length - 4)}project/${this.state.project.slug}/ver`;
        window.open(_url, '_blank').focus();
    }
});
