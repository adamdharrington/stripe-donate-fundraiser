<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 21/07/2015
 * Time: 15:48
 *
 * Defining Options as:
 *
 * * stripe_donate_api_string
 * * stripe_donate_security_blurb
 *
 */





// add the admin options page
add_action('admin_menu', 'sd_admin_add_page');

function sd_admin_add_page() {
  add_options_page('Stripe Donate Setup', 'Stripe Donate', 'manage_options', 'stripe-donate-options', 'stripe_donate_options_page');
}

function stripe_donate_options_page() {
  ?>
  <div>
    <h2>Stripe Donate Options</h2>
    Set up Stripe Donate.
    <form action="options.php" method="post">
      <?php settings_fields('stripe_donate_options'); ?>
      <?php do_settings_sections('stripe-donate-options'); ?>

      <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form></div>
<?php
  $options = get_option('stripe_donate_options');
}

add_action('admin_init', 'stripe_donate_admin_init');

function stripe_donate_admin_init(){
  register_setting( 'stripe_donate_options', 'stripe_donate_options', 'stripe_donate_options_validate' );
  
  add_settings_section('stripe_donate_main', 'Stripe API Settings', 'stripe_donate_section_text', 'stripe-donate-options');
  
  add_settings_field('stripe_api_key', 'Stripe API Key', 'stripe_donate_api_string', 'stripe-donate-options', 'stripe_donate_main');
  add_settings_field('stripe_api_js_key', 'Stripe JS API Publishable Key', 'stripe_donate_api_js_string', 'stripe-donate-options', 'stripe_donate_main');
  
  add_settings_section('stripe_donate_default', 'Plugin defaults', 'stripe_defaults_section_text', 'stripe-donate-options');
  
  add_settings_field('security_blurb', 'Security blurb (blue box)', 'stripe_donate_security_blurb', 'stripe-donate-options', 'stripe_donate_default');
  add_settings_field('default_donate_page', 'Choose the default donate page', 'stripe_donate_default_page', 'stripe-donate-options', 'stripe_donate_default');
}
function stripe_donate_section_text(){
  echo '<p>Configure your plugin with your test or live API keys here.</p>';
}
function stripe_defaults_section_text(){
  echo '<p>Set up some defaults for the plugin.</p>';
}

function stripe_donate_api_string() {
  $options = get_option('stripe_donate_options');
  echo "<input id='stripe_donate_api_string' name='stripe_donate_options[stripe_donate_api_string]' size='40' type='text' value='{$options['api_string']}' />";
}
function stripe_donate_api_js_string() {
  $options = get_option('stripe_donate_options');
  echo "<input id='stripe_donate_api_js_string' name='stripe_donate_options[stripe_donate_api_js_string]' size='40' type='text' value='{$options['api_js_string']}' />";
}
function stripe_donate_security_blurb() {
  $options = get_option('stripe_donate_options');
  echo "<textarea id='stripe_donate_security_blurb' name='stripe_donate_options[stripe_donate_security_blurb]' rows='3' cols='100'>{$options['stripe_donate_security_blurb']}</textarea>";
}
function stripe_donate_default_page() {
  $options = get_option('stripe_donate_options');
  $pages = new WP_Query(array('post_type' => 'fundraiser'));
  $select = "<select id='stripe_donate_default_page' name='stripe_donate_options[stripe_donate_default_page]'>";
  while($pages->have_posts()){
    $pages->the_post();
    $id = get_the_ID();
    $sel = $id == $options['stripe_donate_default_page'] ? "selected" : "";
    $select.= sprintf("<option value='%s' ".$sel.">%s</option>",
	    $id,
      get_the_title()
    );
  }
  echo $select."</select>";
}
// validate all options


function stripe_donate_options_validate($input) {
  $options = get_option('stripe_donate_options');

  $options['api_string'] = trim($input['stripe_donate_api_string']);
  $options['api_js_string'] = trim($input['stripe_donate_api_js_string']);
  $options['stripe_donate_security_blurb'] = trim($input['stripe_donate_security_blurb']);
  $options['stripe_donate_default_page'] = trim($input['stripe_donate_default_page']);

  return $options;
}