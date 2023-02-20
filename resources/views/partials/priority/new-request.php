<div x-data="createRequest" x-show="show" 
x-cloak @open-create-request.document.stop="open()"
class="fixed-top bg-dark bg-opacity-75 vh-100 vw-100 flex"
>
  <div class="border p-2 border-2 border-secondary rounded m-auto" style="background-color: #f9f9f9; width: 500px; max-width: 90vw; max-height: 90vh;">
    <button class="btn btn-sm btn-close d-block ms-auto" @click="setDefault();"></button>
    <!-- Asunto -->
    <div class="mb-2">
      <label for="new-request-subject" class="form-label a-little-small mb-0 text-secondary">Asunto*</label>
      <textarea 
      class="form-control a-little-small shadow-sm bg-white" 
      rows="2" x-model="state.subject"
      id="new-request-subject" style="height: 200px;"
      autofocus></textarea>
    </div>
    
    <div class="d-flex gap-1 mb-3">
      <div class="w-100">
        <label for="new-request-area" class="form-label a-little-small text-muted m-0">Area*</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.area" id="new-request-area">
          <option value="0">-||-</option>
          <?php foreach($areas as $area): ?>
            <option value="<?= $area["area_servicio_id"] ?>"><?= $area["area_servicio_nombre"] ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="w-100">
        <label for="new-request-desarrollo" class="form-label a-little-small text-muted m-0">Desarrollo*</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.desarrollo" id="new-request-desarrollo">
          <option value="0">-||-</option>
          <?php foreach (\App\Models\Request::DESARROLLO as $value): ?>
            <option value="<?= $value ?>"><?= $value ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
    </div>

    <div class="d-flex gap-1 mb-3">
      <div class="w-100">
        <label for="new-request-gema" class="form-label a-little-small text-muted m-0">Alcance Gema*</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.gema" id="new-request-gema">
          <option selected hidden>Selecciona</option>
          <?php
            foreach ($gema as $scope) {
              if ( $scope["visible"] ) {
                echo <<<HTML
                  <option value="{$scope['id']}">{$scope['scope']}</option>
                HTML;
              }
            }
          ?>
        </select>
      </div>

      <div class="w-100">
        <label for="new-request-status" class="form-label a-little-small text-muted m-0">Estado*</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.status" id="new-request-status">
          <option selected hidden>Selecciona</option>
          <template x-for="st in Alpine.store('status').filter( el => el.basic )" :key="st.id">
            <option :hidden="! st.visible"  :value="st.id" x-text="st.status"></option>
          </template>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label for="new-request-rqat" class="form-label a-little-small text-muted m-0">Fecha en la que se solicitó*</label>
      <input type="date" class="form-control form-control-sm a-little-small" x-model="state.requested_at" id="new-request-rqat" >
    </div>
    
    <button class="btn a-little-small btn-success mt-3 d-block ms-auto" :disabled="! isValid()" @click="create()"> Crear </button>
  </div>
</div>
