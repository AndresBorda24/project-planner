<div class="p-2 position-relative h-100" x-show="step === 2">
  <h3>Busca Un proyecto:</h3>
  <div x-data="buscarProyectoRequest" class="p-1">
    <form @submit.prevent="fetchProjects">
      <input type="text" x-model="search" class="form-control form-control-sm w-100" placeholder="Escribe y luego da enter...">
    </form>
    <ul class="list-group my-4">
      <template x-for="project in found" :key="project.id">
        <li class="list-group-item list-group-item-action p-0 small" :class="{'active': select == project.id}">
          <label :for="`req-project${project.id}`" class="d-block p-2 small" role="button">
            <input type="radio" x-model="select" :value="project.id" class="visually-hidden" :id="`req-project${project.id}`">
            <p class="m-0" x-text="project.title"></p>
          </label>
        </li>
      </template>
    </ul>
    <button class="btn btn-sm btn-outline-success float-end" @click="nextStep" :disabled="select == 0"> Siguiente </button>
  </div>
  <button class="btn btn-sm btn-warning position-absolute bottom-0" @click="$dispatch('change-step', 1)"> Volver </button>
</div>
