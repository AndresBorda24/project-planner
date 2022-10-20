<div x-data="saveNewScope" class="d-md-grid gap-2" style="grid-template-columns: 1fr 1fr;">
  <div class="mb-3">
    <label for="new-gema-scope" class="form-label text-muted a-little-small">Nuevo "Alcance en GEMA"</label>
    <div class="d-flex gap-3">
      <input type="text" x-model="scope" @keyup.enter="save()"
      class="form-control form-control-sm" id="new-gema-scope" aria-describedby="new-gema-scope-help" placeholder="Nuevo Alcance">
      <button class="btn btn-sm btn-success a-little-small" @click="save()" :disabled="! canSave()">Crear!</button>
    </div>
    <small id="new-gema-scope-help" class="a-little-small fst-italic text-black-50">Debe ser un "alcance" que no exista. Este alcance es para las solicitudes.</small>
  </div>
  
  <?php require "gema-scopes-list.php"; ?>
</div>

<hr class="w-75 mx-auto">

<?php require "remove-gema-scopes.php"; ?>
