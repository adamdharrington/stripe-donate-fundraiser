<?php


class Stripe_Donate_Fundraiser {


	protected $plugin_name = 'stripe-donate-fundraiser';
	protected $version = '1.0.3';
	
	public $page_status;
	public $form_name;
  public $contributed;

	public $charge_status;

	public function __construct($options) {
		require_once    plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		require_once    plugin_dir_path( dirname( __FILE__ ) ) . 'lib/set-api-keys.php';
    $options = $options ?: array();
		self::set_defaults($options);
		self::prepare();
		self::update_total();
	}
  protected function set_defaults($options){
    $defaults = array(
      'page_status'     => 'new',
      'form_title'      => 'Stripe Donate Form Example',
      'contributed'     => 1,
      'target'          => 1000,
      'amounts'         => self::get_amounts(array(1,2,3,4,5,6)),
      'plugin_options'  => get_option('stripe_donate_options'),
      'generic'         => true,
      'thank_you'       => 'Thank you for your gerous donation',
      'nxt_page'        => htmlspecialchars("$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8'),
      'chip_in_message' => "Contribute to this cause",
      'chip_in_term'    => "Chip in",
      'error_email'     => "tech@example.com",
      'fundraiser_id'   => "43473",
      'security_blurb'  => "Please review our donation policy before donating.",
      'api_string'      => "xxx",
      'api_js_string'   => "xxx"
    );
    foreach ($defaults as $opt => $val){
      if (isset($options[$opt])){
        $this->$opt = $options[$opt];
      }
      else{
        $this->$opt = $val;
      }
    }
  }
  public function get_progressbar(){
    $amount = $this->contributed;
    $target = $this->target;
    $half =  round($target / 2);
    $percent = 1;
    if (
      $target > 0
      && $amount > 0
    ){
      $percent = round(($amount / $target) * 100);
    }


    return <<<HTML
<div class="fundraising-goal">
  <div class="fundraising-goal--labels">
    <span>0</span>
    <span>$half</span>
    <span>$target</span>
  </div>
  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-info active" role="progressbar" aria-valuenow="$percent" aria-valuemin="0" aria-valuemax="100" style="width: $percent%">
      <span class="sr-only">$percent% Complete</span><span class="donated-amount">€ $amount</span>
    </div>
  </div>
</div>
HTML;
  }

  private function update_total(){
    $a = $this->contributed ?: 0;
    if ($this->page_status == 'success' && isset($_POST)){
      $a = $a + round($_POST['amount'] / 100);
      $this->contributed = $a;
    }
  }

  public function on_success(){
    $this->update_total();
  }

	private function get_amounts($amounts_array){
    $is = array(1,2,3,4,5,6);
    $amounts = [];
    foreach ($is as $i){
      $amounts['donation_amount_'.$i] = $amounts_array[$i];
    }
    return $amounts;
  }
	
	function get_form_name($id){
    return get_the_title($id);
	}
	
	function page_check(){
		
		$return = "";
		
		if ($_POST) {
			// TODO: Check if post request is local?

			$return = "success";


      //TODO: Dependency injection for other gateways should go here
      // - Possibly as a hook to this action?
			if (!isset($_POST['stripeToken'])){
        $return = "post-fail";
        throw new Exception("Possible page reload?");
			}
		}
		else $return = "new";
		
		return $return;
	}
	
	private function submit_successful(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/post-charge-card.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/post-error-handling.php';
		$charge = new Charge_Card($this);
    $this->charge_status = $charge->status;
	}
	protected function prepare(){

		require_once    plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		require_once    plugin_dir_path( dirname( __FILE__ ) ) . 'lib/set-api-keys.php';

		if (!class_exists("\\Stripe\\Stripe")) {
			throw new Exception("Stripe not defined.");
		}

		SetAPIKeys::set_api_key($this->api_string);

		// 2. Check if this is a POST Request, e.g. we should charge the token
		if ($this->page_status == "success") {

			$error = '';
			$success = '';

			if (!isset($_POST['stripeToken']))
				throw new Exception("The Stripe Token was not generated correctly");

			self::submit_successful();

		}
	}
	public function get_form (){


		if($this->charge_status != null) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/post-show-receipt.php';
			show_receipt($this->thank_you, $this->charge_status);
		}
		// 3. Not a POST request? Then build the form
		elseif ($this->page_status == "new"){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/make-form.php';
      $form = new Form_Form($this);
      echo $form->make();
		}
		else {
			throw new Exception("Unexpected load scenario. Try reload the page.");
		}

	}

}
