import { url,  _modal, _fetch} from "../../../extra/utilities.js";

export default () => ({
    search: "",
    found: [],
    select: '0',
    /**
     * Realiza la peticion.
     */
    async fetchProjects() {
        if (this.search.length > 1) {
            const _url = url + "projects-search?search=" + this.search;
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
            this.select = '0';
        } else {
            this.select = '0';
        }
    },
    nextStep() {
        this.$dispatch('bind-request', {
            project: this.found.find(p => p.id == this.select)
        });
        this.$dispatch('change-step', 3);
    },
});
