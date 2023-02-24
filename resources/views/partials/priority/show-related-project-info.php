<div x-data="showProjectInfo">
  <template x-if="! hasProject()">
    <?php require __DIR__ . "/bind-request.php" ?>
  </template>

  <template x-if="hasProject()">
    <div class="d-flex justify-content-center align-items-center gap-2">
      <button class="btn btn-outline-primary btn-sm" @click="goToProject()"> Ir a Proyecto! </button>

      <div class="position-relative overflow-visible">
        <i class="bi bi-exclamation-circle-fill text-primary" role="button" @click="showInfo()"></i>
        <div class="p-4 _border shadow-sm bottom-100 bg-dark text-light position-absolute" x-show="show"
        style="width: 230px; right: -50px;">
          <template x-if="info !== null">
            <div class="a-little-small" @click.outside="closeModal()">
              <h6 class="text-center" x-text="info.title"></h6>
              <hr>
              <p class="m-0">Estado &srarr; <span class="fw-bold fst-italic" x-text="getStatus( info.status )"></span></p>
              <p class="m-0">Avance &srarr; <span class="fw-bold fst-italic" x-text="getProgress( info.progress )"></span></p>
              <p class="m-0">Prioridad &srarr; <span class="fw-bold fst-italic" x-text="getPriority( info.priority )"></span></p>
              <p class="m-0">Creado en &srarr; <span class="fw-bold fst-italic" x-text="info.created_at"></span></p>
            </div>
          </template>

          <span x-show="info === null" class="text-center text-light fst-italic fw-bold">Cargando...</span>
        </div>
      </div>
    </div>
  </template>
</div>
