import { url } from '../extra/url.js';
import { _fetch } from '../extra/extra.js';
import { _modal, loader } from '../extra/loaded.js';
import { toastError, toastsSuccess } from "../extra/toast.js";


export default () => ({
    showObsList: true,
    /**
     * Realiza la petición para 'traer' las observaciones.
     * @returns {Void}
     */
    async getObs() {      
        try {
            loader.classList.remove("d-none");
            const _url = `${url}project/${ Alpine.store('current').id }/observations`;
            const {obs} = await ( await fetch(_url) ).json();

            Alpine.store("currentObsList", obs);
        } catch(e) {
            toastError('No se han podido cargar las observaciones.');
        }
        
        this.$dispatch('list-obs'); // Despues de `recuperar` las observaciones, se listan en otro componente
        loader.classList.add("d-none");
    },
    /**
     * Elimina una Observacion.
     * @param {*} id Id de la observacion
     * @param {*} e Elemento (representa el elemento <li> del html )
     */
    async deleteObs(id) {
        loader.classList.remove("d-none");

        const _url = url + `observation/${id}`;

        try {
            const data = await (await fetch(_url, { method: "DELETE" })).json();
            if (data.status == 'error') throw data.message;

            this.removeObsFromList( id );
            this.$dispatch('list-obs');
            toastsSuccess(data.message);     
        } catch (error) {
            toastError(error.message);
        }

        loader.classList.add("d-none");
    },
    /**
     * Remueve una observacion del listado general de observaciones.
     * 
     * @param {number} id 
     * @returns 
     */
    removeObsFromList( id ) {
        const index = Alpine.store("currentObsList").findIndex( ob => ob.id == id);

        if (index >= 0 ) Alpine.store("currentObsList").splice( index, 1 );

        return;
    }
});