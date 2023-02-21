<div x-data="requestsList">
  <!-- 
    Aqui se listan todas las solicitudes que hayan sido fijadas
  -->
  <ul class="d-flex flex-column gap-2 px-0 px-md-2" id="pinned-requests-list" @pinned-moved.document="pinnedMoved( event )">
    <template x-for="request in getPinnedRequests()" :key="`pinned-${request.id}`">
      <li class="d-block cursor-move mb-2" :data-request-id="request.id">
        <input x-model="selected" type="radio" :id="`request-${request.id}`" hidden :value="request.id">
        <div class="border border-secondary border-opacity-50 d-md-flex" x-data="requestItem( request )">
          <div class="small text-center p-1 px-2 d-md-flex flex-column justify-content-center"
          :class="{'text-bg-dark': parseInt(request.pinned) > 0}">
            <i class="bi bi-arrows-move move-handle"></i> <!-- Mueve la request -->
            <!-- Pin | no Pin -->
            <button class="btn rounded-1 btn-sm a-little-small" @click="managePinned( request )"
            :class="(parseInt(request.pinned) > 0) ? 'btn-dark' : 'btn-light'">
              <i class="bi bi-pin-fill"></i>
            </button>
            <span class="a-little-small fw-bold mb-1">Prioridad:</span>
            <span class="a-little-small fw-bold text-decoration-underline fst-italic" x-text="$data.sum( request )"></span>
          </div>
          
          <!-- Contenedor -->
          <label :for="`request-${id}`" class="p-3 d-block request-item overflow-hidden flex-grow-1"
          style="z-index: 1; cursor: pointer;" :id="`rc-${id}`"> <!-- rc: request container -->
            <p class="a-little-small m-0 overflow-hidden d-block" style="height: 35px;" x-text="request.subject"></p>
            <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1">
              Creado el <span class="fw-bold" x-text="getDate( request.created_at )"></span>
            </span> 
            <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1 me-3">
              Solicitado el <span class="fw-bold" x-text="getDate( request.requested_at )"></span>
            </span> 
          </label>
        </div>
      </li>
    </template>
  </ul>
  
  <div class="my-4 w-75 border border-dark m-auto position-relative">
    <i class="bi bi-pin-fill position-absolute m-auto end-0 start-0 border border-dark text-bg-light rounded-2 text-center"
    style="width: 34px; top: -12px; rotate: 180deg;"></i>
  </div>
  
  <!-- 
    Aqui se listan todas las solicitudes que NO han sido fijadas 
  -->
  <ul class="d-flex flex-column gap-2 px-0 px-md-2" id="requests-list">
    <template x-for="request in search( sort( requests() ) )" :key="request.id">
      <li class="d-block mb-2">
        <input x-model="selected" type="radio" :id="`request-${request.id}`" hidden :value="request.id">
        <div class="border border-secondary border-opacity-50 d-md-flex" x-data="requestItem( request )">
          <div class="small text-center p-1 px-2 bg-light d-md-flex flex-column justify-content-center">
            <!-- Pin -->
            <button class="btn btn-light btn-sm a-little-small d-inline-block" @click="managePinned( request )">
              <i class="bi bi-pin-angle"></i>
            </button>
            <span class="a-little-small fw-bold">Prioridad:</span>
            <span class="a-little-small fw-bold text-decoration-underline fst-italic" x-text="$data.sum( request )"></span>
          </div>
          <!-- Contenedor -->
          <label :for="`request-${id}`" class="p-3 d-block request-item overflow-hidden flex-grow-1"
          style="z-index: 1; cursor: pointer;" :id="`rc-${id}`"> <!-- rc: request container -->
            <p class="a-little-small m-0 overflow-hidden d-block" style="height: 35px;" x-text="request.subject"></p>
            <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1">
              Creado el <span class="fw-bold" x-text="getDate( request.created_at )"></span>
            </span>
            <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1 me-3">
              Solicitado el <span class="fw-bold" x-text="getDate( request.requested_at )"></span>
            </span> 
          </label>
        </div>
      </li>
    </template>  
  </ul>
</div>

<!-- 
  Cargar Más
-->
<div class="p-4" x-data="loadMoreRequests" x-intersect.full="loadMore">
  <template x-if="areThereMore() && Alpine.store('searchBox').length == 0">
    <div class="h-auto m-auto" style="width: 50px;">
      <img src="<?= \App\Helpers\Assets::load('images/mini-loader.png') ?>"
      style="object-fit: contain; object-position: center;" class="h-100 w-100"
      alt="loading-requests">
    </div>
  </template>
  <template x-if="! areThereMore() && Alpine.store('searchBox').length == 0">
    <p class="text-center text-muted fst-italic small"> Ya no hay m&aacute;s por cargar... </p>
  </template>
</div>

