import { Alpine } from "../../Alpine.js";
import { dateFormater } from "../../extra/utilities.js";

export default (r) => ({
    /**
     * Este es el id.
     */
    id: r.id,

    /**
     * Registra los timers de los timeouts
     */
    timers: {},

    /**
     * Se setea un valor si la petición es fijada o desfijada.
     */
    async managePinned(r) {
        let newList = {};
        r.pinned = (r.pinned == "0") ? "-1" : "0";  // Se usa el -1 para posicionar la solicitud en el fondo.
        const pinnedValue = (r.pinned == "-1") ? "1" : "0"; 

        this.hasBeenPinned("rc-" + r.id);
        await this.$nextTick( () => {
            newList = this.getNewPinValues();
        });

        const reqs = Alpine.store('saveRequest').updatePinnedRequestPosition( newList );
        this.$nextTick(() => { Alpine.store('requests', reqs) });

        delete newList[ r.id ]; // Se elimina la llave correspondiente a la solicitud para evitar problemas.

        await Alpine.store("saveRequest").updatePinned(this.id, pinnedValue, newList);
    },

    /**
     * Obtiene las nuevas posiciones para las solicitudes `fijas`
     */
    getNewPinValues() {
        const list = document.getElementById('pinned-requests-list');
        const requests = list.children;
        const newPinValues  = {};

        /* Se comienza en 1 ya que el primer 'hijo' es un bloque template */
        for (let i = 1; i < requests.length; i++) {
          const element = requests[i];
          const requestId = element.dataset.requestId;
          const pinned = requests.length -  i;
          const key = `${requestId}`;

          newPinValues[ key ] = pinned;
        }

        return newPinValues;
    },

    /**
     * Resalta el item que acaba de ser fijado o desfijado.
     * @param {string} id Id del contenedor de la petición.
     */
    highLight(id) {
        const el = document.getElementById(id);
        // Añade los estilos
        el.classList.add("transition-easy-out-200");
        el.style.color = "white";
        el.style.outline = "5px solid var(--bs-gray-700)";
        el.style.backgroundColor = "var(--bs-gray-800)";

        if (Object.prototype.hasOwnProperty.call(this.timers, id)) {
            window.clearTimeout(this.timers[id]);
        }
        
        // Los elimina luego de 2 segundos
        this.timers[id] = setTimeout(() => {
            el.style.color = "";
            el.style.outline = "";
            el.style.backgroundColor = "";
            el.classList.remove("transition-easy-out-200");
        }, 2500);
    },

    /**
     * Realiza el scroll hasta la petición seleccionada y la resalta
     * mediandte el metodo highLight
     * @param {string} id Id del contenedor de la petición
     */
    hasBeenPinned(id) {
        this.$nextTick(() => {
            document
                .getElementById(id)
                .scrollIntoView(false, { behavior: "smooth" });
            this.highLight(id);
        });
    },

    /**
     * Se utiliza para dar formato a una fecha.
     * @param {string} d Fecha a ser formateada
     * @returns {string}
     */
    getDate(d) {
        return dateFormater.format(new Date(d));
    },
});
