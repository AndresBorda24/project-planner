import { Alpine } from "./Alpine.js";
import { toastError, toastsSuccess, url as URL, _fetch } from "./extra/utilities.js";
import * as Request from "./components/request-view/bundle.js";
import createProject from "./components/index/create-project.js";
import "./partials/sidebar.js";
import "./components/request-view/alpine-store.js";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("buttons", Request.buttons);
    Alpine.data("addProject", createProject);
    Alpine.data("bindRequest", Request.bindRequest);
    Alpine.data("createRequestBind", Request.createRequestBind);
    Alpine.data("editRequest", Request.edit);
    Alpine.data("requestItem", Request.item);
    Alpine.data("requestsList", Request.list);
    Alpine.data("observations", Request.obs);
    Alpine.data("deleteRequest", Request.deleteRequest);
    Alpine.data("createRequest", Request.create);
    Alpine.data("showProjectInfo", Request.showProjectInfo);
    Alpine.data("loadMoreRequests", Request.loadMoreRequests);
    Alpine.data('buscarProyectoRequest', Request.selectProject);
    Alpine.data("updateProjectInfo", () => ({
        /**
         * Realiza la peticion para guardar en la BD la informacion del proyecto.
         * 
         * @param {number} id El id del proyecto.
         */
        async saveProjectId( id ) {
            try {
                const _url = `${URL}request/${Alpine.store("currentRequest").id}/set-project`;
                const res = await _fetch(_url, "PUT", {
                    projectId: id
                });

                if (res.status == "error") {
                    throw new Error(res.message);
                }

                toastsSuccess("Se actualizó la información de la solicitud");
                Alpine.store("currentRequest").status = res.dId;
                return true;
            } catch (e) {
                toastError(e.message);
                return false;
            }
        },

        /**
         * Despues de creado el proyecto se cargan los datos a la solicitud.
         * 
         * @param {Object} project Proyecto que due creado.
         */
        async setNewProjectInfo( project ) {
            try {
                await this.saveProjectId( project.id );
                Alpine.store("currentRequest").project = {
                    id: project.id,
                    slug: project.slug
                };
            } catch (error) {
                toastError(e.message);
            }
        },
    }));
});

Alpine.start();
