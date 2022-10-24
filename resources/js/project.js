import { Alpine } from './Alpine.js';
import { url, loader, toastError, toastsSuccess, toastQuestion } from "./extra/utilities.js";
import { 
    saveRecord, projectForm, listObs, 
    manageObs, childModal, tasksList, 
    addObservation, addAttachment, manageAttachments 
} from "./components/components.js";

import './extra/alpine-store.js';
window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("project", projectForm);

    Alpine.data("saveRecord", saveRecord);

    Alpine.data("tasksList", tasksList);
    
    Alpine.data("viewChild", childModal);
   
    Alpine.data("manageObservations", manageObs);

    Alpine.data('listingObs', listObs);

    Alpine.data("newObservation", addObservation);

    Alpine.data("addAttachment", addAttachment);

    Alpine.data("manageAttachments", manageAttachments);

    Alpine.data("convertToTask", () => ({
        async confirmConvert() {
            const c = await toastQuestion(
                '¿Realmente desea convertir esta sub-tarea en una tarea?',
                'blue'
            );
            if (! c) return;
        
            const _url = url + `sub-task/${Alpine.store('currentChild').id}/to-task`;
            try {
                const { data: r } = await (await fetch(_url)).json();
                if (r.status == 'error') throw r.message;
        
                toastsSuccess(r.message);
                        
                this.$dispatch('load-tasks');
                this.$dispatch('load-obs');
                this.$data.close();
            } catch (error) {
                toastError(error.message);
            }
        },
    }));

    Alpine.data("saveChild", () => ({
        requested: false,
        /**
         * Ejecuta la peticion que crea o actualiza una tarea o sub-tarea.
         */
        async save() {
            loader.classList.remove("d-none");

            const body = { ... this.$data.child };
            body.id =  this.$data.child.id ?? null;

            if ( typeof this.$data.father.id != "undefined" ) body.parent = this.$data.father.id;

            this.requested = true;
            this.$dispatch("save-record", body);
        },
        /**
         * Determina si se desabilita el botón guardar.
         * @returns {Boolean}
         */
        canSave() {
            if ( ! this.checkAll() ) return false;
            
            if (this.$data.child.title.length < 5) return false;
            if (this.$data.child.created_by_id == 0) return false;
            if (this.$data.child.status != "new" && this.$data.child.started_at == null) return false;
            if (this.$data.child.status != "finished" || this.$data.child.type == "sub_task") return true;
            if (! this.$data.child.delegate_id || this.$data.child.delegate_id == 0) return false;

            return typeof this.$data.child.progress == 'undefined' || this.$data.child.progress >= 100;
        },
        /**
         * Determina si las propiedades obligatorias están seteadas.
         * @returns {boolean}
         */
        checkAll() {
            let props = ["title", "status", "created_by_id", "priority"];
            return props.every( p => this.$data.child[ p ] );
        },
        /**
         * Actualiza valores luego de que la respuesta es enviada por 
         * el comoponente save-record.
         * 
         * @param {*} u El item actualizado
         * @returns {Void}
         */
        handleUpdate(u) {
            if (! this.requested) return; // Aquí se determina si fue este componente quien realizó la peticion

            if (typeof this.$data.father.id != "undefined") {
                this.$data.father.id = undefined;
                this.$data.child.id  = u[this.child.type].id;
            }

            const key = `${this.$data.child.type}_${this.$data.child.id}`;
            Alpine.store("itemCache").updateItem(key, { ...u[this.$data.child.type] });
            Alpine.store('currentChild' , { ...u[this.$data.child.type] });

            this.requested = false;
            this.$data.child = u[this.$data.child.type];

            this.$dispatch('load-tasks');
            this.$dispatch('child-loaded'); // Para que recargen las observaciones. (es necesario)
        },
    }));

    Alpine.data("removeProject", () => ({
        async confirmDel() {
            // Se muestra una alerta de confirmacion de eliminacion
            const confirmation = await Alpine.store('removeItems').confirm();
    
            // Si la confirmacion es afirmativa, se procederá a eliminar el registro.
            if (confirmation) {
                const data = await Alpine.store('removeItems').remove(
                    'project',
                    Alpine.store("project").id
                );
                if (data.status != 'error') {
                    alert('Proyecto Eliminado! La página se recargará.');
                    location.reload();
                }
            }
        },
    }));

    Alpine.data("addButton", () => ({
        /**
         * Determina si se muestra o no el botón de anñadir nueva
         *
         * @return {Boolean}
         */
        isAllowed() {
            return ["process", "new"].includes(Alpine.store("__control").status);
        },
        /**
         * Despacha el evento para crear una nueva tarea
         */
        addTask() {
            this.$dispatch('load-child', { 
                father: Alpine.store("__control").id, 
                type: 'task', 
                pTitle: Alpine.store("__control").title 
            });
        }
    }));
});

Alpine.start();
