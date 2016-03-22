<?php

/* @begin fix */
	// TO-SOLVE: wp_redirect => 'Cannot modify header information'
	// http://wordpress.stackexchange.com/questions/189331/wp-redirect-giving-a-warning-cannot-modify-header-information-custom-plugin
	add_action('init', 'start_buffer_output');
	function start_buffer_output() {
		ob_start();
	}
/* @end fix */

/**
 * Process the profile editor form
 */
function tutsplus_process_user_profile_data() {

    if ( isset( $_POST['user_profile_nonce_field'] ) && wp_verify_nonce( $_POST['user_profile_nonce_field'], 'user_profile_nonce' ) ) {

        // Get the current user id.
        $user_id = get_current_user_id();

        // Put our data into a better looking array and sanitize it.
        $user_data = array(
            'first_name' => sanitize_text_field( $_POST['first_name'] ),
            'last_name' => sanitize_text_field( $_POST['last_name'] ),
            'user_email' => sanitize_email( $_POST['email'] ),
            'twitter_name' => sanitize_text_field( $_POST['twitter_name'] ),
            'user_pass' => $_POST['pass1'],
        );

        if ( ! empty( $user_data['user_pass'] ) ) {

            // Validate the passwords to check they are the same.
            if ( strcmp( $user_data['user_pass'], $_POST['pass2'] ) !== 0 ) {

                wp_redirect( '?password-error=true' );

                exit;
            }

        } else {

            // If the password fields are not set don't save.
            unset( $user_data['user_pass'] );
        }

        // Save the values to the post.
        foreach ( $user_data as $key => $value ) {
            $results = '';
            // http://codex.wordpress.org/Function_Reference/wp_update_user
            if ( $key == 'twitter_name' ) {
                $results = update_user_meta( $user_id, $key, $value );
                unset( $user_data['twitter_name'] );
            } elseif ( $key == 'user_pass' ) {
                wp_set_password( $user_data['user_pass'], $user_id );
                unset( $user_data['user_pass'] );
            } else {
                // Save the remaining values.
                $results = wp_update_user( array( 'ID' => $user_id, $key => $value ) );
            }
            echo ("hasta aqui!!");
            if ( ! is_wp_error( $results ) ) {
                wp_redirect( '?profile-updated=true' );
            }
        }
        wp_redirect( '?profile-updated=false' );
        exit;
    }
}

add_action( 'tutsplus_process_user_profile', 'tutsplus_process_user_profile_data' );

/**
 * Display the correct message based on the query string.
 *
 * @param string $content Post content.
 *
 * @return string Message and content.
 */
function tutsplus_display_messages( $content ) {

		if ( isset($_GET['profile-updated']) ) {

	    if ( 'true' == $_GET['profile-updated'] ) {
	        $message = __( 'Your information was successfully updated', 'sage' );
	        $message_markup = tutsplus_get_message_markup( $message, 'success' );
	    } elseif ( 'false' == $_GET['profile-updated'] ) {
	        $message = __( 'There was an error processing your request', 'sage' );
	        $message_markup = tutsplus_get_message_markup( $message, 'danger' );
	    } elseif ( 'true' == $_GET['password-error'] ) {
	        $message = __( 'The passwords you provided did not match', 'sage' );
	        $message_markup = tutsplus_get_message_markup( $message, 'danger' );
	    }

			return $message_markup . $content;
		}

		return $content;

}

add_filter( 'the_content', 'tutsplus_display_messages', 1 );

/**
 * A little helper function to generate the Bootstrap alerts markup.
 *
 * @param string $message Message to display.
 * @param string $severity Severity of message to display.
 *
 * @return string Message markup.
 */
function tutsplus_get_message_markup( $message, $severity ) {

    $output = '<div class="alert alert-' . $severity . ' alert-dismissable">';
        $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">';
            $output .= '<i class="fa fa-times-circle"></i>';
        $output .= '</button>';
        $output .= '<p class="text-center">' . $message . '</p>';
    $output .= '</div>';

    return $output;

}

/**
 * Makes pages where this function is called only
 * accessible if you are logged in.
 */
function tutsplus_private_page() {

    if ( ! is_user_logged_in() ) {
        wp_redirect( home_url('profile') );
        exit();
    }

}

/**
 * Stop subscribers from accessing the backed
 * Also turn off the admin bar for anyone but administrators.
 */
function tutsplus_lock_it_down() {
    if ( ! current_user_can('administrator') && ! is_admin() ) {
        show_admin_bar( false );
    }
    if ( current_user_can( 'subscriber' ) && is_admin() ) {
        wp_safe_redirect( 'profile' );
    }
}

add_action( 'after_setup_theme', 'tutsplus_lock_it_down' );

/**
 * Outputs some user specific navigation.
 */
function tutsplus_user_nav() {
    $user_id = get_current_user_id();
    $user_name = get_user_meta( $user_id, 'first_name', true );
    $welcome_message = __( 'Welcome', 'sage' ) . ' ' . $user_name;
    echo '<ul class="nav navbar-nav navbar-right">';
        if ( is_user_logged_in() ) {
            echo '<li>';
              echo '<a href="' . home_url( 'profile' ) . '">' . $welcome_message . '</a>';
            echo '</li>';
            echo '<li>';
              echo '<a href="' . wp_logout_url( home_url() ) . '">' . __( 'Log out', 'sage' ) . '</a>';
            echo '</li>';
        } else {
            echo '<li>';
							echo '<a href="' . wp_login_url() . '">' . __( 'Log in', 'sage' ) . '</a>';
            echo '</li>';
        }
    echo '</ul>';

}
