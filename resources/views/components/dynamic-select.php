<div class="w-100" style="height: 50px;">
  <span class="a-little-small form-label m-0 text-muted"><?= $title ?></span>

  <div 
  x-data="dynamicSelect('<?= $ref ?>', '<?= $type ?>')" 
  style="z-index: 1;"
  class="bg-white w-100 rounded-2 border-1 position-relative shadow-sm overflow-auto" 
  :class="showOptions ? 'border border-secondary' : '_border'"
  x-modelable="select" 
  x-model="<?= $model ?>"
  @click.outside="showOptions = false">

    <div class="text-muted">
      <select class="p-1 a-little-small border-0 text-muted w-100" x-model="select" @change="test()" @input.debounce.500ms="<?= $oninput ?>" @mousedown.prevent="showOptions = !showOptions" id="<?= $ref ?>">
        <template x-for="option in options" :key="option.key">
          <option :value="option.key" x-text="option.value"></li>
        </template>
      </select>
    </div>

    <div x-show="showOptions" x-cloak>
      <div class="p-1 d-flex gap-1 align-items-center">
        <input type="text" class="form-control form-control-sm a-little-small" x-model="search">
        <button class="btn btn-sm btn-outline-secondary a-little-small" style="padding: 1px 2px;" :disabled="!canCreate()">
          <i class="bi bi-check"></i>
        </button>
      </div>

      <ul role="listbox" class="list-group list-group-flush mt-1 a-little-small" style="max-height: 150px; overflow-y: auto;">
        <template x-for="option in filter()" :key="option.key">
          <li
          @click="selectOption( option.key )"
          role="option" 
          class="list-group-item py-1 list-group-item-action list-group-item-light user-select-none" 
          x-text="option.value"></li>
        </template>
      </ul>
    </div>
  </div>
</div>
