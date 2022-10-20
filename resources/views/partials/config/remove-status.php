<div x-data="removeStatus">
  <div class="d-md-flex justify-content-between align-items-center gap-2">
    
    <div class="p-1" style="min-width: 300px;">
      <label for="status-to-delete" class="form-label text-muted a-little-small">Estado a eliminar: </label>
      <select class="form-select form-select-sm a-little-small" id="status-to-delete" x-model.number="del">
        <option selected hidden>Selecciona</option>
        <template x-for="status in Alpine.store('status')" :key="'s' + status.id">
          <option :value="status.id" x-text="status.status"></option>
        </template>
      </select>
    </div>

    <i class="d-block bi bi-arrow-left-right text-center my-2"></i>
    
    <div class="p-1" style="min-width: 300px;">
      <label for="status-to-replace" class="form-label text-muted a-little-small">Estado para reemplazar: </label>
      <select class="form-select form-select-sm a-little-small" id="status-to-replace" x-model.number="replace">
        <option selected hidden>Selecciona</option>
        <template x-for="status in Alpine.store('status')" :key="'s2' + status.id">
          <option :value="status.id" x-text="status.status"></option>
        </template>
      </select>
    </div>

  </div>
  <button class="btn btn-sm btn-danger mt-2 d-block ms-auto" :disabled="! canRemove()" @click="removeStatus()">Eliminar</button>
</div>