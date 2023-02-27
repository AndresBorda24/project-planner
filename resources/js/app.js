import { Alpine } from "./Alpine.js";
import { url, toastError, _modal, loader, _fetch, dateFormater } from "./extra/utilities.js";
import createProject from "./components/index/create-project.js";
import "./partials/sidebar.js";

window.Alpine = Alpine;


document.addEventListener("alpine:init", () => {
    /**
     * Guarda las url para hacer las peticiones que recuperan los 
     * proyectos
    */
    Alpine.store("listProjectUrl", {
        url: url + "projects?",
        current: url + "projects?",
    });
    /**
     * Hace las peticiones referentes a los proyectos.
     */
     Alpine.store("getProjects", {
        query: {
            "per-page": 10,
            details: [
                "title",
                "description",
                "status",
                "priority",
                "created_at"
            ],
            main: ["due_date", "slug"],
        },

        async get(q = this.query) {
            loader.classList.remove("d-none");
            const query = q;
            const params = new URLSearchParams(query);
            const _url = Alpine.store("listProjectUrl").url + params;
            const p = await (await fetch(_url, { method: "GET" })).json();

            Alpine.store("listProjectUrl").current = _url;
            loader.classList.add("d-none");
            return p;
        },

        /**
         * Carga los hijos del elemento.
         *
         * @param {Number} id
         * @param {string} type
         * @returns
         */
        async loadChildren(id, type) {
            loader.classList.remove("d-none");

            const t = type == "project" ? "tasks" : "sub-tasks";
            const _url = url + type + "/" + id + "/" + t;

            try {
                const res = await (await fetch(_url)).json();
                loader.classList.add("d-none");

                return res;
            } catch (error) {
                loader.classList.add("d-none");

                return {
                    status: "Ha ocurrido un error hermano.",
                };
            }
        },
    });

    Alpine.data("addProject", createProject);

    Alpine.data("projectList", () => ({
        projects: [],
        interval: 5 * 60 * 1000, // minutos * segundos * milesimas 
        timer: undefined,
        init() {
            this.getProjctList();
            
            this.timer = setInterval( () => { this.refresh() }, this.interval);
        },
        /**
         * Se obtiene el listado de los proyectos
         */
        async getProjctList() {
            const data = await Alpine.store("getProjects").get();
            this.projects = data.projects;
            this.$dispatch("render-pag", data.meta.pagination);
        },
        /**
         * Carga nuevamente el listado de proyectos. Teniendo en cuenta 
         * los filtros pero restaurando la paginación a 1
         */
        async refresh() {
            try {
                const _url = Alpine.store("listProjectUrl").current.replace(
                    /&page=.*/,
                    ""
                );
                // this.projects = [];

                const p = await (await fetch(_url)).json();

                this.projects = p.projects;
                this.$dispatch("render-pag", p.meta.pagination);
            } catch (error) {
                toastError("No se ha podido recuperar los proyectos.");
            }
        },
        /**
         * Refresca el listado de proyectos luego de que uno es creado.
         */
        refreshProjects() {
            clearInterval( this.timer );
            this.refresh();
            this.timer = setInterval( () => { this.refresh() }, this.interval);
        },
        /**
         * Establece el listado de proyectos con los valores de la varible 
         * `data`. Esta funcion se emplea cuando se ejecuta la paginación, 
         * por ejemplo, cuando se va de página 1 a página 2
         * @param {*} data Es el listado de proyectos
         */
        newProjectsFromPag(data) {
            this.projects = data;
        },
        /**
         * Devuelve una string que representa el progreso del proyecto.
         * @param {*} progress 
         * @returns {String}
         */
        proHandler(progress) {
            if (progress > 100) {
                return "No aplica";
            }
            return progress + "%";
        },
        /**
         * Se encara de mostrar el mensaje `Sin descripcion ...` cuando la 
         * descripcion del proyecto es un `falsy value`.
         * @param {*} desc 
         * @returns 
         */
        descHandler(desc) {
            if (!desc) {
                return "(Sin descripcion...)";
            }
            return desc;
        },
        /**
         * Retorna la prioridad en Español. Ostia.
         * @param {String} priority 
         * @returns {String}
         */
        priHandler(priority) {
            switch (priority) {
                case "high":
                    return "Alta";
                case "normal":
                    return "Normal";
                case "low":
                    return "Baja";
            }
        },
        /**
         * Determina de que color pintar la prioridad en el listado de proyectos.
         * @param {String} priority 
         * @returns {String}
         */
        priColor(priority) {
            switch (priority) {
                case "high":
                    return "text-danger";
                case "normal":
                    return "text-warning";
                case "low":
                    return "text-info";
            }
        },
        /**
         * Retorna el estado del proyecto en Español. Tio.
         * @param {String} status 
         * @returns {String}
         */
        stsHandler(status) {
            switch (status) {
                case "new":
                    return "Nuevo";
                case "process":
                    return "En proceso";
                case "paused":
                    return "Pausado";
                case "finished":
                    return "Terminado";
            }
        },
        /**
         * Da formato a la fecha de entrega. De no estar 'seteada' se retorna 
         * el varlo `No establecida.`
         * @param {*} d Fecha
         * @returns 
         */
        manageDueDate(d) {
            if (!d) return "No establecida.";

            return dateFormater.format(new Date(d));
        },
        /**
         * Establece el color de fondo de la loza. La pinta de un color rojo si la 
         * fecha estimada de entrga ya caducó.
         * @param {String} d Fecha estimada de entrega
         * @returns {String}
         */
        setBg(d) {
            if (!d) return;
            
            return new Date(d) < new Date() ? 'bg-pinky-plus' : '';
        },
        /**
         * Para Diferenciar un proyecto finalizado se pinta el border del hover con 
         * un color rojo.
         * 
         * @param {String} status El estado del proyecto 
         */
        hasFinished(status) {
            return status == 'finished' ? 'finished-project-item' : '';
        },
        /**
         * Establece las clases para diferenciar proyectos.
         * 
         * @param {String} d Fecha estimada de entrega
         * @param {String} status Estado del proyecto
         */
        bindClass(d, status) {
            return `${this.hasFinished(status)} ${this.setBg(d)}`
        }
    }));

    Alpine.data("project", () => ({
        collapse: false,
        /**
         * Muestra la descripcion con el hover del mouse.
         */
        collapseShow() {
            this.collapse = true;
        },
        /**
         * Esconde la descripcion cuando el mouse ya no esta sobre la losa.
         */
        collapseHide() {
            this.collapse = false;
        },
        /**
         * Despacha el evento para que se abra el modal de acciones.
         * @param {*} p 
         */
        open(p) {
            window.open( Alpine.store('viewProjectUrl').getUrl(p.slug));
        },
    }));

    Alpine.data("pagination", () => ({
        pages: 0,
        current: 0,
        first: false,
        last: false,
        total: 1,
        url: "",
        /**
         * Maneja los datos de paginacion enviados al cargar el listado de 
         * proyectos.
         * @param {*} data 
         */
        loadPagination(data) {
            this.pages = data.total_pages;
            this.current = data.current_page;
            this.total = data.total;
            this.first = this.current == 1;
            this.last = this.current == this.pages;
            this.url = data.url;
        },
        /**
         * Realiza la peticion a la pagina seleccionada. Por ejemplo, la
         * página 4. 
         * @param {*} i Numero de la página. 
         */
        async pagHandler(i) {
            const query = Alpine.store("getProjects").query;
            query.page = i;

            const { projects, meta } = await Alpine.store("getProjects").get(
                query
            );

            this.$dispatch("new-projects", projects); //-> Lista los proyectos obtenidos por la paginación.
            this.$dispatch("render-pag", meta.pagination); //-> Organiza la paginación.
        },
        /**
         * Cuando se despacha el evento de paginacion, esta funcion lo
         * 'redirecciona' para que sea manejado por la funcion `loadPagination` 
         * @param {*} detail 
         */
        eventHandler(detail) {
            this.loadPagination(detail);
        },
    }));

    Alpine.data("filters", () => ({
        showMenu: false,
        amount: 10,
        field: "id",
        dir: "desc",
        byStatus: "0",
        /**
         * Muestra o esconde el menú 
         */
        switchMenu() {
            this.showMenu = !this.showMenu;
        },
        /**
         * Cerra el menu 
         */
        closeMenu() {
            this.showMenu = false;
        },
        /**
         * Realiza la peticion con los filtros.
         */
        async apply() {
            // Aquí, en lugar de hacer una copia de la variable query, esta se 'vincula' a ella
            // por ende al editar 'query' también se modificará `Alpine.store('getProjects').query`
            const query = Alpine.store("getProjects").query;
            query["per-page"]  = this.amount;
            query["by-status"] = this.byStatus;
            query["order"] = [this.field, this.dir];

            if (this.byStatus == "0") {
                delete query["by-status"];
            }

            const { projects, meta } = await Alpine.store("getProjects").get(
                query
            );

            this.$dispatch("new-projects", projects);
            this.$dispatch("render-pag", meta.pagination);
        },
    }));

    Alpine.data("newProject", () => ({
        /**
         * Despacha el evento para que se abra el modal al clicar `Nuevo Proyecto`
         */
        new() {
            this.$dispatch("open-creation-modal");
        },
    }));

    Alpine.data("searchBox", () => ({
        search: "",
        found: [],
        view: false,
        /**
         * Realiza la peticion.
         */
        async searchBox() {
            if (this.search.length > 1) {
                const _url =
                    url +
                    "projects-search?search=" +
                    this.search;
                const data = await (await fetch(_url, { method: "GET" })).json();

                this.found =
                    data.projects.length > 0
                        ? data.projects
                        : [
                            {
                                id: 0,
                                title: "No se encontró ningun proyecto :'v.",
                                error: true,
                            },
                        ];
                this.view = true;
            } else {
                this.view = false;
            }
        },
        /**
         * Despacha el evento para que se abra el modal de acciones.
         * @param {*} p 
         */
        open(p) {
            window.open( Alpine.store('viewProjectUrl').getUrl(p.slug));
        },
        /**
         * Cada vez que la propiedad `search` es modificada, se ejecuta la 
         * funcion `searchBox`
         */
        handler() {
            this.$watch("search", () => this.searchBox());
        },
        /**
         * Determina si se puede habrir el proyecto.
         * @param {*} f Representa el proyecto encontrado (found)
         * @returns 
         */
        disable(f) {
            return Object.prototype.hasOwnProperty.call(f, "error");
        },
    }));
});

Alpine.start();
