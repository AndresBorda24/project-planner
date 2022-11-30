<!-- Botónes -->
<div class="bg-buttons d-flex flex-column">
  <div class="p-1">
    <a href="<?= \App\App::config('project_path') . '/' ?>" class="text-decoration-none d-block m-0 p-0">
      <img alt="project-planer-logo" src="<?= \App\Helpers\Assets::load('images/favicon.svg') ?>" height="30" width="30">
    </a>
  </div>
  <div class="d-flex flex-grow-1 flex-column gap-3 justify-content-center mb-5">
    <!-- Guardar -->
    <button
      x-data="saveRecord"
      tabindex="-1"
      @save-record.document.stop="await save($event.detail)"
      @click="saveProject()"
      :disabled="! canSave()"
      class="btn-sm pb-0 pt-1 px-2 position-relative btn-show-info btn-show-info-guardar rounded-0 border-0 btn btn-outline-success">
        <i class="bi bi-check-square position-relative fs-5" style="z-index: 4;"></i>
        <template x-if="projectHasChanged()">
          <span class="position-absolute translate-middle bg-danger border border-light rounded-circle" style="padding: .35rem; z-index: 4;"></span>
        </template>
    </button>

    <!-- Nueva tarea -->
    <div 
      x-data="addButton"
      tabindex="-1" 
      x-show="isAllowed()">
      <button
        @click="addTask()"
        tabindex="-1"
        class="btn-sm pb-0 pt-1 px-2 position-relative btn-show-info btn-show-info-new rounded-0 border-0 btn btn-outline-light">
          <i class="bi bi-plus-square position-relative fs-5" style="z-index: 4;"></i>
      </button>
    </div>

    <!-- Nueva Observacion -->
    <button
      x-data
      @click="$dispatch('add-new-ob', { 
        type: 'project',
        id: Alpine.store('__control').id
      })"
      tabindex="-1"
      class="btn-sm pb-0 pt-1 px-2 position-relative btn-show-info btn-show-info-obs rounded-0 border-0 btn btn-outline-warning">
        <i class="bi bi-bookmark-plus position-relative fs-5" style="z-index: 4;"></i>
    </button>

    <!-- Subir Adjunto -->
    <button
    x-data
    tabindex="-1"
    @click="$dispatch('add-attachment')"
    class="btn-sm pb-0 pt-1 px-1 position-relative btn-show-info btn-show-info-attach rounded-0 border-0 btn btn-outline-info">
      <i class="bi bi-cloud-upload position-relative fs-5" style="z-index: 4;"></i>
    </button>

    <!-- Eliminar -->
    <button
      x-data="removeProject"
      @click="confirmDel()"
      tabindex="-1"
      class="btn-sm pb-0 pt-1 px-1 position-relative btn-show-info btn-show-info-elim rounded-0 border-0 btn btn-outline-danger">
        <i class="bi bi-trash3-fill position-relative fs-5" style="z-index: 4;"></i>
    </button>
  </div>
</div>