/**
 * 
 * @param {String} url Representa la url base a la api 
 * @param {String} method Representa el metodo de la peticion (post o put)
 * @param {Object} body Representa el cuerpo de la solicitud :v 
 * @returns
 */
const _fetch = async function (url, method, body) {
    try {
        const res = await (
            await fetch(url, {
                method: method,
                headers: {
                    "Content-type": "application/json",
                },
                body: JSON.stringify(body),
            })
        ).json();

        return res;
    } catch (error) {
        throw new Error("No se ha podido realizar la peticion");
    }
};

/**
 * Se encarga de crear una instancia de `Intl.DateTimeFormat` 
 * para dar un formato especifico a las fechas.
 */
const dateFormater = new Intl.DateTimeFormat("es-CO", {
    weekday: "short",
    year: "numeric",
    month: "short",
    day: "numeric",
    timeZone: 'UTC'
});



let _users = undefined;
/**
 * Se encarga de realizar una petición para cargar los usuarios y 
 * evitar que se realice la misma peticion muchas veces.
 * 
 * @param {String} url Representa la url base de la api
 */
async function getUsers(url) {
    if (typeof _users == 'undefined') {
        const _url = url + 'get-users';
        const { users } = await ( await fetch(_url) ).json()

        _users = users;
    }
    return _users;
};

export { _fetch, dateFormater, getUsers };