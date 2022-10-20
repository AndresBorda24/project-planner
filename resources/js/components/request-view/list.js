// import { url as URL, toastError } from "../../extra/utilities.js";

export default () => ({
    /**
     * Representa el id de la peticion (request) seleccionada.
     */
    selected: null,

    /**
     * Lo que hace el $watch es que cada vez que la propiedad
     * { selected } cambia de valor lo almacena en una "variable global"
     * como es el caso de { Alpine.store('selectedRequest') } para que
     * así otros componentes sepan que peticion esta seleccionada.
     */
    init() {
        this.$watch("selected", (value) => {
            const r = Alpine.store("requests").find((el) => el.id == value);
            Alpine.store("currentRequest", r);
            Alpine.store("obs").fetchObs(Alpine.store("currentRequest"));
        });
    },

    /**
     * Ordena las peticiones con respecto a su "nivel de prioridad" o
     * si estan fijas.
     * @param {array} a Representa el array de las peticiones (request)
     * @returns {array}
     */
    sort(a) {
        return  a.filter( (e) => parseInt( e.pinned ) === 0 ).sort((a, b) => {
            // Si ninguna de las dos esta "fija" se deja la de mayor prioridad.
            return this.sum(b) - this.sum(a);
        });
    },

    /**
     * Ordena las peticiones con respecto a su "nivel de prioridad" o
     * si estan fijas.
     * @param {array} a Representa el array de las peticiones (request)
     * @returns {array}
     */
    sortPinned(a) {
        return a.filter( (e) => e.pinned != 0 );
    },

    /**
     * Realiza un filtro dependiendo del input en la barra de busqueda
     * @param {array} a
     */
    search(a) {
        if (Alpine.store("searchBox").length < 3) {
            return a;
        }

        const reg = new RegExp(`(${Alpine.store("searchBox").trim()})`, "i");

        return a.filter((el) => reg.test(el.subject));
    },
    
    /**
     * Cuando se arrastra una solicitud esta funcion realiza una peticion para 
     * actualizar la posicion en la base de datos.
     * 
     * La propiedad `newOrder` se obtiene gracias a la libreria empleada para arrastrar y soltar 
     * solicitudes. Nos devuelve un objecto en el que sus llaves representan el id de la solicitus 
     * y los valores su posición.
    */
    async pinnedMoved( { detail } ) {
        if ( detail.new == detail.old ) return;

        const pinnedValue = detail.newOrder[ `${detail.item}` ];

        Alpine.store("saveRequest").updatePinned( detail.item, pinnedValue, detail.newOrder );
        Alpine.store("saveRequest").updatePinnedRequestPosition( detail.newOrder );
    },

    /**
     * Devuelve la suma de las "notas de priorizaion" de una peticion.
     * @param { object } Request Representa la request (peticion)
     * @returns { number }
     */
    sum({ data }) {
        return Object.values(data).reduce((a, b) => parseInt(a) + parseInt(b));
    },
});
