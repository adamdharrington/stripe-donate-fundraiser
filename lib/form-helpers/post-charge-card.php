<?php

class Charge_Card{

  public $status;

  public function __construct(Stripe_Donate_Fundraiser $fundraiser = null){
    $this->error = null;
    $this->success = null;
    $this->fundraiser = $fundraiser;
    $this->status = self::do_charge();
  }


  private function do_charge(){
    $fundraiser = $this->fundraiser;
    if (!$fundraiser){
      $description = "Unknown Action";
    }else{
      $description = $fundraiser->form_title;
    }

    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");

    function monthly_payment($description = "Unknown Action"){
      $customer = \Stripe\Customer::create(array(
        'source'      => $_POST['stripeToken'],
        'email'       => $_POST['email'],
        'plan'        => 'monthly' . ($_POST['amount'] / 100),
        'description' => $description
      ));

      return 'Your monthly payment was successful.';
    }
    function single_payment($description = "Unknown Action"){
      $customer = \Stripe\Customer::create(array(
        'source'     => $_POST['stripeToken'],
        'email'      => $_POST['email']
      ));

      $charge = \Stripe\Charge::create(array(
        'customer'    => $customer->id,
        "amount"      => $_POST['amount'],
        "currency"    => "eur",
        "description" => $description
      ));

      return _('Your payment was successful.');
    }



    /*
     *  ========================== Try to charge monthly or singular payment
     */

    try {
      // New Monthly payments
      if (isset($_POST['recurring']) && $_POST['recurring'] === "monthly") {
        $this->success = monthly_payment($description);
        if(function_exists("log_payment_to_crm")) log_payment_to_crm($fundraiser->fundraiser_id, $description, 'monthly');
        $fundraiser->on_success();
      } // New Single Payment
      else {
        $this->success = single_payment($description);
        if(function_exists("log_payment_to_crm")) log_payment_to_crm($fundraiser->fundraiser_id, $description, 'single');
        $fundraiser->on_success();
      }
    }
      /*
       *  ========================== Catch various errors
       */

      // Decline, \Stripe\Error\Card will be caught
    catch (\Stripe\Error\Card $e) {
      $this->error = Errors::handle_decline($e);
      //Errors::send_mail($this->error, $e);
    }

      // Invalid parameters were supplied to Stripe's API
    catch (\Stripe\Error\InvalidRequest $e) {
      $this->error = Errors::handle_invalid_parameters($e);
      //Errors::send_mail($this->error, $e);
    }

      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
    catch (\Stripe\Error\Authentication $e) {
      $this->error = Errors::handle_no_api_authentication($e);
      //Errors::send_mail($this->error, $e);
    }

      // Network communication with Stripe failed
    catch (\Stripe\Error\ApiConnection $e) {
      $this->error = Errors::handle_network_problem($e);
      //Errors::send_mail($this->error, $e);
    }

      // Generic error, unspecified cause
    catch (\Stripe\Error\Base $e) {
      $this->error = Errors::handle_generic_error($e);
      //Errors::send_mail($this->error, $e);
    }

      // Non-Stripe error, don't rely on Stripe
    catch (Exception $e) {
      $this->error = Errors::handle_unknown_error($e);
      //Errors::send_mail($this->error, $e);
    }
    return array(
      'success' => $this->success,
      'error'   => $this->error
    );
  }
}