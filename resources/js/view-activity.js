import { Alpine } from "./Alpine.js";
import { Grid, html } from "./libs/gridjs.js";
import { url as __URL, loader } from "./extra/utilities.js";
import "./partials/sidebar.js";

window.Alpine = Alpine;

const log = await (await fetch(`${__URL}get-obs-log`)).json();

document.addEventListener("alpine:init", () => {
    Alpine.store("log", log);
    Alpine.store("logFilters", {
        before: undefined,
        after: undefined,
        author: "",
        types: ["project", "task", "sub_task"]
    });
    Alpine.store("logFiltered", []);

    Alpine.data("filters", () => ({
        f: {
            before: undefined,
            after: undefined,
        },
        filters: {
            author: "",
            types: ["project", "task", "sub_task"]
        },
        init() {
            this.$watch('filters, $store.log', () => {
                Alpine.store('logFiltered', this.applyFilters(
                    Alpine.store("log")
                ))
            });
            const a = new Date();
            this.f.before = new Date(a.getTime() + 1000 * 60 * 60 * 24)
                .toISOString()
                .substring(0, 10);
            this.f.after = new Date(a - 1000 * 60 * 60 * 24 * 30)
                .toISOString()
                .substring(0, 10);
            Alpine.store("logFilters", this.filters);
        },
        async getLog() {
            try {
                loader.classList.remove('d-none');
                const log = await (
                    await fetch(`${__URL}get-obs-log?before=${this.f.before}&after=${this.f.after}`)
                ).json();
                Alpine.store("log", log);
            } catch (error) {
                console.error(error)
            }
            loader.classList.add('d-none');
        },
        /**
         * fb Signiica Filter-By xD
         * @param {array} log
         */
        fbAuthor( log ) {
            if (this.filters.author === "") {
                return log;
            }

            return log.filter( el => el.author_id == this.filters.author ); 
        },
        /**
         * fb Signiica Filter-By xD
         * @param {array} log
         */
        fbType( log ) {
            return log.filter( el => this.filters.types.includes( el.type )); 
        },
        /** @param {[object]} log */
        applyFilters( log ) {
            return this.fbType( this.fbAuthor(log) );
        }
    }));

    Alpine.data("dataTable", () => ({
        grid: undefined,
        init() {
            this.$watch('$store.logFiltered', () => { this.updateGridData() } );
            const container = document.getElementById('data-table');

            this.grid =  new Grid({
                columns: ['Proyecto', {
                    name: 'Observacion',
                    sort: { enabled: false },
                }, {
                    id: 'nombre',
                    sort: { enabled: false },
                    name: html(`
                            <div class="p-1">
                                <span class="d-block">Nombre</span>
                                <span class="p-1 rounded small bg-secondary text-light">Projecto</span>
                                <span class="p-1 rounded small bg-warning text-dark">Tarea</span>
                                <span class="p-1 rounded small bg-primary text-light">SubTarea</span>
                            </div>
                    `),
                    formatter: (_, row) => html(`<span 
                        class="d-block p-2" 
                        :class="getTextColor('${row.cells[5].data}')"
                        role="button" 
                        @click="open(${row.cells[6].data})">
                            ${row.cells[2].data}
                        </span>`)
                }, { name: 'Autor', sort: {enabled: false} }, 
                { name: 'Fecha', sort: { enabled: true }}, 
                { name: 'Tipo',  hidden: true }, 
                { name: 'Index', hidden: true }],
                data: this.setData( Alpine.store("log") ),
                search: true,
                fixedHeader: true,
                 height: this.getTableHeight( container.clientHeight ) + 'vh',
                sort: {
                    enabled: true,
                    multiColumn: true
                },
                pagination: {
                    enabled: true,
                    limit: 10,
                    summary: false
                },
                style: { 
                    table: { 'table-layout': 'auto' }, td: { 'min-width': '100px'}
                },
                className: { 
                    table: "w-100", td: "p-2", th: "p-2" 
                }
            }).render( container );
        },
        /** @param {array} arr */
        setData( arr ) {
            return arr.map((el, index) => [
                el.project, el.body, el.title, this.getAuthor( el.author_id ), el.created_at, el.type, index
            ]);
        },
        getTableHeight( conatinerHeight ) {
            const h = document.documentElement.clientHeight;
            const realHeight = conatinerHeight - 32 - 50 - 60; // Los numeros corresponden al aproximado del padding

            return realHeight * 100 / h;
        },
        updateGridData() {
            this.grid.updateConfig({
                data: this.setData( Alpine.store("logFiltered") ),
            }).forceRender();
        },
        /** @param {integer} author_id */ 
        getAuthor(author_id) {
            const author = Alpine.store("users").find(
                (el) => el.consultor_id == author_id
            );

            return author ? author.consultor_nombre : author_id;
        },
        /** 
         * Determina el color del nombre de la observacion 
         * @param {string} t Tipo  
        */
        getTextColor( t ) {
            switch( t ) {
                case 'project':
                    return "bg-secondary text-light rounded text-center";
                case 'task':
                    return "text-dark bg-warning rounded text-center";
                case 'sub-task':
                default:
                    return "text-light bg-primary rounded text-center";
            }
        },
        /**
         * Abre el modal del proyecto y seguidamente despacha el evento para que
         * se abra la tarea o subtarea
         * @param {Object} p El `pendiente`
         */
        open(p) {
            Alpine.store("viewProjectUrl").open( Alpine.store('log')[ p ] );
        },
    }));
});

Alpine.start();
