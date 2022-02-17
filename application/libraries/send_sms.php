<?php
require 'vendor/autoload.php';

use AfricasTalking\SDK\AfricasTalking;

#SENDBOX
//$username = 'sandbox'; // use 'sandbox' for development in the test environment
//$apiKey   = '94892b99409d4ca49309fcc891fd26e4d69d0fd273d27ee1ea76237863c6cb07'; // use your sandbox app API key for development in the test environment

#PRODUCTION
$username = 'sery'; 
$apiKey   = '4c6ab4e87feef99d8d739ba52590bf5b70d4cffe51d9244aefc9fa80ae0a7b67';

$AT       = new AfricasTalking($username, $apiKey);

// Get one of the services for example 
$sms      = $AT->sms();
/*----------------For test purpose it is puted also in customer_helper.php for login api 
$otp_code = '000000';
$destination_number = COUNTRY_CODE.'945437996';
// Use the service
$result   = $sms->send([
    'to'      =>  '+'.$destination_number,
    'from'      => '10849',
    'message' => "Dear Customer,\n This is a test message. ,\n $otp_code is your OTP to register with Sery application. Please use this code to proceed.\nThank You,\nTeam Sery",
]);
*/
//echo json_encode($result);
