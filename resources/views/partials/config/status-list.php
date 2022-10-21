<div class="p-2 bg-white _border rounded-1 shadow-sm small" x-data="statusList">
  <h6 class="small text-muted">Listado de Estados:</h6>
  <div class="overflow-auto" style="max-height: 150px;">
    <ul class="list-group list-group-flush small text-muted list-group-numbered me-2" id="status-list">
      <template x-for="status in Alpine.store('status')" :key="status.id">
        <li class="list-group-item small list-group-item-action" :id="'status' + status.id">
          <span class="text-muted" x-text="status.status"></span>

          <input type="checkbox" :disabled="canExcludeStatus( status )" @input.debounce.750ms="updateStatus(status)" x-model="status.basic" class="d-block float-end ms-3">

          <label class="float-end" :for="'l-status-'+status.id">
            <i 
            class="bi"  
            :class="( status.visible ) ? 'bi-eye-fill' : 'bi-eye-slash'"  
            role="button"></i>
          </label>

          <input type="checkbox" x-model="status.visible" @input.debounce.750ms="updateStatus( status )" class="d-none" :id="'l-status-' + status.id">
        </li>
      </template>
    </ul>
  </div>
</div>