<?php
/**
 * WeTuts
 *
 * @package           WeTuts
 * @author            Arafat Jamil
 * @copyright         2022 Arafat Jamil
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WeTuts
 * Plugin URI:        https://themeforest.net/user/themexplosion
 * Description:       Learning WordPress Plugin Development
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Arafat Jamil
 * Author URI:        https://github.com/arafatjamil01
 * Text Domain:       wetuts
 * License:           GPL v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://themexplosion.com/update/wetuts
 */

/*
WeTuts is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WeTuts is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WeTuts. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/includes/admin/profile.php';
}

/**
 * Print SEO Keywords in the header
 *
 * @return void
 */
function wetuts_seo_tags() {
	?>
        <meta name="description" content="Arafat Jamil | Web Application Developer">
        <meta name="keywords" content="php, ajax, jQuery, php5, php7, wordPress">
    	<?php
}

add_action( 'wp_head', 'wetuts_seo_tags', 10 );

/**
 * Show items in the footer area
 *
 * @return void
 */
function wetuts_wp_footer() {
	echo '<h3>Jamil\'s text in the footer in the footer</h3>';
}

add_action( 'wp_footer', 'wetuts_wp_footer', 111 );

/**
 * Add author bio below the post content
 *
 * @return string
 */
function wetuts_author_bio( $contnet ) {
	global $post;

	$author = get_user_by( 'id', $post->post_author );

	$bio      = get_user_meta( $author->ID, 'description', true );
	$twitter  = get_user_meta( $author->ID, 'twitter', true );
	$facebook = get_user_meta( $author->ID, 'facebook', true );
	$linkedin = get_user_meta( $author->ID, 'linkedin', true );

	ob_start();
	?>
        <div class="wetuts-bio-wrap">
            <div class="avatar-image">
    			<?php echo get_avatar( $author->ID, 64 ) ?>
            </div>

            <div class="wetuts-bio-content">
    			<?php echo wpautop( wp_kses_post( $bio ) ); ?>
            </div>

            <ul class="wetuts-socials">
    			<?php if ( $twitter ): ?>
                    <li><a href="<?php echo esc_url( $twitter ); ?>"><?php _e( 'Twitter', 'wetuts' ) ?></a></li>
    			<?php endif; ?>

    			<?php if ( $facebook ): ?>
                    <li><a href="<?php echo esc_url( $facebook ); ?>"><?php _e( 'Facebook', 'wetuts' ) ?></a></li>
    			<?php endif; ?>

    			<?php if ( $linkedin ): ?>
                    <li><a href="<?php echo esc_url( $linkedin ); ?>"><?php _e( 'Linkedin', 'wetuts' ) ?></a></li>
    			<?php endif; ?>
            </ul>
        </div>
    	<?php
	$bio_content = ob_get_clean();

	return $contnet . $bio_content;
}

add_filter( 'the_content', 'wetuts_author_bio' );

function wetuts_enqueue_scripts() {
	wp_enqueue_style( 'wetuts-style', plugins_url( 'assets/css/style.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'wetuts_enqueue_scripts' );

function wetuts_page_content_top( $a, $b ) {
	var_dump( $a, $b );
	echo '<h1> Jamil\'s top contents </h1>';
}

add_action( 'wetuts_page_content_top', 'wetuts_page_content_top', 10, 2 );

function wetuts_page_content_bottom( $a, $b ) {
	var_dump( $a );
	var_dump( $b );

	echo '<h1> Jamil\'s bottom contents </h1>';
}

add_action( 'wetuts_page_content_bottom', 'wetuts_page_content_bottom', 10, 2 );

function wetuts_welcome_message( $message, $a, $b ) {

	return 'Changed message from AJ  ' . $a . $b;
}

add_filter( 'wetuts_welcome_message', 'wetuts_welcome_message', 10, 3 );

/**
 * Shortcod API, writing shortcodes
 *
 * By Default 3 attributes are passed in a shortcode
 */
function wetuts_contact_form( $atts, $content ) {
	$atts = shortcode_atts( array(
		'email'  => get_option( 'admin_email' ),
		'submit' => __( 'Send Email', 'wetuts' ),
	), $atts );

	$submit = false;
	if ( isset( $_POST['wetuts_submit'] ) ) {
		$subject = $_POST['wetuts_subject'];
		$name    = $_POST['wetuts_name'];
		$email   = $_POST['wetuts_email'];
		$message = $_POST['wetuts_message'];

//        wp_mail($atts['email'],$subject,$message); //no sending mail as test project
		$submit = true;
	}

	ob_start();
	?>

	<?php
	if ( $submit ) {
		_e( 'Mail sent successfully', 'wetuts' );
	} ?>
    <form action="#" id="wetuts_contact" method="post">
        <p>
            <label for="name">Name</label>
            <input type="text" name="wetuts_name" id="name">
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" name="wetuts_email" id="email">
        </p>

        <p>
            <label for="subject">Subject</label>
            <input type="text" name="wetuts_subject" id="subject">
        </p>

        <p>
            <label for="message">Message</label>
            <input type="text" name="wetuts_message" id="message">
        </p>

        <p><input type="submit" name="wetuts_submit" value="<?php echo esc_attr( $atts['submit'] ); ?>"></p>
    </form>
	<?php
	return ob_get_clean();
}

add_shortcode( 'wetuts_contact', 'wetuts_contact_form' );