import { Alpine } from "./Alpine.js";
import { toastError, toastsSuccess, url as __URL, _fetch} from "./extra/utilities.js";
import "./partials/sidebar.js";
import "./components/config/alpine-store.js";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("saveNewScope", () => ({
        scope: "",
        /**
         * Determina si se puede guardar el Alcance o no.
         * @returns {boolean}
         */
        canSave() {
            return (this.scope.trim()).length > 3;
        },
        /**
         * Realiza la peticion para guardar el alcance.
         */
        async save() {
            try {
                const res = await _fetch(`${__URL}gema-scope`, 'POST', { 
                    scope: this.scope.trim() 
                });

                if ( res.status == "error" ) {
                    throw new Error(res.message);
                }

                this.addToList( res );
                toastsSuccess("Agregado!");
            } catch (error) {
                toastError( error );
            }
        },
        /**
         * Añade el nuevo alcance al listado.
         * 
         * @param {object} res El objeto con la info del nuevo alcance.
         */
        addToList( res ) {
            const sc = this.scope.trim();
            Alpine.store("gemaScopes").push({
                id: res.id,
                scope: sc.toUpperCase(),
                visible: true
            });

            this.scope = "";
        }
    }));

    Alpine.data("saveNewStatus", () => ({
        status: "",
        /**
         * Determina si se puede guardar el Alcance o no.
         * @returns {boolean}
         */
        canSave() {
            return (this.status.trim()).length > 3;
        },
        /**
         * Realiza la peticion para guardar el alcance.
         */
        async save() {
            try {
                const res = await _fetch(`${__URL}add-status`, 'POST', { status: this.status.trim() });

                if ( res.status == "error" ) {
                    throw new Error(res.message);
                }

                this.addToList( res );
                toastsSuccess("Agregado!");
            } catch (error) {
                toastError( error );
            }
        },
        /**
         * Añade el Estado al listado.
         * @param {object} res Objeto con la informacion del nuevo Estado
         */
        addToList( res ) {
            const s = this.status.trim();
            Alpine.store("status").push({
                id: res.id,
                status: s.toUpperCase(),
                visible: true
            });

            this.status = "";
        }
    }));

    Alpine.data("removeStatus", () => ({
        del: null,
        replace: null,
        canRemove() {
            if (typeof this.del != 'number' || typeof this.replace != 'number') {
                return false;
            }
            
            if (this.del == this.replace) {
                return false
            }

            return true;
        },
        removeElements() {
            const index = Alpine.store("status").findIndex( el => el.id == this.del );
            if ( index === -1) {
                console.warn("No se ha podido remover el Estado del listado");
            } else {
                Alpine.store("status").splice(index, 1);
            }

            this.del = null;
            this.replace = null;
        },
        async removeStatus() {
            try {
                const _url = `${__URL}status/${this.del}/replacement/${this.replace}`;
                const res =  await ( await fetch(_url, { method: "DELETE" }) ).json();

                if ( res.status == "error" ) {
                    throw new Error(res.message);
                }

                this.removeElements();
                toastsSuccess('Eliminado!');
            } catch (error) {
                toastError( error.message );
            }
        }
    }));

    Alpine.data("removeScope", () => ({
        del: null,
        replace: null,
        canRemove() {
            if (typeof this.del != 'number' || typeof this.replace != 'number') {
                return false;
            }
            
            if (this.del == this.replace) {
                return false
            }

            return true;
        },
        removeElements() {
            const index = Alpine.store("gemaScopes").findIndex( el => el.id == this.del );
            if ( index === -1) {
                console.warn("No se ha podido remover el alcance del listado");
            } else {
                Alpine.store("gemaScopes").splice(index, 1);
            }

            this.del = null;
            this.replace = null;
        },
        async removeScope() {
            try {
                const _url = `${__URL}gema-scope/${this.del}/replacement/${this.replace}`;
                const res =  await ( await fetch(_url, { method: "DELETE" }) ).json();

                if ( res.status == "error" ) {
                    throw new Error(res.message);
                }

                toastsSuccess('Eliminado!');
                this.removeElements();
            } catch (error) {
                toastError( error.message );
            }
        }
    }));

    Alpine.data("statusList", () => ({
        /**
         * Realiza la petición para modificar la información del Status.
         * 
         * @param {object} data Representa el Status, un objecto con el id y la visibilidad
         */
        async updateStatus( data ) {
            try {
                const _url = `${__URL}status/${data.id}`;
                const res = await _fetch(_url, "PUT", {
                    visible: data.visible,
                    basic: data.basic
                });

                if (res.status == "error") {
                    throw new Error(res.message);
                }
                
                toastsSuccess("Estado Modificado!");
            } catch(e) {
                toastError(e.message);
            }
        },
        
        /**
         * Impide que se desmarquen todos los Estados `basic`
         * 
         * @param {bool} status.basic La propiedad `basic` del estado 
         * @returns {bool}
         */
        canExcludeStatus({ basic }) {
            const basics = Alpine.store("status").reduce( (a, e) => (e.basic) ? ++a : a, 0 );
            console.log(basics);
            
            return (basics === 1 && basic);
        }
    }));

    Alpine.data("gemaScopeList", () => ({
        /**
         * Realiza la petición para modificar la visibilidad del Status.
         * 
         * @param {object} data Representa el Status, un objecto con el id y la visibilidad
         */
        async changeVisibility( data ) {
            try {
                const _url = `${__URL}gema-scope/${data.id}/change-visibility`;
                const res = await _fetch(_url, "PUT", {
                    visibility: data.visible
                });

                if (res.status == "error") {
                    throw new Error(res.message);
                }
                
                toastsSuccess("Visibilidad modificada!");
            } catch(e) {
                toastError(e.message);
            }
        }
    }));
});

Alpine.start();
