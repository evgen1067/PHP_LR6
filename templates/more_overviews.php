<?php
/* @var array $params */
foreach ($params as $movie_card) {?>
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
<script type="text/javascript">
    $('.movie-card__detail-button').click(function (event) {

        event.preventDefault();

        let url = (String)('detail_page=') + (String)(($(this).attr('data-id')));

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                $('#movie-detail-page').children().remove();
                $('#movie-detail-page').append(data);
            }
        })
    });
</script>
