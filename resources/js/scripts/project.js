"use strict";

function init(project) {
    document.addEventListener("alpine:init", () => {
        Alpine.store("project", project);
        Alpine.store("__control", { ...project });
    });
}

/**
 * Expanse o contrae las subtareas al dar en el botón.
 * 
 * @param {id de la tarea} l 
 */
async function expand(l) {
    let timeout = 0;
    const i = document.getElementById(`stlist-${l}`); // Contenedor de las subtareas
    const x = document.getElementById(`expand-${l}`); // Icono / botón

    if (i.style.height == "") {
        i.style.height = i.scrollHeight + "px";
        timeout = 50;
    }

    setTimeout(() => {
        if (i.style.height == "0px") {
            i.classList.remove("d-none");
            i.style.height = i.scrollHeight + "px";
            x.style.transform = "rotate(180deg)";
        } else {
            i.style.height = "0px";
            x.style.transform = "rotate(0deg)";
            setTimeout(() => i.classList.add("d-none"), 201);
        }
        timeout = 0;
    }, timeout);
}


function getTimeDiff() {
    const now = new Date();
    const d = new Date( Alpine.store("__control").due_date );
    const diff = d - now;

    if (diff < 1) {
        return '0 días restantes.';
    }

    switch (Alpine.store("__control").estimated_time) {
        case "months":
            const m = 1000 * 60 * 60 * 24 * 30;
            const months = Math.round( (diff / m ) );
            return `( ${months} meses restantes aprox. )`;
        case "weeks":
            const w = 1000 * 60 * 60 * 24 * 7;
            const weeks = Math.round( (diff / w ) );
            return `( ${weeks} semanas restantes aprox. )`; 
        default:
            const ds = 1000 * 60 * 60 * 24;
            const days = Math.round( (diff / ds ) );
            return ` ( ${days} dias restantes aprox. )`;
    }

}

function getHowLongAgo( date ) {
    if (! date) {
        return "";
    }
    const now = new Date();
    const d = new Date( date );
    const diff = now - d;

    const ds = 1000 * 60 * 60 * 24;
    const days = Math.round( (diff / ds ) );
    return ` ( Hace ${days} días aprox. )`;

}