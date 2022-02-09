<?php
/* @var array $params */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Добавление обзор</title>
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Slider Film -->
    <link rel="stylesheet" href="css/lightslider.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Chief Slider -->
    <link rel="stylesheet" href="css/chief-slider.min.css">
    <!-- My Style -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/movie-page.css">
    <!-- Font Montserrat -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
          rel="stylesheet">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
</head>
<body>
<?php
require_once 'home_templates/modals.php';
require_once 'home_templates/header.php';
?>
<section class="section chief night">
    <div style="text-align: center" class="heading">Добавление обзора</div>
    <div class="container">
        <div class="row">
            <form id="add-movie__form">
                <div class="offset-2 col-8 offset-2">
                    <img id="outImage_1">
                </div>
                <div class="offset-3 col-6 offset-3">
                    <div class="input-group mb-3">
                        <input type="file" accept="image/png, image/jpeg" class="form-control" id="picField_1">
                        <label class="input-group-text" for="picField_1">Альбомный постер</label>
                    </div>
                </div>
                <div class="offset-4 col-4 offset-4">
                    <img id="outImage_2">
                </div>
                <div class="offset-3 col-6 offset-3">
                    <div class="input-group mb-3">
                        <input type="file" accept="image/png, image/jpeg" class="form-control" id="picField_2">
                        <label class="input-group-text" for="picField_2">Постер</label>
                    </div>
                    <div class="mb-3">
                        <label for="add-movie__name_movie" class="form-label">Название фильма</label>
                        <input type="text" list="nameMoviesList" class="form-control" id="add-movie__name_movie">
                        <datalist id="nameMoviesList">
                            <?php foreach ($params['films'] as $film) {?>
                            <option value="<?=$film?>"></option>
                            <?php } ?>
                        </datalist>
                        <label for="add-movie__producer_movie" class="form-label">Имя режиссера</label>
                        <input type="text" list="nameProducersList" class="form-control" id="add-movie__producer_movie">
                        <datalist id="nameProducersList">
                            <?php foreach ($params['producers'] as $producer) {?>
                                <option value="<?=$producer?>"></option>
                            <?php } ?>
                        </datalist>
                        <label for="add-movie__country_movie" class="form-label">Страна выпуска</label>
                        <input type="text" list="nameCountryList" class="form-control" id="add-movie__country_movie">
                        <datalist id="nameCountryList">
                            <?php foreach ($params['countries'] as $country) {?>
                                <option value="<?=$country?>"></option>
                            <?php } ?>
                        </datalist>
                        <label for="add-movie__date_movie" class="form-label">Дата выхода</label>
                        <input type="date" class="form-control" id="add-movie__date_movie">
                        <label for="add-movie__name_overview" class="form-label">Название обзора</label>
                        <input type="text" class="form-control" id="add-movie__name_overview">
                        <label for="add-movie__trailer" class="form-label">Ссылка на трейлер</label>
                        <input type="url" class="form-control" id="add-movie__trailer">
                    </div>
                    <div class="mb-3">
                        <label for="add-movie__genre" class="form-label">Жанр</label>
                        <div class="input-group">
                            <span style="cursor: pointer; font-weight: 900" class="input-group-text"
                                  id="add-movie__plus-button">+</span>
                            <input type="text" list="nameGenreList" class="form-control" aria-describedby="add-movie__plus-button"
                                   id="add-movie__genre">
                            <datalist id="nameGenreList">
                                <?php foreach ($params['genres'] as $genre) {?>
                                    <option value="<?=$genre?>"></option>
                                <?php } ?>
                            </datalist>
                        </div>
                        <div id="add-movie__text" class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="add-movie__textarea" class="form-label">Текст обзора</label>
                        <textarea style="min-height: 200px" class="form-control" id="add-movie__textarea"></textarea>
                    </div>
                    <div class="mb-3">
                        <label id="add-info-error" class="form-label"></label>
                    </div>
                </div>
                <div class="offset-5 col-2 offset-5">
                    <input type="submit" class="btn btn-dark mb-3" value="Добавить">
                </div>
            </form>
        </div>
    </div>
</section>
<?php
require_once 'home_templates/footer.php';
?>
<!-- JQuery -->
<script type="text/javascript" src="js/JQuery3.3.1.js"></script>
<!-- OwlCarousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Chief Slider -->
<script type="text/javascript" src="js/chief-slider.min.js"></script>
<!-- LightSlider -->
<script type="text/javascript" src="js/lightslider.js"></script>
<!-- Bootstrap -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- My Script -->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/add.js"></script>
</body>
</html>
