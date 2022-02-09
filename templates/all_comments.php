<?php
/* @var array $params */
foreach ($params as $comment) { ?>

    <div class="comment">
        <div class="head__comment" style="display: flex">
            <div class="comment-header" style="width: 90%">
                <?=$comment['name_users']?>
            </div>
            <?php
            if (isset($_SESSION['user']['id_roles']) && ($_SESSION['user']['id_roles'] == 1 || $_SESSION['user']['id_users'] == $comment['id_current_user'])) {
                echo '<i data-id="' . $comment['id_comments'] . '" class=\'bx bx-x delete_comment\'></i>';
            }
            ?>
        </div>
        <div class="comment-content">
            <?=$comment['text_comments']?>
        </div>
    </div>
    <?php
}?>
<script type="text/javascript">
    $('.bx.bx-x.delete_comment').click(function () {
        let id_comments = ($(this).attr('data-id'));
        $.ajax({
            url: 'delete_comment',
            type: 'GET',
            data: {
                id_comments: id_comments
            },
            success(data){
                $('#detailModal').modal('hide');
            }
        })
    })
</script>
