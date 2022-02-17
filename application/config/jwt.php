<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Store your secret key here
// Make sure you use better, long, more random key than this
$config['jwt_key'] = 'epyoqgqickqebhyaibamhghsponbkyvh';

/*Generated token will expire in 1440 minute for sample code
* Increase this value as per requirement for production
*/
// $config['token_timeout'] = 5; // minute
//$config['token_timeout'] = 1440;// 1 Day
//$config['token_timeout'] = 10080; // 7 Day
$config['token_timeout'] = 43800; // 1 month
