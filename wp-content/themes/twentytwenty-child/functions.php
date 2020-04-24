<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

function theme_scripts() {

//  Adding styles
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/app/libs/bootstrap/bootstrap-grid.min.css' );

//  Adding scripts
//    wp_enqueue_script( 'libs-scripts', get_stylesheet_directory_uri() . '/src/js/libs.min.js', array(), '1.0.0', true );
}

function mytheme_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case '' :
            ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>">
                <div id="comment_block">
                    <div class="comment-author vcard">
                        <?php echo get_avatar( $comment->comment_author_email, $args['avatar_size']); ?>
                        <?php printf(__('<cite class="fn">%s</cite> <span class="says"></span>'), get_comment_author_link()) ?>
                        <div class="comment-meta commentmetadata">
                            <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('Изменить '),'&nbsp;&nbsp;','');  delete_comment_link(get_comment_ID());?>
                            <div class="coll_comm">Комментариев: <?php commentCount(); ?></div>
                        </div>
                    </div>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <div class="comment-awaiting-verification"><?php _e('Ваш комментарий ожидает проверки модератором .') ?></div>
                        <br>
                    <?php endif; ?><div class="comment_text">
                        <?php comment_text() ?>
                    </div>
                    <div class="reply">
                        <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <?php
            break;
        case 'pingback'  :
        case 'trackback' :
            ?>
            <li class="post pingback">
            <?php comment_author_link(); ?>
            <?php edit_comment_link( __( 'Редактировать' ), ' ' ); ?>
            <?php
            break;
    endswitch;
}

//КОЛИЧЕСТВО КОМЕНТАРИЕВ
function commentCount(){
    global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE comment_author_email = "' . get_comment_author_email() . '"');
    echo $count . '';
}
// КНОПКИ СПАМ И УДАЛИТЬ
function delete_comment_link($id) {
    if (current_user_can('edit_post')) {
        echo '| <a href="'.admin_url("comment.php?action=cdc&c=$id").'">Удалить</a> ';
        echo '| <a href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id").'"> Спам </a>';
    }
}