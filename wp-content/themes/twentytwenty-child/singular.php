<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header(); ?>

    <main id="site-content" role="main">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            the_content();
                            if (is_user_logged_in() && !is_feed()) { ?>
                                <?php comments_template(); ?>
                            <?php } else { ?>
                                You should be
                                <a href="<?php echo get_home_url(); ?>/member-register/">registred</a> on our website. If you are registerd, please
                                <a href="<?php echo get_home_url(); ?>/member-login/">log in</a>
                            <?php }
                        }
                    } ?>
                </div>
            </div>
        </div>

    </main>

<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php get_footer(); ?>