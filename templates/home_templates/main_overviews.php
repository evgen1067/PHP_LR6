<!-- Overviews -->
<section id="overviews" class="section films night">
    <div class="heading">
        Обзоры
    </div>

    <div class="header-films">
        <form id="film-selector">
            <div class="select-boxes">
                <div class="sel">
                    <select id="selected-genre">
                        <option value="Все жанры">Все жанры</option>
                        <option value="Все жанры">Все жанры</option>
                        <?php
                        foreach ($params['genres'] as $name_genres) {?>
                            <option value="<?=$name_genres?>">
                                <?=$name_genres?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="sel">
                    <select id="selected-year">
                        <option value="Все годы">Все годы</option>
                        <option value="Все годы">Все годы</option>
                        <?php
                        foreach ($params['years'] as $year) {?>
                            <option value="<?=$year?>">
                                <?=$year?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="sel">
                    <select id="selected-country">
                        <option value="Все страны">Все страны</option>
                        <option value="Все страны">Все страны</option>
                        <?php
                        foreach ($params['countries'] as $country) {?>
                            <option value="<?=$country?>">
                                <?=$country?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

            </div>
        </form>
    </div>
    <?php
    foreach ($params['movie_cards'] as $movie_card) {
        ?>
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
</section>

<section class="section night showmore">
    <button class="showmore_button" id="showmore_button">Показать еще
        <i class='bx bx-down-arrow-alt'></i>
    </button>
</section>