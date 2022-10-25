<table class="table table-custom m-0 table-striped-columns a-little-small table-hover">
    <thead>
        <tr>
            <th colspan="2" class="text-center">Informaci&oacute;n</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Creado por</td>
            <td><span x-text="Alpine.store('__control').author"></span></td>
        </tr>
        <template x-if="Alpine.store('__control').started_at">
            <tr>
                <td>Fecha de Inicio</td>
                <td><span x-text="state.started_at"></span> &srarr; <span x-text="getHowLongAgo(state.started_at)"></span></td>
            </tr>
        </template>
        <tr>
            <td>Fecha Creaci&oacute;n</td>
            <td><span> <?= $project->created_at ?> </span></td>
        </tr>
        <tr>
            <td>Ultima Actualizaci&oacute;n </td>
            <td><span x-text="state.updated_at"></span></td>
        </tr>
        <template x-if="Alpine.store('__control').due_date">
            <tr>
                <td>Fecha Estimada de Finalizaci&oacute;n</td>
                <td> <span x-text="state.due_date"></span> &srarr; <span x-text="getTimeDiff()"></span></td>
            </tr>
        </template>
        <tr>
            <td>Fecha Finalizaci&oacute;n </td>
            <td><span x-text="state.finished_at"></span> &srarr; <span x-text="getHowLongAgo(state.finished_at)"></span></td>
        </tr>
    </tbody>
</table>