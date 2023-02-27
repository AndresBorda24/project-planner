// import { Alpine } from "../Alpine.js";
import { url, loader, getUsers } from "../extra/utilities.js";
const users = await getUsers(url);

document.addEventListener("alpine:init", () => {
    Alpine.store("users", users);    

    Alpine.data('sidebar', () => ({
        shrink: true
    }));

    Alpine.store('viewProjectUrl', {
        /**
         * Abre el modal del proyecto y seguidamente despacha el evento para que
         * se abra la tarea o subtarea
         * @param {Object} p El `pendiente`
         */
        open( p ) {
            let _url;
            switch (p.type) {
                case 'project':
                    _url = Alpine.store('viewProjectUrl').getUrl(p.slug);
                    break;
                case 'task':
                    _url = Alpine.store('viewProjectUrl').getUrl(p.slug) + '?task=' + p.detail_id;
                    break;
                case 'sub_task':
                    _url = Alpine.store('viewProjectUrl').getUrl(p.slug) + '?task=' + p.task_id + '&sub-task=' + p.detail_id;
                    break;
            }
            window.open( _url );
            return;
        },
        getUrl( slug ) {
            return `${url.substring(0, url.length - 5)}/project/${slug}/ver`;
        }
    });

    Alpine.data("selectUsers", () => ({
        selectedUser: 0,
        users: [],

        async init() {
            this.users = await getUsers();
        },
    }));

    Alpine.data("pending", () => ({
        delegate: 0,
        expand: false,
        pendingList: [],
        /**
         * Obtiene el listado de pendientes dependiendo del id del usuario
         */
        async getPending() {
            const _url = url + `get-pending/${this.delegate}`;
            const { pending } = await (await fetch(_url)).json();

            this.pendingList = pending;
        },
        /**
         * Si el id del delegado es 0 no se permitirá realizar la peticion.
         * @returns {Boolean}
         */
        allow() {
            return this.delegate == 0;
        },
        /**
         * Abre el modal del proyecto y seguidamente despacha el evento para que
         * se abra la tarea o subtarea 
         * @param {Object} p El `pendiente`
         */
        open(p) {
            Alpine.store("viewProjectUrl").open(p);
        },
    }));

    Alpine.data("excels", () => ({
        /**
         * Genera un excel básico.
         */
        async getExcel() {
            loader.classList.remove("d-none");
            const _url = url + "reports/projects";

            const { projects } = await (await fetch(_url)).json();

            const excel = XLSX.utils.book_new();
            const sheet = XLSX.utils.json_to_sheet(projects);

            loader.classList.remove("d-none");
            loader.classList.add("d-none");
            XLSX.utils.book_append_sheet(excel, sheet, "Proyectos");
            XLSX.writeFile(
                excel,
                `reporte_proyectos_${new Date().toISOString()}.xlsx`
            );
        },
        /**
         * Esta funcion carga el excel con la información de todos los proyectos
         * junto con sus tareas.
         */
        async getFullExcel() {
            loader.classList.remove("d-none");
            const _url = url + "reports/projects-with-tasks";
            const table = document.createElement("table");
            const tableHead = document.createElement("thead");
            const tableBody = document.createElement("tbody");

            const { projects } = await (await fetch(_url)).json();

            tableHead.innerHTML = `
            <tr>
                <th>Proyecto</th>
                <th>Tarea</th>
                <th>Fecha de Creacion</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Progreso</th>
                <th>Fecha Entrega</th>
            </tr>`;

            let inner = "";

            projects.forEach((pr) => {
                inner += `
                <tr>
                    <td colspan="2">${pr.title}</td>
                    <td>${pr.created_at}</td>
                    <td>${pr.status}</td>
                    <td>${pr.priority}</td>
                    <td>${pr.progress_ == 101 ? "--" : pr.progress_}</td>
                    <td>${pr.due_date ? pr.due_date : "--"}</td>
                </tr>
                <tr>
                    <td>====================</td>
                    <td>====================</td>
                    <td>====================</td>
                    <td>====================</td>
                    <td>====================</td>
                    <td>====================</td>
                    <td>====================</td>
                </tr>
                `;

                pr.tasks_.forEach((task) => {
                    inner += `<tr>
                    <td>====================</td>
                    <td>${task.title}.</td>
                    <td>${task.created_at}</td>
                    <td>${task.status}</td>
                    <td>${task.priority}</td>
                    <td>${task.progress_ == 101 ? "--" : task.progress_}</td>
                    <td>${task.due_date ? task.due_date : "--"}</td>
                </tr>`;
                });
                inner +=
                    '<tr> <td></td><td></td><td></td><td></td><td></td><td></td> </tr><tr> <td colspan="6"></td> </tr>';
            });

            tableBody.innerHTML = inner;
            table.append(tableHead);
            table.append(tableBody);

            const excel = XLSX.utils.book_new();
            const sheet = XLSX.utils.table_to_sheet(table);
            XLSX.utils.book_append_sheet(excel, sheet, "Proyectos");
            XLSX.writeFile(
                excel,
                `reporte_full_${new Date().toISOString()}.xlsx`
            );

            loader.classList.add("d-none");
        },
    }));
});
