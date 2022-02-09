<?php
/* @var array $params */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
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
<!-- Overviews -->
<section id="overviews" style="min-height: 100vh" class="section chief night films">
    <div class="heading">
        Результаты поиска
    </div>
    <?php
    if (isset($params['rowCount']) && $params['rowCount'] == 0) {
        echo '<div class=\"heading\">' . $params['message'] . '></div>';
    }
    foreach ($params as $search_overview) { ?>
        <!-- Обзор -->
        <div data-year="<?=$search_overview['card_year']?>" data-genre='<?=$search_overview['card_genre']?>'
             class="movie-card"
             data-id="<?= $search_overview['id_overviews'] ?>" data-country="<?=$search_overview['card_country']?>">
            <img class="movie-card__image" src="<?=$search_overview['card_poster']?>">
            <div class="movie-card__name">
                <?=$search_overview['card_name']?>
            </div>

            <div class="movie-card__year">
                <?=$search_overview['card_year_and_genre']?>
            </div>

            <div class="movie-card__overview">
                <?=$search_overview['name_overviews']?>
            </div>
            <a data-id="<?=$search_overview['id_overviews']?>" data-bs-toggle="modal" data-bs-target="#detailModal"
               class="movie-card__detail-button">
                Подробнее
                <i class="bx bx-play"></i>
            </a>
        </div>
        <?php
    } ?>
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
</body>
</html>
