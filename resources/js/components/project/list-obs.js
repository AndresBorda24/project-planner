import { toastError } from "../../extra/utilities.js";

export default () => ({
    obs: [],
    obsType: undefined,
    obsDate: undefined,
    obsAuthor: undefined,
    obsId: undefined,
    showMenu: false,

    async init() {      
        this.$watch('obsType, obsDate, obsAuthor', val => this.filter(val) );
    },
    /**
     * Pinta el fondo de la observacion con el fin de identificar mejor el 
     * tipo.
     * 
     * @param {String} param0 tipo de la observacion
     * @returns {String}
     */
    bgColor({ obs_type }) {
        switch (obs_type) {
            case 'project':
                return 'list-group-item-secondary';
            case 'task':
                return 'list-group-item-warning';
            default:
                return 'list-group-item-primary';
        }
    },
    /**
     * Obtiene el usuario.
     */
    setUser({ author_id }) {
        const author = Alpine.store("users").find( el => el.consultor_id == author_id );

        return author ? author.consultor_nombre : author_id
    },
    /* 
    * Se encarga de filtrar las observaciones 
    */
    filter() {
        this.obs = Alpine.store("currentObsList").filter( ob => {
            let date = true;
            let type = true;
            let author = true;
            let id = true;

            if (this.obsDate) date = ( new Date(ob.created_at) >= new Date(this.obsDate) );
            
            if (this.obsType) type = ( ob.obs_type == this.obsType ); 
            
            if (this.obsAuthor && this.obsAuthor > 0) author = ( ob.author_id == this.obsAuthor );
            
            if (this.obsId) id = ( ob.obs_id == this.obsId );
            
            return (date && type && author && id);
        });
    },
    /**
     * 
     */
    justProjects( c = false ) {
        this.obsType = c ? 'project' : undefined;
    },
    /**
     * Se ejecuta cuando el evento child-loaded se dispara. Principalmente 
     * justo despues de que la información de la tarea o (sub)tarea es cargada 
     * @param {String} type 
     * @param {String} id 
     */
     loadChildObs(type, id) {
        this.obsId = id;
        this.obsType = type;

        this.filter();
    },
    /**
     * Se encarga de abrir (o no) el modal al dar click en el nombre de la tarea 
     * (subtarea o proyecto) en una de las observaciones.
     * @param {Object} ob Tarea o Subtarea
     * @returns {Void}
     */
    canOpenTask( ob ) {
        switch (ob.obs_type) {
            case 'task':
                this.$dispatch('load-child', {
                    id: ob.obs_id,
                    type: "task"
                });
                return;
            case 'sub_task':
                const index = Alpine.store('currentTasksList')
                    .findIndex( el => {
                        return Object.prototype.hasOwnProperty.call(el, '_subTasks') 
                                && el._subTasks.some( st => st.id == ob.obs_id );
                    });

                if (index === -1) {
                    toastError('Ha surgido un problema al recuperar la informacion.');
                    return;
                };

                const task = Alpine.store('currentTasksList')[ index ];
                this.$dispatch('load-child', {
                    id: ob.obs_id,
                    type: "sub_task",
                    pStatus: task.status, 
                    pTitle: task.title 
                });

                return;
            default:
                return;
        }
    },
});
