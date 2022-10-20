import { url as URL, toastError, _modal, loader, _fetch, toastsSuccess } from "../../extra/utilities.js";

export default ( autoOpen = true ) => ({
    show: false,
    requestId: undefined,
    autoOpen: autoOpen,
    state: { delegate_id: "0" },
    /**
     * Abre el modal y realiza ciertas operaciones.
     */
    open( data = null ){
        if (
            Object.prototype.hasOwnProperty.call(data, 'title')
            && Object.prototype.hasOwnProperty.call(data, 'desc')
        ) {
            this.state.title = data.title;
            this.state.description = data.desc;
            this.requestId = data.requestId ?? undefined;
        }
        
        this.show = true;
        
        setTimeout(() => {
            ( document.getElementById('n-project-title') ).focus();
        }, 200);
    },
    /**
     * Determina si se habilita o no el botón de guardar.
     * @returns {boolean}
     */
    canSave() {
        const required = ['title', 'priority', 'created_by_id', 'delegate_id'];
        const areSet = required.every( p => this.state[p] );

        if (! areSet) return false;
        if (this.state.title.length < 5) return false;
        if (this.state.created_by_id == 0) return false;

        return true;
    }, 
    /**
     * Reestablece algunas propiedades a sus valores por defecto.
     */
    setDefault() {
        this.show   = false;
        this.requestId = undefined;
        this.state  = { delegate_id: "0" };
    },
    /**
     * Guarda xD
     */
    async save() {
        const _url = URL + 'project';
        const body = this.state;
        try {
            loader.classList.remove('d-none');

            if (this.requestId !== undefined) {
                body.requestId = this.requestId;
            }
            
            const res = await _fetch(_url, "POST", body);
            
            if (res.status == "error") {
                toastError(res.message);
                return;
            }

            if (this.autoOpen) {
                this.$dispatch('refresh-projects');
                window.open( Alpine.store('viewProjectUrl').getUrl(res.project.slug), '_blank');
            }

            this.setDefault();
            this.$dispatch('new-project-info', res.project);
            toastsSuccess("Proyecto creado!");
        } catch (error) {
            toastError(error.message);
        }
        loader.classList.add('d-none');
    }
});