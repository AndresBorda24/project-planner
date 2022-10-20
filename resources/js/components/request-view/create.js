import { toastError, toastsSuccess } from "../../extra/toast.js";

export default () => ({
    show: false,
    state: {},
    init() {
        this.setDefault();
    },
    /**
     * Abre el modal de creacion y enfoca el input de asunto.
     */
    open() {
        this.setDefault();
        this.show = true;

        setTimeout(() => {
            document.getElementById("new-request-subject").focus();
        }, 200);
    },
    /**
     * Setea las propiedades a su `default`
     */
    setDefault() {
        this.show = false;
        this.state = {
            subject: "",
            area: 0,
            desarrollo: 0,
            gema: 0,
            status: 0,
        };
    },
    /**
     * Determina si la información del formulario es valida o cumple con
     * unos requisitos minimos. Esta función habilita el botón de crear.
     * @returns {boolean}
     */
    isValid() {
        if (this.state.gema <= 0) return false;
        if (this.state.area <= 0) return false;
        if (this.state.subject.length < 10) return false;
        if (this.state.desarrollo != 0) return false;
        if (this.state.status <= 0) return false;

        return true;
    },
    /**
     * Llama a la funcion save para realizar la petición;
     */
    async create() {
        const body = this.state;
        const res = await Alpine.store("saveRequest").save(body);

        if ( res.status == "error" ) {
            toastError(res.message);
        } else {
            toastsSuccess("Hecho!");
            Alpine.store("requests").push(res.request);
            this.setDefault();
        }
    },
});
