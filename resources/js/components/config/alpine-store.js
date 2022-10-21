import { Alpine } from "../../Alpine.js";
import { url as __URL, toastError } from "../../extra/utilities.js";

const status = await (
    await fetch(`${__URL}status`).catch((e) => toastError(e.message))
).json()

const gemaScopes = await (
    await fetch(`${__URL}gema-scopes`).catch((e) => toastError(e.message))
).json()

document.addEventListener("alpine:init", () => {
    Alpine.store("status", status);
    Alpine.store("gemaScopes", gemaScopes);
});

