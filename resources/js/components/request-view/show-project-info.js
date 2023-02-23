import { toastError, url as URL } from "../../extra/utilities.js";

export default () => ({
    show: false,
    info: null,
    async showInfo() {
        if (this.show) {
            this.closeModal();
            return;
        }

        try {
            this.show = true;
            this.info = null;
            this.info = await this.getInfo( Alpine.store('currentRequest').project.id );
        } catch (error) {
            this.show = false;
            this.info = null;

            toastError(error.message);
        }
    },
    async getInfo( id ) {
        if ( Alpine.store("projectsInfoCache").isProjectLoaded(id) ) {
            return Alpine.store("projectsInfoCache").getProject(id);
        }

        const info = await this.fetchProjectInfo( id );
        
        if (info === false ) {
            throw new Error("Ha ocurrido un Error al obtener la informacion del proyecto.");
        }

        Alpine.store("projectsInfoCache").addProject(id, info);
        return info;
    },
    async fetchProjectInfo( id ) {
        try {
            const _url = `${URL}get-project-basic-info/${id}`;
            const res = await (await fetch(_url) ).json();

            if (res.status == "error") {
                throw new Error(res.message);
            }

            return res.info;
        } catch(e) {
            toastError(e.message);
            return false;
        }
    },
    getProgress( progress ) {
        return progress > 100 ? 'No Aplica' : progress + '%';
    },
    getPriority( priority ) {
        switch (priority) {
            case 'high':
                return 'Alta';
            case 'normal':
                return "Normal";
            case 'low':
                return "Baja";
            default:
                return "Nomal-"
        }
    },
    getStatus( status ) {
        switch (status) {
            case 'new':
                return 'Nuevo';
            case 'paused':
                return 'En Pausa';            
            case 'finished':
                return 'Finalizado';
            case 'process':
                return 'En proceso';
            default:
                return 'En proceso-';
        }
    },
    /**
     * Devuelve true si esta seteado el project_id de la solicitud.
     */
    hasProject() {
        return (typeof Alpine.store('currentRequest').project === 'object' &&  Alpine.store('currentRequest').project !== null);
    },
    closeModal() {
        this.show = false;
        this.info = null;
    },
});
