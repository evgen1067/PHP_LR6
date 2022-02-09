<?php
/* @var array $params */
?>
        <div class="movie-detail-page__column-1">
            <div class="column-1__poster">
                <img class="column-1__poster-image" src="<?=$params['poster']?>">
            </div>
        </div>
        <div class="movie-detail-page__column-2">
            <div class="heading">
                <?=$params['name_film']?>
            </div>
            <div class="movie-detail">
                <div class="set">
                    <label>Дата выпуска:</label>
                    <span><?=$params['date_films']?></span>
                </div>
                <div class="set">
                    <label>Имя режиссера:</label>
                    <span><?=$params['producer']?></span>
                </div>
                <div class="set">
                    <label>Страна выпуска:</label>
                    <span><?=$params['country_films']?></span>
                </div>
            </div>
            <div class="movie-detail">
                <div class="set">
                    <label>Дата добавления:</label>
                    <span><?=$params['date_added']?></span>
                </div>
                <div class="set">
                    <label>Имя автора:</label>
                    <span><?=$params['name_users']?></span>
                </div>
                <div class="set">
                    <label>Жанры:</label>
                    <?=$params['name_genres']?>
                </div>
            </div>
            <div class="heading_overviews" style="font-weight: 900; font-size: 1.2rem; color: #0D7460; margin-top: 1rem;">
                <?=$params['name_overviews']?>
            </div>
            <div class="movie-description">
                <?=$params['text_overview']?>
            </div>
            <div class="video-player">
                <iframe src="<?=$params['trailer']?>" title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
        <div class="column-3">
        <div class="heading">
            Комментарии
            <span>
                <br>Всего: <?=$params['comments_count']?>
            </span>
        </div>
        <div class="form_Bx">
        <?php
        if (!empty($_SESSION['user']['id_users'])) {
            echo '<form id="comment-form"><textarea id="comment-textarea" type="text" placeholder="Оставьте комментарий"></textarea><input' . ' data-id="' . $params['id_overviews'] . '" ' . ' id="comment-submit" type="submit" value="Отправить" class="btn-submit"></form>';
        }?>
        <div id="container-comments" class="container-comments">

        </div>

        <script type="text/javascript">

            comments_add();

            function comments_add() {
                let id_overviews = <?=$params['id_overviews']?>;
                let url = (String)('all_comments');
                $.ajax({
                    url: 'all_comments',
                    type: 'GET',
                    dataType: 'html',
                    data: {
                        id_overviews: id_overviews
                    },
                    success: function (data) {
                        $('#container-comments').children().remove();
                        $('#container-comments').append(data);
                    }
                })
            }

            $("#comment-form").on('submit', function (e) {
                e.preventDefault();

                let text_comment = $('#comment-textarea').val(),
                    id_overviews = ($('#comment-submit').attr('data-id'));
                let flag = true;
                if (text_comment)
                    if (flag === true) {
                        $.ajax({
                            url: 'add_comment',
                            type: 'POST',
                            dataType: 'html',
                            data: {
                                text_comment: text_comment,
                                id_overviews: id_overviews
                            },
                            success(data) {
                                $('#container-comments').children().remove();
                                $('#container-comments').append(data);
                            }
                        })
                    }
            });
        </script>