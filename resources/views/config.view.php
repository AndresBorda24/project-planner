<!doctype html>
<html lang="es" x-data @keyup.ctrl.up="$dispatch('show-side-bar')">
  <head>
    <title>Configuración</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <!-- css -->
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?>"/>
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/config.css') ?>">
    
    <!-- icons -->
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/extra/icons/bootstrap-icons.css') ?>">
    
    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.10.3/dist/cdn.min.js"></script>
    
    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- Js -->
    <script type="module" src="<?= \App\Helpers\Assets::load('js/config.js') ?>"></script>

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
          <h3 class="h5 mx-5 px-3 text-center fst-italic">Configuraci&oacute;n</h3>
        </div>

        <div class="d-block d-md-flex">
          <div class="p-2 overflow-auto w-100">
            <div class="p-2">
              <h5 class="text-decoration-underline">Alcances En Gema</h5>
              <?php require "partials/config/gema-scopes.php"; ?>

              <hr class="my-4">

              <h5 class="text-decoration-underline">Estados de solicitudes</h5>
              <?php require "partials/config/status.php"; ?>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Footer -->
    <?php require 'partials/index/footer.php'; ?>
<?php require 'partials/index/foot.php' ?>