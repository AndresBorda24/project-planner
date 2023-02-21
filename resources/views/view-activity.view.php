<!doctype html>
<html lang="es" x-data @keyup.ctrl.up="$dispatch('show-side-bar')">
  <head>
    <title>Actividad Reciente</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 & css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?> "/>
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/view-activity.css') ?>">
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/extra/icons/bootstrap-icons.css') ?>">
    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Excels -->
    <script src="https://cdn.sheetjs.com/xlsx-0.18.9/package/dist/xlsx.full.min.js"></script>
    <!-- JS -->
    <script type="module" src="<?= \App\Helpers\Assets::load('js/view-activity.js') ?>"></script>
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
        <div class="p-2 border-bottom bg-light">
          <h3 class="h5 text-center fst-italic">Actividad Reciente</h3>
          <div class="d-grid gap-1 align-items-end pb-1" style="grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));"  x-data="filters">
            <div>
              <label for="log-after" class="form-label a-little-small m-0">Despues de:</label>
              <input type="date" class="form-control form-control-sm a-little-small" x-model="f.after" id="log-after">
            </div>
            <div>
              <label for="log-before" class="form-label a-little-small m-0">Antes de:</label>
              <input type="date" class="form-control form-control-sm a-little-small" x-model="f.before" id="log-before">
            </div>
            <button class="btn btn-sm a-little-small btn-dark d-block" @click="getLog()">
              Buscar<i class="bi bi-arrow-right-short"></i>
            </button>
            <template x-if="Alpine.store('log').length > 0">
              <div class="position-relative" x-data="{ showfiltros: false }">
                <button class="btn btn-sm btn-outline-primary w-100 a-little-small" @click="showfiltros = !showfiltros">Filtros</button>
                <div class="position-absolute bg-light _border p-1 end-0 mt-1 shadow-sm" style="width: 220px; z-index: 1;" x-show="showfiltros" @click.outside="showfiltros = false">
                  <div>
                    <label for="log-select-user" class="form-label a-little-small">Filtra por Usuario:</label>
                    <select class="form-select form-select-sm a-little-small" id="log-select-user" x-model="filters.author">
                      <option value="">Todos</option>
                      <template x-for="usr in Alpine.store('users')" :key="usr.consultor_id">
                        <option x-text="usr.consultor_nombre" :value="usr.consultor_id"></option>
                      </template>
                    </select>
                  </div>
                  <hr>
                  <div class="p-1">
                    <div class="form-check a-little-small">
                      <input class="form-check-input" type="checkbox" x-model="filters.types" value="project" id="log-project-type">
                      <label class="user-select-none form-check-label" for="log-project-type">Proyecto</label>
                    </div>
                    <div class="form-check a-little-small">
                      <input class="form-check-input" type="checkbox" x-model="filters.types" value="task" id="log-task-type">
                      <label class="user-select-none form-check-label" for="log-task-type">Tarea</label>
                    </div>
                    <div class="form-check a-little-small">
                      <input class="form-check-input" type="checkbox" x-model="filters.types" value="sub_task" id="log-type-sub">
                      <label class="user-select-none form-check-label" for="log-type-sub">Sub-Tarea</label>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Contenido principal -->
        <div class="p-0 bg-main flex-fill overflow-auto">
          <div id="data-table" x-data="dataTable" class="p-3 a-little-small h-100"></div>
        </div>
        <!-- Footer -->
        <?php require 'partials/index/footer.php'; ?>
      </main>
    </div>
<?php require 'partials/index/foot.php' ?>
