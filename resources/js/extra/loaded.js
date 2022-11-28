// Esto solamente esconde el loader :v
let loader, _modal;

window.addEventListener("alpine:init", function() {
    loader = document.getElementById("loader-z");
    _modal = document.getElementById("modal-actions");

    setTimeout(() => {
        document.getElementById("loader").remove();
    }, 1000);
});

export { loader, _modal };