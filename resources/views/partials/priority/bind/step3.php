<div class="p-2 h-100" x-show="step === 3">
  <div x-data="createRequestBind" class="position-relative h-100" @bind-request.document.stop="setUp($event.detail)">
    <h3 class="m-0">Creando
      <span
        :class="hasProject() ? 'text-bg-warning' : 'text-bg-primary'"
        class="px-1 rounded-1" x-text="hasProject() ? 'Tarea' : 'Proyecto'"></span>
    </h3>
    <template x-if="hasProject()">
      <small class="fst-italic text-muted a-little-small">Proyecto: <span x-text="project.title"></span></small>
    </template>
    <form @submit.prevent="save" id="new-bind-request" class="mt-3">
      <!-- Titulo -->
      <div class="mb-2">
        <label class="form-label a-little-small mb-0 text-secondary" for="n-project-title">T&iacute;tulo*</label>
        <textarea
        class="form-control text-small bg-white rounded-0"
        rows="2" x-model="state.title"
        id="n-project-title"
        autofocus required></textarea>
      </div>
      <!-- Descripcion -->
      <div class="mb-2">
        <label class="form-label a-little-small mb-0 text-secondary" for="n-project-desc">Descripci&oacute;n</label>
        <textarea
        class="form-control text-small bg-white rounded-0"
        rows="2" x-model="state.description" id="n-project-desc"
        style="height: 150px;"></textarea>
      </div>
      <!-- Prioridad -->
      <label class="form-label a-little-small mb-0 text-secondary">Prioridad*</label>
      <div style="border: 1px solid #ced4da;" class="bg-white p-2 d-flex justify-content-center rounded-0 flex-grow-1">
        <div class="form-check form-check-inline">
            <input
            class="form-check-input" id="np-priority-low"
            type="radio" x-model="state.priority" value="low" required>
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
      <div class="d-flex w-100">
        <div x-data="selectUsers" x-modelable="selectedUser" x-model="state.created_by_id" class="p-1 flex-fill">
          <label for="n-author" class="form-label a-little-small mb-0 text-secondary">Autor*</label>
          <select class="form-select form-select-sm a-little-small" x-model="selectedUser" id="n-author" required>
            <option value="" selected>- Selecciona -</option>
            <template x-for="u in users" :key="u.consultor_id">
              <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
            </template>
          </select>
        </div>
        <div x-data="selectUsers" x-modelable="selectedUser" x-model="state.delegate_id" class="p-1 flex-fill">
          <label for="n-delegate" class="form-label a-little-small mb-0 text-secondary">Delegado*</label>
          <select class="form-select form-select-sm a-little-small" x-model="selectedUser" id="n-delegate" required>
            <option value="0" selected>- Libres -</option>
            <template x-for="u in users" :key="u.consultor_id">
              <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
            </template>
          </select>
        </div>
      </div>
    </form>
    <div class="d-flex justify-content-between position-absolute w-100 bottom-0">
      <button class="btn btn-sm btn-warning" @click="goBack()"> Volver </button>
      <button type="submit" class="btn btn-sm btn-success" form="new-bind-request">Guardar</button>
    </div>
  </div>
</div>
