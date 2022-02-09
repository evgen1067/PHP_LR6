<?php
/* @var array $params */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <meta charset="UTF-8">
    <title>Profile</title>
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
    <link rel="stylesheet" href="css/profile.css">
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
<section id="overviews" style="min-height: 100vh" class="section chief night films">
    <div class="container" style="box-shadow: none">
        <div class="row">
            <!-- Single Advisor-->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="single_advisor_profile wow fadeInUp" data-wow-delay="0.3s"
                     style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                    <!-- Team Thumb-->
                    <div class="advisor_thumb"><img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="">
                        <!-- Social Info-->
                        <div class="social-info"><a href="mailto:<?php
                            $params['user']['email'] ?>"><i class="fa fa-envelope"></i></a><a href="tel:<?php
                            $params['user']['phone'] ?>"><i class="fa fa-phone"></i></a></div>
                    </div>
                    <!-- Team Details-->
                    <div class="single_advisor_details_info">
                        <h6><?= $params['user']['name_users'] ?></h6>
                        <p class="designation"><?=$params['user']['role']?></p>
                        <p><?= $params['user']['email'] ?></p>
                        <p><?= $params['user']['phone'] ?></p>
                        <p>Прошел авторизацию <?=$params['user']['date']?> </p>
                        <?php if ($params['user']['id_roles'] == '1') {
                                echo '<p>Добавил обзоров: ' . $params['numberAddedOverviews'] . '</p>';
                            }?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-9"
                 style="display: flex; flex-wrap: wrap; justify-content: flex-start; align-items: flex-start; align-content: flex-start">
                <?php if ($params['user']['id_roles'] == '1') {
                                echo '<div class="heading">Мои обзоры (' . $params['numberAddedOverviews'] . '):</div>';
                            }

                foreach ($params['movie_cards'] as $movie_card) {?>
                <!-- Обзор -->
                <div data-year="<?=$movie_card['card_year']?>" data-genre='<?=$movie_card['card_genre']?>' class="movie-card"
                     data-id="<?=$movie_card['id_overviews']?>" data-country="<?=$movie_card['card_country']?>">
                    <img class="movie-card__image" src="<?=$movie_card['card_poster']?>">
                    <div class="movie-card__name">
                        <?=$movie_card['card_name']?>
                    </div>

                    <div class="movie-card__year">
                        <?=$movie_card['card_year_and_genre']?>
                    </div>
                    <div class="movie-card__overview">
                        <?=$movie_card['name_overviews']?>
                    </div>
                    <a data-id="<?=$movie_card['id_overviews']?>" data-bs-toggle="modal" data-bs-target="#detailModal"
                       class="movie-card__detail-button">
                        Подробнее
                        <i class="bx bx-play"></i>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
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
</body>
</html>
