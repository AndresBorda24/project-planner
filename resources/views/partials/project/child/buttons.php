<!--////////////////////////////////////////////////////////////////////
  Botones para el modal de modificacion / creacion de tareas y sub tareas
///////////////////////////////////////////////////////////////-->
<div class="p-1 overflow-hidden sticky-bottom">
  <div class="p-2 float-end d-flex justify-content-end gap-3 rounded bg-buttons shadow-sm sticky-bottom">
    <!-- Regresar -->
    <button
      @click="await getBack()"
      tabindex="-1"
      x-show="child.type == 'sub_task'"
      title="Volver a tarea"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-primary">
        <i class="bi bi-arrow-bar-left" style="z-index: 3;"></i>
    </button>
    <!-- Cerrar -->
    <button
      @click="close()"
      title="Cerrar"
      tabindex="-1"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-light">
        <i class="bi bi-x" style="z-index: 3;"></i>
    </button>
    <!-- Añadir Observaciones -->
    <button
      @click="$dispatch('add-new-ob', { 
        type: child.type,
        id: child.id
      })"
      title="Añadir Observaciones"
      tabindex="-1"
      x-show="! isNew()"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-warning">
        <i class="bi bi-bookmark-plus" style="z-index: 3;"></i>
    </button>
    <!-- Añadir Sub-tarea -->
    <button
      @click="$dispatch('load-child', {
        father: child.id,
        type: 'sub_task',
        pTitle: child.title
      })"
      x-show="canAddSubTask()"
      tabindex="-1"
      title="Añadir Sub-tarea"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-info">
        <i class="bi bi-plus" style="z-index: 3;"></i>
    </button>
    <!-- Guardar -->
    <button
      x-data="saveChild"
      @click="save()"
      title="Guardar"
      :disabled="! canSave()"
      tabindex="-1"
      @saved-record.document="handleUpdate($event.detail)"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-success">
        <i class="bi bi-check" style="z-index: 3;"></i>
        <template x-if="childHasChanged()">
          <span class="position-absolute translate-middle bg-danger border border-light rounded-circle" style="padding: .35rem; z-index: 3;"></span>
        </template>
    </button>
    <!-- Eliminar -->
    <button
      x-show="! isNew()"
      title="Eliminar"
      @click="await confirmChildDel()"
      tabindex="-1"
      class="btn-sm py-1 px-1 position-relative btn-show-info btn btn-outline-danger">
        <i class="bi bi-trash3-fill" style="z-index: 3;"></i>
    </button>
  </div>
</div>