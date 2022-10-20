<div class="d-none d-md-block flex-shrink-0 ps-4">
  <div class="sticky-top bg-light" style="top: 7rem; z-index: 1000;">
    <div class="d-flex">
      <!-- Botones -->
      <div class="d-flex flex-column gap-2 p-2" x-data="{ 
        disable() {
          if ( typeof Alpine.store('currentRequest') === 'undefined' ) {
            return true;
          }
          if ( ! Object.prototype.hasOwnProperty.call( Alpine.store('currentRequest'), 'id' ) ) {
            return true;
          }
          return false;
        },
        handle( id ) {
          const _hide = ( id == 'edit-menu-normal' ) ? 'view-obs-normal' : 'edit-menu-normal';
          const bc = bootstrap.Collapse.getInstance( document.getElementById( _hide ) );

          if (! bc) return;
          bc.hide();
        },
        closeBoth() {
          const obs = document.getElementById( 'view-obs-normal' );
          const menu = document.getElementById( 'edit-menu-normal');

          if ( menu.classList.contains('show') ) {
            ( bootstrap.Collapse.getInstance( menu ) ).hide();
            return;
          }

          if ( obs.classList.contains('show') ) {
            ( bootstrap.Collapse.getInstance( obs ) ).hide();
            return;
          }
        }
      }" @show-bs-collapse.dot.window="handle( $event.target.id )">

        <button type="button" class="btn btn-sm btn-outline-primary mb-5 p-0 d-block" style="height: 40px;" @click="$dispatch('open-create-request')">
          <i class="bi bi-plus"></i>
        </button>

        <button :disabled="disable()" data-bs-toggle="collapse"
        class="btn btn-sm btn-outline-secondary p-0 d-block" style="height: 120px;"
        data-bs-target="#edit-menu-normal" aria-expanded="false" aria-controls="edit-menu-normal">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button :disabled="disable()" data-bs-toggle="collapse"
        class="btn btn-sm btn-outline-success p-0 d-block" style="height: 120px;" 
        data-bs-target="#view-obs-normal" aria-expanded="false" aria-controls="view-obs-normal">
          <i class="bi bi-bookmark-plus-fill"></i>
        </button>

        <button x-data="deleteRequest" type="button" 
        class="btn btn-sm btn-outline-danger mt-5 p-0 d-block" style="height: 40px;" @click="remove()" :disabled="$data.disable()">
          <i class="bi bi-trash-fill"></i>
        </button>

      </div>
      
      <div 
      class="flex-fill collapse collapse-horizontal" id="edit-menu-normal" style="transition: all .1s ease-out;">
        <h4 class="text-center text-muted h6">Editar</h4>
        <div style="width: 340px; max-height: 75vh;" class="overflow-auto">
          <?php require 'edit.php' ?>
       </div>
      </div>
      
      <div class="position-relatve flex-fill collapse collapse-horizontal" x-data="observations" id="view-obs-normal" style="transition: all 100ms ease-out;">
        <?php require 'observations.php' ?>
      </div>


    </div>
  </div>
</div>