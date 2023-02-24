<div class="m-auto w-100 h-100" x-show="step === 1">
  <button class="d-block btn btn-warning rounded-0 w-100 h-50" @click="$dispatch('change-step', 2)">
    <p>Vincular a Proyecto</p>
    <small>Selecciona un proyecto y crea <span class="fw-bold text-decoration-underline">una nueva tarea</span> en base a la solicitud</small>
  </button>
  <button class="d-block btn btn-primary rounded-0 w-100 h-50" @click="changeToStep3()">
    <p>Crear Proyecto</p>
    <small>Crea <span class="fw-bold text-decoration-underline">un nuevo proyecto</span> en base a la solicitud</small>
  </button>
</div>
