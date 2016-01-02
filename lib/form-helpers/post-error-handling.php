<?php

class Errors
{
  public static function handle_decline($e = [])
  {
    // Decline, \Stripe\Error\Card will be caught
    $body = $e->getJsonBody();
    $err = $body['error'];

    $error = "It seems your card was declined.\n<pre>"
      . 'Status is:' . $e->getHttpStatus() . "\n"
      . 'Type is:' . self::safe_check($err, 'type') . "\n"
      . 'Code is:' . self::safe_check($err, 'code') . "\n"
      . 'Message is:' . self::safe_check($err, 'message') . "\n</pre>";

    return $error;
  }

  public static function handle_invalid_parameters($e = [])
  {
    // Invalid parameters were supplied to Stripe's API
    $body = $e->getJsonBody();
    $err = $body['error'];

    $error = "It seems we processed your card incorrectly,"
      . "we'll check it out but don't worry, you were not charged.\n<pre>"
      . 'Status is: ' . $e->getHttpStatus() . "\n"
      . 'Type is: ' . self::safe_check($err, 'type') . "\n"
      . 'Code is: ' . self::safe_check($err, 'code') . "\n"
      . 'Parameters: ' . self::safe_check($err, 'param') . "\n"
      . 'Message is: ' . self::safe_check($err, 'message') . "\n</pre>";

    return $error;
  }

  public static function handle_no_api_authentication($e = [])
  {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
    $body = $e->getJsonBody();
    $err = $body['error'];

    $error = "We're having difficulty with our payment gateway,"
      . "we'll check it out but don't worry, you were not charged.\n<pre>"
      . 'Status is: ' . $e->getHttpStatus() . "\n"
      . 'Type is: ' . self::safe_check($err, 'type') . "\n"
      . 'Code is: ' . self::safe_check($err, 'code') . "\n"
      . 'Parameters: ' . self::safe_check($err, 'param') . "\n"
      . 'Message is: ' . self::safe_check($err, 'message') . "\n</pre>";

    return $error;
  }

  public static function handle_network_problem($e = [])
  {
    // Network communication with Stripe failed
    $body = $e->getJsonBody();
    $err = $body['error'];

    $error = "We're having difficulty connecting to our payment gateway,"
      . "we'll check it out but don't worry, you were not charged.\n<pre>"
      . 'Status is: ' . $e->getHttpStatus() . "\n"
      . 'Type is: ' . self::safe_check($err, 'type') . "\n"
      . 'Code is: ' . self::safe_check($err, 'code') . "\n"
      . 'Parameters: ' . self::safe_check($err, 'param') . "\n"
      . 'Message is: ' . self::safe_check($err, 'message') . "\n</pre>";

    return $error;
  }

  public static function handle_generic_error($e = [])
  {
    // Display a very generic error to the user, and maybe send
    // yourself an email
    $body = $e->getJsonBody();
    $err = $body['error'];

    $error = "We encountered a problem with your request, you card has not been charged"
      . "please email with the following message if you would like additional information.\n<pre>"
      . 'Status is: ' . $e->getHttpStatus() . "\n"
      . 'Type is: ' . self::safe_check($err, 'type') . "\n"
      . 'Code is: ' . self::safe_check($err, 'code') . "\n"
      . 'Parameters: ' . self::safe_check($err, 'param') . "\n"
      . 'Message is: ' . self::safe_check($err, 'message') . "\n</pre>";
    return $error;
  }

  public static function handle_unknown_error($e = [])
  {
    // Unknown error,
    return "We encountered a problem with this page."
    . "Your card was not charged and we'll try figure out what went wrong.\n<pre>";
  }

  public static function send_mail($error = "", $e = null)
  {
    if ($e) $error = $error . "\nFull exception text code:\n<pre>" . $e . "</pre>";
    $page = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');

    $to = get_option('admin_email');
    $date = date('d/m/Y H:i:s');
    $subject = "Problem with Stripe " . $date;

    $error = "New exception on " . $page . "\n"
      . $date . "\n"
      . $error;
    mail($to, $subject, wordwrap($error, 70));

  }

  private function safe_check($obj, $prop)
  {
    if (isset($obj[$prop])) return $obj[$prop];
    else return "";
  }
}