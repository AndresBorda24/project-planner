<div class="d-md-grid gap-2" style="grid-template-columns: 1fr 1fr;">
  <div x-data="saveNewStatus" class="mb-3">
    <label for="new-request-status" class="form-label text-muted a-little-small">Nuevo Estado</label>
    <div class="d-flex gap-3">
      <input type="text" x-model="status" @keyup.enter="save()"
        class="form-control form-control-sm" id="new-request-status" aria-describedby="new-request-status-help" placeholder="Nuevo Estado">
      <button class="btn btn-sm btn-success a-little-small" @click="save()" :disabled="! canSave()">Crear!</button>
    </div>
    <small id="new-request-status-help" class="a-little-small fst-italic text-black-50">Este estado es para las solicitudes.</small>
  </div>

  <?php require "status-list.php"; ?>
</div>

<hr class="w-75 mx-auto">

<?php require "remove-status.php"; ?>
