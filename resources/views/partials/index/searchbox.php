<!-- Busqueda -->
<div class="mb-1 position-relative" x-data="searchBox" x-init="handler()">
    <input type="text" x-model.debounce.550="search" class="form-control form-control-sm" placeholder="Busca un proyecto">
    <div x-show="view" class="list-group rounded-0 position-absolute w-100 shadow z-4" @click.outside="view = false">
    <template x-for="f in found" :key="f.id">
        <button type="button" :disabled="disable(f)" @click="open(f)" class="list-group-item list-group-item-action small p-1 px-3" x-text="f.title"></button>
    </template>
    </div>
</div>
