<!doctype html>
<html lang="es" x-data @keyup.ctrl.up="$dispatch('show-side-bar')" @keyup.escape="$dispatch('close-modal')">
  <head>
    <title>Project Planner - Asotrauma</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/main.css') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?> "/>

    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.10.3/dist/cdn.min.js"></script>

    <!-- icons -->
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/extra/icons/bootstrap-icons.css') ?>">

    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Excels -->
    <script src="https://cdn.sheetjs.com/xlsx-0.18.9/package/dist/xlsx.full.min.js"></script>

    <!-- Nuestro Js -->
    <script type="module" src="<?= \App\Helpers\Assets::load('js/app.js') ?>"></script>
  </head>
  <body class="bg-light">
