<div 
class="hw-100 vh-100 fixed-top bg-dark bg-opacity-75 flex" 
x-data="newObservation"
x-show="show" id="new-ob-modal"
x-cloak
style="z-index: 6;"
>
  <div 
  @add-new-ob.window.stop="handleShow($event.detail)"
  class="m-auto main-bg rounded p-3 border border-2 new-obs overflow-auto"
  :class="borderColor( type )">
    <button class="d-block float-end btn btn-sm btn-close mb-3" @click="setDefault()"></button>
    <h6 class="text-center text-secondary h-6">Nueva Observaci&oacute;n</h6>
    <div class="mb-3">
      <textarea 
      class="form-control text-small"
      autofocus
      x-model="newObsBody" id="new-ob-body"
      style="min-height: 200px;"></textarea>
    </div>

    <div>
      <label for="ob-created-by" class="form-label a-little-small m-0 text-muted fst-italic">Autor*</label>
      <select class="form-select form-select-sm a-little-small" id="ob-created-by" x-model="newObsAuthor">
        <option value="0" selected>--- ||| ---</option>
        <template x-for="u in Alpine.store('users')" :key="u.consultor_id">
          <option :value="u.consultor_id" x-text="u.consultor_nombre"></option>
        </template>
      </select>
    </div>
    <hr class="w-50 m-auto my-2">
    <button 
    type="button" 
    :disabled="! validate()"
    @click="saveNewOb()"
    class="btn btn-success btn-sm d-block m-auto">Guardar</button>
  </div>
</div>
