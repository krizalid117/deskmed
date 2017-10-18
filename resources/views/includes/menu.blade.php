<div class="side-menu-container">
    <div class="side-menu-minified">
        <ul class="side-menu-item-container">
            <li class="side-menu-collapser">
                <img class="img-menu" src="{{ URL::to('img/menu.png') }}" alt="menu">
            </li>
            <li class="side-menu-item side-menu-selected">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 32px;">
                            <img class="img-menu" src="{{ URL::to('img/home.png') }}" alt="home">
                        </td>
                        <td class="td-content-menu">
                            <div class="content-menu">Inicio</div>
                        </td>
                    </tr>
                </table>
            </li>
            <li class="side-menu-item no-cursor-li">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 32px;">
                            <img class="img-menu" src="{{ URL::to('img/search.png') }}" alt="search">
                        </td>
                        <td class="td-content-menu">
                            <div class="content-menu">
                                <span class="icon-search-menu ui-icon ui-icon-caret-1-s"></span>
                                <input class="content-menu-inp txt-search-menu" type="text" placeholder="Búsqueda">
                            </div>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
    </div>
</div>