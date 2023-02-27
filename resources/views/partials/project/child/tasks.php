<div x-show="current_ == 1">
  <div
  @child-loaded.document="getSubTasks"
  @task-list-loaded.document="loadSubTasks"
  x-data="childTaskList"
  class="list-group list-group-numbered ps-1 pt-2 overflow-hidden sub-tasks-list">
    <template x-if="subTasks">
      <template x-for="subt in subTasks" :key="subt.id">
        <div
        :class="subt.status == 'finished' ? 'list-group-item-danger' : 'list-group-item-light'"
        class="list-group-item list-group-item-action p-1 d-flex align-items-center gap-2">
          <!-- Titulo -->
          <div class="flex-grow-1" role="button" @dblclick="loadSubTask( subt )">
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
