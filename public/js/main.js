$(document).ready(() => {

    //добавить обзор
    $('#button_for_add_movie').click(function () {
        location.href = '/add_page';
    });

    //профиль пользователя
    $('#header-profile-circle').click(function () {
        location.href = 'profile';
    });

    //поиск
    $('input[name="search-query"]').keydown(function (e) {
        e.preventDefault();
        if (e.keyCode === 13) {
            let search_query = ($(this).val());
            if (search_query.trim() !== '') {
                let url = (String)('search?search_query=') + (String)(search_query);
                location.href = url;
            }
        }
    });

    //поиск
    $('#button_for_search').click(function () {
        let search_query = $('input[name="search-query"]').val();
        if (search_query.trim() !== '') {
            let url = (String)('search?search_query=') + (String)(search_query);
            location.href = url;
        }
    });

    let id_users = 0, id_roles = 0;

    //проверка авторизации
    $.ajax({
        url: 'check_log',
        dataType: 'json',
        success: function (data) {
            let button = document.getElementById('button_for_add_movie');
            if (data['status']) {
                document.getElementById('header-profile-circle').style.display = 'flex';
                document.getElementById('button_for_auth').style.display = 'none';
                document.getElementById('button_for_exit').style.display = 'flex';
                document.getElementById('hello_world').style.display = 'flex';
                document.getElementById('hello_world').textContent = 'Привет, ' + data['name_users'] + '.';
                if (data['id_roles'] == 1) {
                    button.style.display = 'flex';
                }
                else {
                    button.style.display = 'none';
                }
            }
        }
    });

    //найти минимальный id обзора
    function check_end_point(min) {
        let overviews_list_items = document.querySelectorAll('.movie-card')
        min = Number(overviews_list_items[0].dataset.id);
        for (let i = 1, len = overviews_list_items.length; i < len; i++) {
            if (Number(overviews_list_items[i].dataset.id) < min) {
                min = Number(overviews_list_items[i].dataset.id);
            }
        }
        return min;
    }

    //показать еще обзоры
    $('#showmore_button').click(function () {
        let endpoint = 0;
        endpoint = check_end_point(endpoint);

        $.ajax({
            url: 'show_more',
            type: 'GET',
            dataType: 'html',
            data: {
                endpoint: endpoint
            },
            success: function (data) {
                $('#overviews').append(data);
            }
        })
    });

    //детальная страница
    $('.movie-card__detail-button').click(function (event) {

        event.preventDefault();

        let id_overviews = ($(this).attr('data-id'));

        $.ajax({
            url: 'detail_page',
            type: 'GET',
            dataType: 'html',
            data: {
                id_overviews: id_overviews
            },
            success: function (data) {
                $('#movie-detail-page').children().remove();
                $('#movie-detail-page').append(data);
            }
        })
    });

    //детальная страница
    $('.movie-detail-button').click(function (event) {

        event.preventDefault();

        let id_overviews = ($(this).attr('data-id'));

        $.ajax({
            url: 'detail_page',
            type: 'GET',
            dataType: 'html',
            data: {
                id_overviews: id_overviews
            },
            success: function (data) {
                $('#movie-detail-page').children().remove();
                $('#movie-detail-page').append(data);
            }
        });
    });

    //регистрация
    $('#reg-form').submit(function (event) {

        event.preventDefault();

        let flag = true;

        let email = $('#email').val().trim(),
            password_1 = $('input[name="password-1"]').val().trim(),
            password_2 = $('input[name="password-2"]').val().trim(),
            phone = $('input[name="phone"]').val().trim(),
            name = $('input[name="name"]').val().trim();

        if (name === '') {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Введите имя');
        }

        if (email === '') {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Введите Email');
        }

        if (phone === '') {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Введите телефон');
        }

        if (password_1 === '' || password_2 === '') {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Вы не ввели пароль');
        }

        if (password_1 !== password_2) {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Пароли не совпадают');
        }

        let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            flag = false;
            $('#reg-form-title').text('Ошибка регистрации');
            $('#reg-info-error').text('Email введен неверно');
        }

        if (flag === true) {

            let formData = new FormData();
            formData.append('email', email);
            formData.append('password-1', password_1);
            formData.append('password-2', password_2);
            formData.append('phone', phone);
            formData.append('name', name);
            $.ajax({
                url: 'sign_up',
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success(data) {
                    let button = document.getElementById('button_for_add_movie');
                    if (data['status']) {
                        $('#authModal').modal('hide');
                        $('#reg-form-title').text('Регистрация');
                        $('#reg-info-error').text('');
                        document.getElementById('header-profile-circle').style.display = 'flex';
                        document.getElementById('button_for_auth').style.display = 'none';
                        document.getElementById('button_for_exit').style.display = 'flex';
                        document.getElementById('hello_world').style.display = 'flex';
                        document.getElementById('hello_world').textContent = 'Привет, ' + data['name_users'] + '.';
                        id_users = data['id_users'];
                        id_roles = data['id_roles'];
                        if (id_roles === 1) {
                            button.style.display = 'flex';
                        }
                        else {
                            button.style.display = 'none';
                        }
                    }
                    if (!data['status']) {
                        button.style.display = 'none';
                        $('#reg-form-title').text('Ошибка регистрации');
                        $('#reg-info-error').text(data['message']);
                        document.getElementById('header-profile-circle').style.display = 'none';
                        document.getElementById('button_for_auth').style.display = 'flex';
                        document.getElementById('button_for_exit').style.display = 'none';
                    }
                }
            });
        }
    });

    //авторизация
    $('#auth-form').submit(function (event) {

        event.preventDefault();

        var flag = true;

        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test($('#au_email').val())) {
            flag = false;
            $('#auth_form-title').text('Ошибка авторизации');
            $('#auth-info-error').text('Email введен неверно');
        }

        if (flag === true) {

            let email = $('#au_email').val().trim(),
                password = $('input[name="password"]').val().trim();
            $.ajax({
                url: 'authorization',
                type: 'POST',
                dataType: 'json',
                data: {
                    au_email: email,
                    au_password: password
                },
                success(data) {
                    let button = document.getElementById('button_for_add_movie');
                    if (data['status']) {
                        $('#authModal').modal('hide');
                        $('#auth_form-title').text('Авторизация');
                        $('#auth-info-error').text('');
                        document.getElementById('header-profile-circle').style.display = 'flex';
                        document.getElementById('button_for_auth').style.display = 'none';
                        document.getElementById('button_for_exit').style.display = 'flex';
                        document.getElementById('hello_world').style.display = 'flex';
                        document.getElementById('hello_world').textContent = 'Привет, ' + data['name_users'] + '.';
                        id_users = data['id_users'];
                        id_roles = data['id_roles'];
                        if (id_roles === 1) {
                            button.style.display = 'flex';
                        }
                        else {
                            button.style.display = 'none';
                        }
                    }
                    if (!data['status']) {
                        button.style.display = 'none';
                        $('#auth_form-title').text('Ошибка авторизации');
                        $('#auth-info-error').text(data['message']);
                        document.getElementById('header-profile-circle').style.display = 'none';
                        document.getElementById('button_for_auth').style.display = 'block';
                        document.getElementById('button_for_exit').style.display = 'none';
                    }
                }
            });
        }
    });

    //выход
    $('#button_for_exit').click(function () {
        let button = document.getElementById('button_for_add_movie');
        button.style.display = 'none';
        document.getElementById('button_for_auth').style.display = 'flex';
        document.getElementById('button_for_exit').style.display = 'none';
        document.getElementById('header-profile-circle').style.display = 'none';
        document.getElementById('hello_world').style.display = 'none';
        document.getElementById('hello_world').textContent = '';
        $.ajax({
            url: 'logout',
            dataType: 'json',
            success(data) {
                if (data['status']) {
                    location.href = '/';
                }
            }
        });

    });

    //очистка полей при закрытии модалки
    $("#detailModal").on('hide.bs.modal', function () {
        $('textarea[type="text"]').val('');
    });

    //очистка полей при закрытии модалки
    $("#authModal").on('hide.bs.modal', function () {
        $('input[name="email"]').val('');
        $('input[name="password"]').val('');
        $('input[name="name"]').val('');
        $('input[name="phone"]').val('');
        $('input[name="password-1"]').val('');
        $('input[name="password-2"]').val('');
        $('#reg-form-title').text('Регистрация');
        $('#reg-info-error').text('');
        $('#auth_form-title').text('Авторизация');
        $('#auth-info-error').text('');
    });

    //смена темы
    $('.header-lightmode').click(function () {
        change_theme();
    })

    //смена темы
    function change_theme() {
        var lightmode = document.querySelector('.header-lightmode');
        var section = document.querySelectorAll('.section');
        lightmode.classList.toggle('night');
        for (var i = 0, len = section.length; i < len; i++) {
            section[i].classList.toggle('night')
        }
    }

    //работа лайтслайдера
    $('#autoWidth').lightSlider({
        autoWidth: true,
        loop: true,
        onSliderLoad: function () {
            $('#autoWidth').removeClass('cS-hidden');
        }
    });

    //стилизация и работа комбобоксов
    $('.sel').each(function () {

        $(this).children('select').css('display', 'none');

        let $current = $(this);

        $(this).find('option').each(function (i) {
            if (i == 0) {
                $current.prepend($('<div>', {
                    class: $current.attr('class').replace(/sel/g, 'sel__box')
                }));

                let placeholder = $(this).text();
                $current.prepend($('<span>', {
                    class: $current.attr('class').replace(/sel/g, 'sel__placeholder'),
                    text: placeholder,
                    'data-placeholder': placeholder
                }));

                return;
            }

            $current.children('div').append($('<span>', {
                class: $current.attr('class').replace(/sel/g, 'sel__box__options'),
                text: $(this).text()
            }));

        });

        $current.children('div').append($('<div>', {
            class: $current.attr('class').replace(/sel/g, 'force-overflow'),
        }));
    });

    //стилизация и работа комбобоксов
    $('.sel').click(function () {
        $('.sel').removeClass('active');
        $(this).toggleClass('active');
    });

    //стилизация и работа комбобоксов
    $('.sel__box__options').click(function () {
        let txt = $(this).text();
        let index = $(this).index();

        $(this).siblings('.sel__box__options').removeClass('selected');
        $(this).addClass('selected');

        let $currentSel = $(this).closest('.sel');
        $currentSel.children('.sel__placeholder').text(txt);
        $currentSel.children('select').prop('selectedIndex', index + 1);
    });

    //стилизация и работа комбобоксов
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.sel').length) {
            $('.sel').removeClass('active');
        }
    });

    const formBx = document.querySelector('.auth-container__formBx');

    //свап стиля на авторизации/регистрации
    $('#signinBtn').click(function () {
        formBx.classList.remove('active');
    });

    //свап стиля на авторизации/регистрации
    $('#signupBtn').click(function () {
        formBx.classList.add('active');
    });

    //чекбокс регистрации
    $('#checkbox_check').click(function () {
        if ($('#checkbox_check').is(':checked')) {
            document.getElementById("btn_registration").removeAttribute('disabled');
            document.getElementById("btn_registration").classList.toggle('disabled');
        }
        else {
            document.getElementById("btn_registration").disabled = "disabled";
            document.getElementById("btn_registration").classList.toggle('disabled');
        }
    });

    //фильтрация по комбо-боксам
    $('.sel').click(function (event) {

        event.preventDefault();

        let movie_cards = document.querySelectorAll('.movie-card');

        let sel_name = document.querySelector('.sel.active').children[0].dataset.placeholder;

        let sel_textContent = document.querySelector('.sel.active').children[0].textContent.trim();

        //Фильтрация по странам
        if (sel_name === 'Все страны') {
            if (sel_textContent !== 'Все страны') {
                for (let i = 0, len = movie_cards.length; i < len; i++) {
                    for (let i = 0, len = movie_cards.length; i < len; i++) {
                        if (movie_cards[i].dataset.country !== sel_textContent) {
                            movie_cards[i].style.display = 'none';
                        }
                        else {
                            movie_cards[i].style.display = 'block';
                        }
                    }
                }
            }
        }
        else {
            for (let i = 0, len = movie_cards.length; i < len; i++) {
                movie_cards[i].style.display = 'block';
            }
        }


        //Фильтрация по жанрам
        if (sel_name === 'Все жанры') {

            if (sel_textContent !== 'Все жанры') {
                for (let i = 0, len = movie_cards.length; i < len; i++) {
                    let genres = jQuery.parseJSON(movie_cards[i].dataset.genre);
                    let counter = 0;
                    for (let j = 0; leng = genres.length, j < leng; j++) {
                        if (genres[j] !== sel_textContent) {

                        }
                        else {
                            counter++;
                        }
                    }
                    if (counter === 0) {
                        movie_cards[i].style.display = 'none';
                    }
                    else {
                        movie_cards[i].style.display = 'block';
                    }
                }
            }
            else {
                for (let i = 0, len = movie_cards.length; i < len; i++) {
                    movie_cards[i].style.display = 'block';
                }
            }
        }

        //Фильтрация по годам
        if (sel_name === 'Все годы') {
            if (sel_textContent !== 'Все годы') {
                for (let i = 0, len = movie_cards.length; i < len; i++) {
                    if (movie_cards[i].dataset.year !== sel_textContent) {
                        movie_cards[i].style.display = 'none';
                    }
                    else {
                        movie_cards[i].style.display = 'block';
                    }
                }
            }
            else {
                for (let i = 0, len = movie_cards.length; i < len; i++) {
                    movie_cards[i].style.display = 'block';
                }
            }
        }

    });

    //гамбургер-меню
    $('#header__hamburger-menu').click(function () {
        var hamburger = document.querySelector('#header__hamburger-menu');
        var head_nav = document.querySelector('.header-navigation');
        var sec_chief = document.querySelector('.section.chief');
        hamburger.classList.toggle('open');
        head_nav.classList.toggle('open');
        sec_chief.classList.toggle('open');
    })

    //альбомная карусель
    $('.owl-carousel').owlCarousel({
        autoHeight: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false
            },
            700: {
                items: 2,
                nav: true,
                loop: false
            }
        }
    });
})
