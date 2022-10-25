<table class="table table-sm m-0 table-striped-columns a-little-small table-hover" 
x-show="! isNew()"
:class="child.type == 'task' ? 'table-warning' : 'table-primary'">
    <thead>
        <tr>
            <th colspan="2" class="text-center">Informaci&oacute;n</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Creado por</td>
            <td><span x-text="child.author"></span></td>
        </tr>
        <template x-if="! canSetStartedAt() && Alpine.store('__childControl').started_at">
            <tr>
                <td>Fecha de Inicio</td>
                <td><span x-text="child.started_at"></span> &srarr; <span x-text="getHowLongAgo(child.started_at)"></span></td>
            </tr>
        </template>
        <tr>
            <td>Fecha Creaci&oacute;n</td>
            <td><span x-text="child.created_at"></span></td>
        </tr>
        <tr>
            <td>Ultima Actualizaci&oacute;n </td>
            <td><span x-text="child.updated_at"></td>
        </tr>
        <tr>
            <td>Fecha Finalizaci&oacute;n </td>
            <td><span x-text="child.finished_at"></span> &srarr; <span x-text="getHowLongAgo(child.finished_at)"></span></td>
        </tr>
    </tbody>
</table>