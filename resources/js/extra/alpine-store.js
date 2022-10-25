import { toastQuestion, toastError, toastsSuccess,_modal, loader, _fetch, url, getUsers } from "./utilities.js";
await getUsers(url);

document.addEventListener("alpine:init", () => {
    getUsers().then( u => Alpine.store("users", u));

    Alpine.store("progress", { raw: '', formated: '' });
    /**
     * Aquí se almacena una copia del proyecto que se está mostrando en 
     * el modal.
     */
    Alpine.store("current", {});
    /**
     * Aquí se almacena una copia del proyecto que se está mostrando en 
     * el modal.
     */
     Alpine.store("__childControl", {});
    /**
     * Aquí se almacena una copia del proyecto que se está mostrando en 
     * el modal.
     */
    //  Alpine.store("users", JSON.parse(data.innerText));
    /**
     * Aqui se almacena el listado de tareas y sub tareas cargados al
     * mostrar un proyecto.
     */
    Alpine.store("currentTasksList", []);
    /**
     * Aqui se almacena el listado de observaciones cargadas al
     * mostrar un proyecto.
     */
    Alpine.store("currentObsList", undefined);
    /**
     * Almacena las funciones para confirmar una eliminacion y para eliminar 
     * una tarea o proyecto o sub...
     */
    Alpine.store("removeItems", {
        /**
         * Muestra mensaje de confirmacion de eliminación.
         */
        async confirm() {
            const c = await toastQuestion(
                "¿Realmente quere eliminar este registro?",
                "red"
            );
            return c;
        },

        /**
         * Lleva a cabo la operacion de eliminación.
         *
         * @returns {Object}
         */
        async remove(type, id) {
            loader.classList.remove("d-none");

            const _url = `${url}${type.replace('_', '-')}/${id}`;
            try {
                const res = await (
                    await fetch(_url, {
                        method: "delete",
                    })
                ).json();

                if (res.status == "error") {
                    toastError();
                    loader.classList.add("d-none");

                    return res;
                }

                toastsSuccess();
                loader.classList.add("d-none");
                return res;
            } catch (error) {
                toastError();
                loader.classList.add("d-none");
                return {
                    status: "error",
                };
            }
        },
    });
    /**
     * Lo mismo de arriba pero para las tareas y sub-tareas.
     */
    Alpine.store("itemCache", {
        _items: {},

        isLoaded(key) {
            return key in this._items;
        },

        remove(key) {
            delete this._items[key];
        },

        addItem(key, value) {
            if (this.isLoaded(key)) {
                return false;
            }

            this._items[key] = value;
            return true;
        },

        updateItem(key, value) {
            this._items[key] = value;
            return true;
        },
        /**
         * Devuelve el item guardado, si existe.
         * @param {String} key Representa la llave del objeto con la que se encuentra guardado en `_items`
         * @returns {Object}
         */
        getItem(key) {
            if (!this.isLoaded(key)) {
                return false;
            }

            return this._items[key];
        },

        updateProgress(key, progress) {
            this._items[ key ].progress = progress;
        }
    });
});