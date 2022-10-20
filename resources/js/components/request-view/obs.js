import { _fetch, toastsSuccess, toastError, url as URL  } from "../../extra/utilities.js";


export default () => ({
    body: "",
    author: undefined,
    timeout: undefined,
    showForm: false,
    /**
     * Valores por defecto
     */
    setDefault() {
        this.showForm = false;
        this.author = undefined;
        this.body = "";
    },
    /**
     * Obtiene el usuario.
     */
    setUser({ author_id }) {
        const author = Alpine.store("users").find(
            (el) => el.consultor_id == author_id
        );

        return author ? author.consultor_nombre : author_id;
    },
    /**
     * Guarda una observacion en la base de datos. Ejecuta la peticion.
     */
    async save() {
        try {
            const _url = `${URL}request/${
                Alpine.store("currentRequest").id
            }/observations`;
            const res = await _fetch(_url, "POST", {
                body: this.body,
                author: this.author,
            });

            if (res.status == "error") {
                throw new Error(res.message);
            }

            Alpine.store("obs").obs = res.obs;
            Alpine.store("observationsCache").addObs(
                Alpine.store("currentRequest").id,
                res.obs
            );
            this.setDefault();
            toastsSuccess("Agregada!");
        } catch (error) {
            toastError(error.message);
        }
    },
    /**
     * Realiza la peticion para eliminar una observacion de la base de datos.
     *
     * @param {number} id Id de la observacion a eliminar
     */
    async deleteObs(id) {
        try {
            const _url = `${URL}observation/${id}`;
            const res = await (await fetch(_url, { method: "DELETE" })).json();
            if (res.status == "error") {
                throw new Error(res.message);
            }
            this.manageRemove(id);
            toastsSuccess("Eliminada!");
        } catch (error) {
            toastError(error.message);
        }
    },
    /**
     * Elimina la observacion de los listados.
     *
     * @param {number} id Id de la observacion eliminada.
     */
    manageRemove(id) {
        const localIndex = Alpine.store("obs").obs.findIndex(
            (el) => el.id == id
        );
        Alpine.store("obs").obs.splice(localIndex, 1);

        Alpine.store("observationsCache").removeObsNode(
            Alpine.store("currentRequest").id,
            id
        );
    },
    /**
     * Determina si se puede crear una observacion. Habilita el boton.
     *
     * @returns {boolean}
     */
    canSave() {
        if (this.body.length < 10) {
            return false;
        }

        if (typeof this.author == "undefined") {
            return false;
        }

        return true;
    },
});
