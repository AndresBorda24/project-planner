<?php require 'partials/index/head.php'; ?>
  <!-- Loader primera carga -->
  <?php require 'partials/loader-start.php'; ?>
  <!-- Loader pequeño, principalmente para cargas menores -->
  <?php require 'partials/loader.php'; ?>

  <div class="w-100 d-flex p-0 vh-100 position-relative bg-dark m-0">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php'; ?>
    <!-- Contenido principal -->
    <main class="d-flex flex-column vh-100 flex-fill bg-light" id="main-project-list">
      <div class="p-2 border-bottom">
        <!-- Titulo  -->
        <h3 class="h5 text-center fst-italic">Listado de proyectos</h3>
        <!-- Busqueda -->
        <?php require 'partials/index/searchbox.php'; ?>
        <!-- Paginacion y filtros -->
        <div class="d-flex flex-wrap justify-content-between align-items-center">
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
      <!-- Footer -->
      <?php require 'partials/index/footer.php'; ?>
    </main>
  </div>

  <?= \App\Components\CreateProject::load("addProject") ?> 

<?php require 'partials/index/foot.php' ?>
