$(document).ready(() => {

    let poster_1 = 1, poster_2 = 1, j = 0, genres = [];
    let image_flag_1 = false, image_flag_2 = false;

    document.getElementById('picField_1').onchange = function (evt) {
        image_flag_1 = true;
        let tgt = evt.target || window.event.srcElement,
            files = tgt.files;

        if (FileReader && files && files.length) {
            let fr = new FileReader();
            fr.onload = function () {
                document.getElementById('outImage_1').src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }
    }

    document.getElementById('picField_2').onchange = function (evt) {
        image_flag_2 = true;
        let tgt = evt.target || window.event.srcElement,
            files = tgt.files;

        if (FileReader && files && files.length) {
            let fr = new FileReader();
            fr.onload = function () {
                document.getElementById('outImage_2').src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }

    }

    $('#picField_1').change(function (e) {
        poster_1 = e.target.files[0];
    })

    $('#picField_2').change(function (e) {
        poster_2 = e.target.files[0];
    })

    //добавить обзор
    $('#add-movie__form').submit(function (event) {
        event.preventDefault();
        let flag = true;
        let overview_name = $('#add-movie__name_overview').val(),
            trailer = $('#add-movie__trailer').val(),
            genre = $('#add-movie__genre').val(),
            text_movie = $('#add-movie__textarea').val(),
            date_movie = $('#add-movie__date_movie').val(),
            country_movie = $('#add-movie__country_movie').val(),
            producer_movie = $('#add-movie__producer_movie').val(),
            name_movie = $('#add-movie__name_movie').val();
        if (text_movie.trim() === '') {
            $('#add-info-error').text('Введите текст обзора');
            flag = false;
        }
        if (genre.trim() === '') {
            if (genres.length === 0) {
                $('#add-info-error').text('Введите жанр');
                flag = false;
            }
        }
        else {
            genres[j] = genre;
            j++;
        }
        if (trailer.trim() === '') {
            $('#add-info-error').text('Введите ссылку на трейлер');
            flag = false;
        }
        if (overview_name.trim() === '') {
            $('#add-info-error').text('Введите название обзора');
            flag = false;
        }
        if (date_movie.trim() === '') {
            $('#add-info-error').text('Введите дату выхода фильма');
            flag = false;
        }
        if (country_movie.trim() === '') {
            $('#add-info-error').text('Введите страну');
            flag = false;
        }
        if (producer_movie.trim() === '') {
            $('#add-info-error').text('Введите имя режиссера');
            flag = false;
        }
        if (name_movie.trim() === '') {
            $('#add-info-error').text('Введите название фильма');
            flag = false;
        }
        if (image_flag_2 === false) {
            $('#add-info-error').text('Добавьте постер');
            flag = false;
        }
        if (image_flag_1 === false) {
            $('#add-info-error').text('Добавьте альбомный постер');
            flag = false;
        }
        let formData = new FormData();
        formData.append('name_movie', name_movie);
        formData.append('date_movie', date_movie);
        formData.append('country_movie', country_movie);
        formData.append('producer_movie', producer_movie);
        formData.append('overview_name', overview_name);
        formData.append('trailer', trailer);
        formData.append('genres', (JSON.stringify(genres)));
        formData.append('text_movie', text_movie);
        formData.append('poster', poster_1);
        formData.append('album_poster', poster_2);
        if (flag === true) {
            $.ajax({
                url: 'add_overview',
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success(data) {
                    if (data['status']) {
                        location.href = '/';
                    }
                    else {
                        $('#add-info-error').text(data['message']);
                    }
                }
            });
        }
    });

    //добавление жанров
    $('#add-movie__plus-button').click(function () {
        let genre_name = $('#add-movie__genre').val();
        if (genre_name.trim() !== '') {
            genres[j] = genre_name;
            j++;
            add_genres_text();
        }
    });

    // сейв добавленных жанров
    function add_genres_text() {
        let text = '';
        for (let i = 0; i < genres.length; i++) {
            if (i !== (genres.length - 1)) {
                text += genres[i] + ', ';
            }
            else {
                text += genres[i] + '.';
            }
        }
        $('#add-movie__text').text(text);
        $('#add-movie__genre').val('');
    }
});

