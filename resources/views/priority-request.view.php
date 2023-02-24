<!doctype html>
<html lang="es">
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
    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.10.3/dist/cdn.min.js"></script>
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

    <div class="w-100 d-flex p-0 vh-100 position-relative bg-dark m-0">
      <!-- Sidebar -->
      <?php require 'partials/sidebar.php'; ?>
      <main class="d-flex flex-column flex-fill bg-light overflow-auto vh-100">
        <div class="border-bottom shadw-sm p-3 py-2 row row-cols-12 row-cols-md-6 align-items-center g-0">
          <h1 class="m-0 h5 text-center flex-fill fst-italic">Priorizaci&oacute;n & Solicitudes</h1>
          <!-- Busqueda -->
          <?php require __DIR__ . '/partials/priority/search-box.php' ?>
        </div>
        <div class="p-2 pt-3 p-md-4 overflow-auto flex-fill bg-main">
          <!-- Listado de peticiones -->
          <?php require __DIR__ . '/partials/priority/requests-list.php' ?>
        </div>
        <!-- Menu de edicion mobilen't  -->
        <?php require __DIR__ . '/partials/priority/menu.php' ?>
        <?php require __DIR__ . '/partials/index/footer.php'; ?>
      </main>
    </div>
    <?php require 'partials/priority/new-request.php'; ?>

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
            const element   = event.to.children[i];
            const requestId = element.dataset.requestId;
            const pinned    = event.to.children.length -  i;
            const key       = `${requestId}`;

            newPinValues[ key ] = pinned;
          }

          ul.dispatchEvent(
            new CustomEvent('pinned-moved', {
              bubbles: true,
              detail: {
                new: event.newIndex,
                old: event.oldIndex,
                item: event.item.dataset.requestId,
                newOrder: newPinValues,
              }
            })
          );
        }
      });
    </script>
<?php require 'partials/index/foot.php' ?>
