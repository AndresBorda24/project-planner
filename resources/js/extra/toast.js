/**
 * Muestra una 'tostada' con un mensaje de error.
 * @param {String} m Mensaje a mostrar
 */
const toastError = (m = null) => {
    iziToast.error({
        title: "Oh no!",
        message: m ?? "La operacion ha fallado.",
        position: "topRight",
    });
};

/**
 * Muestra una 'tostada' con un mensaje de exito.
 * @param {String} m Mensaje a mostrar
 */
const toastsSuccess = (m = null) => {
    iziToast.success({
        title: "Exito!",
        message: m ?? "La operacion se ha completado con exito.",
        position: "topRight",
    });
};

/**
 * Muestra una 'tostada' que pide una confirmacion.
 * @param {String} message Mensaje a mostrar.
 * @param {String} color Representa el color de la tostada.
 */
const toastQuestion = async (message = "¿?", color = "green") => {
    let test = await new Promise((res, rej) => {
        iziToast.question({
            timeout: 10000,
            close: true,
            overlay: true,
            zindex: 2000,
            displayMode: "once",
            id: "confirm-delete",
            color: color,
            title: "Confirmar",
            message: message,
            position: "center",
            buttons: [
                [
                    "<button><b>Si</b></button>",
                    function (i, t) {
                        i.hide({ transition: "fadeOut" }, t, "button");
                        res(true);
                    },
                    true,
                ],
                [
                    "<button><b>No</b></button>",
                    function (i, t) {
                        i.hide({ transition: "fadeOut" }, t, "button");
                        res(false);
                    },
                ],
            ],
        });
    });

    return test;
};

export { toastError, toastsSuccess, toastQuestion };