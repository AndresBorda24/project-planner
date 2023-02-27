import { toastError, toastsSuccess, url, loader } from '../../extra/utilities.js';

export default () => ({
    open: false,
    files: [],
    /**
     * Abre el modal cuando `escucha` el evento 'add-attachment'.
     */
    handleShow() {
        this.open = true && true;
    },
    /**
     * Limpia el array de archivos y cierra el modal.
     */
    close() {
        this.files = [];
        this.open = false;
    },
    /**
     * Toma los archivos almacenados por el input y los almacena 
     * en la variable ~files~.
     * @param {Object} files 
     */
    handleFiles(files) {
        this.files = Object.values(files);
    },
    /**
     * Elmina un archivo del listado que se planea enviar.
     * @param {number} index 
     */
    removeFile( index ) {
        this.files.splice( index , 1);
        if (! this.hasFiles()) {
            ( document.getElementById('attachments') ).value = '';
        }
    },
    /**
     * Realiza la peticion y sube los archivos.
     */
    async upload() {
        const _data = new FormData();
        const _url = `${url}project/${ Alpine.store("__control").id }/attachments`;

        this.files.forEach( f => _data.append('attachments[]', f) );

        try {
            loader.classList.remove('d-none');
            const res = await ( await fetch(_url, {
                method: 'POST', 
                body: _data
            })).json();

            if ( !res.save_errors ) throw ('Archivos subidos, pero no guardados en la base de datos...');
            if ( res.upload_errors.length > 0 ) {
                console.table(res.upload_errors);

                throw ('Errores al subir ciertos archivos: ' + JSON.stringify(
                    Object.keys( res.upload_errors )
                ));
            };

            toastsSuccess('Archivo subido con exito!');
            this.$dispatch('reload-attachments');
            this.close();
        } catch (error) {
            toastError( error.message );
        }

        loader.classList.add('d-none');
    },
    /**
     * Determina si mostrar o no el botón de envio dependiendo 
     * de si hay (o no) archivos en ~files~
     * @returns {boolean}
     */
    hasFiles() {
        return this.files.length > 0;
    },
});
