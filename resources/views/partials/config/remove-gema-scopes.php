<div x-data="removeScope">
  <div class="d-md-flex justify-content-between align-items-center gap-2">

    <div class="p-1" style="min-width: 300px;">
      <label for="scope-to-delete" class="form-label text-muted a-little-small">Alcance a eliminar: </label>
      <select class="form-select form-select-sm a-little-small" id="scope-to-delete" x-model.number="del">
        <option selected hidden>Selecciona</option>
        <template x-for="scope in Alpine.store('gemaScopes')" :key="'sc' + scope.id">
          <option :value="scope.id" x-text="scope.scope"></option>
        </template>
      </select>
    </div>
    
    <i class="d-block bi bi-arrow-left-right text-center my-2"></i>
    
    <div class="p-1" style="min-width: 300px;">
      <label for="scope-to-replace" class="form-label text-muted a-little-small">Alcance para reemplazar: </label>
      <select class="form-select form-select-sm a-little-small" id="scope-to-replace" x-model.number="replace">
        <option selected hidden>Selecciona</option>
        <template x-for="scope in Alpine.store('gemaScopes')" :key="'sc2' + scope.id">
          <option :value="scope.id" x-text="scope.scope"></option>
        </template>
      </select>
    </div>
    
  </div>
  <button class="btn btn-sm btn-danger mt-2 d-block ms-auto" :disabled="! canRemove()" @click="removeScope()">Eliminar</button>
</div>
