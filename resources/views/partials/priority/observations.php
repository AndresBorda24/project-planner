<!-- Creacion de observacion -->
<button class="btn btn-sm btn-success a-little-small mx-3 my-2" @click="showForm = !showForm"> <i class="bi bi-plus-square"></i></button>
<div x-show="showForm" class="_border shadow-sm mx-2 overflow-auto position-absolute bg-light w-75 bottom-100"
style="z-index: 1; max-width: 300px; min-height: 300px; max-height: 400px;">
  <div class="p-2">
    <div class="mb-3 small">
      <label for="new-request-obs-body" class="form-label text-muted a-little-small m-0">Nueva Observaci&oacute;n*</label>
      <textarea class="form-control form-control-sm" style="height: 130px;" x-model="body"></textarea>

      <label for="new-request-obs-body" class="form-label text-muted a-little-small m-0">Autor*:</label>
      <div x-data="selectUsers" x-modelable="selectedUser" x-model="author">
        <select class="form-select form-select-sm" x-model="selectedUser">
            <option selected hidden>Selecciona</option>
            <template x-for="u in users" :key="u.consultor_id">
                <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
            </template>
        </select>
      </div>
    </div>
    <button class="btn btn-sm btn-info a-little-small ms-auto d-block" :disabled="! canSave()" @click="save()"><i class="bi bi-plus-square"></i></button>
  </div>
</div>
<!-- Hasta aqui va el formulario de creacion -->

<!-- listado -->
<div class="overflow-auto p-2" style="max-height: 34vh;">
  <template x-if="Alpine.store('obs').loadingObs">
    <span class="a-little-small text-success">Cargando Observaciones...</span>
  </template>

  <template x-if="!Alpine.store('obs').loadingObs && Alpine.store('obs').obs &&  Alpine.store('obs').obs.length === 0">
    <p class="text-center text-bg-dark p-2 w-50 rounded-2 mb-4 mx-auto">Sin observaciones a&uacute;n</p>
  </template>

  <ul class="list-group overflow-auto">
    <template x-for="o in Alpine.store('obs').obs" :key="o.id">
      <li class="list-group-item a-little-small d-block text-muted">
        <p x-text="o.body"></p>
        <p class="m-0"><span class="fst-italic" x-text="setUser(o)"></span> &srarr; <span x-text="o.created_at"></span></p>
        <span class="m-0 text-danger underline-hover" role="button" @dblclick="deleteObs(o.id)">Eliminar</span>
      </li>
    </template>
  </ul>
</div>
