<!---------------------------------- Observaciones ---------------------------------->
<div 
x-data="manageObservations" 
@load-obs.document.stop="getObs()" style="margin-top: -2.5rem;"
x-init="$nextTick(() => getObs());" class="overflow-auto position-relative">
  <div 
  x-data="listingObs" @list-obs.document="filter()" class="mt-5"
  x-show="showObsList" x-collapse x-ref="mainObsList" style="min-height: 150px;">
    <div
    class="obs-filters me-3 top-0 end-0">
      <button class="btn btn-sm btn-outline-success m-1 float-end" @click="showMenu = !showMenu" >Filtros</button>
      <!-- Filtros -->
      <div class="list-group shadow" @click.outside="showMenu = false" x-show="showMenu" x-cloak style="width: 200px; z-index: 2;">
        <!-- Si se hace check a este input cargará solamente las observaciones del proyecto -->
        <div class="list-group-item list-group-item-action p-1">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="show-only-projects"  @change="justProjects($event.target.checked)">
            <label class="form-check-label a-little-small text-muted" for="show-only-projects">Solo proyecto</label>
          </div>
        </div>
        <!-- Autor -->
        <div class="list-group-item list-group-item-action p-1">
          <label for="child-obs-author" class="a-little-small text-muted">Autor: </label>
          <select class="form-select form-select-sm a-little-small" x-model="obsAuthor" id="child-obs-author">
            <option value="0" selected>--- ||| ---</option>
            <template x-for="u in Alpine.store('users')" :key="u.consultor_id">
              <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
            </template>
          </select>
        </div>
        <!-- Muestra las observaciones a partir de una fecha especifica -->
        <div class="list-group-item list-group-item-action p-1">
          <div>
            <label for="child-obs-date" class="a-little-small text-muted">A partir de: </label>
            <input class="form-control form-control-sm a-little-small" type="date" id="child-obs-date" x-model="obsDate">
          </div>
        </div>
      </div>
    </div>

    <div class="list-group px-2 position-relative" style="font-size: 12px;">
      <!-- Se listan las observaciones -->
      <template x-for="ob in obs" :key="ob.id">
        <div class="p-1 py-2 list-group-item list-group-item-action flex align-items-center" :class="bgColor(ob)">
          <div class="flex-grow-1">
            <p class="mb-1 text-dark break-text" x-text="ob.body"></p>
            <span class="a-little-small" x-text="setUser(ob)"></span> &srarr;
            <span class="a-little-small" x-text="ob.created_at"></span><br>
            <!-- Este es el titulo de la tarea | proyecto | sub-tarea al que pertenece-->
            <span
            @click="canOpenTask(ob)"
            class="m-0 p-0 pt-2 small float-end me-2 fst-italic text-secondary underline-hover" 
            role="button" x-text="ob.title"></span>
          </div>
          
          <button 
          @dblclick="deleteObs(ob.id, $event.target)" 
          style="z-index: 1;"
          class="d-block btn-sm pb-0 px-1 position-relative btn btn-outline-danger small">
            <i class="bi bi-trash3-fill small" style="z-index: -1;"></i>
          </button>
        </div>
      </template>
    </div>
  </div>
</div>
