<!-- Campos principales del formulario del proyecto -->
<div class="p-2 d-md-flex flex-column" style="overflow-y: auto; overflow-x: hidden;" x-data="project" @saved-record.window="handleUpdate($event.detail)">
  <div class="d-flex align-items-center gap-2 mb-2">
    <span class="d-block a-little-small text-blue">Avance:</span>
    <div class="progress flex-grow-1 bg-success bg-opacity-25 shadow-sm">
      <div class="progress-bar bg-success" role="progressbar"
      :style="{ width: Alpine.store('progress').raw + '%' }" :aria-valuenow="Alpine.store('progress').raw" 
      aria-valuemin="0" aria-valuemax="100" x-text="Alpine.store('progress').formated"></div>
      </div>
  </div>

  <div class="flex-fill d-flex flex-column">
    <!-- Titulo -->
    <input
    type="text"
    class="form-control fw-semibold mb-2 shadow-sm rounded-0"
    :class="hasFinished() ? '' : 'bg-white'"
    tabindex="0"
    x-model="state.title"
    id="title"
    :readonly="hasFinished()"
    autofocus>

    <!-- Descripcion -->
    <label for="description" class="form-label a-little-small mb-0 text-blue fst-italic">Descripci&oacute;n</label>
    <textarea
    class="form-control mb-2 resive-v shadow-sm rounded-0 flex-fill"
    :class="hasFinished() ? '' : 'bg-white'"
    style="height: 178px; font-size: .9rem;"
    x-model="state.description"
    id="description"
    :readonly="hasFinished()"></textarea>

    <!-- Prioridad y delegado -->
    <div class="row g-0 mb-2 align-items-center">
      <div class="col-md-6 col-12 p-1">
        <span class="form-label a-little-small text-blue fst-italic">Prioridad*</span>
        <div
        class="d-flex w-100 _border a-little-small pt-1 justify-content-center gap-2 shadow-sm"
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
        <select class="form-select form-select-sm shadow-sm rounded-0" x-model.number="state.delegate_id" :disabled="hasFinished()">
          <option value="0" selected>- Libre -</option>
          <template x-for="u in Alpine.store('users')" :key="u.consultor_id">
            <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
          </template>
        </select>
      </div>
    </div>

    <?php
      if (! $project->due_date) {
        echo <<<HTML
        <template x-if="! Alpine.store('__control').due_date">
          <!-- Fecha de Estimada de finalizacion Y tiempo estimado -->
          <div class="_border rounded-0 shadow-sm p-2 my-3">
            <p class="a-little-small text-muted mb-2">
              <span class="fw-bold">Importante: </span>
              <span>Una vez establecida no se podr&aacute; alterar.</span>
            </p>
            <div class="g-0 d-md-flex gap-3">
              <!-- Tiempo estimado -->
              <div class="flex-grow-1">
                <label for="estimated" class="form-label a-little-small m-0 text-blue fst-italic">Tiempo estimado:</label>
                <div class="d-flex gap-2">
                  <!-- Aquí se calcula la fecha dependiendo del input -->
                  <div style="width: 60px;">
                    <input
                    type="number" :disabled="allowDueDate()" min="0"
                    class="form-control form-control-sm shadow-sm"
                    x-model.number="addToDates" @input.debounce.300ms="setDueDate()">
                  </div>
                  <!-- Aqui se selecciona el `tipo` de fecha a calcular -->
                  <div class="flex-grow-1">
                    <select class="form-select form-select-sm  shadow-sm" x-model="state.estimated_time" @input="setDueDate()" :disabled="allowDueDate()">
                      <option value="0">--- ||| ---</option>
                      <option value="days">Dias</option>
                      <option value="weeks">Semanas</option>
                      <option value="months">Meses</option>
                      <option value="years">A&ntilde;os</option>
                    </select>
                  </div>
                </div>
              </div>

              <i class="d-md-block m-auto bi bi-arrow-left-right text-center m-1 d-none"></i>
              <i class="d-block d-md-none m-auto bi bi-arrow-down-up text-center m-1 mt-1"></i>

              <!-- Fecha de entrega -->
              <div class="flex-grow-1">
                <label for="dueDate" class="form-label a-little-small m-0 text-blue fst-italic">F. Finalizaci&oacute;n Estimada:</label>
                <input
                type="date"
                class="form-control form-control-sm w-100 shadow-sm"
                x-model="state.due_date" id="dueDate" :disabled="allowDueDate()">
              </div>
            </div>
          </div>
        </template>
        HTML;
      }
    ?>

    <!-- Extras -->
    <div class="row g-0 mb-3">
      <!-- Estado -->
      <div class="m-0 p-1 col-12 col-md-6">
        <label for="status" class="form-label m-0 a-little-small text-blue fst-italic">Estado*</label>
        <select class="form-select form-select-sm shadow-sm rounded-0" id="status" x-model="state.status">
          <option value="new" x-show="! isStarted()">Nuevo</option>
          <option value="process">En Proceso</option>
          <option value="paused">Pausado</option>
          <option value="finished" x-show="! isNew()">Finalizado</option>
        </select>
      </div>

      <?php
        if (! $project->started_at ) {
          echo <<<HTML
          <!-- Fecha de Inicio -->
          <template x-if="allowStartedAt() && !Alpine.store('__control').started_at">
            <div class="m-0 p-1 col-12 col-md-6 _border rounded-1 shadow-sm">
              <p class="a-little-small text-muted mb-2">
                <span class="fw-bold">Importante: </span>
                <span>Una vez establecida no se podr&aacute; alterar.</span>
              </p>
              <label for="startedAt" class="form-label a-little-small m-0 text-blue fst-italic">Fecha Inicio*</label>
              <input
              class="form-control form-control-sm shadow-sm"
              :class="(state.status != 'new' && !state.started_at) ? 'border-danger' : ''"
              id="startedAt" x-model="state.started_at"
              :disabled="isStarted()" type="date">
            </div>
          </template>
          HTML;
        }
      ?>
    </div>
  </div>

  <!-- Fechas -->
  <?php require 'dates-table.php'; ?>
</div>
