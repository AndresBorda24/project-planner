<div x-data="{ current: 1 }" class="w-100 h-100 position-relative overflow-auto">
  <div class="btn-group btn-group-sm sticky-lg-top p-1 _border shadow-sm bg-light rounded-3" style="z-index: 2;">
    <button type="button" class="btn btn-outline-success" @click="current = 1" :class="current == 1 ? 'active' : ''">Tareas</button>
    <button type="button" class="btn btn-outline-success" @click="current = 2" :class="current == 2 ? 'active' : ''">Observaciones</button>
    <button type="button" class="btn btn-outline-success" @click="current = 3" :class="current == 3 ? 'active' : ''">Adjuntos</button>
  </div>
  <div x-show="current === 1">
    <?php require 'task-list.php'; ?>
  </div>
  <div x-show="current === 2">
    <?php require 'obs-list.php'; ?>
  </div>
  <div x-show="current === 3">
    <?php require 'attachments-list.php'; ?>
  </div>
</div>