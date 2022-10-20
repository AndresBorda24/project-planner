<!-- Paginacion -->
<nav aria-label="Projects Pagination" class="my-1" x-data="pagination" @render-pag.window="eventHandler($event.detail)">
    <ul class="pagination pagination-sm mb-1">
    <li :class="first ? 'disabled' : ''" class="page-item">
        <a class="page-link" href="#" @click="pagHandler(current-1)" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="visually-hidden">Previous</span>
        </a>
    </li>
    <template x-for="i in (pages)" :key="i">
        <li :class="i == current ? 'active' : ''" class="page-item">
        <a class="page-link" x-text="i" href="#" @click="i == current ? null : pagHandler(i)"></a>
        </li>             
    </template>                  
    <li :class="last ? 'disabled' : ''" class="page-item">
        <a class="page-link" href="#"  @click="pagHandler(current+1)" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="visually-hidden">Next</span>
        </a>
    </li>
    </ul>
    <span class="small text-muted mb-1">Total proyectos: <span class="small fw-bold m-0" x-text="total"></span></span>
</nav>