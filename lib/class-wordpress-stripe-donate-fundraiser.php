<?php
include_once plugin_dir_path( __FILE__ ) . 'class-stripe-donate-fundraiser.php';

class WP_Stripe_Donate_Fundraiser extends Stripe_Donate_Fundraiser {

	public function __construct($post_id) {
    $plugin_options = get_option('stripe_donate_options');
    $options = array(
      'page_status'     => self::page_check(),
      'form_title'      => get_the_title($post_id),
      'contributed'     => get_post_meta($post_id, 'total_donated', true) ?: 0,
      'target'          => get_post_meta($post_id, 'donate_target', 1) ?: 1000,
      'amounts'         => self::get_amounts($post_id),
      'plugin_options'  => get_option('stripe_donate_options'),
      'generic'         => get_post_meta($post_id, 'is_generic', 1),
      'thank_you'       => get_post_meta($post_id, 'thank_you_message', 1),
      'nxt_page'        => get_post_meta($post_id, 'redirect_url', 1),
      'chip_in_message' => get_post_meta($post_id, 'chip_in_message', 1) ?: "Contribute to this cause",
      'chip_in_term'    => get_post_meta($post_id, 'chip_in_term',1) ?: "Chip in",
      'fundraiser_id'    => get_post_meta($post_id, 'fundraiser_id',1) ?: "43473",
      'security_blurb'  => $plugin_options['stripe_donate_security_blurb'],
      'api_string'      => $plugin_options['api_string'],
      'api_js_string'   => $plugin_options['api_js_string']
    );
    parent::set_defaults($options);
		self::enqueue_all();
		parent::prepare();
	}


  private function update_total(){
    if ($this->page_status == 'success' && isset($_POST)){
      $a = $this->contributed;
      $a = $a + round($_POST['amount'] / 100);
      update_post_meta(get_the_ID(), 'total_donated', $a);
      $this->contributed = $a;
    }
  }

  public function on_success(){
    $this->update_total();
  }

	private function get_amounts($id){
    $is = array(1,2,3,4,5,6);
    $amounts = [];
    foreach ($is as $i){
      $amounts['donation_amount_'.$i] = get_post_meta($id, 'contribute_amount_'.$i)[0];
    }
    return $amounts;
  }

	
	public function get_form () {
    parent::get_form();
  }

  /*
  *   =================================     UI Functions
  */

  private function enqueue_all(){
    wp_enqueue_script(
      "vendor-script",
      plugin_dir_url( dirname( __FILE__ ) ) . 'js/vendor.js',
      array('jquery'),
      "v1.0.0"
    );
    wp_enqueue_script(
      "stripe-donate",
      plugin_dir_url( dirname( __FILE__ ) ) . 'js/stripeDonate.js',
      array('jquery', 'stripe'),
      "v1.0.2"
    );
    wp_enqueue_script(
      "jquery-validate",
      "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js",
      array('jquery')
    );
    wp_enqueue_script(
      "jquery-payment",
      "https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.3.2/jquery.payment.min.js",
      array('jquery')
    );
    wp_enqueue_script(
      "stripe",
      "https://js.stripe.com/v2/"
    );
    wp_enqueue_script(
      "setup",
      plugin_dir_url( dirname( __FILE__ ) ) . 'js/main.js',
      array('jquery', "stripe-donate"),
      "v1.0.2"
    );
    wp_enqueue_style(
      'stripe-donate-style',
      plugin_dir_url( dirname( __FILE__ ) ) . 'css/stripe-donate.css',
      array('bootstrap'),
      "v1.0.2"
    );

    wp_localize_script("setup", "__stripe_donate_opts", array(
      'publishable' => $this->api_js_string,
      'donationAmounts' => $this->amounts,
      'generic' => $this->generic
      )
    );


  }
}
