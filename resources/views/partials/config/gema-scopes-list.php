<div class="p-2 bg-white _border rounded-1 shadow-sm small" x-data="gemaScopeList">
  <h6 class="small text-muted">Listado GEMA:</h6>
  <div class="overflow-auto" style="max-height: 150px;">
    <ul class="list-group list-group-flush small text-muted list-group-numbered me-2" id="gema-scopes-list">
      <template x-for="scope in Alpine.store('gemaScopes')" :key="scope.id">
        <li class="list-group-item small list-group-item-action" :id="'scope' + scope.id">
          <span class="text-muted" x-text="scope.scope"></span>

          <label class="float-end" :for="'l-scope-' + scope.id">
            <i 
            class="bi"  
            :class="( scope.visible ) ? 'bi-eye-fill' : 'bi-eye-slash'"  
            role="button"></i>
          </label>

          <input type="checkbox" x-model="scope.visible" @input.debounce.750ms="changeVisibility(scope)" class="d-none" :id="'l-scope-' + scope.id">
        </li>
      </template>
    </ul>
  </div>
</div>