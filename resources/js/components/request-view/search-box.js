import { loader, toastError, url as URL } from "../../extra/utilities.js";

export default () => ({
    search: "",
    results: [],
    init() {
        this.$watch("search", () => {
            const search = this.search.trim();
            if (search === "") this.results = [];
            if (search.length <= 3) return;
            if (!Alpine.store("canLoadMoreRequests")) return;
            this.fecthRequests(search);
        });

        /* Cada que se agregan o eliminan solicitudes actualizamos el listado de ids  */
        this.$watch("$store.requests", () => {
            Alpine.store(
                "requestsId",
                Alpine.store("requests").reduce((a, b) => {
                    a.push(b.id);
                    return a;
                }, [])
            );
        });
    },
    async fecthRequests(search) {
        try {
            const ids = encodeURIComponent(Alpine.store("requestsId"));
            const val = encodeURIComponent(search);

            const _url = `${URL}search-requests?search=${val}&ids=${ids}`;
            const res = await (await fetch(_url)).json();

            if (res.status == "error") {
                throw new Error(res.message);
            }

            this.results = res.requests;
        } catch (e) {
            toastError(e.message);
        }
    },
    /**
     * Trae la info de una solicitud y la añade al listado.
     * 
     * @param {number} id Id de la solicitud
     */
    async fetchOneRequest(id) {
        try {
            if ( Alpine.store("requests").some( el => el.id == id ) ) {
                this.results = [];
                return;
            }
            
            loader.classList.remove("d-none");
            const _url = `${URL}request/${id}`;
            const { request } = await (await fetch(_url)).json();
            
            Alpine.store("requests").push(request);
            this.results = [];
        } catch (e) {
            toastError(e.message);
        }
        loader.classList.add("d-none");
    },
});
