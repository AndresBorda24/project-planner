<div class="m-2 p-1" x-data="editRequest">
  <div class="mb-2">
    <label for="request-subject" class="m-0 form-label a-little-small text-muted">Asunto*</label>
    <textarea class="form-control text-small" :class="(state.subject.length < 10) ? 'is-invalid' : ''"
    id="request-subject" x-model="state.subject" @input.debounce.1300ms="save()" style="height: 30vh;"></textarea>
  </div>

  <!-- 
    Selects de Area & dearrollo 
  -->
  <div class="d-flex gap-1">
    <div class="w-100">
      <label for="request-area" class="form-label a-little-small text-muted m-0">Area</label>
      <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.area" @input.debounce.500ms="save()" id="request-area">
        <?php foreach($areas as $area): ?>
          <option value="<?= $area["area_servicio_id"] ?>"><?= $area["area_servicio_nombre"] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="w-100">
      <label for="request-area" class="form-label a-little-small text-muted m-0">Desarrollo</label>
      <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.desarrollo" @input.debounce.500ms="save()" id="request-area">
        <?php foreach (\App\Models\Request::DESARROLLO as $value): ?>
          <option value="<?= $value ?>"><?= $value ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    
  </div>

  <!-- 
    Selects de Status & Scopes de Gema 
  -->
  <div class="d-flex gap-1 mb-1">
    <div class="w-100">
      <label for="request-gema" class="form-label a-little-small text-muted m-0">Alcance Gema</label>
      <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.gema" @input.debounce.500ms="save()" id="request-gema">
        <?php foreach ($gema as $scope): ?>
          <option <?= ($scope['visible']) ? '' : 'hidden' ?> value="<?= $scope['id'] ?>"><?= $scope['scope'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="w-100">
      <label for="request-status" class="form-label a-little-small text-muted m-0">Estado</label>
      <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.status" @input.debounce.500ms="save()" id="request-status">
        <template x-for="st in Alpine.store('status')" :key="st.id">
          <option :hidden="! st.visible || ! showStatus( st.basic )" :value="st.id" x-text="st.status"></option>
        </template>
      </select>
    </div>
  </div>

  <hr class="w-75 mx-auto">

  <!--  
    Calificaciones?!
  -->
  <div class="d-grid gap-1" style="grid-template-columns: 1fr 1fr;">
    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-scope" class="m-0 form-label a-little-small">Alcance: </label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="getText( state.data.scope )"></span>
      <input type="range" id="request-scope" x-model.number="state.data.scope" @input.debounce.700ms="save()" min="1" max="5" step="1" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-impo" class="m-0 form-label a-little-small">Importancia Relativa: </label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="getText( state.data.importance )"></span>
      <input type="range" id="request-impo" min="1" max="5" step="1" x-model.number="state.data.importance" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-cost" class="m-0 form-label a-little-small">Costo: </label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="state.data.cost == 1 ? 'Si' : 'No'"></span>
      <input type="range" id="request-cost" min="1" max="5" step="4" x-model.number="state.data.cost" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-span" class="m-0 form-label a-little-small">Plazo:</label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="({ 1:'Meses', 3:'Días', 5:'Horas' })[ state.data.span ]" >Meses</span>
      <input type="range" id="request-span" min="1" max="5" step="2" x-model.number="state.data.span" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-viability" class="m-0 form-label a-little-small">Viabilidad T&eacute;cnica:</label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="getText( state.data.viability )"></span>
      <input type="range" id="request-viability" min="1" max="5" step="1" x-model.number="state.data.viability" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-frequency" class="m-0 form-label a-little-small">Frecuencia: </label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="getText( state.data.frequency )"></span>
      <input type="range" id="request-frequency" min="1" max="5" step="1" x-model.number="state.data.frequency" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-economy" class="m-0 form-label a-little-small">Impacto Econ&oacute;mico: </label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="getText( state.data.economy )"></span>
      <input type="range" id="request-economy" min="1" max="5" step="1" x-model.number="state.data.economy" @input.debounce.700ms="save()"  class="position-absolute bottom-0 start-0">
    </div>

    <div class="_border bg-white p-1 pb-3 rounded-1 position-relative mb-3 lh-1">
      <label for="request-norma" class="m-0 form-label a-little-small">Cumplimiento Normativo:</label><br>
      <span class="text-dark fst-italic text-muted" style="font-size: .65em;" x-text="state.data.normativity == 1 ? 'No' : 'Si'" ></span>
      <input type="range" id="request-norma" min="1" max="5" step="4" x-model.number="state.data.normativity" @input.debounce.700ms="save()" class="position-absolute bottom-0 start-0">
    </div>
  </div>

  <hr class="w-75 mx-auto">

  <div>
    <template x-if="! hasProject()">
      <button class="btn btn-warning btn-sm d-block m-auto" @click="createProject()"> Empezar Proyecto! </button>
    </template>

    <template x-if="hasProject()">
        <div class="d-flex justify-content-center align-items-center gap-2">
          <button class="btn btn-outline-primary btn-sm" @click="goToProject()"> Ir a Proyecto! </button>
          
          <div class="position-relative overflow-visible" x-data="showProjectInfo">
            <i class="bi bi-exclamation-circle-fill text-primary" role="button" @click="showInfo( $data.state.project.id )"></i>
            <div class="p-4 _border shadow-sm bottom-100 bg-dark text-light position-absolute" x-show="show" style="width: 230px; right: -25px;">
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
</div>