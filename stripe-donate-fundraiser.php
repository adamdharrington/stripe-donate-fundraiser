<?php

/**
 * ====== Stripe Donate Fundraiser - Plugin structure adapted from Plugin Bootstrap
 * 
 * @link              http://adamharrington.eu
 * @since             1.0.0
 * @package           stripe_donate_fundraiser
 *
 * @wordpress-plugin
 * Plugin Name:       Stripe Donate Fundraiser
 * Plugin URI:        http://adamharrington.eu
 * Description:       Developed to help Uplift.ie accept donations via Stripe
 * Version:           1.0.0
 * Author:            Adam Harrington
 * Author URI:        http://adamharrington.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stripe_donate_fundraiser
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_stripe_donate_fundraiser() {

}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_stripe_donate_fundraiser() {

}

register_activation_hook( __FILE__, 'activate_stripe_donate_fundraiser' );
register_deactivation_hook( __FILE__, 'deactivate_stripe_donate_fundraiser' );


/**
 * Always check if it is a fundraiser page.
 */
function slug_fundraiser_content_filter($content) {
  if ( get_post_type() == 'fundraiser' ) {
    $obj = pods('fundraiser', get_the_id() );
    return $obj->template('Fundraiser Single').$content;
  }
  return $content;
}


if (is_admin()){
  require_once plugin_dir_path( __FILE__ ) . 'lib/admin-ui.php';
}
else{
//add_filter( 'the_content', 'slug_fundraiser_content_filter' );
  require_once plugin_dir_path( __FILE__ ) . 'lib/class-wordpress-stripe-donate-fundraiser.php';
}
