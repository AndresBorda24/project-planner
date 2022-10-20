<div x-data="createRequest" x-show="show" 
x-cloak @open-create-request.document.stop="open()"
class="fixed-top bg-dark bg-opacity-75 vh-100 vw-100 flex"
>
  <div class="border p-2 border-2 border-secondary rounded m-auto" style="background-color: #f9f9f9; width: 90vw; max-width: 400px;">
    <button class="btn btn-sm btn-close d-block ms-auto" @click="setDefault();"></button>
    <!-- Asunto -->
    <div class="mb-2">
      <label for="new-request-subject" class="form-label a-little-small mb-0 text-secondary">Asunto:</label>
      <textarea 
      class="form-control a-little-small shadow-sm bg-white" 
      rows="2" x-model="state.subject"
      id="new-request-subject" style="height: 100px;"
      autofocus></textarea>
    </div>
    
    <div class="d-flex gap-1 mb-3">
      <div class="w-100">
        <label for="new-request-area" class="form-label a-little-small text-muted m-0">Area</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.area" id="new-request-area">
          <option value="0">-||-</option>
          <option value="1">New Delhi</option>
          <option value="2">Istanbul</option>
          <option value="3">Libano</option>
          <option value="4">Jakarta</option>
          <option value="5">Ibagu&eacute;</option>
        </select>
      </div>

      <div class="w-100">
        <label for="new-request-desarrollo" class="form-label a-little-small text-muted m-0">Desarrollo</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model.number="state.desarrollo" id="new-request-desarrollo">
          <option value="0">-||-</option>
          <?php foreach (\App\Models\Request::DESARROLLO as $value): ?>
            <option value="<?= $value ?>"><?= $value ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
    </div>

    <div class="d-flex gap-1 mb-1">
      <div class="w-100">
        <label for="new-request-gema" class="form-label a-little-small text-muted m-0">Alcance Gema</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.gema" id="new-request-gema">
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
        <label for="new-request-status" class="form-label a-little-small text-muted m-0">Estado</label>
        <select class="form-control form-control-sm form-control-sm a-little-small" x-model="state.status" id="new-request-status">
          <option selected hidden>Selecciona</option>
          <?php
            foreach ($status as $st) {
              if ( $st["visible"] ) {
                echo <<<HTML
                  <option value="{$st['id']}">{$st['status']}</option>
                HTML;
              }
            }
          ?>
        </select>
      </div>
    </div>
    <button class="btn a-little-small btn-success mt-3 d-block ms-auto" :disabled="! isValid()" @click="create()"> Crear </button>
  </div>
</div>