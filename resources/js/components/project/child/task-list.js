export default () => ({
    subTasks: [],
    getSubTasks() {
        if (Alpine.store('__childControl').type != 'task') {
            this.subTasks = [];
            return;
        }
        const index = Alpine.store('currentTasksList')
            .findIndex(el => el.id == Alpine.store('__childControl').id && el.type == 'task');

        if (index === -1) { this.subTasks = []; return; };

        this.subTasks = Alpine.store('currentTasksList')[index]._subTasks;
        return;
    },
    loadSubTasks() {
        if (Alpine.store('__childControl').hasOwnProperty('id')) {
            getSubTasks()
        }
    },
    /**
     * Carga La informacion de una subtarea luego de darle doble click en el titulo
    */
    loadSubTask( subt ) {
        this.$dispatch('load-child', {
            ...subt,
            pStatus: Alpine.store('__childControl').status,
            pTitle: Alpine.store('__childControl').title
        })
    }
})
