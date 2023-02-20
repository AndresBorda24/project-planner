<!-- Side-bar  -->
<div x-data="sidebar" class="sticky-lg-top flex flex-column bg-dark h-100 vh-100 small w-auto shadow-lg" style="white-space: nowrap; z-index: 1025;">
  <div class="p-2 p-md-3 border-bottom border-secondary">
    <div class="w-100">
      <img src="<?= \App\Helpers\Assets::load('images/Logo.png') ?>" alt="aso-logo" class="w-100 mx-auto logo"
        :class="{'d-block': ! shrink, 'd-none': shrink}" x-show="! shrink" x-cloak>
      <img src="<?= \App\Helpers\Assets::load('images/mini-logo.png') ?>" alt="aso-logo" class="w-100 mx-auto"
        :class="{'d-block': shrink, 'd-none': ! shrink}" x-show="shrink" style="max-height: 30px; object-position: center; object-fit: contain;">
    </div>
    <h1 class="h5 m-0 mx-0 mx-sm-2 mx-md-3 mx-lg-5 text-white text-center mb-1 bg-dark" x-show="! shrink">Project Planner</h1>
  </div>

  <div class="flex-fill overflow-auto">
    <!-- Botones -->
    <a href="<?= \App\App::config("project_path") . '/' ?>" title="Proyectos"
      class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start text-decoration-none">
      <i class="text-info bi bi-list-task fs-5"></i> <span class="flex-fill" x-show="! shrink">Proyectos</span>
    </a>
    <a href="<?= \App\App::config("project_path") . '/priorizacion-&-solicitudes'  ?>" title="Solicitudes"
      class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start text-decoration-none">
      <i class="text-info bi bi-card-checklist fs-5"></i> <span class="flex-fill" x-show="! shrink">Solicitudes</span>
    </a>
    <a href="<?= \App\App::config("project_path") . '/view-activity'  ?>" title="Actividad Reciente"
      class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start text-decoration-none">
      <i class="text-info bi bi-list-columns-reverse fs-5"></i> <span class="flex-fill" x-show="! shrink">Actividad Reciente</span>
    </a>

    <!-- Listado de pendientes -->
    <div x-data="pending" class="transition-easy-out-200 rounded-0" :style="expand && { backgroundColor: '#2b2e30' }">
      <button style="box-shadow: none;" title="Revisar pendientes"
        class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start w-100 p-2"
        @click="() => { expand = !expand; $data.shrink = false }">
        <i class="text-info bi bi-eye-fill fs-5"></i> <span class="flex-fill" x-show="! $data.shrink">Revisar pendientes</span>
      </button>

      <div class="rounded-bottom max-h-300 overflow-auto" style="background-color: #2b2e30;" x-show="expand && ! $data.shrink" x-cloak>
        <div class="row g-0 sticky-top" style="background-color: #2b2e30;">
          <div class="col-10 p-1">
            <label for="delegated" class="form-label a-little-small m-0 ms-2 text-secondary fst-italic">Delegado</label>
            <div x-data="selectUsers" x-modelable="selectedUser" x-model="delegate">
              <select class="form-select form-select-sm a-little-small" x-model="selectedUser">
              <option value="0" selected>- Libres -</option>
              <template x-for="u in users" :key="u.consultor_id">
                  <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
              </template>
              </select>
            </div>
          </div>
          <div class="col-2 p-1 pt-2 flex justify-content-center align-items-center">
            <button class="btn btn-sm pb-0 btn-secondary a-little-small" @click="await getPending()"> &Xi; </button>
          </div>
        </div>
        <hr class="my-1 border-secondary">
        <div class="text-secondary">
            <ul class="small" style="max-height: 150px; max-width: 200px; white-space: initial;">
            <template x-for="(p, i) in pendingList " :key="i">
                <li
                :class="p.type == 'task' ? 'text-warning' : p.type == 'project' ? 'text-light' : 'text-primary'"
                class="underline-hover"
                role="button"
                x-text="p.title"
                @dblclick="open(p)"></li>
            </template>
            </ul>
        </div>
      </div>
    </div>

    <a href="<?= \App\App::config("project_path") . 'config' ?>" title="Configuraci&Oacute;n"
      class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start text-decoration-none">
      <i class="text-info bi bi-gear fs-5"></i> <span class="flex-fill" x-show="! shrink">Configuraci&oacute;n</span>
    </a>
    <hr class="border-secondary">
    <!-- Excels xd -->
    <div x-data="excels">
      <button title="Excel de Proyectos" class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start w-100"
      @click="getExcel()">
        <i class="text-success bi bi-file-earmark-spreadsheet-fill fs-5"></i> <span class="flex-fill" x-show="! $data.shrink">Excel de Proyectos</span>
      </button>
      <button title="Excel Full" class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start w-100" @click="getFullExcel()">
        <i class="text-success bi bi-file-earmark-spreadsheet-fill fs-5"></i> <span class="flex-fill" x-show="! $data.shrink">Excel Full</span>
      </button>
    </div>
    <!-- Fin botones -->
  </div>

  <div class="border-top border-secondary">
    <button class="btn btn-sm btn-outline-secondary rounded-0 d-flex align-items-center justify-content-center gap-3 border-0 my-1 px-3 px-md-4 text-start w-100" @click="shrink = !shrink">
      <i class="text-white bi fs-5" :class="{'bi-caret-left-fill': ! shrink, 'bi-caret-right-fill': shrink }"></i>
      <span class="flex-fill" x-show="! shrink" x-text="shrink ? '' : 'Contraer'"></span>
    </button>
  </div>
</div>
