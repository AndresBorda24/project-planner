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
