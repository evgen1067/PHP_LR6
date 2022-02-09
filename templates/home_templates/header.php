<!-- Header -->
<header class="header">
    <div style="min-width: 117px;" class="header__logo-hamburger">
        <a href="/" class="header-logo">
            <i class="bx bx-movie-play bx-tada"></i>
            Film
        </a>
        <div id="header__hamburger-menu" class="header__hamburger-menu">
            <div class="hamburger">

            </div>
        </div>
    </div>
    <div class="header-search">
        <form class="header-search__form" id="header-search__form">
            <input id="search-query" name="search-query" type="text" placeholder="Поиск">
            <input type="submit" style="display: none">
        </form>
        <i id="button_for_search" class='bx bxs-search'></i>
    </div>
    <ul class="header-navigation">
        <li id="hello_world" style="display: none; text-align: center"></li>
        <li id="button_for_add_movie" style="display: none">
            <a>Добавить обзор</a></li>
        <li id="button_for_auth"><a data-bs-toggle="modal" data-bs-target="#authModal">Вход</a></li>
        <li id="button_for_exit" style="display: none">Выход<i class="bx bx-exit exit-account"></i></li>
    </ul>
    <div class="header_lightmode_profile">
        <div id="header-profile-circle"
             style="display: none" class="header-profile">
            <i class='bx bxs-user-circle'></i>
        </div>
        <div class="header-lightmode">
            <i class='bx bxs-moon bx-tada'></i>
            <i class='bx bxs-sun bx-tada'></i>
        </div>
    </div>
</header>