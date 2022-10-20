<!-- Filtros -->
<div x-data="filters">
  <button class="btn btn-success btn-sm" @click="switchMenu()">Filtros</button>

  <div x-show="showMenu" @click.outside="closeMenu()" x-cloak class="position-absolute right-15 mt-1 shadow list-group">
    <div class="list-group-item list-group-item-action small">
      <label for="per-page">Cantidad de projectos : </label>
      <input id="per-page" class="form-control form-control-sm" x-model="amount" type="number" step="5" min="5" max="25" value="10">
    </div>
      
    <div class="list-group-item list-group-item-action small">
      <p class="mb-1">Ordenar: </p>
      <div class="input-group-sm small">
          <label for="order-field">Por:</label>
          <select class="form-select form-select-sm" x-model="field" id="order-field">
              <option value="id">Default</option>
              <option value="title">Nombre</option>
              <option value="status">Estado</option>
              <option value="priority">Prioridad</option>
              <option value="created_at">Fecha Creacion</option>
              <option value="updated_at">Ultima Actualizacion</option>
              <option value="due_date">Fecha Entrega Est.</option>
          </select>

          <label for="order-dir">Direccion</label>
          <select class="form-select form-select-sm" x-model="dir" id="order-dir">
              <option value="asc">Ascendente</option>
              <option value="desc">Descendente</option>
          </select>
      </div>
    </div>

    <div class="list-group-item list-group-item-action small">
      <label for="by-status">Estado:</label>
      <select class="form-select form-select-sm" x-model="byStatus" id="by-status">
          <option value="0" selected>--- || ---</option>
          <option value="new">Nuevo</option>
          <option value="process">En Proceso</option>
          <option value="paused">Pausado</option>
          <option value="finished">Finalizado</option>
      </select>
    </div>

    <div class="list-group-item list-group-item-action small">
      <button class="btn btn-primary btn-sm shadow" @click="apply()">Aplicar</button>
    </div>
  </div>
</div>
