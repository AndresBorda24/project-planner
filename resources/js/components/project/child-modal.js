import { url, _fetch, loader, toastError } from "../../extra/utilities.js";

export default () => ({
    key: "",
    show: false,
    requested: false,
    father: {},
    child: {},

    /**
     * Realiza la petición para cargar la informacion del item (tarea - subtarea )
     * @param {*} info es la informacion pasada por el evento.
     * @returns
     */
    async handler(info) {
        if ( this.isBeingCreated(info) || this.existsInCache(info) ) return; 
        await this.fetchChild(info);
    },

    /**
     * Realiza la peticion para traer la informacion de la tarea|subtarea.
     * 
     * @param {object} info 
     */
    async fetchChild(info) {
        loader.classList.remove("d-none");
        try {
            const type = info.type == "task" ? "task" : "sub-task";
            const res = await (await fetch(`${url}${type}/${info.id}`)).json();

            /* Para este momento {this.key} ya se seteo en el metodod exists */
            Alpine.store("itemCache").addItem(this.key, { ...res[info.type] });
            this.setChild( res[info.type], info );
        } catch (error) {
            toastError(error.message);
        }
        loader.classList.add("d-none");
    },

    /**
     * Determina si la petición actual es para crear un nuevo registro.
     * 
     * @param {Object} info El objeto que se envia en el detalle del evento
     * @returns {bool}
     */
    isBeingCreated(info) {
        /* Cuando se quiere crear un nuevo registro el objeto enviado en
        el evento debe contener una propiedad llamada `fater` */
        if (Object.prototype.hasOwnProperty.call(info, "father")) {
            this.setDefault();
            this.father.id = info.father;
            this.father.title = info.pTitle;
            this.child.type = info.type;
            this.show = true;
            this.focusTitle();

            return true;
        }
        return false;
    },

    /**
     * Determina si el item que se desea cargar, ya sea una tarea o una subtarea, está cargado
     * y almacenado en `itemCache`
     * @param {*} info Es el objecto con la info necesaria.
     * @returns
     */
    existsInCache(info) {
        this.key = `${info.type}_${info.id}`;

        if (Alpine.store("itemCache").isLoaded(this.key)) {
            if (info.type == "task") {
                this.updateTaskProgress(info.id, this.key);
            }

            const item = {... Alpine.store("itemCache").getItem(this.key)}
            this.setChild(item, info);
            return true;
        }

        return false;
    },

    /**
     * Actualiza el progreso de una tarea ya cargada.
     */
    updateTaskProgress(id, key) {
        const p =
            Alpine.store("currentTasksList")[
                Alpine.store("currentTasksList").findIndex((el) => el.id == id)
            ];

        Alpine.store("itemCache").updateProgress(key, p.progress);
    },

    /**
     * Despues de que se recupera la info, se establecen sus valores a las propiedades
     * del componenete
     * @param {} child Es el objecto que contiene la info del item
     * @param {*} info Tiene informacion pasada por el evento.
     */
    setChild(child, info) {
        this.setDefault();
        this.father.title =  info.pTitle ?? "";
        // --------------------
        this.child = child;
        Alpine.store("__childControl", { ...child });
        // --------------------
        this.show = true;
        this.father.status = Object.prototype.hasOwnProperty.call(info, "pStatus")
            ? info.pStatus
            : undefined;
        // --------------------
        this.focusTitle();
        this.$dispatch("child-loaded");
    },

    /**
     * Establece los valores por defecto.
     */
    setDefault() {
        this.child = { status: "new" };
        Alpine.store("__childControl", {});
        this.father = {};
        this.requested = false;
        this.subTasks = null;
    },

    /**
     * Determina si se puede 'setear' la propiedad startedAt
     * @returns {Boolean}
     */
    canSetStartedAt() {
        if (this.child.status == "new") {
            return false;
        }

        return (Alpine.store("__childControl").started_at ? false : true);
    },

    /**
     * Determina si se puede modificar el estado o no.
     * @returns {Boolean}
     */
    canModifyStatus() {
        if (this.child.type == "task") {
            return (
                Alpine.store("__control").status == "finished" ||
                Alpine.store("__control").status == "paused"
            );
        }
        return (
            this.father.status == "finished" || this.father.status == "paused"
        );
    },

    /**
     * Determina si los campos del formulario están desabilidatos o no.
     * @returns {Boolean}
     */
    canModify() {
        return Alpine.store("__childControl").status == "finished";
    },

    /**
     * Determina si se puede crear una sub-tarea o no.
     * @returns {Boolean}
     */
    canAddSubTask() {
        return (
            Alpine.store("__childControl").type == "task" &&
            ["new", "process"].includes(Alpine.store("__childControl").status) &&
            ["new", "process"].includes(Alpine.store("__control").status)
        );
    },

    /**
     * Retorna el progeso en una cadena de texto.
     * @returns {String}
     */
    getProgress() {
        return this.child.progress > 100 ||
            !this.child.hasOwnProperty("progress")
            ? "Progreso: No Aplica"
            : `Progreso: ${this.child.progress}%`;
    },

    /**
     * Retorna una clase dependiendo del tipo del item (tarea [amarillo], subtarea[azul])
     * @returns {String}
     */
    paintBorder() {
        return this.child.type == "task" ? "border-warning" : "border-primary";
    },

    /**
     * Hace focus al titulo
     */
    focusTitle() {
        this.$nextTick(() => {
            setTimeout(() => {
                document.getElementById("view-child").scroll({
                    top: 0,
                    behavior: "smooth",
                });
                document.getElementById("child-title").focus();
            }, 100);
        });
    },

    /**
     * Determina si el item es nuevo, osea que es una subtarea que está siendo creada.
     * @returns {Boolean}
     */
    isNew() {
        return !(this.child.id && this.child.created_at);
    },

    /**
     * Pide una confirmacion y realiza el delete.
     */
    async confirmChildDel() {
        const confirmation = await Alpine.store("removeItems").confirm();

        if (confirmation) {
            await Alpine.store("removeItems").remove(
                this.child.type,
                this.child.id
            );
            const key = `${this.child}_${this.child.id}`;
            Alpine.store("itemCache").remove(key); // Para que vuelva a cargar el listado de tareas.

            if (this.child.type == "sub_task") {
                this.getBack();
            } else {
                this.close();
            }

            this.$dispatch("load-tasks");
            this.$dispatch("load-obs");
        }
    },

    /**
     * Regresa a la tarea
     */
    async getBack() {
        const id = this.child.task_id ? this.child.task_id : this.father.id;

        await this.handler({
            type: "task",
            id: id,
        });
    },

    /* --- */
    close() {
        this.show = false;
        this.setDefault();
    },
});
