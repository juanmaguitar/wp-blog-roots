<?php
/**
 * Template Name: User Profile
 */
?>

<?php tutsplus_private_page() ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>

	<?php

	/**
	 * Gets the user info.
	 * Returned in an object.
	 *
	 * http://codex.wordpress.org/Function_Reference/get_userdata
	 */

	$user_id = get_current_user_id();
	$user_info = get_userdata( $user_id );

	?>

	<!-- <?php var_dump($user_info); ?> -->

	<form role="form" action="" id="user_profile" method="POST">

    <?php wp_nonce_field( 'user_profile_nonce', 'user_profile_nonce_field' ); ?>

    <div class="form-group">
        <label for="first_name"><?php _e( 'First name', 'sage' ); ?></label>
        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php _e( 'First name', 'sage' ); ?>" value="<?php echo $user_info->first_name; ?>">
    </div>
    <div class="form-group">
        <label for="last_name"><?php _e( 'Last name', 'sage' ); ?></label>
        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php _e( 'Last name', 'sage' ); ?>" value="<?php echo $user_info->last_name; ?>">
    </div>
    <div class="form-group">
        <label for="twitter_name"><?php _e( 'Twitter name', 'sage' ); ?></label>
        <input type="text" class="form-control" id="twitter_name" name="twitter_name" placeholder="<?php _e( 'Twitter name', 'sage' ); ?>" value="<?php echo $user_info->twitter_name; ?>">
    </div>
    <div class="form-group">
        <label for="email"><?php _e( 'Email address', 'sage' ); ?></label>
        <input type="email" class="form-control" id="email" name="email" placeholder="<?php _e( 'Email address', 'sage' ); ?>" value="<?php echo $user_info->user_email; ?>">
    </div>
    <div class="form-group">
        <label for="pass1"><?php _e( 'Password', 'sage' ); ?></label>
        <input type="password" class="form-control" id="pass1" name="pass1" placeholder="<?php _e( 'Password', 'sage' ); ?>">
    </div>
    <div class="form-group">
        <label for="pass2"><?php _e( 'Repeat password', 'sage' ); ?></label>
        <input type="password" class="form-control" id="pass2" name="pass2" placeholder="<?php _e( 'Repeat password', 'sage' ); ?>">
    </div>
    <button type="submit" class="btn btn-default"><?php _e( 'Submit', 'sage' ); ?></button>
	</form>

<?php endwhile; ?>
<?php do_action( 'tutsplus_process_user_profile' ); ?>
