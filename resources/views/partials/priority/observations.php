<h4 class="text-center text-muted h6">Observaciones</h4>
<button class="btn btn-sm btn-success a-little-small mb-2" @click="showForm = !showForm"> <i class="bi bi-plus-square"></i></button>
<div x-collapse x-show="showForm" class="_border shadow-sm mx-2 position-absolute bg-light w-75" style="z-index: 1;">
  <div class="p-2">
    <div class="mb-3">
      <label for="new-request-obs-body" class="form-label text-muted a-little-small">Nueva Observaci&oacute;n*</label>
      <textarea class="form-control form-control-sm a-little-small" style="height: 130px;" x-model="body"></textarea>

      <label for="new-request-obs-body" class="form-label text-muted a-little-small">Autor*:</label>
      <div x-data="selectUsers" x-modelable="selectedUser" x-model="author">
        <select class="form-select form-select-sm a-little-small" x-model="selectedUser">
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

<div style="width: 335px; max-height: 70vh;" class="overflow-auto p-2">
  <template x-if=" Alpine.store('obs').loadingObs">
    <span class="a-little-small text-success">Cargando Observaciones...</span>
  </template>
  <ul class="list-group list-group-flush">
    <template x-for="o in Alpine.store('obs').obs" :key="o.id">
      <li class="list-group-item a-little-small d-block bg-transparent text-muted">
        <p x-text="o.body"></p>
        <p class="m-0"><span class="fst-italic" x-text="setUser(o)"></span> &srarr; <span x-text="o.created_at"></span></p>
        <span class="m-0 text-danger underline-hover" role="button" @dblclick="deleteObs(o.id)">Eliminar</span>
      </li>
    </template>
  </ul>
</div>
