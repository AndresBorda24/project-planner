<div 
class="w-100 mt-1 mb-2 d-flex justify-content-end align-items-center gap-2 flex-wrap position-relative" 
x-data="requestSearchBox" x-modelable="search" x-model.debounce.500ms="$store.searchBox">
    <label for="search-request" class="form-label a-little-small m-0">Buscar: </label>
    <div>
        <input 
        type="text" 
        id="search-request" x-model.debounce.500ms="search"
        class="form-control form-control-sm d-inline-block" style="width: 250px;">
        <button type="button" class="btn btn-sm btn-light d-inline-block" @click="search = ''">
            <i class="bi bi-x a-little-small"></i>
        </button>
    </div>
    <ul class="list-group a-little-small position-absolute shadow-sm top-100 end-0 me-3 mt-1 overflow-auto" style="width: 280px; z-index: 1; max-height: 300px;">
        <template x-for="request in results" :key="request.id">
            <li 
            type="button" x-text="request.subject" @click="fetchOneRequest( request.id )"
            class="list-group-item list-group-item-light list-group-item-action py-1 px-2"></li>
        </template>
    </ul>
</div>
