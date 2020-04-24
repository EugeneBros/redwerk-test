<?php
/**
 * Plugin Name:       Custom Authorization Bros
 * Description:       Custom authorization.
 * Version:           10.
 * Author:            Bros
 * Text Domain:       custom-authorization-bros
 */

class Personalize_Login_Plugin {

    public function __construct() {
        add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
        add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
        add_action( 'login_form_register', array( $this, 'do_register_user' ) );
    }

    public static function plugin_activated() {
        $page_definitions = array(
            'member-login' => array(
                'title' => __( 'Sign In', 'personalize-login' ),
                'content' => '[custom-login-form]'
            ),
            'member-account' => array(
                'title' => __( 'Your Account', 'personalize-login' ),
                'content' => '[account-info]'
            ),
            'member-register' => array(
                'title' => __( 'Register', 'personalize-login' ),
                'content' => '[custom-register-form]'
            ),
        );

        foreach ( $page_definitions as $slug => $page ) {
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }

    public function render_login_form( $attributes, $content = null ) {

        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'personalize-login' );
        }

        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        return $this->get_template_html( 'login_form', $attributes );
    }

    private function get_template_html( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'personalize_login_before_' . $template_name );

        require( 'templates/' . $template_name . '.php');

        do_action( 'personalize_login_after_' . $template_name );

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function render_register_form( $attributes, $content = null ) {

        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'personalize-login' );
        } elseif ( ! get_option( 'users_can_register' ) ) {
            return __( 'Registering new users is currently not allowed.', 'personalize-login' );
        } else {
            return $this->get_template_html( 'register_form', $attributes );
        }
    }

    public function redirect_to_custom_register() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
            } else {
                wp_redirect( home_url( 'member-register' ) );
            }
            exit;
        }
    }

    private function register_user( $email, $first_name, $last_name ) {
        $errors = new WP_Error();

        if ( ! is_email( $email ) ) {
            $errors->add( 'email', $this->get_error_message( 'email' ) );
            return $errors;
        }

        if ( username_exists( $email ) || email_exists( $email ) ) {
            $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
            return $errors;
        }

        $password = wp_generate_password( 12, false );

        $user_data = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );

        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, $password );

        return $user_id;
    }

    public function do_register_user() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = home_url( 'member-register' );

            if ( ! get_option( 'users_can_register' ) ) {
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );

                $result = $this->register_user( $email, $first_name, $last_name );

                if ( is_wp_error( $result ) ) {
                    $errors = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    $redirect_url = home_url( 'member-login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }


}

$personalize_login_pages_plugin = new Personalize_Login_Plugin();

register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );