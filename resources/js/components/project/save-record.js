import { toastError, toastsSuccess, _modal, loader, _fetch, url } from "../../extra/utilities.js";
import { Alpine } from "../../Alpine.js";

export default () => ({
    method: undefined,
    /**
     * Se ejecuta la funcion de guardar y se despacha un evento dependiendo 
     * del resultado de la peticion(si se pudo guadar o no).
     */
    async saveProject() {
        loader.classList.remove("d-none");
        Alpine.store('saveProjectRequest', true);
        await this.save();
        
        loader.classList.add("d-none");
    },
    /**
     * Se encarga de realizar la peticion para guardar o actualizar un registro.
     */
    async save(o = false) {
        const _o =  o ? o : Alpine.store("project");
        this.method = _o.id ? "put" : "post"; // Definimos si es una insercion o actualizacion
        const body = this.handleBody(_o);
        const url = this.handleUrl(_o);

        try {
            // Se realiza la peticion
            const res = await _fetch(url, this.method, body);

            if (res.status == "error") {
                toastError(res.message);
                return;
            }

            toastsSuccess();
            this.$dispatch("saved-record", res);
        } catch (error) {
            toastError("La operacion ha fallado. " + error.message);
        }
    },
    /**
     * Genera el cuerpo de la petición
     *
     * @param {Object} o Representa el objecto del que se toman las propiedades
     * @returns {Object}
     */
    handleBody(o) {
        o.started_at = 
                o.status == "new"
                    ? null
                    : o.started_at
                        ? o.started_at == ""
                            ? null
                            : o.started_at
                        : null;

        o.due_date = o.due_date
                ? o.due_date == ""
                    ? null
                    : o.due_date
                : null;

        return o;
    },
    /**
     * Genera la url apropiada para la petición.
     *
     * @param {Object} o Representa el objeto de que se esta recuperando la info
     * @returns
     */
    handleUrl(o) {
        let _url = "";
        const t = o.type ? o.type.replace(/_/, '-') : "project";

        if (this.method == "put") {
            return url + t + "/" + o.id;
        }

        switch (t) {
            case "project":
                _url += url + t;
                break;
            case "task":
                _url += url + `project/${o.parent}/tasks`;
                break;

            case "sub-task":
                _url += url + `task/${o.parent}/sub-tasks`;
                break;
        }

        return _url;
    },
    /**
     * Determina si el registro puede guardarse.
     *
     * @returns {boolean}
     */
    canSave() {
        try {
            if (Alpine.store("project").title.length < 5) return false;
            if (Alpine.store("project").created_by_id == 0) return false;
            if (Alpine.store("project").status != "new" && ! Alpine.store("project").started_at) return false;
            if (Alpine.store("project").status != "finished") return true;
            if (! Alpine.store("project").delegate_id || Alpine.store("project").delegate_id == '0') return false;
    
            return /100%|No Aplica/i.test(Alpine.store("progress").formated);
        } catch (error) {
            return false;
        }
    },

    /**
     * Muestra un botón verde encima del botón save siempre que haya cambios
     * sin guardar.
     * 
     * @returns {boolean}
     */
    projectHasChanged() {
        for (const key in Alpine.store("__control")) {
            if (Object.hasOwnProperty.call(Alpine.store("__control"), key)) {
                if (Alpine.store("__control")[key] !== Alpine.store("project")[key]) {
                    return true;
                }
            }
        }
        return false;
    }
})
