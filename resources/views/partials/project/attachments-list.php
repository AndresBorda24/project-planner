<div class="position-relative overflow-auto mt-2 p-2"
x-data="manageAttachments" 
@reload-attachments.document.stop="getAttachments()">
  <ul class="list-group shadow-sm list-group-numbered">
    <template x-for="at in attachments" :key="at.id">
      <li class="list-group-item list-group-item-action a-little-small gap-2 d-flex align-items-center justify-content-between" role="button">
        <a role="button" :href="getUrl(at)" target="_blank" x-text="at.name" class="flex-grow-1 text-center text-dark text-decoration-none" style="word-break: break-all;"></a>
        <button 
        style="z-index: 1;"
        @click="deleteAttachment(at.id)"
        class="d-block btn-sm pb-0 px-1 position-relative btn btn-outline-danger small">
          <i class="bi bi-trash3-fill small" style="z-index: -1;"></i>
        </button>
      </li>
    </template>
  </ul>
</div>