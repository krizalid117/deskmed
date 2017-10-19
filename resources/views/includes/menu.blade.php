<div class="side-menu-container">
    <div class="side-menu-minified">
        <ul class="side-menu-item-container">
            <li class="side-menu-collapser">
                <img class="img-menu" src="{{ URL::to('img/menu.png') }}" alt="menu" aria-label="toggle-menu">
            </li>
            <li class="side-menu-item side-menu-selected">
                <img class="img-menu" src="{{ URL::to('img/home.png') }}" alt="home" aria-hidden="true">
                <div class="content-menu menu-pad-helper">Inicio</div>
            </li>
            <li class="side-menu-item no-cursor-li">
                <img class="img-menu" src="{{ URL::to('img/search.png') }}" alt="search" aria-hidden="true">
                <div class="content-menu">
                    <span class="icon-search-menu ui-icon ui-icon-caret-1-s"></span>
                    <input class="content-menu-inp txt-search-menu" type="text" placeholder="Búsqueda">
                </div>
            </li>
        </ul>
    </div>
</div>