import { url, loader, _fetch, toastError, toastsSuccess } from "../../extra/utilities.js";

export default () => ({
    show: false,
    type: 'project',
    newObsUrl: "",
    newObsBody: "",
    newObsAuthor: 0,
    /**
     * Realiza la peticion para guardar la observacion.
     * @returns {Void}
     */
    async saveNewOb() {
        loader.classList.remove("d-none");
        const body = {
            author: this.newObsAuthor,
            body: this.newObsBody.trim(),
            project_id: Alpine.store("__control").id
        };

        try {
            const res = await _fetch(this.newObsUrl, "post", body);
            if (res.status == "error") throw res;

            toastsSuccess(res.message);
            this.$dispatch("load-obs");
        } catch (e) {
            toastError(
                "Ha ocurrido un error: " + e.message
            );
        }

        this.setDefault();
        loader.classList.add("d-none");
        return;
    },
    /**
     * Determina si se habilita el boton de guardar o no.   
     * @returns {Boolean}
     */
    validate() {
        return this.newObsBody.trim().length > 10 && this.newObsAuthor > 0;
    },
    /**
     * Establece los valores por defecto.
     */
    setDefault() {
        this.newObsBody = "";
        this.newObsAuthor = 0;
        this.newObsUrl = "";
        this.show = false;
        document.activeElement.blur();
    },
    /**
     * Abre la tarjeta de creacion de observaciones.
     * @param {Object}  
     */
    handleShow({ type, id }) {
        this.type = type;
        this.newObsUrl = url + `${type.replace(/_/, '-')}/${id}/observations`;
        this.show = true;
        document.activeElement.blur();

        this.$nextTick( () => {
            setTimeout( () => {
                document.getElementById('new-ob-body').focus();
            }, 200)
        });
    },
    /**
     * Le da color al borde
     */
    borderColor( type ) {
        switch (type) {
            case 'task':
                return 'border-warning';
            case 'sub_task':
                return 'border-primary';
            default:
                return 'border-secondary';
        }
    },
});
