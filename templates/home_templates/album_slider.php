<section class="section chief night">
    <div class="owl-carousel">
        <?php
        foreach ($params['album_posters'] as $album_poster) {
            ?>
            <img src="<?=$album_poster?>">
            <?php
        }
        ?>
    </div>
</section>