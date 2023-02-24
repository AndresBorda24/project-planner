<div x-data="bindRequest" @change-step.document.stop="step = $event.detail">
  <button class="btn btn-warning btn-sm d-block m-auto" @click="show = !show"> Relacionar Con </button>
  <template x-teleport="body">
    <div class="vw-100 vh-100 bg-black bg-opacity-75 flex fixed-top overflow-auto" x-show="show">
      <div class="m-auto bg-main border border-secondary d-flex flex-column" style="width: 600px; height: 600px; max-width: 90vw; max-height: 90vh;">
        <header>
          <button class="btn btn-close btn-sm float-end" @click="show = false"></button>
        </header>
        <div class="flex-fill overflow-auto">
          <?php require __DIR__ . "/bind/step1.php" ?>
          <?php require __DIR__ . "/bind/step2.php" ?>
          <?php require __DIR__ . "/bind/step3.php" ?>
          <?php // require __DIR__ . "/bind/step4.php" ?>
        </div>
        <footer class="d-flex justify-content-center gap-2 p-1">
          <div class="rounded-circle border border-dark" :class="{ 'bg-warning': step === 1 }"
            style="width: 10px; aspect-ratio: 1;"></div>
          <div class="rounded-circle border border-dark" :class="{ 'bg-warning': step === 2 }"
            style="width: 10px; aspect-ratio: 1;"></div>
          <div class="rounded-circle border border-dark" :class="{ 'bg-warning': step === 3 }"
            style="width: 10px; aspect-ratio: 1;"></div>
<!--           <div class="rounded-circle border border-dark" :class="{ 'bg-warning': step === 4 }"
            style="width: 10px; aspect-ratio: 1;"></div>
 -->        </footer>
      </div>
    </div>
  </template>
</div>
