import { toastError, toastsSuccess, toastQuestion, url, loader } from '../../extra/utilities.js';

export default () => ({
    attachments: [],
    init() {
        this.$nextTick( () => this.getAttachments() );
    },
    /**
     * Se obtienen los adjuntos.
     */
    async getAttachments () {
        try {
            loader.classList.remove('d-none');
            const _url = `${url}project/${ Alpine.store("__control").id }/attachments`;
            const attachments = await ( await fetch(_url) ).json();

            this.attachments = attachments;
        } catch (e) {
            toastError(e.message);            
        }
        loader.classList.add('d-none');
    },
    /**
     * Realiza la consulta para eliminar un adjunto.
     * 
     * @param {number} id Id del archivo a eliminar
     * @returns {void}
     */
    async deleteAttachment( id ) {
        if ( ! await toastQuestion('¿Está seguro?', 'red') ) return;

        try {
            loader.classList.remove('d-none');
            const _url = `${url}attachment/${id}`;
            const res = await ( await fetch(_url, { method: 'DELETE' }) ).json();

            if ( res.status == 'error' ) throw (res.message);

            toastsSuccess("Archivo eliminado con exito!");
            this.removeAttachemntFromList( id );
        } catch (e) {
            toastError(e.message);
        }

        loader.classList.add('d-none');
        return;
    },
    /**
     * Esta funcion no realiza la peticion, solamente elimina el 
     * adjunto de la variable ~attachments~
     * 
     * @param {number} id id del adjunto a eliminar.
     * @returns {void}
     */
    removeAttachemntFromList( id ) {
        const index = this.attachments.findIndex( a => a.id == id );
        if ( index === -1 ) return;

        this.attachments.splice( index, 1 );
        return;
    },
    /**
     * Genera el href para el archivo.
     * 
     * @param {*} id
     * @returns {string}
     */
    getUrl({ id }) {
        return `${url}attachment/${id}/download`;
    }
});
