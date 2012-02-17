<?php

/**
 * First create your partner application in the GoCardless sandbox:
 * https://sandbox.gocardless.com
 *
 * Then grab your application identifier and secret and paste them in below
 *
 * Now test the 'authorize app' link which will generate an access_token
 *
 * Save access_token and merchant_id in your database against the current user
 * And use them to initialize GoCardless for that user
 *
 * NB. You can then paste in access_token and merchant_id below for testing
 * And you may want to replace the ids in the various API calls too.
 *
 *
 * This page then does the following:
 *
 *  1. Shows an authorize link
 *  2. Generates an access_token from the retured $_GET['code']
 *  3. Instantiate new GoCardless_Client object
 *  (4. Check for GET vars required to confirm a payment)
 *  5. Show new bill url
 *  6. Fetch a bill from the API
 *
*/



// Include library
include_once 'gocardless.php';

// Sandbox
GoCardless::$environment = 'sandbox';

// Config vars for your PARTNER account
$account_details = array(
  'app_id'        => null,
  'app_secret'    => null,
  'access_token'  => null,
  'merchant_id'   => null
);

$gocardless_client = new GoCardless_Client($account_details);

if (isset($_GET['code'])) {

  $params = array(
    'client_id'     => $account_details['app_id'],
    'code'          => $_GET['code'],
    'redirect_uri'  => 'http://localhost:8888/demo_partner.php',
    'grant_type'    => 'authorization_code'
  );

  // Fetching token returns merchant_id and access_token
  $token = $gocardless_client->fetchAccessToken($params);

  $account_details = array(
    'app_id'        => null,
    'app_secret'    => null,
    'access_token'  => null,
    'merchant_id'   => null
  );

  $gocardless_client = new GoCardless_Client($account_details);

  echo '<p>Authorization successful!
  <br />Add the following to your database for this merchant
  <br />Access token: '.$token['access_token'].'
  <br />Merchant id: '.$token['merchant_id'].'</p>';

}

if ($account_details['access_token']) {
  // We have an access token

  echo '<h2>Partner authorization</h2>';

  echo '<p>Access token found!</p>';

  // New pre-authorization


  echo '$gocardless_client->merchant(\'012GM2H8FA\')';
  echo '<blockquote><pre>';
  $merchant = $gocardless_client->merchant();
  print_r($merchant);
  echo '</pre></blockquote>';

  echo 'echo $gocardless_client->merchant(\'012GM2H8FA\')->pre_authorizations()';
  echo '<blockquote><pre>';
  $preauths = $gocardless_client->merchant()->pre_authorizations();
  print_r($preauths);
  echo '</pre></blockquote>';

  $account_details = array(
    'app_id'        => null,
    'app_secret'    => null,
    'access_token'  => null,
    'merchant_id'   => null
  );

  $gocardless_client2 = new GoCardless_Client($account_details);

  echo 'echo $gocardless_client2->merchant()';
  echo '<blockquote><pre>';
  $merchant = $gocardless_client2->merchant();
  print_r($merchant);
  echo '</pre></blockquote>';

  echo '$gocardless_client2->merchant()->pre_authorizations()';
  echo '<blockquote><pre>';
  $preauths = $gocardless_client2->merchant()->pre_authorizations();
  print_r($preauths);
  echo '</pre></blockquote>';

} else {
  // No access token so show new authorization link

  echo '<h2>Partner authorization</h2>';
  $authorize_url_options = array(
    'redirect_uri' => 'http://localhost:8888/demo_partner.php'
  );
  $authorize_url = $gocardless_client->authorizeUrl($authorize_url_options);
  echo '<p><a href="'.$authorize_url.'">Authorize app</a></p>';

}

?>