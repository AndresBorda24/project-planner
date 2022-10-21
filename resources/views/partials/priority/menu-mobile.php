<div x-data="{
  show: null,
  loadMenu: (window.innerWidth < 768 ),
  disable() {
    if ( ! Object.prototype.hasOwnProperty.call( Alpine.store('currentRequest'), 'id' ) ) {
      return true;
    }
    return false;
  },
  open( o ) {
    if ( this.show == o ) {
      this.show = null;
      return;
    }

    this.show = o;
    return;
  }
}"
class="d-md-none position-relative p-1" style="overflow-y: visible;" @resize.window.debounce.300ms="show = null">
  <div class="m-0 p-0">
    <!-- Botones -->
    <div class="d-flex gap-2">
      <button class="btn btn-outline-dark btn-sm w-25 d-block text-center p-0" @click="open('edit')" :disabled="disable()">
        <i class="bi bi-pencil-square"></i>
      </button>
      <button class="btn btn-outline-success btn-sm w-25 d-block text-center p-0" @click="open('obs')" :disabled="disable()">
        <i class="bi bi-bookmark-plus-fill"></i>
      </button>
      <button type="button" class="btn btn-outline-primary btn-sm py-0 px-3 me-2 d-block text-center ms-auto" @click="$dispatch('open-create-request')">
        <i class="bi bi-plus"></i>
      </button>
      <button type="button" class="btn btn-outline-danger btn-sm py-0 px-3 me-1 d-block text-center" :disabled="disable()" @click="show = null">
        <i class="bi bi-trash-fill"></i>
      </button>
    </div>
    
    <div class="position-absolute bg-light mt-1 border border-1 border-secondary rounded-1 shadow-lg overflow-auto" x-collapse x-show="show == 'edit'">
      <!-- <template x-if="loadMenu"> -->
        <div style="min-width: 300px; max-width:83vw; max-height:75vh;">
          <?php require 'edit.php' ?>
        </div>
      <!-- </template> -->
    </div>

    <div class="position-absolute bg-light mt-1 border border-1 border-secondary rounded-1 shadow-lg overflow-auto" x-collapse x-show="show == 'obs'" 
    style="min-width: 300px; max-width:83vw; max-height:80vh; min-height: 370px;">
      <div class="m-2 p-1">
        <div class="position-relatve" x-data="observations">
          <?php require 'observations.php' ?>
        </div>
      </div>
    </div>
  </div>
</div>
