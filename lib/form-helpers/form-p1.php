<?php
Class Page_One{

  private $is_generic;
  private $amounts;

  public function __construct(Stripe_Donate_Fundraiser $form){
    $this->is_generic = $form->generic;
    $this->amounts = $form->amounts;
  }

  function make_payment_html(){
    $block = "";
    $i = 1;
    foreach($this->amounts as $m){
        $block .= sprintf('<div class="input-group amounts">
        <label for="amount-%s" class="hidden">%s</label>
        <button type="button" id="amount-%s" class="btn btn-lg" value="%s">
          € <span class="amount">%s</span>
        </button>
      </div>',
          $i,
        $m,
        $i,
        $m . "00",
        $m
      );
      $i++;
    }
    return $block;
  }
  function generic(){
    if ($this->is_generic){
      return <<<HTML
    <label for="recurring">Donate monthly</label>
    <div class="form-group" data-stripe="recurring">
      <div class="input-group recurring col-xs-5">
        <label for="single" class="hidden">Single donation</label>
        <button type="button" id="single" class="btn btn-md active-button" value="single">
          Single donation
        </button>
        <input type="radio" name="recurring" id="recurring_single" class="hidden" checked value="single">
      </div>
      <div class="input-group recurring col-xs-5">
        <label for="monthly" class="hidden">Monthly Donation</label>
        <button type="button" id="monthly" class="btn btn-md" value="monthly">
          Monthly donation
        </button>
        <input type="radio" name="recurring" id="recurring_monthly" class="hidden" value="monthly">
      </div>
    </div>
HTML;
    }
    else return null;
  }
  public function make(){
    $amount_block = self::make_payment_html();
    $non_generic = self::generic();

    return <<<HTML
    <form id="donate-amount" autocomplete="on">
    $non_generic
    <div class="form-group" id="donation-amounts">
      <label for="amount">Donation amount</label>
      <input type="hidden" id="donation-amount" data-stripe="amount" value="">
      <div class="clearfix"></div>
      $amount_block
      <div class="form-group form-horizontal other-amount">
        <label for="other-amount" class="col-sm-6 control-label">Other (€)</label>
        <div class="input-group col-sm-5">
          <span class="input-group-addon">€</span>
          <input type="tel" class="form-control" id="other-amount" data-numeric placeholder="10">
          <span class="input-group-addon">.00</span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-success btn-lg btn-next pull-right">Next <span class="icon-next"></span></button>
    </div>
  </form>
HTML;
  }

}

