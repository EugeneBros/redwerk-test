<?php
/*
Template Name: Форум
*/
?>

<?php get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php while (have_posts()) : the_post(); ?>

                    <?php the_content(); ?>

                    <?php if ( is_user_logged_in() && !is_feed() ) { ?>
                        <?php comments_template(); ?>
                    <?php } else { ?>
                        You should be <a href="<?php echo get_home_url(); ?>/member-register/">registred</a> on our website. If you are registerd, please
                        <a href="<?php echo get_home_url(); ?>/member-login/">log in</a>
                    <?php } ?>

                <?php endwhile; ?>


            </div>
        </div>
    </div>


<?php get_footer(); ?>