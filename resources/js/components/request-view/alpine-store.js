import { Alpine } from "../../Alpine.js";
import { url as __URL, toastError } from "../../extra/utilities.js";

const requestsData = await (await fetch(`${__URL}requests`))
    .json()
    .catch((e) => toastError(e.message));

const status = await ( await fetch(`${__URL}status`) )
    .json()
    .catch((e) => toastError(e.message));

document.addEventListener("alpine:init", () => {
    if (requestsData.status == "error") {
        toastError(requestsData.message);
        Alpine.store("requests", []);
    } else {
        Alpine.store("requests", requestsData.requests);
    }

    Alpine.store("requestLimit", 10);

    Alpine.store("searchBox", "");

    Alpine.store("status", status);

    Alpine.store("defaultRequest", {
        getDefault: () => ({
            "gema": null,
            "area": null,
            "pinned": null,
            "status": null,
            "subject":"",
            "project": null,
            "desarrollo": null,
            "data": { "scope":"1","importance":"1","cost":"5","span":"1","viability":"1","frequency":"1","economy":"1","normativity":"1" }
        })
    })

    Alpine.store("currentRequest", Alpine.store("defaultRequest").getDefault() );
    
    Alpine.store("observationsCache", {
        obs: {},
        pre: "obs",
        areObsLoaded(id) {
            return `${this.pre}_${id}` in this.obs;
        },
        getObs(id) {
            return this.obs[`${this.pre}_${id}`];
        },
        addObs(id, obs) {
            this.obs[`${this.pre}_${id}`] = obs;
        },
        removeObs(id) {
            delete this.obs[`${this.pre}_${id}`];
        },
        apendObs(id, obs) {
            this.obs[`${this.pre}_${id}`].push(obs);
        },
        removeObsNode(id, nodeId) {
            const index = this.obs[`${this.pre}_${id}`].findIndex(
                (el) => el.id == nodeId
            );

            if (index === -1) {
                return false;
            }

            try {
                this.obs[`${this.pre}_${id}`].splice(index, 1);
            } catch (error) {
                return false;
            }

            return true;
        },
    });

    Alpine.store("projectsInfoCache", {
        projects: {},
        pre: "p",
        isProjectLoaded(id) {
            return `${this.pre}_${id}` in this.projects;
        },
        getProject(id) {
            return this.projects[`${this.pre}_${id}`];
        },
        addProject(id, info) {
            this.projects[`${this.pre}_${id}`] = info;
        }
    });

    Alpine.store("obs", {
        obs: [],
        timeout: undefined,
        loadingObs: false,
        async fetchObs(e) {
            this.obs = [];
            this.loadingObs = true;

            if (typeof this.timeout != "undefined") {
                window.clearTimeout(this.timeout);
            }

            if (Alpine.store("observationsCache").areObsLoaded(e.id)) {
                this.obs = Alpine.store("observationsCache").getObs(e.id);
                this.loadingObs = false;

                return;
            }

            await this.loadObs(e.id);
        },
        /**
         * Realiza la peticion para traer las observaciones.
         *
         * @param {number} id Id de la solicitud seleccionada
         */
        async loadObs(id) {
            this.timeout = setTimeout(async () => {
                const res = await (
                    await fetch(`${__URL}request/${id}/observations`)
                ).json();
                this.loadingObs = false;
                this.obs = res.obs;
                this.timeout = undefined;
                Alpine.store("observationsCache").addObs(id, res.obs);
            }, 2000);
        },
    });

    Alpine.store("saveRequest", {
        /**
         * Realiza la peticion para guardar o actualizar una solicitud.
         *
         * @param {Object} data El cuerpo de la peticion
         * @param {String} type El metodo de la petición POST | PUT
         * @returns
         */
        async save(data, type = "POST") {
            try {
                const _url = this.getUrl(data, type);

                const res = await (
                    await fetch(_url, {
                        method: type,
                        body: JSON.stringify(data),
                        headers: {
                            "Content-Type": "application/json",
                        },
                    })
                ).json();

                return res;
            } catch (e) {
                return {
                    status: "error",
                    message: e.message
                };
            }
        },

        /**
         * Realiza la peticion para actualizar el pin de la solicitud.
         * @param {number} pinnedValue El nuevo valor para la propiedad pinned de la solicitud
         * @returns {boolean | object}
         */
        async updatePinned( id, pinnedValue, newOrder) {
            try {
                const _url = `${__URL}request/${id}/set-pin`;

                const res = await (
                    await fetch(_url, {
                        method: "PUT",
                        body: JSON.stringify({ pinnedValue: pinnedValue, newOrder: newOrder }),
                        headers: {
                            "Content-Type": "application/json",
                        },
                    })
                ).json();

                if (res.status == "error") {
                    throw new Error(res.message);
                }

                return res;
            } catch (e) {
                console.warn(e);
                toastError(e.message);
                return false;
            }
        },

        updatePinnedRequestPosition( res ) {
            const req = JSON.parse( JSON.stringify(Alpine.store("requests")));

            Object.keys( res ).forEach( key => {
                const index = req.findIndex( el => el.id == key );
                delete Alpine.store('requests')[ index ];

                if (index === -1) {
                    console.log( '-1' );
                    return;
                }

                req[ index ].pinned = res[ key ];
            });

            return req;
        },

        /**
         * Genera la url indicada
         * @param {Object} data cuerpo de la peticion
         * @param {string} type Si es POST o PUT
         */
        getUrl(data, type) {
            switch (type.toLocaleLowerCase()) {
                case "post":
                    return `${__URL}request`;
                case "put":
                    if (!Object.prototype.hasOwnProperty.call(data, "id")) {
                        throw new Error(
                            "No se ha encontrado el id de la Solicitud a actualizar"
                        );
                    }

                    return `${__URL}request/${data.id}`;
            }
        },
    });
});
