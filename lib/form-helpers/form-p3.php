<?php
Class Page_Three
{

  private $is_generic;
  private $chip_in;

  public function __construct(Stripe_Donate_Fundraiser $master)
  {
    $this->is_generic = $master->generic;
    $this->chip_in = $master->chip_in_term;
  }

  function make()
  {

    return <<<HTML
<form id="donate-form" action="" method="POST"  autocomplete="on">
<div class="form-group">
  <label for="cc-number">Card Number</label>
  <input type="tel" class="form-control cc-number" id="cc-number" placeholder="Example: 4242 4242 4242 4242">
</div>
<div class="form-group">
  <label for="cc-expires">Expiration (MM/YYYY)</label>
  <input type="tel" class="form-control cc-exp" id="cc-expires" placeholder="Example: 06 / 2018">
</div>
<div class="form-group">
  <label for="cc-cvc">CVC</label>
  <input type="tel" class="form-control cc-cvc" id="cc-cvc" placeholder="Example: 424">
</div>
<div class="form-group">
  <button type="button" class="btn btn-default btn-sm btn-prev"><span class="icon-prev"></span> Back</button>
  <button type="button" class="submit-button btn btn-success btn-lg btn-next pull-right">$this->chip_in <span data-role="amount"></span> <span class="icon-next"></span></button>
</div>
</form>
HTML;
  }
}