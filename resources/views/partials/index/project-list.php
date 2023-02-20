<!-- Listado de proyectos -->
<div 
x-data="projectList" 
@refresh-projects.window.stop="refreshProjects()" 
@new-projects.window="newProjectsFromPag($event.detail)"
class="d-sm-grid gap-3 py-3 bg-main grid-projects p-1 p-sm-2 p-md-3 p-lg-4 flex-fill overflow-auto"
id="project-list"
>
  <template x-for="p in projects" :key="p.id">
    <div class="project-item p-3 shadow-sm user-select-none overflow-hidden" 
    :class="bindClass(p.due_date, p.status)"
    role="button" 
    x-data="project" 
    @mouseover="collapseShow()"
    @mouseleave="collapseHide()"
    @dblclick="open(p)"
    >
      <h3 class="h6 fst-italic fw-bold" x-text="p.title"></h3>
      <p x-show="collapse" x-collapse.duration.150ms.min.21px x-text="descHandler(p.description)" class="small mb-2"></p>
      <ul class="small text-muted mb-1">
        <li class="text-black">Prioridad: <span x-text="priHandler(p.priority)" :class="priColor(p.priority)"></span></li>
        <li class="text-black">Estado: <span x-text="stsHandler(p.status)" :class="p.status == 'finished' ? 'text-danger' : 'text-muted'"></span></li>
        <li class="text-black">Avance: <span x-text="proHandler(p.progress)" class="text-muted"></span></li>
        <li class="text-black">Fecha Creaci&oacute;n: <span x-text="manageDueDate(p.created_at)" class="text-muted"></span></li>
        <li class="text-black">Entrega Est.: <span x-text="manageDueDate(p.due_date)" class="text-muted"></span></li>
      </ul>
    </div>
  </template>
</div>
