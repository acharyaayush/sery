<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

# Creating as per requirement
define("PROFILE_IMAGE_RESIZE_WIDTH", "500");

#service (sub cateogry) icon image
define("SERVICE_IMAGE_RESIZE_WIDTH", "40");
define("SERVICE_IMAGE_RESIZE_HIGHT", "40");

#service (sub cateogry)  banner image for mobile
define("SERVICE_MOBILE_BANNER_IMAGE_RESIZE_WIDTH", "375");
define("SERVICE_MOBILE_BANNER_IMAGE_RESIZE_HIGHT", "282");

#Mobile home banner
define("MOBILE_HOME_BANNER_WIDTH", "903");
define("MOBILE_HOME_BANNER_HIGHT", "588");

define("ADMIN_PER_PAGE_RECORDS", "20");
define("MOBILE_PAGE_LIMIT", '10');

define("APP_NAME", "Sery");

define("COUNTRY_CODE", "251"); //Country name - Ethiopia
define("CURRENCY", "Birr"); //currency of ethiopia

define("MOBILE_NUMBER_MAX_DIGIT", "9"); //mobile number maximum digit
define("MOBILE_NUMBER_MIN_DIGIT", "9"); //mobile number minimum digit

//For order page - ready for assign, ongoing,completed, cancelled
#we are passing it in 4th segement when click from sidemenu
define("READY_FOR_ASSIGN_ORDER_PAGE_VALUE", 1);
define("ONGOING_ORDER_PAGE_VALUE", 2);
define("COMPLETED_ORDER_PAGE_VALUE", 3);
define("CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE", 4);
define("CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE", 5);

#Andriod Notification for customer and service provider 
define("CUSTOMER_API_SERVER_KEY", 'AAAAkZX-SHQ:APA91bHEeUoFCvLXukL3crGJEegOyDEi186PZiYw3gpeyaR2KWAtN99_FPFv4ak_iE63LQdBRG09dy4ZzuJZZOU1CUXmHUXiCEM5f_PSG9-s2Fp8rhGYUbtFyho4dA9fwiFvZoVR6Kke');
define("PROVIDER_API_SERVER_KEY", 'AAAAey6YmPI:APA91bFHWQYipNWVFVzbS-tOval6cbE-RLsQJPjFxhiC9JRjpd-qPMLphPcXb4Gw1l7sxcrTd9JDjyB9yXgWXPYf1XztfdQSuHfzrFX16Aru__QwQwnrCN0wO_jQy3zMKnVFYYuF-Wtv');

#IOS Notification Bundle id
define('IOS_PROVIDER_BUNDLE_ID', 'Com.Sery.Provider');//will provide by ios team
define('IOS_CUSTOMER_BUNDLE_ID', 'Com.Sery.Customer');

define('AdminProfileFolder','admin_profile/');
define('CustomerImgFolder','customer_images/resize_customer_images/');
define('ProviderImgFolder','service_provider_images/resize_service_provider_images/');
define('ServiceCategoryImgFolder','category_images/resize_category_images');
define('ServiceImgFolder','service_images/resize_service_images/');
define('ServiceBannerImgFolder','service_banner_images/service_mobile_banners/resize_service_mobile_banners/');
define('HomeBannerImgFolder','banner_images/resize_banner_images/');