<!-- 
  Aqui se listan todas las solicitudes que hayan sido fijadas
-->
<ul class="d-flex flex-column gap-2 px-4 px-md-2" x-data="requestsList" id="pinned-requests-list" @pinned-moved.document="pinnedMoved( event )">
  <template x-for="request in search( sortPinned( Alpine.store('requests') ) )" :key="request.id">
    <li class="d-block cursor-move" x-data="requestItem( request )" x-modelable="selectedRequest" x-model="selected" :data-request-id="request.id">
      <input x-model="selectedRequest" type="radio" :id="`request-${id}`" hidden :value="id"> 
      <div class="_border shadow-sm rounded-1 d-flex align-items-center"> 
        <!-- Mover -->
        <div class="p-2 border-end bg-light text-center">
          <i class="bi bi-arrows-move move-handle"></i>
        </div>

        <!-- Prioridad -->
        <div class="small text-center border-end p-1 px-2 bg-light">
          <button class="btn btn-light btn-sm a-little-small" @click="managePinned( request )">
            <i class="bi bi-pin-fill"></i>
          </button>
          <span class="a-little-small fw-bold">Prioridad:</span><br>
          <span class="a-little-small fw-bold text-decoration-underline fst-italic" x-text="$data.sum( request )"></span>
        </div>
        
        <!-- Contenedor -->
        <label :for="`request-${id}`" class="p-3 d-block request-item overflow-hidden h-100 flex-grow-1" style="z-index: 1; cursor: pointer;" :id="`rc-${id}`"> <!-- rc: request container -->
          <p class="a-little-small fst-italic m-0 overflow-hidden d-block" style="height: 35px;" x-text="request.subject"></p>
          <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1">
            Creado el <span class="fw-bold" x-text="getDate( request.created_at )"></span>
          </span> 
        </label>
      </div>
    </li>
  </template>
</ul>

<hr class="w-75 mx-auto">

<!-- 
  Aqui se listan todas las solicitudes que NO han sido fijadas 
-->
<ul class="d-flex flex-column gap-2 px-4 px-md-2" x-data="requestsList" id="pinned-requests-list">
  <template x-for="request in search( sort( Alpine.store('requests') ) )" :key="request.id">
    <li class="d-block" x-data="requestItem( request )" x-modelable="selectedRequest" x-model="selected" >
      <input x-model="selectedRequest" type="radio" :id="`request-${id}`" hidden :value="id"> 
      <div class="_border shadow-sm rounded-1 d-flex align-items-center"> 
        <!-- Sip, prioridad -->
        <div class="small text-center border-end p-1 px-2 bg-light" style="max-width: 62px;">
          <!-- Pin -->
          <button class="btn btn-light btn-sm a-little-small" @click="managePinned( request )">
            <i class="bi bi-pin-angle"></i>
          </button>
          <span class="a-little-small fw-bold">Prioridad:</span><br>
          <span class="a-little-small fw-bold text-decoration-underline fst-italic" x-text="$data.sum( request )"></span>
        </div>
        <!-- Contenedor -->
        <label :for="`request-${id}`" class="p-3 d-block request-item overflow-hidden h-100 flex-grow-1" style="z-index: 1; cursor: pointer;" :id="`rc-${id}`"> <!-- rc: request container -->
        <p class="a-little-small fst-italic m-0 overflow-hidden d-block" style="height: 35px;" x-text="request.subject"></p>
        <span class="a-little-small fst-italic m-0 float-end d-block border-top border-secondary mt-2 px-1">
          Creado el <span class="fw-bold" x-text="getDate( request.created_at )"></span>
          </span> 
        </label>
      </div>
    </li>
  </template>  
</ul>

<!-- 
  Cargar Más
-->
<div class="p-4" x-data="loadMoreRequests">
  <template x-if="showButton()">
    <button class="btn btn-outline-dark btn-sm d-block m-auto" @click="loadMore()">Cargar m&aacute;s</button>
  </template>

  <template x-if="! showButton()">
    <p class="text-center text-muted fst-italic small"> Ya no hay m&aacute;s por cargar... </p>
  </template>
</div>