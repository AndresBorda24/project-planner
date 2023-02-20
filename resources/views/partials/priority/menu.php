<div class="flex-shrink-0 shadow d-flex flex-column" x-data="buttons" style="max-height: 45vh;">
    <!-- Botones -->
  <div class="d-flex gap-2 p-1 border-top border-secondary">
    <button type="button" class="btn btn-sm btn-outline-primary p-1 px-2 d-block" @click="$dispatch('open-create-request')">
      <i class="bi bi-plus"></i>
    </button>
    <button :disabled="disable()" @click="changeShow( 1 )" :class="{'active': show === 1}"
      class="btn btn-sm btn-outline-secondary p-1 px-2 d-block">
      <i class="bi bi-pencil-square"></i>
      <span x-show="show === 1" class="small">Editar</span>
    </button>
    <button :disabled="disable()" @click="changeShow( 2 )" :class="{'active': show === 2}"
      class="btn btn-sm btn-outline-success p-1 px-2 d-block">
      <i class="bi bi-bookmark-plus-fill"></i>
      <span x-show="show === 2" class="small">Observaciones</span>
    </button>
    <button x-data="deleteRequest" type="button"
    class="btn btn-sm btn-outline-danger p-1 px-2 d-block" @click="remove()" :disabled="$data.disable()">
      <i class="bi bi-trash-fill"></i>
    </button>
  </div>

  <div class="flex-fill flex flex-column overflow-auto" id="edit-menu-normal" x-show="show === 1" x-cloak>
    <?php require __DIR__ . '/edit.php' ?>
  </div>

  <div class="position-relatve flex-fill" id="view-obs-normal" x-show="show === 2" x-cloak>
    <div x-data="observations">
      <?php require __DIR__ . '/observations.php' ?>
    </div>
  </div>

</div>
