<!-- Side-bar  -->
<div 
x-data="{ x: false }"
x-init="if (window.innerWidth > 991) { x = true }"
x-show="x"
x-transition:enter="transition-easy-out-200"
x-transition:enter-start="opacity-0 start-50"
x-transition:enter-end="opacity-100 start-0 shadow"
x-transition:leave="transition-easy-out-200"
x-transition:leave-start="opacity-100 start-0"
x-transition:leave-end="opacity-0 start-100"

@resize.window="if (window.innerWidth >= 992) { x = true }  else { $el.style.zIndex = undefined }"
@click.outside="if (window.innerWidth < 992) { x = false }"
@show-side-bar.window.stop="if (window.innerWidth < 992) {x = !x;  $el.style.zIndex == 1050} else { $el.style.zIndex = $el.style.zIndex == 1050 ? 0 : 1050 }"
:class="x ? 'd-flex' : '' "
class="col-lg-3 sticky-lg-top flex-column side-bar bg-dark h-100 min-vh-100"
>
  <div class="sticky-lg-top">
    <div class="w-100">
      <img src="<?= \App\Helpers\Assets::load('images/Logo.png') ?>" alt="aso-logo" class="w-100 p-2 pt-4 d-block mx-auto logo">
    </div>
    <h1 class="h5 m-0 text-white text-center mb-1 bg-dark">Project Planner</h1>
    <div class="w-75 mx-auto border-bottom border-1 border-light mb-3"></div>


    <!-- Botones -->
    <a href="<?= \App\App::config("project_path") . '/' ?>" class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2 text-decoration-none">Proyectos</a>
    <a href="<?= \App\App::config("project_path") . '/priorizacion-&-solicitudes'  ?>" class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2 text-decoration-none">Solicitudes</a>
    <a href="<?= \App\App::config("project_path") . '/view-activity'  ?>" class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2 text-decoration-none">Actividad Reciente</a>

    <hr class="border-secondary mx-auto w-75">

    <!-- Listado de pendientes -->
    <div x-data="pending" class="transition-easy-out-200" :style="expand && { backgroundColor: '#2b2e30', border: '1px solid #6e6e6e', borderRadius: '5px' }">
      <button style="box-shadow: none;" class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2 mb-1" @click="expand = !expand">Revisar pendientes</button>

      <div class="rounded-bottom max-h-300 overflow-auto" style="background-color: #2b2e30;" x-collapse.duration.200ms x-show="expand" x-cloak>
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
        <div class="text-secondary small">
            <ul class="small" style="max-height: 150px;">
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

    <hr class="border-secondary mx-auto w-75">
      <a href="<?= \App\App::config("project_path") . 'config' ?>" class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2 text-decoration-none">Configuración</a>
    <hr class="border-secondary mx-auto w-75">

    <!-- Excels xd -->
    <div x-data="excels">
      <button class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2" @click="getExcel()">Excel de Proyectos</button>
      <button class="btn btn-outline-secondary btn-sm border-0 w-100 d-block p-2" @click="getFullExcel()">Excel Full</button>
    </div>
    <!-- Fin botones -->
  </div>
</div>