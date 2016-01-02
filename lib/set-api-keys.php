<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 21/07/2015
 * Time: 15:48
 */

class SetAPIKeys {
  public static function set_api_key($api_key){
    if ($api_key)
    \Stripe\Stripe::setApiKey($api_key);
  }
}