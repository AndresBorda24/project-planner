<div 
class="flex-fill position-relative"
x-data x-model.debounce.500ms="$store.searchBox">
    <div class="d-flex gap-1 align-items-center">
        <div class="flex-fill">
            <input
            placeholder="Buscar"
            type="text"
            id="search-request" x-model.debounce.500ms="$store.searchBox"
            class="form-control form-control-sm">
        </div>
        <button type="button" class="btn btn-sm btn-outline-dark" @click="$store.searchBox = ''">
            <i class="bi bi-x a-little-small"></i>
        </button>
    </div>
</div>
