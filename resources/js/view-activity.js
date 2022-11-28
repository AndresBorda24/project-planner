import { Alpine } from "./Alpine.js";
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

    Alpine.data("filters", () => ({
        filters: {
            before: undefined,
            after: undefined,
            author: "",
            types: ["project", "task", "sub_task"]
        },
        init() {
            const a = new Date();
            this.filters.before = new Date(a.getTime() + 1000 * 60 * 60 * 24)
                .toISOString()
                .substring(0, 10);
            this.filters.after = new Date(a - 1000 * 60 * 60 * 24 * 30)
                .toISOString()
                .substring(0, 10);
            Alpine.store("logFilters", this.filters);
        },
        async getLog() {
            try {
                loader.classList.remove('d-none');
                const log = await (
                    await fetch(`${__URL}get-obs-log?before=${this.filters.before}&after=${this.filters.after}`)
                ).json();
                Alpine.store("log", log);
            } catch (error) {
                console.error(error)
            }
            loader.classList.add('d-none');
        }
    }));

    Alpine.data("log", () => ({
        getClass(type) {
            switch (type) {
                case "project":
                    return "border-secondary border-start bg-secondary";
                case "task":
                    return "border-warning border-start bg-warning";
                default:
                    return "border-primary border-start bg-primary";
            }
        },
        getAuthor(author_id) {
            const author = Alpine.store("users").find(
                (el) => el.consultor_id == author_id
            );

            return author ? author.consultor_nombre : author_id;
        },
        /**
         * Abre el modal del proyecto y seguidamente despacha el evento para que
         * se abra la tarea o subtarea
         * @param {Object} p El `pendiente`
         */
        open(p) {
            Alpine.store("viewProjectUrl").open(p);
        },
        /**
         * fb Signiica Filter-By xD
         * @param {array} log
         */
        fbAuthor( log ) {
            if (Alpine.store('logFilters').author === "") {
                return log;
            }

            return log.filter( el => el.author_id == Alpine.store('logFilters').author ); 
        },
        /**
         * fb Signiica Filter-By xD
         * @param {array} log
         */
        fbType( log ) {
            return log.filter( el => Alpine.store('logFilters').types.includes( el.type )); 
        },
        applyFilters( log ) {
            return this.fbType( this.fbAuthor(log) );
        }
    }));
});

Alpine.start();
