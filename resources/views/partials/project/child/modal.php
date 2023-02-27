<!-- 
  Este componente se encarga de mostrar un modal de edicion y/o creacion para 
  tareas y sub-tareas.
    -Si se carga una tarea se mostrarán sus observaciones y sub-tareas 
    -Si se carga una sub-tarea se mostrarán solamente sus obsercaciones
-->
<div 
class="hw-100 vh-100 fixed-top bg-dark bg-opacity-75 flex" 
x-data="viewChild"
id="view-child"
x-cloak
x-show="show"
@load-child.window="await handler($event.detail)"
style="z-index: 5;">
  <div 
  class="view-child-modal gap-2 rounded-2 pt-3 px-3 border border-2 overflow-auto bg-main m-auto"
  :class="paintBorder()" :style="isNew() ? {} : { width: '90%' }">
    <div class="grid-child-modal gap-3">
      <!-- Panel principal de informacion de la (sub)tarea -->
      <div>
        <!-- 
          Aqui se muestra el titulo del `padre`. Si es una tarea muestra el titulo del proyecto pero
          si es una sub-tarea muestra el titulo de la tarea a la que pertenece 
        -->
        <template x-if="child.type == 'sub_task'">
          <h6 class="text-small text-secondary text-center">
            <b>Sub-Tarea para</b> &srarr; <span x-text="father.title"></span>
          </h6>
        </template>

        <!-- Titulo -->
        <div class="mb-2">
          <label class="form-label a-little-small mb-0 text-secondary">T&iacute;tulo</label>
          <input
          type="text"
          class="form-control text-small shadow-sm fw-semibold rounded-0"
          :class="canModify() ? '' : 'bg-white'"
          x-model="child.title"
          :disabled="canModify()" id="child-title"
          autofocus>
        </div>
        <!-- Descripcion -->
        <div class="mb-2">
          <label class="form-label a-little-small mb-0 text-secondary">Descripci&oacute;n</label>
          <textarea 
          class="form-control text-small shadow-sm rounded-0"
          :class="canModify() ? '' : 'bg-white'"
          rows="2" x-model="child.description" id="child-desc" 
          :disabled="canModify()" style="height: 150px;"></textarea>
        </div>

        <!-- Prioridad y `Convertir a tarea` -->
        <div class="m-0 p-1">
          <label for="priority" class="form-label a-little-small m-0 text-secondary fst-italic d-block">Prioridad*</label>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="bg-white p-2 _border d-flex justify-content-center shadow-sm flex-grow-1">
              <div class="form-check form-check-inline">
                  <input 
                  class="form-check-input" id="c-priority-low"
                  type="radio" x-model="child.priority"  value="low" :disabled="canModify()">
                  <label class="form-check-label a-little-small" for="c-priority-low">Baja</label>
              </div>
              <div class="form-check form-check-inline">
                  <input 
                  class="form-check-input" id="c-priority-normal"
                  type="radio"  x-model="child.priority"  value="normal" :disabled="canModify()">
                  <label class="form-check-label a-little-small" for="c-priority-normal">Normal</label>
              </div>
              <div class="form-check form-check-inline">
                  <input 
                  class="form-check-input" id="c-priority-high"
                  type="radio"  x-model="child.priority"  value="high" :disabled="canModify()">
                  <label class="form-check-label a-little-small" for="c-priority-high">Alta</label>
              </div>
            </div>

            <div class="d-inline-block">
              <!-- Muestra el progreso solamente si es una tarea -->
              <span 
              x-show="child.type != 'sub_task'" 
              class="btn btn-success btn-sm user-select-none a-little-small" 
              role="text" x-text="getProgress()"></span>
              <!-- Muestra el botón de convertir a Tarea solamente si es una sub-tarea -->
              <?php require __DIR__ . "/subToTask.php"; ?>
            </div>
          </div>
        </div>

        <!-- Autor y Delegado -->
        <div class="flex">
          <!-- Autor -->
          <template x-if="typeof child.id == 'undefined'">
            <div class="m-0 p-1 w-100">
              <label for="child-author" class="form-label a-little-small m-0 text-secondary fst-italic">Creado Por*</label>
              <select class="form-select form-select-sm a-little-small rounded-0" id="child-author" x-model="child.created_by_id" :disabled="canModify()">
                <option value="0" selected>--- ||| ---</option>
                <template x-for="u in Alpine.store('users')" :key="u.consultor_id">
                  <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
                </template>
              </select>
            </div>
          </template>

          <!-- Delegado -->
          <div class="m-0 p-1 w-100">
            <label for="child-delegate" class="form-label a-little-small m-0 text-secondary fst-italic">Delegado*</label>
            <select 
            class="form-select form-select-sm a-little-small rounded-0" id="child-delegate"
            :class="(child.status == 'finished' && (! child.delegate_id || child.delegate_id == 0)) ? 'border-danger' : ''" 
            x-model.number="child.delegate_id":disabled="canModify()">
              <option value="0" selected>- Libre -</option>
              <template x-for="u in Alpine.store('users')" :key="u.consultor_id">
                <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
              </template>
            </select>
          </div>
        </div>

        <!-- Estado Y fecha de Inicio -->
        <div class="flex mb-2">
          <!-- Estado -->
          <div class="m-0 p-1 w-100">
            <label for="child-status" class="form-label a-little-small m-0 text-secondary fst-italic">Estado*</label>
            <select
            class="form-select form-select-sm a-little-small" id="child-status" 
            x-model="child.status" :disabled="canModifyStatus()">
              <option value="new" x-show="! child.started_at">Nuevo</option>
              <option value="process">En Proceso</option>
              <option value="paused">Pausado</option>
              <option value="finished">Finalizado</option>
            </select>
          </div>

          <template x-if="canSetStartedAt()">
            <div class="m-0 p-2 w-100 _border shadow-sm rounded-2">
              <p class="a-little-small text-muted mb-2">
                <span class="fw-bold">Importante: </span>
                <span>Una vez establecida no se podr&aacute; alterar.</span>
              </p>
              <label for="child-started_at" class="form-label a-little-small m-0 text-secondary fst-italic">Fecha Inicio*</label>
              <input 
              type="date" 
              class="form-control form-control-sm form a-little-small" 
              :class="(child.status != 'new' && ! child.started_at) ? 'border-danger' : ''" 
              id="child-started_at" x-model="child.started_at" :disabled="! canSetStartedAt()">
            </div>
          </template>
        </div>

        <?php require __DIR__ . '/child-dates-table.php'; ?>
      </div>
      
      <!-- Obs y sub-tareas -->
      <?php require __DIR__ . '/obs-tasks.php'; ?>
    </div>

    <!-- Botones -->
    <?php require __DIR__ . '/buttons.php' ?>
  </div>
</div>



