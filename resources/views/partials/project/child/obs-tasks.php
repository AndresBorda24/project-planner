<!-- 
  Aqui también se listan las observaciones y las subtareas pero exclusivamente de 
  la tarea que se esta editando. 
-->
<div class="mt-3 mt-lg-0" x-data="{ current_: 1 }" id="sub-tasks-and-obs" x-show="!isNew()" x-effect="current_ = (child.type == 'sub_task') ? 2 : 1;">
  <div class="btn-group btn-group-sm sticky-lg-top p-1 _border shadow-sm main-bg rounded-3" style="z-index: 2;">
    <button 
    type="button" class="btn" x-show="child.type != 'sub_task'" @click="current_ = 1" 
    :class="(child.type == 'task' ? 'btn-outline-warning ' : 'btn-outline-primary ') + (current_ == 1 ? 'active' : '')">Subtareas</button>
    <button 
    type="button" class="btn" @click="current_ = 2" 
    :class="(child.type == 'task' ? 'btn-outline-warning ' : 'btn-outline-primary ') + (current_ == 2 ? 'active' : '')">Observaciones</button>
  </div>
  <!-- Observaciones y/o listado de subtareas -->
  <div class="p-3 pt-1">
    <!-------------------- Tasks -------------------->
    <?php require __DIR__ . '/tasks.php'; ?>

    <!-------------------- Obs -------------------->
    <?php require __DIR__ . '/obs.php'; ?>
  </div>
</div>
