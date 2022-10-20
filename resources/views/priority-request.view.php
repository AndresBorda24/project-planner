<!doctype html>
<html lang="es" x-data @keyup.ctrl.up="$dispatch('show-side-bar')">
  <head>
    <title>Priorizaci&oacute;n de Solicitudes</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- css -->
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?> "/>
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/priority-request.css') ?>">

    <!-- icons -->
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/extra/icons/bootstrap-icons.css') ?>">

    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.10.3/dist/cdn.min.js"></script>

    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Js -->
    <script type="module" src="<?= \App\Helpers\Assets::load('js/priority-request.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Excels -->
    <script src="https://cdn.sheetjs.com/xlsx-0.18.9/package/dist/xlsx.full.min.js"></script>
  </head>
  <body class="bg-light">
    <!-- Loader primera carga -->
    <?php require 'partials/loader-start.php'; ?>
    <!-- Loader pequeño, principalmente para cargas menores -->
    <?php require 'partials/loader.php'; ?>

    <main class="w-100 row p-0 min-vh-100 position-relative bg-dark m-0">
      <?php require 'partials/index/sidebar.php'; ?>
      
      <!-- Contenido principal -->
      <div class="p-0 col-lg-9 sticky-lg-top bg-light">
        <div class="sticky-top px-2 px-md-4 pt-4 pb-1 bg-light">
          <button
          @click="$dispatch('show-side-bar')"
          type="button" 
          class="btn btn-secondary btn-sm position-absolute start-0 rounded-0 rounded-end d-lg-none a-little-small" data-bs-toggle="button">
            Abrir (ctrl + &uarr;)
          </button>
          <h3 class="h5 mx-5 px-3 text-center fst-italic">Priorizaci&oacute;n & Solicitudes</h3>
          
          <!-- Busqueda -->
          <?php require 'partials/priority/search-box.php' ?>

          <!-- Menu de edicion mobile -->
          <?php require 'partials/priority/menu-mobile.php' ?>
        </div>

        <div class="d-block d-md-flex">
          <!-- Menu de edicion mobilen't  -->
          <?php require 'partials/priority/menu.php' ?>
          
          <div class="p-2 overflow-auto w-100">
            <!-- Listado de peticiones -->
            <?php require 'partials/priority/requests-list.php' ?>
          </div>
        </div>
      </div>
    </main>
    <!-- Footer -->
    <?php require 'partials/index/footer.php'; ?>
    <?php require 'partials/priority/new-request.php'; ?>

    <!--  
      Componente para crear un proyecto.
    -->
    <?= \App\Components\CreateProject::load("addProject(false)") ?> 
    
    <!-- 
      Aquí se maneja la actualizacion del id del proyecto en una solicitud.
      Se hace de esta manera ya que si se pone directamente en el componente de 
      edición la solicitud se ejecuta dos veces.
    -->
    <div x-data="updateProjectInfo" @new-project-info.document.stop="setNewProjectInfo( $event.detail )"></div>

    <script>
      /* Para poder hacer el drag N' drop de las solicitudes fijas */
      const ul = document.querySelector('#pinned-requests-list');
      var s = new Sortable(ul, {
        handle: '.move-handle',
        animation: 150,
        ghostClass: 'ghost',

        /**
         * Aqui se quita el `ghost` que se genera al arrastrar un elemento.
         */
        setData: function (dataTransfer, dragEl) {
          dataTransfer.setData('Text', dragEl.textContent);
          const img = new Image();
          dataTransfer.setDragImage(img, 10, 10);
        },

        /** 
         * Despachamos un evento para que se pueda atrapar desde Alpine.
         */
        onEnd: function( event ) {
          const newPinValues  = {};

          for (let i = 1; i < event.to.children.length; i++) {
            const element = event.to.children[i];
            const requestId = element.dataset.requestId;
            const pinned = event.to.children.length -  i;
            const key = `${requestId}`;

            newPinValues[ key ] = pinned;
          }

          ul.dispatchEvent(
            new CustomEvent('pinned-moved', {
              bubbles: true,
              detail: {
                item: event.item.dataset.requestId,
                new: event.newIndex,
                newOrder: newPinValues,
                old: event.oldIndex 
              }
            })
          );
        }
      });
    </script>
<?php require 'partials/index/foot.php' ?>