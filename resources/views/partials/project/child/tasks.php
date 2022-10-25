<div x-show="current_ == 1">
  <div
  @child-loaded.document="getSubTasks()"
  @task-list-loaded.document="
    if ( Alpine.store('__childControl').hasOwnProperty('id') ) {
      getSubTasks()
    }
  "
  x-data="{
    subTasks: [],
    getSubTasks() {
      if ( Alpine.store('__childControl').type != 'task') {
          this.subTasks = [];
          return;
      }
      const index = Alpine.store('currentTasksList')
        .findIndex( el => el.id == Alpine.store('__childControl').id && el.type == 'task');
      
      if (index === -1) { this.subTasks = []; return; };

      this.subTasks = Alpine.store('currentTasksList')[ index ]._subTasks;
      return;
    },
  }"
  class="d-flex flex-column gap-2 ps-1 pt-2 overflow-hidden transition-ease-200 sub-tasks-list">
    <template x-if="subTasks">
      <template x-for="subt in subTasks" :key="subt.id">
        <div
        :class="subt.status == 'finished' ? 'bg-pinky-plus finished-project-item' : 'bg-white'"
        class="sub-task-item ps-3 shadow-sm shadow-hover user-select-none p-1 flex _border">
          <!-- Titulo -->
          <div class="flex-grow-1" role="button" @dblclick="$dispatch('load-child', { 
            ...subt,
            pStatus: Alpine.store('__childControl').status, 
            pTitle: Alpine.store('__childControl').title 
          })">
            <span 
              x-text="subt.title"
              class="small m-0 line-height-26 a-little-small underline-hover lh-sm">
            </span>
          </div>
          <!-- Status -->
          <span class="text-muted fst-italic float-end really-small p-1 m-0 me-2" x-text="subt.status"></span>
        </div>
      </template>
    </template>
  </div>
</div>