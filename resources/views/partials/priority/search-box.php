<div 
class="flex-fill position-relative"
x-data="requestSearchBox" x-modelable="search" x-model.debounce.500ms="$store.searchBox">
    <div class="d-flex gap-1 align-items-center">
        <div class="flex-fill">
            <input
            placeholder="Buscar"
            type="text"
            id="search-request" x-model.debounce.500ms="search"
            class="form-control form-control-sm">
        </div>
        <button type="button" class="btn btn-sm btn-outline-dark" @click="search = ''">
            <i class="bi bi-x a-little-small"></i>
        </button>
    </div>
    <ul class="list-group a-little-small position-absolute shadow-sm top-100 end-0 me-3 mt-1 overflow-auto" style="z-index: 1; max-height: 300px;">
        <template x-for="request in results" :key="request.id">
            <li 
            type="button" x-text="request.subject" @click="fetchOneRequest( request.id )"
            class="list-group-item list-group-item-light list-group-item-action py-1 px-2"></li>
        </template>
    </ul>
</div>
