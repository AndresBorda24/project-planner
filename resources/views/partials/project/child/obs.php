<!-------------------- Observaciones -------------------->
<div x-show="current_ == 2" x-data="manageObservations">
  <div 
  x-data="listingObs"
  x-show="! isNew()"
  @child-loaded.document="loadChildObs(child.type, child.id)" 
  @list-obs.window="if (obsType) filter();"
  class="position-relative overflow-auto"
  style="margin-top: -2.5rem;">
    <div class="obs-filters end-0 top-0 m-1 me-2">
      <button  :class="(child.type == 'task' ? 'btn-outline-warning ' : 'btn-outline-primary ')"
      class="btn btn-sm float-end ms-2" @click="showMenu = !showMenu" >Filtros</button>
      <!-- Filtros -->
      <div class="list-group shadow" @click.outside="showMenu = false" x-show="showMenu" x-cloak style="width: 200px; z-index: 2; left: -144px;">
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
    <!-- Listado de observaciones  -->
    <div class="list-group rounded-0 px-2 overflow-auto child-obs-max-h mt-5" style="min-height: 130px; font-size: 12px;">
      <template x-for="ob in obs" :key="ob.id">
        <div
        class="p-1 py-2 list-group-item list-group-item-action flex align-items-center" 
        :class="bgColor(ob)">

          <div class="flex-grow-1">
            <p class="mb-1 text-dark break-text" x-text="ob.body"></p>
            </b><span class="a-little-small" x-text="setUser(ob)"></span> &srarr;
            </b><span class="a-little-small" x-text="ob.created_at"></span><br>
          </div>
          
          <button 
          @dblclick="deleteObs(ob.id, $event.target)" 
          style="z-index: 1;"
          class="d-block btn-sm pb-0 px-1 position-relative btn btn-outline-danger small">
            <i class="bi bi-trash3-fill position-relative small"  style="z-index: -1;"></i>
          </button>
        </div>
      </template>
    </div>
  </div>
</div>
