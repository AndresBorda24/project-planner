<div
x-data="addProject"
class="fixed-top bg-dark bg-opacity-75 vh-100 vw-100 flex"
x-show="show"
x-cloak
@open-create-modal-from-outside.document="fromOutside()"
@open-create-modal.document="open()"
>
  <div class="border p-2 border-2 border-secondary rounded m-auto" style="background-color: #f9f9f9;">
    <button type="button" class="btn btn-close d-block ms-auto" @click="setDefault()"></button>
    <!-- Titulo -->
    <div class="mb-2">
      <label class="form-label a-little-small mb-0 text-secondary" for="n-project-title">T&iacute;tulo</label>
      <textarea 
      class="form-control text-small shadow-sm bg-white" 
      rows="2" x-model="state.title"
      id="n-project-title"
      autofocus></textarea>
    </div>
    <!-- Descripcion -->
    <div class="mb-2">
      <label class="form-label a-little-small mb-0 text-secondary" for="n-project-desc">Descripci&oacute;n</label>
      <textarea 
      class="form-control text-small shadow-sm bg-white" 
      rows="2" x-model="state.description" id="n-project-desc" 
      style="height: 150px;"></textarea>
    </div>
    <!-- Prioridad -->
    <label class="form-label a-little-small mb-0 text-secondary">Prioridad*</label>
    <div style="border: 1px solid #ced4da;" class="bg-white p-2 d-flex justify-content-center rounded shadow-sm flex-grow-1">
      <div class="form-check form-check-inline">
          <input 
          class="form-check-input" id="np-priority-low"
          type="radio" x-model="state.priority"  value="low">
          <label class="form-check-label a-little-small" for="np-priority-low">Baja</label>
      </div>
      <div class="form-check form-check-inline">
          <input 
          class="form-check-input" id="np-priority-normal"
          type="radio"  x-model="state.priority"  value="normal">
          <label class="form-check-label a-little-small" for="np-priority-normal">Normal</label>
      </div>
      <div class="form-check form-check-inline">
          <input 
          class="form-check-input" id="np-priority-high"
          type="radio"  x-model="state.priority"  value="high">
          <label class="form-check-label a-little-small" for="np-priority-high">Alta</label>
      </div>
    </div>
    <!-- Delegado y autor -->
    <div class="d-flex">
      <div x-data="selectUsers" x-modelable="selectedUser" x-model="state.created_by_id" class="p-1">
        <label for="n-author" class="form-label a-little-small mb-0 text-secondary">Autor*</label>
        <select class="form-select form-select-sm a-little-small" x-model="selectedUser" id="n-author">
          <option value="0" selected>- Libres -</option>
          <template x-for="u in users" :key="u.consultor_id">
            <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
          </template>
        </select>
      </div>
      <div x-data="selectUsers" x-modelable="selectedUser" x-model="state.delegate_id" class="p-1">
        <label for="n-delegate" class="form-label a-little-small mb-0 text-secondary">Delegado*</label>
        <select class="form-select form-select-sm a-little-small" x-model="selectedUser" id="n-delegate">
          <option value="0" selected>- Libres -</option>
          <template x-for="u in users" :key="u.consultor_id">
            <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
          </template>
        </select>
      </div>
    </div>
    <button type="button" class="btn btn-sm btn-success my-3" @click="save()" :disabled="!canSave()">Guardar</button>
  </div>
</div>