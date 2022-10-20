import { url, _fetch, loader, toastError } from '../extra/utilities.js';

export default () => ({
    key: '',
    show: false,
    requested: false,
    father: {},
    child: {},
    /**
     * Realiza la petición para cargar la informacion del item (tarea - subtarea )
     * @param {*} k 
     * @returns 
     */
    async handler(k) {
        this.setDefault();
        this.father.title = Object.prototype.hasOwnProperty.call(k, "pTitle") ? k.pTitle : Alpine.store('current').title;

        if ( this.createNew(k) ) return; // Si se desea crear un nuevo registro
        if ( this.exists(k) ) return; // Si ya existe en el sistema de cache xD

        loader.classList.remove("d-none");
        try {
            const type = k.type == "task" ? "task" : "sub-task"
            const res = await ( await fetch(`${url}${type}/${k.id}`) ).json();

            Alpine.store("itemCache").addItem( this.key, {...res[k.type]} );
            this.setChild(res, k, k.type);
        } catch (error) {
            toastError(error.message);
        }
        loader.classList.add("d-none");
    },
    /**
     * Determina si se desea crear una nueva Tarea o Subtarea.
     * @param {Object} k El objeto que se envia en el detalle del evento 
     * @returns {boolean}
     */
    createNew( k ) {
        if (Object.prototype.hasOwnProperty.call(k, "father")) {
            this.father.id = k.father;
            this.child.type = k.type;
            this.show = true;
            this.focusTitle();

            return true;
        }
        return false;
    },
    /**
     * Determina si el item que se desea cargar, ya sea una tarea o una subtarea, está cargado 
     * y almacenado en `itemCache`
     * @param {*} k Es el objecto con la info necesaria.
     * @returns 
     */
    exists(k) {
        this.key = `${k.type}_${k.id}`;

        if (Alpine.store("itemCache").isLoaded(this.key)) {

            if (k.type == 'task') this.updateProgress(k.id, this.key); // Aquí se actualiza el progreso del item guardado con la info que viene de `k`
            
            const r = { item: Alpine.store("itemCache").getItem(this.key) };
            this.setChild(r, k, 'item');
            return true;
        }

        return false;
    },
    /**
     * Actualiza el progreso de una tarea ya cargada.
     */
    updateProgress( id, key ) {
        // Aqui se encuentra la tarea en el listado general de tareas.
        const p = Alpine.store('currentTasksList')[
            Alpine.store('currentTasksList').findIndex( el => el.id == id )
        ];

        Alpine.store('itemCache').updateProgress(key, p.progress);
    },
    /**
     * Despues de que se recupera la info, se establecen sus valores a las propiedades 
     * del componenete
     * @param {} res Es el objecto que contiene la indo del item 
     * @param {*} k Tiene info requerida
     * @param {*} t Representa la llave del objecto `res` en la que está la info `res[ t ]`
     */
    setChild(res, k, t) {
        this.child = res[t];
        Alpine.store('currentChild', { ...res[t] });
        this.show = true;
        this.father.status = Object.prototype
            .hasOwnProperty.call( k, "pStatus")
                ? k.pStatus
                : undefined;

        this.focusTitle();
        this.$dispatch('child-loaded');
    },
    /**
     * Establece los valores por defecto.
     */
    setDefault() {
        this.child = { status: 'new' };
        Alpine.store('currentChild', {});
        this.father = {};
        this.requested = false;
        this.subTasks = null;
    },
    /**
     * Determina si se puede 'setear' la propiedad startedAt
     * @returns {Boolean}
     */
    canSetStartedAt() {
        return (
            this.child.status == "new" ||
            (Alpine.store('currentChild').started_at ? true : false)
        );
    },
    /**
     * Determina si se puede modificar el estado o no.
     * @returns {Boolean}
     */
    canModifyStatus() {
        if (this.child.type == "task") {
            return Alpine.store("current").status == "finished" || Alpine.store("current").status == "paused";
        }
        return this.father.status == "finished" || this.father.status == "paused";
    },
    /**
     * Determina si los campos del formulario están desabilidatos o no.
     * @returns {Boolean}
     */
    canModify() {
        return Alpine.store('currentChild').status == "finished";
    },
    /**
     * Determina si se puede crear una sub-tarea o no.
     * @returns {Boolean}
     */
    canAddSubTask() {
        return ( Alpine.store('currentChild').type == 'task'
            && ( ['new', 'process'].includes(Alpine.store('currentChild').status) ) 
            && ( ['new', 'process'].includes(Alpine.store("current").status) ));
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
        return this.child.type == "task"
            ? "border-warning"
            : "border-primary";
    },
    /**
     * Hace focus al titulo
     */
    focusTitle() {
        this.$nextTick(() => {
            setTimeout(() => {
                document.getElementById('view-child').scroll({
                    top: 0,
                    behavior: 'smooth'
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
            
            if (this.child.type == 'sub_task') {
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
            id: id
        });
    },
    /* --- */
    close() {
        this.show = false;
        this.setDefault();
    },
});