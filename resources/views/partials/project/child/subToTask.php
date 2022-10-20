<button
x-data="convertToTask"
type="button" 
x-show="$data.child.type == 'sub_task' && ! $data.isNew()"
class="btn btn-sm btn-primary a-little-small"
@click="confirmConvert()">Convertir en Tarea</button>