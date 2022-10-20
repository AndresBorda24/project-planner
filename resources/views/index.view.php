<?php require 'partials/index/head.php'; ?>
  <!-- Loader primera carga -->
  <?php require 'partials/loader-start.php'; ?>
  <!-- Loader pequeño, principalmente para cargas menores -->
  <?php require 'partials/loader.php'; ?>

  <main class="w-100 row p-0 min-vh-100 position-relative bg-dark m-0">
    <!-- Sidebar -->
    <?php require 'partials/index/sidebar.php'; ?>
    
    <!-- Contenido principal -->
    <div class="p-0 col-lg-9 sticky-lg-top bg-light" id="main-project-list">
      <div class="sticky-top px-5 pt-4 pb-1 bg-light">
        <button 
        x-data
        @click="$dispatch('show-side-bar')"
        type="button" 
        class="btn btn-secondary btn-sm position-absolute start-0 rounded-0 rounded-end d-lg-none" data-bs-toggle="button"
        >
          Abrir (ctrl + &uarr;)
        </button>
        <!-- Titulo  -->
        <h3 class="h5 text-center fst-italic">Listado de proyectos</h3>

        <!-- Busqueda -->
        <?php require 'partials/index/searchbox.php'; ?>

        <!-- Paginacion y filtros -->
        <div class="d-flex flex-wrap justify-content-between align-items-start">
          
          <!-- Paginacion top -->
          <?php require 'partials/index/pagintion.php'; ?>

          <!-- Add new -->
          <button class="btn btn-primary btn-sm" x-data="newProject" @click="$dispatch('open-create-modal')" id="new-project-button">
            Nuevo Proyecto
          </button>

          <!-- Filtros -->
          <?php require 'partials/index/filters.php'; ?>
        </div>
      </div>

      <!-- Aquí se listan todos los projectos -->
      <?php require 'partials/index/project-list.php'; ?>
    </div>
  </main>

  <!-- Footer -->
  <?php require 'partials/index/footer.php'; ?>
  <?= \App\Components\CreateProject::load("addProject") ?> 

<?php require 'partials/index/foot.php' ?>