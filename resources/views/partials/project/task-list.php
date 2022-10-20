<!---------------------------------- Tareas | Sub tareas ---------------------------------->
<div 
class="overflow-auto position-relative" 
x-data="tasksList" style="margin-top: -2.5rem;"
@load-tasks.document.stop="await handlerLoader()">
  <div class="obs-filters me-3 top-0 end-0" x-show="showList">
    <button class="btn btn-sm btn-outline-success m-1" @click="sortControl++; sortByPriority()">Ordenar</button>
  </div>

  <!-- Se realiza el listado de tareas -->
  <div class="d-flex gap-3 mt-5 flex-column px-1 px-md-3 px-lg-4 position-relative" id="child-list" x-show="showList" x-collapse>

    <template x-for="task in children" :key="task.id">
      <div>
        <!-- Tarea -->
        <div
          :class="isFinished(task.status)"
          :id="'task-' + task.id"
          class="project-item ps-3 mb-1 shadow-sm user-select-none p-1 d-flex align-items-center _border rounded">
          <!-- Titulo de la Tarea  -->
          <div class="flex-grow-1" role="button" @dblclick="$dispatch('load-child', { ... task })">
            <span x-text="task.title" class="a-little-small m-0 line-height-26 lh-sm"></span>
          </div>

          <!-- Iconos y Estatus -->
          <div class="d-flex flex-shrink-0" style="padding-left: 10px;">
            <!-- prioridad de la tarea -->
            <span 
            x-text="prioritySpanish(task.priority)" style="background-color: var(--bs-gray-100)"
            class="rounded _border really-small p-1 m-0 me-1 d-inline-block"></span>
            <!-- Estado -->
            <span 
            x-text="statusSpanish(task.status)" style="background-color: var(--bs-gray-200)"
            class="rounded  _border really-small p-1 m-0 me-1 d-inline-block"></span>
            <!-- Progreso de la tarea -->
            <span 
            x-text="childProgress(task.progress)" style="background-color: var(--bs-gray-300)"
            class="rounded _border really-small p-1 m-0" :class="task.progress == 101 ? 'd-none' : 'd-inline-block'"></span>
            <!-- Botón de añadir sub-tarea -->
            <template x-if="showAddSubTask(task)">
              <i
              @click="$dispatch('load-child', { father: task.id, type: 'sub_task', pTitle: task.title })"
              role="button"
              title="Agregar sub-tarea"
              class="bi bi-plus-lg d-inline-block px-1"></i>
            </template>

            <!-- Botón de expandir sub-tareas -->
            <template x-if="task._subTasks">
              <i 
                @click="await expand(task.id)" 
                role="button" 
                :id="'expand-'+task.id"
                title="Ocultar/Expandir sub-tareas"
                class="bi bi-chevron-down transition-easy-out-200 d-inline-block"></i>
            </template>
          </div>
        </div>

        <!-- Listado de Sub-Tareas -->
        <template x-if="task._subTasks">
          <div 
          class="d-flex flex-column gap-2 ps-2 ps-md-3 ps-lg-4 overflow-hidden transition-ease-200 d-none sub-tasks-list" 
          :id="'stlist-'+task.id" style="height: 0;">

            <template x-for="subt in task._subTasks" :key="subt.id">
              <div
                :class="subt.status == 'finished' ? 'bg-pinky-plus finished-project-item' : 'bg-white'"
                :id="'sub-task-' + subt.id"
                class="sub-task-item ps-3 ms-5 user-select-none p-1 d-flex _border rounded align-items-center me-2">
                <!-- Titulo de la Sub-Tarea -->                              
                <div class="flex-grow-1" role="button" @dblclick="$dispatch('load-child', { 
                  ...subt,
                  pStatus: task.status,
                  pTitle: task.title
                })">
                  <span x-text="subt.title" class="small m-0 line-height-26 a-little-small underline-hover lh-sm"></span>
                </div>
                <!-- Estatus de la tarea -->
                <span class="text-muted fst-italic float-end really-small p-1 m-0 me-2" x-text="statusSpanish(subt.status)"></span>
              </div>
            </template>
          </div>
        </template>
      </div>
    </template>
  </div>
</div>