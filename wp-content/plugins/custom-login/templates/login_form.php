<div class="login-form-container">

    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
            printf(
                __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'personalize-login' ),
                get_bloginfo( 'name' )
            );
            ?>
        </p>
    <?php endif; ?>

    <form method="post" action="<?php echo wp_login_url(); ?>">
        <p class="login-username">
            <label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
            <input type="text" name="log" id="user_login">
        </p>
        <p class="login-password">
            <label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
            <input type="password" name="pwd" id="user_pass">
        </p>
        <p class="login-submit">
            <input type="submit" value="<?php _e( 'Sign In', 'personalize-login' ); ?>">
        </p>
    </form>
</div>