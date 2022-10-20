<!-- Campos principales del formulario del proyecto -->
<div class="p-2" style="overflow-y: auto; overflow-x: hidden;" x-data="project" @saved-record.window="handleUpdate($event.detail)">
  <div class="d-flex align-items-center gap-2 mb-2">
    <span class="d-block a-little-small text-blue">Avance:</span>
    <div class="progress flex-grow-1 bg-success bg-opacity-25 shadow-sm">
      <div class="progress-bar bg-success" role="progressbar"
      :style="{ width: Alpine.store('progress').raw + '%' }" :aria-valuenow="Alpine.store('progress').raw" 
      aria-valuemin="0" aria-valuemax="100" x-text="Alpine.store('progress').formated"></div>
      </div>
  </div>

  <!-- Titulo -->
  <input
  type="text"
  class="form-control fw-semibold mb-2 shadow-sm"
  :class="hasFinished() ? '' : 'bg-white'"
  tabindex="0"
  x-model="state.title"
  id="title"
  :readonly="hasFinished()" 
  autofocus>

  <!-- Descripcion -->
  <label for="description" class="form-label a-little-small mb-0 text-blue fst-italic">Descripci&oacute;n</label>
  <textarea 
  class="form-control mb-2 resive-v shadow-sm"
  :class="hasFinished() ? '' : 'bg-white'"
  style="height: 178px; font-size: .9rem;"
  x-model="state.description" 
  id="description" 
  :readonly="hasFinished()"></textarea>

  <!-- Prioridad y delegado -->
  <div class="row g-0">
    <div class="col-md-6 col-12 p-1">
      <span class="form-label a-little-small text-blue fst-italic">Prioridad*</span>
      <div 
      class="d-flex w-100 _border a-little-small pt-1 justify-content-center gap-2 rounded shadow-sm" 
      :class="hasFinished() ? 'bg-input-disabled' : 'bg-white'">
        <div class="form-check m-0">
          <input 
          class="form-check-input" 
          type="radio" 
          name="priority" id="priority-low"
          x-model="state.priority" value="low"
          :disabled="hasFinished()">
          <label class="form-check-label text-muted" role="button" for="priority-low">Baja</label>
        </div>
        <div class="form-check m-0">
          <input 
          class="form-check-input" 
          type="radio" 
          name="priority" id="priority-normal"
          x-model="state.priority" value="normal"
          :disabled="hasFinished()">
          <label class="form-check-label text-muted" role="button" for="priority-normal">Normal</label>
        </div>
        <div class="form-check m-0">
          <input 
          class="form-check-input" 
          type="radio" 
          name="priority" id="priority-high"
          x-model="state.priority" value="high"
          :disabled="hasFinished()">
          <label class="form-check-label text-muted" role="button" for="priority-high">Alta</label>
        </div>
      </div>
    </div>
    <!-- Delegado -->
    <div class="col-md-6 col-12 p-1">
      <label for="delegated" class="form-label a-little-small m-0 text-blue fst-italic">Delegado</label>
      <select class="form-select form-select-sm shadow-sm" x-model="state.delegate_id" :disabled="hasFinished()">
        <option value="0" selected>- Libre -</option>
        <?php foreach ($users as $user) :?>
          <option value="<?= $user['consultor_id'] ?>"><?= $user['consultor_nombre'] ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>

  <!-- Fecha de Estimada de finalizacion Y tiempo estimado -->
  <div class="row g-0">
    <!-- Tiempo estimado -->
    <div class="col-12 col-md-6 p-1">
      <label for="estimated" class="form-label a-little-small m-0 text-blue fst-italic">Tiempo estimado:</label>
      <div class="d-flex gap-2">
        <!-- Aquí se calcula la fecha dependiendo del input -->
        <div style="width: 60px;">
          <input 
          type="number" :disabled="allowDueDate()" 
          class="form-control form-control-sm shadow-sm" 
          x-model.number.debounce.300ms="addToDates">
        </div>
        <!-- Aqui se selecciona el `tipo` de fecha a calcular -->
        <div class="flex-grow-1">
          <select class="form-select form-select-sm  shadow-sm" x-model="state.estimated_time" :disabled="allowDueDate()">
            <option value="0">--- ||| ---</option>
            <option value="days">Dias</option>
            <option value="weeks">Semanas</option>
            <option value="months">Meses</option>
            <option value="years">A&ntilde;os</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Fecha de entrega -->
    <div class="col-12 col-md-6 p-1">
      <label for="dueDate" class="form-label a-little-small m-0 text-blue fst-italic">F. Finalizaci&oacute;n Estimada:</label>
      <input 
      type="date"
      class="form-control form-control-sm w-100 shadow-sm"
      x-model="state.due_date" id="dueDate" :disabled="allowDueDate()">
    </div>
  </div>

  <!-- Extras -->
  <div class="row g-0 mb-3">
    <!-- Estado -->
    <div class="m-0 p-1 col-12 col-md-6">
      <label for="status" class="form-label m-0 a-little-small text-blue fst-italic">Estado*</label>
      <select class="form-select form-select-sm shadow-sm" id="status" x-model="state.status">
        <option value="new" x-show="! isStarted()">Nuevo</option>
        <option value="process">En Proceso</option>
        <option value="paused">Pausado</option>
        <option value="finished" x-show="! isNew()">Finalizado</option>
      </select>
    </div>
    <!-- Fecha de Inicio -->
    <div class="m-0 p-1 col-12 col-md-6" x-show="allowStartedAt()">
      <label for="startedAt" class="form-label a-little-small m-0 text-blue fst-italic">Fecha Inicio*</label>
      <input 
      class="form-control form-control-sm shadow-sm"
      :class="(state.status != 'new' && !state.started_at) ? 'border-danger' : ''" 
      id="startedAt" x-model="state.started_at" 
      :disabled="isStarted()" type="date">
    </div>

  </div>

  <!-- Fechas -->
  <div x-show="! isNew()" class="p-2 bg-white _border rounded shadow-sm a-little-small">
    <ul class="list-group list-group-flush">
      <li class="list-group-item  p-1">
        <b>Creado por </b> &srarr; <span> <?= $autor ?></span>
      </li>
      <li class="list-group-item  p-1">
        <b>Fecha Creaci&oacute;n </b> &srarr; <span x-text="state.created_at"></span>
      </li>
      <li class="list-group-item  p-1">
        <b>Ultima Actualizaci&oacute;n </b> &srarr; <span x-text="state.updated_at"></span>
      </li>
      <li class="list-group-item  p-1">
        <b>Fecha Finalizaci&oacute;n </b> &srarr; <span x-text="state.finished_at"></span>
      </li>
    </ul>
  </div>
</div>
