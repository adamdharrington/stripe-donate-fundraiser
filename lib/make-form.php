<?php

class Form_Form {

  private $f1;
  private $f2;
  private $f3;

  private $blurb;
  private $form_title;
  private $chip_in_message;

  public function __construct(Stripe_Donate_Fundraiser $master){

	  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/form-p1.php';
	  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/form-p2.php';
	  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/form-helpers/form-p3.php';

    $f1 = new Page_One($master);
    $f2 = new Page_Two($master);
    $f3 = new Page_Three($master);

    $this->blurb = $master->security_blurb;
    $this->form_title = $master->form_title;
    $this->chip_in_message = $master->generic ? null : $master->chip_in_message;

    $this->f1 = $f1->make();
    $this->f2 = $f2->make();
    $this->f3 = $f3->make();
  }

  public function make(){
return <<<HTML
<div id="stripe-donate" class="floating-form hidden">
  <div class="top-wrap">
    <p class="secure-form"><span class="lock"></span>
      SECURE</p>

    <div class="steps-list">
      <ul>
        <li class="first active">1 <span></span></li>
        <li class="second">2 <span></span></li>
        <li class="third">3 <span></span></li>
      </ul>
    </div>
  </div>
  <div class="form-title">
    <h3>$this->chip_in_message</h3>
  </div>
  <div class="payment-errors">
  </div>
  <div class="amount-donating">
  	<p>I'm donating</p>
  	<p class="amount"><span data-role="amount"></span></p>
	</div>
  <div class="mid-note">
    <p>Secure donations using SSL and Stripe.</p>
    <p class="text-muted">$this->blurb</p>
  </div>
  <div class="pages-wrap payment stripe row">
    <div class="page col-xs-4" data-page="1">
      $this->f1
    </div>
    <div class="page col-xs-4" data-page="2">
      $this->f2
    </div>
    <div class="page col-xs-4" data-page="3">
      $this->f3
    </div>
  </div>
</div>
HTML;
  }
}
