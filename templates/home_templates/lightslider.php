<!-- Lightslider Overviews -->
<section id="lightslider" class="section night">
    <div class="heading">
        Фильмы 2021 года
    </div>
    <ul id="autoWidth" class="cS-hidden">
        <?php
        foreach ($params['showcase_boxes'] as $showcase_box) {?>
            <li class="item">
                <!-- Обзор -->
                <div class="showcase-box" data-id="<?=$showcase_box['id_overviews']?>">
                    <img class="movie-card__image" src="<?=$showcase_box['card_poster']?>">
                    <div class="movie-card__name">
                        <?=$showcase_box['card_name']?>
                    </div>

                    <div class="movie-card__year">
                        <?=$showcase_box['card_year_and_genre']?>
                    </div>
                    <a data-bs-toggle="modal" data-bs-target="#detailModal"
                       data-id="<?=$showcase_box['id_overviews']?>" class="movie-detail-button">
                        <i class='bx bx-play'></i>
                    </a>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
</section>