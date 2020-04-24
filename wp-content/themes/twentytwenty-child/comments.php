<?php // DO NOT DELETE THESE LINES
if (post_password_required()) {
    echo '<p class="nocomments">Эта запись защищена паролем. Введите пароль чтобы увидеть комментарии.</p>';
    return;
}
$oddcomment = "graybox";
?>
<?php if ($comments) : ?>
    <h4 class="comments"><?php comments_number('Комментарии  %' );?></h4>
    <?php $args = array(
        'avatar_size'       => 70,
        'reply_text'       => 'Ответить',
        'callback'          => 'mytheme_comment',
    );
    ?>


    <ul class="comments-list"><?php wp_list_comments($args); ?></ul>
    <div id="comment-nav-above">
        <?php paginate_comments_links() ?>
    </div>
<?php else:?>
    <?php if (comments_open()) : ?>
    <?php elseif (!is_page()) : // COMMENTS ARE CLOSED ?>
        <h4>Комментарии запрещены.</h4>
    <?php endif; ?>
<?php endif; ?>
<?php if (comments_open()) : ?>
    <?php $comments_args = array(

        'comment_notes_after' => '',

    ); ?>
    <?php
    $args = array(
        'comment_notes_before' => '<p class="comment-notes"><a id="reg" href="/wp-login.php">Войдите</a> или заполните поля ниже. Ваш e-mail не будет опубликован. Обязательные поля помечены *</p>',
        'comment_field'        => '<p class="comment-form-comment "><label for="comment" >' . _x( 'Comment', 'noun' ) . '</label><br /> <textarea id="comment" name="comment" rows="8"  aria-required="true"></textarea></p>',
        'comment_notes_after'  => '',
        'id_submit'            => '',
        'label_submit'         => __( 'Отправить' ),
    );
    comment_form( $args );
    ?>
<?php endif; ?>