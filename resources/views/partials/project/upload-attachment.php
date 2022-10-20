<div x-data="addAttachment" id="add-attachment-" @add-attachment.window.stop="handleShow()">
  <template x-if="open">
    <div class="hw-100 vh-100 fixed-top bg-dark bg-opacity-75 flex" style="z-index: 6;">
      <div class="m-auto main-bg rounded p-3 border border-2 overflow-auto">
        <button class="btn btn-close float-end" @click="close()"></button>
        <h6 class="text-center text-secondary h-6">Nuevo Adjunto</h6>
        <div class="mb-3">
          <label for="attachments" class="form-label a-little-small">Archivos</label>
          <input 
          class="form-control form-control-sm" 
          type="file" accept="<?= implode(', ', \App\Models\Adjuntos::MIMES )  ?>"
          id="attachments" 
          @change="handleFiles( $el.files )" multiple>
        </div>
        <!-- Some borders are removed -->
        <span class="a-little-small">Archivos a Subir: </span>
        <ul class="list-group list-group-flush my-2 main-bg shadow-sm">
          <template x-for="(file, index) in files" :key="index">
            <li class="list-group-item bg-info bg-opacity-25 a-little-small">
              <span x-text="file.name"></span>
              <button class="btn btn-close float-end a-little-small" @click="removeFile( index )"></button>
            </li>
          </template>
        </ul>
        <button class="btn btn-info btn-sm a-little-small mt-3" x-show="hasFiles()" type="button" @click="upload">Subir archivos</button>
      </div>
    </div>
  </template>
</div>