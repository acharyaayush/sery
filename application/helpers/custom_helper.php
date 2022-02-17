<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*function Api_Error_handling($error_no, $error_msg) {
    $ci=& get_instance();
    $data['status'] = 500;
    $data['message'] = $ci->lang->line('internal_server_error');
    $data['data'] = array();
    log_message('error',$data['message']);
    //echo json_encode($data);
    return $ci->response($data, $data['status']);
   
    die();
}*/
function Api_Catch_Response($e){
    log_message('error', $e); # make error log
    $ci=& get_instance();
    $data['status'] = 500;
    $data['message'] = $ci->lang->line('internal_server_error');
    $data['data'] = array();
    return $ci->response($data, $data['status']);
}
#table pagination ------START------------
# Common function for pagination start
function create_pagination($base_url , $total_records , $limit_per_page)
{
    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_records;
    $config['per_page'] = $limit_per_page;
    // $config["uri_segment"] = $ci->uri->total_segments(); # Imp to make it as dynamic because we may have some functions with parameter and some with no parameter
    # CUSTOM START
    $config['num_links'] = 2;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;

    $config['full_tag_open'] = "<ul class='pagination'>";
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['first_link'] = '<i class="page-item fa fa-angle-double-left"></i>';
    $config['last_tag_open'] = '<li class="page-item lastt">';
    $config['last_tag_close'] = '</li>';
    $config['last_link'] = '<i class="page-item fa fa-angle-double-right"></i>';
    $config['prev_link'] = '<i class="page-item fa fa-angle-left"></i>';
    $config['prev_tag_open'] = '<li class="page-item previouss">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '<i class="page-item fa fa-angle-right"></i>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    # CUSTOM END

    $ci=& get_instance();
    $ci->pagination->initialize($config);
    # build paging links
}
# Common function for pagination end
#table pagination ------END--------------

#split name 
//for get first name 
function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}

/**ci function used for send mail */
function send_mail($email,$subject,$message,$file_path = ""){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    $setting_data=$ci->Common->getData('settings','*',"name = 'smtp_email' or name = 'smtp_password'");

    $smtp_user='';
    $smtp_password='';

    foreach ($setting_data as $value) {
        if($value['name'] == 'smtp_email')
        {
            $smtp_user = $value['value'];
        }else if($value['name'] == 'smtp_password'){
            $smtp_password = $value['value'];
        }
    }
    // echo $smtp_user."<br>";
    // echo $smtp_password;die;
    if($smtp_user!="" && $smtp_password!=""){

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com'; 
        $config['smtp_port']    = '465';
        $config['smtp_user']    = $smtp_user;
        $config['smtp_pass']    = $smtp_password;
        $config['charset']        = 'utf-8';
        $config['newline']        = "\r\n";
        $config['mailtype']     = 'html';
        //$config['validation']     = TRUE; // bool whether to validate email or not      
        $CI = get_instance();
        $CI->email->initialize($config);
        $CI->email->from($smtp_user,"Sery Team",'');
        $CI->email->reply_to('', "Sery");
        $CI->email->to($email);
        $CI->email->subject("$subject");
        $CI->email->message($message);
        if($file_path != ""){
            $CI->email->attach($file_path);
        }
        $mail = $CI->email->send();
        // echo $CI->email->print_debugger();die;

    }
    return true;
}

/**ci function used for generate random token with time*/
function generate_token() {
    $Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $Chars_Len = 10;
    $Salt_Length = 10;
    $salt = "";
    for($i=0; $i<=$Salt_Length; $i++)
    {
        $salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
    }
    return $salt.time();
}

/**ci function used for generate random code */
function generate_verification_code() {
    $Allowed_Chars = '1234567890';
    $Chars_Len = 6;
    $Salt_Length = 6;
    $salt = "";
    for($i=0; $i<$Salt_Length; $i++)
    {
        $salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
    }
    return (int)$salt;
}

# Image resize function start
function image_resize($source_image , $new_image , $width , $height)
{
    $configer =  array(
        'image_library'   => 'gd2',
        'source_image'    =>  $source_image,
        'new_image'       =>  $new_image,
        'maintain_ratio'  =>  FALSE,
        'width'           =>  $width,
        'height'          =>  $height,
    );
    $ci=& get_instance();
    $ci->image_lib->clear();
    $ci->image_lib->initialize($configer);
    $ci->image_lib->resize();
}
# Image resize function end

// Get Social media link FUNCTION-----START----------
# get_social_urls method is used to get the footer icon href urls for mail function. Instead of writing same code again and again , we are writing this function and will just call it in single line
function get_social_urls() {
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $get_social_urls = $ci->Common->getData('settings', 'value,status', 'name IN("facebook" , "google" , "instagram" , "website")');
    
    $facebook = isset($get_social_urls[0]) ? $get_social_urls[0]['value'] : '';
    $fb_status = isset($get_social_urls[0]) ? $get_social_urls[0]['status'] : '1';
    $insta = isset($get_social_urls[2]) ? $get_social_urls[1]['value'] : '';
    $insta_status = isset($get_social_urls[2]) ? $get_social_urls[1]['status'] : '1';
    $google = isset($get_social_urls[1]) ? $get_social_urls[2]['value'] : '';
    $google_status= isset($get_social_urls[1]) ? $get_social_urls[2]['status'] : '1';
    $website = isset($get_social_urls[3]) ? $get_social_urls[3]['value'] : '';
    return array('facebook' => $facebook, 'fb_status' => $fb_status,'google' => $google,'google_status' => $google_status, 'insta' => $insta,'insta_status'=>$insta_status , 'website' => $website);
}
// Get Social media link FUNCTION-----END----------

//Getting Customer Service Number ------------START--------------
#for calling by customer or service provider
function get_support_call_number() {
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $get_support_call_number = $ci->Common->getData('settings', 'value', 'name = "support_call"');
    return $get_support_call_number[0]['value'];
}
//Getting Customer Service Number -------------END--------------

# This funcion is used to send sms for OTP on users mobile number ----START---
function send_otp_on_mobile($otp_code, $destination_number) {
    require_once (APPPATH . 'libraries/send_sms.php');
    $destination_number = COUNTRY_CODE.$destination_number;
    $result   = $sms->send([
        'to'      =>  '+'.$destination_number,
        'from'      => 'Sery',
        'message' => "Dear Customer,\n $otp_code is your OTP to register with Sery application. Please use this code to proceed.\nThank You,\nTeam Sery",
         
    ]);
    //echo json_encode($result);
}
# This funcion is used to send sms for OTP on users mobile number ----END--

//Create last  accpet service time for service provider----START---
function generate_service_accept_till_time(){
   
    #Geeting duration from the settings table ---start---
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
   
    $get_service_accept_duration=$ci->Common->getData('settings','value','name = "service_provider_service_accept_duration"');
    $service_accept_duration = $get_service_accept_duration[0]['value'];

    # DURATION will always be passed in HOURS:MINUTES Format. Ex 1 hours and 30 minutes (1:30) and 1 hours (1:00)
    if(strpos($service_accept_duration, ":") !== false) # We are keeping : to separate the hours and minutes value
    {
        # That means minutes also exists (Ex 1 hours and 30 minutes 1:30)
        $hm = explode(":", $service_accept_duration);
         $hours = $hm[0];
        $minutes = $hm[1];

        $order_current_time_from = time();
        $to_add_h = $hours * (60 * 60); # Convert the hours into seconds.
        $to_add_m = $minutes * 60; # Convert the minutes into seconds.
        $last_till_time_of_service_accept = $order_current_time_from + $to_add_h + $to_add_m;

        $expiration_date = strtotime("".$hours." hours");

    }else # However it won't be used but just kept it here.
    {
        # Only Hours value exists (Ex Next 1 hours)
        $hours = $service_accept_duration;
        $order_current_time_from =  time();
        $to_add = (int)$hours * (60 * 60); # Convert the hours into seconds.
        $last_till_time_of_service_accept = $order_current_time_from + $to_add;
    }

    return $last_till_time_of_service_accept;
     #DB_last_till_time_of_service_accept = ci time will create according to order current time with duration time which is set by admin (settings table). Ex. order time 12:00 and durtion time 0:30 then service provider has last accept time will be 12:30 after that "customer service or super admin" will be assign service to service provider. because after complete duration no one service provider cant accept service. (12:30 time will be go here in timestamp formate)
}
//Create last accpet service time for service provider----END---

#check order time and accept till  last time  with current time 
function check_can_service_provider_accept_service($booked_time,$last_till_time_of_service_accept){

    date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia cuntry
    //echo time().'>='.$booked_time.'<='.$last_till_time_of_service_accept;

    if ((time() >= $booked_time) && (time() <= $last_till_time_of_service_accept))
    {
        $can_accept_or_not = "1";//service provider can accpet service
    }else{
        $can_accept_or_not  = "2";//service provider can not accpet service , customer service/admin will assign service
    }
    return $can_accept_or_not;
}

#calculate time diffrence between two time stamp
function comman_getting_time_between_duration($qw,$saw)#start time, end time
{
    $datetime1 = new DateTime("@$qw");
    $datetime2 = new DateTime("@$saw");
    $interval = $datetime1->diff($datetime2);
    return $interval->format("%H:%I:%S");//"%H:%I:%S"
}

#comman getting start time of service(order) start ------START-----
function comman_getting_service_start_time($order_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    $get_starting_time_of_service_start = $ci->Common->getData('service_order_bookings','start_service_time','order_id='.$order_id.'','','','','');
    $start_time = $get_starting_time_of_service_start[0]['start_service_time'];
    return $start_time;
}
#comman getting start time of service(order) start ------END-----
function base64($data) {
    return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
  }
#send push notification
function sendPushNotification($token,$data,$bundleid = '',$API_SERVER_KEY='') {
     if(strlen($token) != 64){#for andriod
        /* For Android push notification */
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
         
         $fields = array (
                'to' => $token,
                 "data"=> $data
             );

        $headers = array(
            'Authorization:key=' . $API_SERVER_KEY,
            'Content-Type:application/json'
        );  
         
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        
        curl_close($ch);
        return $result;

    }else{  #for ios
        // echo "bundleid".$bundleid;
        /* For IOS push notification */
        $keyfile = APPPATH.'helpers/AuthKey_7VYWB5CHVL.p8'; # <- Your AuthKey file
        $keyid = '7VYWB5CHVL';                            # <- Your Key ID
        $teamid = 'QZ3M78L297';                           # <- Your Team ID (see Developer Portal)
        $bundleid = $bundleid;                # <- Your Bundle ID
        $url = 'https://api.development.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
        //$url = 'https://api.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
        $msg = array
        (
            'message' => $data['message'],
            'title' => $data['title'],
            'order_id' => $data['order_id'],
            'notification_type' => $data['notification_type'],
        );
        $payload['aps'] = array('alert' => $msg, 'badge' => intval(0),'sound' => 'default');
        $message = json_encode($payload);
        $key = openssl_pkey_get_private('file://'.$keyfile);
        $header = ['alg'=>'ES256','kid'=>$keyid];
        $claims = ['iss'=>$teamid,'iat'=>time()];

        $header_encoded = base64($header);
        $claims_encoded = base64($claims);

        $signature = '';
        openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
        $jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);

        $http2ch = curl_init();
        curl_setopt_array($http2ch, array(
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_URL => "$url/3/device/$token",
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => array(
              "apns-topic: {$bundleid}",
              "authorization: bearer $jwt"
            ),
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => 1
        ));

        $result = curl_exec($http2ch);
        // print_r($result);
        if ($result === FALSE) {
            throw new Exception("Curl failed: ".curl_error($http2ch));
        }

        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
        // echo "in customhelper";
        // print_r($status);die;
    }
}

#get wallet balance 
#ci function is a comman function which  is using in admin or api controller
# ci function will get the existing wallet amount.
function get_wallet_balance($user_id){

    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    #total sum of credited amount ----------start----------------
    $query_credited = "SELECT  sum(amount) AS total_credited FROM wallet WHERE service_provider_id = $user_id AND type = 1 ";
     #db_type = (1 -Credit, 2 -Debit)

    $credited =  $ci->Common->custom_query($query_credited,"get");
    #total sum of credited amount ------------end------------

    #total sum of debited amount-------------start----------------
    $query_debited = "SELECT  sum(amount) AS  total_debited FROM wallet WHERE service_provider_id = $user_id AND type = 2 ";
     #db_type = (1 -Credit, 2 -Debit)

    $debited =  $ci->Common->custom_query($query_debited,"get");
    #total sum of debited amount-----------end-------------------

    #getting wallet balance 
    $wallet_balance =  $credited[0]['total_credited'] - $debited[0]['total_debited'];

    $total_balance = number_format($wallet_balance,2, '.', '');
    
    return $total_balance;
}

#getting profile details for customer and service provider------START------
function get_comman_profile_details($user_id, $language_type){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    $profile_data =  $ci->Common->getData('users','*','user_status != 3 AND user_id = '.$user_id.' AND language = '.$language_type.'');
    //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
    //Db_language = (there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1

    if(!empty($profile_data)){
        if(trim($profile_data[0]['age'] == 0)){
            $age = "";
        }else{
            $age = trim($profile_data[0]['age']);
        }

        if(trim($profile_data[0]['gender'] == 0)){
            $gender = "";
        }else{
            $gender = trim($profile_data[0]['gender']);
        }

        $profile_data_arr = [
            'user_id' => $profile_data[0]['user_id'],
            'fullname' => trim($profile_data[0]['fullname']), # It will contain user full name as John Doe
            'email' => trim($profile_data[0]['email']),
            'age' => $age,
            'gender' => $gender,
            'country_code' => trim($profile_data[0]['country_code']),
            'mobile' => trim($profile_data[0]['mobile']),
            'profile_image' => trim($profile_data[0]['profile_image']),
            'latitude' => trim($profile_data[0]['latitude']),
            'longitude' => trim($profile_data[0]['longitude']),
            'street_address' => trim($profile_data[0]['street_address']),
            'google_map_pin_address' => trim($profile_data[0]['google_map_pin_address']),
            'role' => trim($profile_data[0]['role']), #1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
            'language' => $profile_data[0]['language']
        ];
    }else{
        $profile_data_arr  = array();
    }

    return $profile_data_arr;
}
#getting profile details for customer and service provider------END--------

//getting key skills/services of service provider -----START-----------
function comman_get_service_provider_skills($user_id,$controller_type,$language_type){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    if($controller_type == 2){#for admin
        $key_skills = $ci->Common->getData('service_provider_key_skills','service_provider_key_skills.*,services.service_name_english,service_categories.cat_name_english','service_provider_key_skills.service_provider_id = '.$user_id.'',array('services','service_categories'),array('services.service_id = service_provider_key_skills.service_id','service_categories.cat_id = service_provider_key_skills.category_id'));
    }else{#for api
        if($language_type == 1){
            $key_skill = 'key_skill_english';
            $service_name = 'service_name_english';
            $cat_name = 'cat_name_english';
        }else if($language_type == 2){
            $key_skill = 'key_skill_amharic';
            $service_name = 'service_name_amharic';
            $cat_name = 'cat_name_amharic';
        }
 
        $key_skills = $ci->Common->getData('service_provider_key_skills','service_provider_key_skills.key_skill_id,service_provider_key_skills.'.$key_skill.' as key_skill,service_provider_key_skills.service_provider_id,service_provider_key_skills.category_id,service_provider_key_skills.service_id,services.'.$service_name.' as service_name,service_categories.'.$cat_name.' as category_name','service_provider_key_skills.service_provider_id = '.$user_id.'',array('services','service_categories'),array('services.service_id = service_provider_key_skills.service_id','service_categories.cat_id = service_provider_key_skills.category_id'));
    }

    return $key_skills;
}
//getting key skills of service provider -----END-----------

//send notification when order status update from admin-------START------
function send_notification_when_order_status_change($order_id,$order_status){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
   
    $get_provider_id = $ci->Common->getData('service_order_bookings','order_accept_by','order_id='.$order_id.'','','','','');

         if ($order_status == 1) { #accept
            send_notification_on_order_accept_by_provider($get_provider_id[0]['order_accept_by'],$order_id,$order_status,"");
        } else if ($order_status == 2) { #on-the-way
            send_notification_on_the_way_provider($get_provider_id[0]['order_accept_by'],$order_id,$order_status,$device_id,"");
        } else if ($order_status == 3) { #started
            send_notification_for_service_started_by_provider($get_provider_id[0]['order_accept_by'],$order_id,$order_status,"");
        } else if ($order_status == 4) { #end/completed
            send_notification_for_service_compeleted_by_provider($get_provider_id[0]['order_accept_by'],$order_id,$order_status,"");
        }
}
//send notification when order status update from admin-------END--------

#genrate order_number_id  when orde create ----------START--------
function generate_order_number_id($order_id){
    $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
    $or_display_id = $display_id_start + $order_id;
    $or_display_id = 'S'.$or_display_id;

    return $or_display_id;
}
#genrate order_number_id  when orde create ----------END--------

#generate  amount after order complete -------START---------
function generate_total_order_amount($order_id,$start_time,$stop_time){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    
    #Send notification To Customer------------------start------------------
    $order_data = $ci->Common->getData('service_order_bookings','service_price,service_price_type, visiting_price','order_id='.$order_id.'','','','','');
    #db_price_type = 1- fixed, 2- hourly

    if(!empty($order_data)){
       
        #If hourly then total amount = (service price * total taken time) + visting price
        #If service price type is fixed then "total_amount = service price + visting price"
        if($order_data[0]['service_price_type'] == 2){#hourly

            #getting service price for per mint 
            $per_mint_service_price = number_format($order_data[0]['service_price']) / 60;

            #getting total_minuts
            $total_mints = ($stop_time - $start_time) / 60;

            $total_amount = ($total_mints*$per_mint_service_price) + $order_data[0]['visiting_price'];
        }else if($order_data[0]['service_price_type'] == 1){#fixed
             $total_amount = number_format($order_data[0]['service_price']) + number_format($order_data[0]['visiting_price']);
        }
    }

   return  $total_amount = number_format($total_amount,2, '.', '');
}
#generate  amount after order complete -------END----------

//getting service provider average rating------------START-----------------
function calculate_average_rating_of_service_provider($service_provider_id = ""){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    
    $rating_data = $ci->Common->getData('ratings','given_rate','to_user_id = '.$service_provider_id.'');

    if(!empty($rating_data)){
        $total_rating_of_provider = count($rating_data);

        $total_of_rate = 0;
        foreach ($rating_data as $rate) {
            $total_of_rate =  $total_of_rate+$rate['given_rate'];
        }

        $average_rating = $total_of_rate/$total_rating_of_provider;
    }else{
     $average_rating = 0;
    }

    return $average_rating;
}
//getting service provider average rating------------END------------------

//send notification when order cancel for service provider------------START---------
function send_notification_when_service_cancel($order_id,$order_status,$service_provider_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');

    $token = $ci->Common->getData('user_devices','device_token','user_id='.$service_provider_id.'');
    
    $provider_selected_language =  get_user_selected_language($service_provider_id);
    if($provider_selected_language == 2){
        $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_amharic');
    }else{
        $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_english');
        $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_english');
    }

    $notification_msg = $ci->lang->line('order_cancelled_success_english');
    $notification_msg_amharic = $ci->lang->line('order_cancelled_success_amharic');

    if(!empty($token)){
        foreach ($token as $token_value) {
            $provider_token = $token_value['device_token'];
            $provider_notification_data_fields = array(
                'message' => $notification_msg_language_wise,
                'title' => $NOTIFICATION_TITLE_CANCELLED,

                'order_status'=> $order_status,
                #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled

                'order_number'=> $customer_data[0]['order_number_id'],
                'order_id'=> $order_id,
                'notification_type' => 'ORDER_STATUS_UPDATED'
            );
            if($provider_token != "")
            {
               sendPushNotification($provider_token, $provider_notification_data_fields,IOS_PROVIDER_BUNDLE_ID,PROVIDER_API_SERVER_KEY);
            }  
        }
    }

    # Now insert notification to Database
    $insertData = [
        'title' => $customer_data[0]['order_number_id']." ".$notification_msg,
        'to_user_id' => $service_provider_id,
        'message' => $notification_msg,
        'amharic_title' =>$customer_data[0]['order_number_id']." ".$notification_msg_amharic,
        'amharic_message' => $notification_msg_amharic,
        'type' => 1,
        'order_id' => $order_id,

        'order_status'=> $order_status,
        #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled

        'role' => 3,//Service Provider
        'created_at' => time(),
        'updated_at' => time(),
    ];
    $ci->Common->insertData('notifications',$insertData);
}
//send notification when order cancel for service provider------------START---------

//comman function for getting order details-----------START-------------
#ci function is uses for order details page and invoice in admin
function getting_order_details($order_id,$controller_type){#controller_type = 1 - api, 2 - admin
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    if($controller_type == 1){#api 
        $order_detail = $ci->Common->getData('service_order_bookings','service_order_bookings.order_id,service_order_bookings.order_number_id,service_order_bookings.customer_id,service_order_bookings.customer_name,service_order_bookings.customer_email,service_order_bookings.customer_contact,service_order_bookings.customer_email,service_order_bookings.customer_contact,service_order_bookings.customer_email,
            ,service_order_bookings.google_pin_address as customer_pin_address,service_order_bookings.service_id,service_order_bookings.service_name,service_order_bookings.total_amount,service_order_bookings.invoice_path,service_order_bookings.taken_time,service_order_bookings.order_status,service_order_bookings.order_accept_by,service_order_bookings.created_at as booked_date,users.created_at as member_since','service_order_bookings.order_id = '.$order_id .'',array('users'),array('service_order_bookings.customer_id = users.user_id'),'','','');

    }else{#admin
        $order_detail = $ci->Common->getData('service_order_bookings','service_order_bookings.*,users.created_at as member_since','service_order_bookings.order_id = '.$order_id .'',array('users'),array('service_order_bookings.customer_id = users.user_id'),'','','');
    }  

    if(!empty($order_detail)){
        foreach ($order_detail as $key=>$value) {

            //getting customer image from the user table------------START-----
            $customer_other_details= $ci->Common->getData('users','profile_image,country_code','user_id = "'.$value['customer_id'].'"','',''); 
            if(!empty($customer_other_details)){
                $order_detail[$key]['customer_image'] = $customer_other_details[0]['profile_image'];
                $order_detail[$key]['customer_country_code'] = $customer_other_details[0]['country_code'];
            }else{
                $order_detail[$key]['customer_image'] = "";
                $order_detail[$key]['customer_country_code'] = "";
            }
            //getting customer image from the user table------------END-----

            //getting order accpected by name (service provider name) -----START-----
            $service_provider_data = $ci->Common->getData('users','fullname,email,country_code,mobile,google_map_pin_address','user_id = "'.$value['order_accept_by'].'"','',''); 
            #order_accept_by = service provider id

            if(!empty($service_provider_data)){
             $order_detail[$key]['order_accept_by_name'] = $service_provider_data[0]['fullname'];//service_provider_name
             $order_detail[$key]['service_provider_email'] = $service_provider_data[0]['email'];//service_provider_email
             $order_detail[$key]['service_provider_country_code'] = $service_provider_data[0]['country_code'];//service_provider_country_code
             $order_detail[$key]['service_provider_mobile'] = $service_provider_data[0]['mobile'];//service_provider_mobile
             $order_detail[$key]['pin_address'] = $service_provider_data[0]['google_map_pin_address'];//service_provider_address
            }else{
             $order_detail[$key]['order_accept_by_name'] = 'N/A';
             $order_detail[$key]['service_provider_email'] = 'N/A';
             $order_detail[$key]['service_provider_country_code'] = '';
             $order_detail[$key]['service_provider_mobile'] = 'N/A';
             $order_detail[$key]['pin_address'] = 'N/A';
            }
            //getting order accpected by name (service provider name) ------END-----
        }
    }else{
       $order_detail = array();
    }
    return $order_detail;
}
//comman function for getting order details-----------END-------------

//comman function for getting customer or service provider reviews API---START----
function getting_review($user_id,$role,$limit,$page){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    
    if($role == 4){#customer review
        $user_number_id = 'service_provider_number_id';
        $user_name = 'service_provider_name';
    }else if($role == 3){#provider review
        $user_number_id = 'customer_number_id';
        $user_name = 'customer_name';
    }
    $all_reviews =  $ci->Common->getData('ratings','service_order_bookings.order_number_id,ratings.rating_id,ratings.    given_rate,ratings.comment,users.number_id as '.$user_number_id.',users.fullname as '.$user_name.'','ratings.to_user_id = '.$user_id.'',array('users','service_order_bookings'),array('users.user_id = ratings.from_user_id','service_order_bookings.order_id = ratings.order_id'),'ratings.rating_id','DESC',$limit,$page);//AND users.role = '.$role.'

    return $all_reviews;
}
//comman function for getting customer or service provider reviews API---END----

//deduct money when order status update as completed---------------START--------
#THIS function is useing for api and admin controller 
function deduct_amount_from_provider_wallet($order_id,$total_amount){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    
    #Amount will be deduct on order complete 
    #Amount will be deduct from service provider wallet
    #Amount added by Super admin
    #In directly all deducted amount will be sery earning amount
    #commision will be deduct from total order/service amount and add  in sery wallet

    $order_data = $ci->Common->getData('service_order_bookings','order_accept_by,admin_commission','order_id='.$order_id.'','','','','');
    $commission_amount = $total_amount*$order_data[0]['admin_commission']/100;
    $debit_amount = $commission_amount; //SERY earn amount 

    $insert_wallet_table = [
        'service_provider_id' => $order_data[0]['order_accept_by'],//primary id of the user table, who role is - 3

        'type' =>  2,//(1 -Credit, 2 -Debit)
        'payment_type' =>  1,//1 - offline, 2 - online

        'order_id' => $order_id,
        #order_id for , on which order, amount deducted from service provider. because indirectly it will be earning for sery. only in debit case order id will be go from service provider side other wise this will value will be 0.

        'amount' =>  $debit_amount,
        'created_at' => time(),
        ];

    $insert_status =  $ci->Common->insertdata('wallet',$insert_wallet_table);
}
//deduct money when order status update as completed---------------END---------

//Getting service provider all transaction details -------START------
#THIS METHOD  IS USEGING FOR API and ADMIN controller 
function get_service_provider_all_transactions($user_id,$limit = "",$page="",$role=""){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    if($role == 1){
        $query_part = '';
    }else{
        $query_part = 'service_provider_id = "'.$user_id.'"';
    }   

    $all_transactions = $ci->Common->getData('wallet','*',''.$query_part.'','','','wallet_id','DESC',$limit,$page);
    if(!empty($all_transactions)){
        foreach ($all_transactions as $key => $value) 
        {
            if($value['order_id'] == 0)
            {
                #credit mode (Money added)
                $all_transactions[$key]['display_name'] = 'SERY';
                $all_transactions[$key]['order_number_id'] = '';
            }else{

                #debit mode(Mondy deduct on order completed)
                $service_data = $ci->Common->getData('service_order_bookings','customer_name, order_number_id','order_id = "'.$value['order_id'].'"');
                if(!empty($service_data)){
                    $all_transactions[$key]['display_name'] = $service_data[0]['customer_name'];
                    $all_transactions[$key]['order_number_id'] = $service_data[0]['order_number_id'];
                }else{
                    $all_transactions[$key]['display_name'] = "";
                    $all_transactions[$key]['order_number_id'] = "";
                }
                #order_id for , on which order, amount deducted from service provider. because indirectly it will be earning for sery. only in debit case order id will be go from service provider side other wise this will value will be 0.
            }
            $all_transactions[$key]['currency'] = CURRENCY;
            

            if($role == 1){ #only admin wallet history
                $service_provider_data = $ci->Common->getData('users','fullname','user_id = "'.$value['service_provider_id'].'"'); 
                if(!empty($service_provider_data)){
                    $all_transactions[$key]['provider_name'] = $service_provider_data[0]['fullname'];
                }else{
                    $all_transactions[$key]['provider_name'] = "-";
                }
                $all_transactions[$key]['provider_id'] = $value['service_provider_id'];
            }
        }
    }
    return $all_transactions;
}
//Getting service provider all transaction details -------START------

#getting total credited amount of service provider amount---------START-----
#added amount by admin(sery)
function get_total_credited_amount_for_service_provider($user_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    $query_credited = "SELECT  sum(amount) AS total_credited FROM wallet WHERE service_provider_id = $user_id AND type = 1 ";
     #db_type = (1 -Credit, 2 -Debit)

    $money_added =  $ci->Common->custom_query($query_credited,"get");
    if($money_added[0]['total_credited'] == null)
    {
        $total_money_added = '0.00';
    }else
    {
        $money_added = str_replace(",", "", $money_added[0]['total_credited']);
        $total_money_added = number_format($money_added,2, '.', '');
    }

    return $total_money_added;
}
#getting total credited amount of service provider amount---------END-----

#getting total credited amount of service provider amount---------START-----
#added amount by admin(sery)
function get_total_debited_amount_from_provider_wallet($user_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
    $query_debited = "SELECT  sum(amount) AS  total_debited FROM wallet WHERE service_provider_id = $user_id AND type = 2 ";
    #db_type = (1 -Credit, 2 -Debit)

    $debited = $ci->Common->custom_query($query_debited,"get");
    
    if($debited[0]['total_debited'] == null)
    {
        $total_debited = '0.00';
    }else
    {
        $total_debited = number_format($debited[0]['total_debited'],2, '.', '');
    }

    return $total_debited;          
}
#getting total credited amount of service provider amount---------END-----

#getting Time ago -----------------------------START---------------
function get_time_ago( $time )
{
    $time_difference = time() - $time;

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;

        if( $d >= 1 )
        {
            $t = round( $d );
            return  $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
#getting Time ago -----------------------------END---------------

#pagination page segments ----------------START------------------
function comman_get_query_accroding_page_segment($page_segment,$common_query){
    $ci=& get_instance();
    $page = ($ci->uri->segment($page_segment)) ? ($ci->uri->segment($page_segment) - 1) : 0;
    if($page >0){
       $page_offset = $page * ADMIN_PER_PAGE_RECORDS;

    }else{
      $page_offset = $page ;
    }
     
return $query = "".$common_query." LIMIT ".ADMIN_PER_PAGE_RECORDS." OFFSET ".$page_offset." " ;
}
#pagination page segments ----------------END------------------

#getting user selected language------------------START-------------------
function get_user_selected_language($user_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $data = $ci->Common->getData('users','language','user_id='.$user_id);
    return $data[0]['language'];
}

#getting user selected language------------------END-------------------

//send notification while order booked by admin or customer-------START-------
#this function is using api or admin controller both
function send_notification_while_service_book($customer_id,$or_display_id,$order_id,$service_id,$device_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    #Send notification To Customer------------------start------------------
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id = '.$customer_id.' AND device_id ="'.$device_id.'"');
   
    $customer_selected_language =  get_user_selected_language($customer_id);
    if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_PLACED = $ci->lang->line('NOTIFICATION_TITLE_PLACED_amharic');
        $order_placed_msg_language_wise = $ci->lang->line('service_booked_success_amharic');
    }else{
        $NOTIFICATION_TITLE_PLACED = $ci->lang->line('NOTIFICATION_TITLE_PLACED_english');
        $order_placed_msg_language_wise = $ci->lang->line('service_booked_success_english');
    }
    $order_placed_msg = $ci->lang->line('service_booked_success_english');
    $order_placed_msg_amharic = $ci->lang->line('service_booked_success_amharic');
    if(!empty($token)){
        $customer_token = $token[0]['device_token'];
        $customer_notification_data_fields = array(
            'message' => $order_placed_msg_language_wise,
            'title' => $NOTIFICATION_TITLE_PLACED,

            'order_status'=> 0,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled

            'order_number'=> $or_display_id,
            'order_id'=> $order_id,
            'notification_type' => 'ORDER_STATUS_UPDATED'
        );
        if($token != "")
        {
          sendPushNotification($customer_token, $customer_notification_data_fields,IOS_CUSTOMER_BUNDLE_ID,CUSTOMER_API_SERVER_KEY);
        }
    }

    # Now insert notification to Database
    $insertData1 = [
        'title' => "Order ".$or_display_id." ".$order_placed_msg,
        'to_user_id' => $customer_id,
        'message' => $order_placed_msg,
        'amharic_title' => "Order ".$or_display_id." ".$order_placed_msg_amharic,
        'amharic_message' => $order_placed_msg_amharic,
        'type' => 1,
        'order_id' => $order_id,
        'order_status'=> 0,
        'role' => 4,//Customer
        'created_at' => time(),
        'updated_at' => time(),
    ];
    $ci->Common->insertData('notifications',$insertData1);
    #Send notification To Customer---------------------end-------------------

    #Send notification To all service provider who is realted to the orderd service ----start-----
    $service_provider_data = $ci->Common->getData('users','users.user_id,users.is_online,users.language','service_provider_key_skills.service_id = '.$service_id.'',array('service_provider_key_skills'),array('service_provider_key_skills.service_provider_id = users.user_id'),'users.user_id','DESC');

    
    $order_received_msg = $ci->lang->line('new_order_received_english');
    $order_received_msg_amharic = $ci->lang->line('new_order_received_amharic');

    foreach ($service_provider_data as $value) {
        
        if($value['is_online'] == 1 ){ #if provider is online then they will receive the service notification
            $token2 = $ci->Common->getData('user_devices','device_token','user_id='.$value['user_id'].' AND device_token != "null"');
            $provider_selected_language  = $value['language'];//(1 - English 2. Amharic) default 1 

            if($provider_selected_language == 2){  
                $NOTIFICATION_TITLE_PLACED = $ci->lang->line('ORDER_RECEIVED_amharic');
                $ORDER_RECEIVED = $ci->lang->line('new_order_received_amharic');
            }else{
                $NOTIFICATION_TITLE_PLACED = $ci->lang->line('ORDER_RECEIVED_english');
                $ORDER_RECEIVED = $ci->lang->line('new_order_received_english');
            }
            if(!empty($token2)){
                foreach ($token2 as  $token_value) {
                    //echo $token_value['device_token'].'<br>';
                    $provider_token = $token_value['device_token'];
                    $provider_notification_data_fields = array(
                        'message' => $NOTIFICATION_TITLE_PLACED,
                        'title' => $ORDER_RECEIVED,
                        'order_status'=> 0,
                        #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled

                        'order_number'=> $or_display_id,
                        'order_id'=> $order_id,
                        'notification_type' => 'ORDER_STATUS_UPDATED'
                    );
                    if($token2 != "")
                    {
                       sendPushNotification($provider_token, $provider_notification_data_fields,IOS_PROVIDER_BUNDLE_ID,PROVIDER_API_SERVER_KEY);
                    }
                }
                
            }

            # Now insert notification to Database
            $insertData2 = [
                'title' => "Order ".$or_display_id." ".$order_placed_msg,
                'to_user_id' => $value['user_id'],//service provider id
                'message' => $order_received_msg,
                'amharic_title' => $ci->lang->line('order_word_in_amharic').$or_display_id." ".$order_placed_msg_amharic,
                'amharic_message' => $order_received_msg_amharic,
                'type' => 1,
                'order_id' => $order_id,
                'role' => 3,//service proivder 
                'order_status'=> 0,
                'created_at' => time(),
                'updated_at' => time(),
            ];
            $ci->Common->insertData('notifications',$insertData2);
        }
    }
    #Send notification To all service provider who is realted to the service ----end-----

    #send notification to Admin --------------start---------------------
    #because we are getting service provider list in admin notification list and we are insert provider booking service notification in loop. so thats why for getting single booking booking notification we are insert only this booking notification for admin
    $insertData3 = [
            'title' => "Order ".$or_display_id." ".$order_placed_msg,
            'to_user_id' => $customer_id,//customer id
            'message' => $order_received_msg,
            'amharic_title' => $ci->lang->line('order_word_in_amharic').$or_display_id." ".$order_placed_msg_amharic,
            'amharic_message' => $order_received_msg_amharic,
            'type' => 1,
            'order_id' => $order_id,
            'role' => 1,//admin
            'order_status'=> 0,
            'created_at' => time(),
            'updated_at' => time(),
        ];
    $ci->Common->insertData('notifications',$insertData3);
    #send notification to Admin --------------end---------------------
}
//send notification while order booked by admin or customer--------END------

//generate order invoice pdf when order status change as complete-----START------ 
#this method is commanly use for API and admin controller 
function comman_generate_and_download_order_invoice_pdf($order_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $ci->load->library('pdf');
    $order_details['order_details'] = getting_order_details($order_id,2);
    
    $html = $ci->load->view('admin/order-invoice', $order_details, true);

    $ci->dompdf->loadHtml($html);

    $ci->dompdf->setPaper('A4','portrait');//landscape
    $ci->dompdf->render();
   //$ci->dompdf->stream($filename.".pdf", array("Attachment" => 0)); //for view pdf

    $output = $ci->dompdf->output();

    file_put_contents(order_invoice_upload_path()[0].'invoice/order_'.$order_id.'_invoice.pdf', $output);
    $f = 'invoice/order_'.$order_id.'_invoice.pdf';
    $file = $f;
    
   /* $filetype=filetype($file);
    $filename=basename($file);*/
   /* header ("Content-Type: ".$filetype);
    header ("Content-Length: ".filesize($file));
    header ("Content-Disposition: attachment; filename=".$filename);*/
   // readfile($file);

    $update_array = [
                       'invoice_path' => $file,
                       'updated_at' => time(),
                   ];

    #update pdf path
    $update_status = $ci->Common->updateData('service_order_bookings',$update_array,'order_id = "'.$order_id.'"');
}
//generate order invoice pdf when order status change as complete-----END------ 

//getting date according to day name----------START----------
function get_date_by_day_name(){

    $today_day_name =  date('l');

    if($today_day_name == 'Sunday'){
        $days_value = 7;
    }else if($today_day_name == 'Monday'){
        $days_value = 1;
    }else if($today_day_name == 'Tuesday'){
        $days_value = 2;
    }else if($today_day_name == 'Wednesday'){
        $days_value = 3;
    }else if($today_day_name == 'Thursday'){
        $days_value = 4;
    }else if($today_day_name == 'Friday'){
        $days_value = 5;
    }else if($today_day_name == 'Saturday'){
        $days_value = 6;
    }
 
    return $date = Date('d-m-Y', strtotime('-'.$days_value.' days')); //get  date according to day 

}
//getting date according to day name----------END----------

//getting first date of this month-----------START--------------
function get_first_date_of_month(){
    // Current timestamp is assumed, so these find first and last day of THIS month
    $first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
    //$last_day_this_month  = date('m-t-Y');

    // With timestamp, this gets last day of April 2010
   // $last_day_april_2010 = date('m-t-Y', strtotime('April 21, 2010'));



    return $first_day_this_month;
}
//getting first and last date of month-----------END--------------

//getting dashboard data------------------------START------------------
function get_dashboard_chart_data(){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $last_to_last_week_sunday_date = date('d-m-Y',strtotime('last sunday -7 days'));  
    $last_week_monday_date =  date("d-m-Y",strtotime('monday',strtotime('last week')));  
    $last_week_tuesday_date =  date("d-m-Y",strtotime('tuesday',strtotime('last week')));  
    $last_week_wednesday_date =  date("d-m-Y",strtotime('wednesday',strtotime('last week')));  
    $last_week_thursday_date =  date("d-m-Y",strtotime('thursday',strtotime('last week')));  
    $last_week_friday_date =  date("d-m-Y",strtotime('friday',strtotime('last week')));  
    $last_week_saturday_date =  date("d-m-Y",strtotime('saturday',strtotime('last week'))); 
    
    #sunday
    $sunday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_to_last_week_sunday_date.'00:00:00').'" AND "'.strtotime($last_to_last_week_sunday_date.' 23:59:59').'")';
    $sunday_data =  $ci->Common->custom_query($sunday_query,"get");
    if($sunday_data[0]['total_sale'] == ""){
        $sunday_value = "0.00";
    }else{
        $sunday_value =  $sunday_data[0]['total_sale']; 
    }
    
    #monday
    $monday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_week_monday_date.'00:00:00').'" AND "'.strtotime($last_week_monday_date.' 23:59:59').'")';
    $monday_data =  $ci->Common->custom_query($monday_query,"get");
    if($monday_data[0]['total_sale'] == ""){
        $monday_value = "0.00";
    }else{
        $monday_value =  $monday_data[0]['total_sale']; 
    }

    #tuesday
    $tuesday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4  AND ( `updated_at` between "'.strtotime($last_week_tuesday_date.'00:00:00').'" AND "'.strtotime($last_week_tuesday_date.' 23:59:59').'")';
    $tuesday_data =  $ci->Common->custom_query($tuesday_query,"get");
    
    if($tuesday_data[0]['total_sale'] == ""){
        $tuesday_value =  "0.00";
    }else{
        $tuesday_value =  $tuesday_data[0]['total_sale']; 
    }

    #wednesday
    $wednesday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_week_wednesday_date.'00:00:00').'" AND "'.strtotime($last_week_wednesday_date.' 23:59:59').'")';
    $wednesday_data =  $ci->Common->custom_query($wednesday_query,"get");

    if($wednesday_data[0]['total_sale'] == ""){
        $wednesday_value =  "0.00";
    }else{
        $wednesday_value =  $wednesday_data[0]['total_sale']; 
    }

    #thursday
    $thursday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_week_thursday_date.'00:00:00').'" AND "'.strtotime($last_week_thursday_date.' 23:59:59').'")';
    $thursday_data =  $ci->Common->custom_query($thursday_query,"get");

    if($thursday_data[0]['total_sale'] == ""){
        $thursday_value = "0.00";
    }else{
        $thursday_value =  $thursday_data[0]['total_sale']; 
    }

    #friday
    $friday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_week_friday_date.'00:00:00').'" AND "'.strtotime($last_week_friday_date.' 23:59:59').'")';
    $friday_data =  $ci->Common->custom_query($friday_query,"get");

    if($friday_data[0]['total_sale'] == ""){
        $friday_value =  "0.00";
    }else{
        $friday_value =  $friday_data[0]['total_sale']; 
    }

    #saturday
    $saturday_query = 'SELECT SUM(`total_amount`) AS total_sale FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "'.strtotime($last_week_saturday_date.'00:00:00').'" AND "'.strtotime($last_week_saturday_date.' 23:59:59').'")';
    $saturday_data =  $ci->Common->custom_query($saturday_query,"get");

    if($saturday_data[0]['total_sale'] == ""){
        $saturday_value = "0.00";
    }else{
        $saturday_value =  $saturday_data[0]['total_sale']; 
    }

    return array($sunday_value,$tuesday_value,$wednesday_value,$thursday_value,$friday_value,$saturday_value);
}
//getting dashboard data------------------------END------------------

//getting distance between customer and provider  latitude and longtitude----START-----
#reffrence https://gist.github.com/LucaRosaldi/5676464
function get_distance_between_customer_and_service_provider($customer_latitude,$customer_longitude,$provider_latitude,$provider_longitude){
    $theta = $customer_longitude - $provider_longitude;
    $miles = (sin(deg2rad($customer_latitude)) * sin(deg2rad($provider_latitude))) + (cos(deg2rad($customer_latitude)) * cos(deg2rad($provider_latitude)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('miles','feet','yards','kilometers','meters'); 
}
//getting distance between customer and provider  latitude and longtitude----END-----

//comman getting data "customer order history" and service provider service history ----START------
#Show pending, ongoing, Past, Cancelled in mobile app
function comman_getting_user_service_history_for_API($language_type = "", $user_id = "", $order_id = "", $order_view_mode = "", $user_role = "") {
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    if ($language_type == 1) {
        $service_name = 'service_name_english';
    } else if ($language_type == 2) {
        $service_name = 'service_name_amharic';
    }
    $query_part = "";
    $query_part_detail_page = "";
    if ($order_id != "") {
        $query_part_detail_page = 'AND service_order_bookings.order_id=' . $order_id . '';
    }
    $get_data_query_part = "";
    #db_order_status = (0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled )
    if ($order_view_mode == 1) {
        if ($user_role == 4) {
            #pending and ongoing services
            #As for now in mobile has two tab ongoing and past so that's why in "ongoing tab" pending to before complete services and in "past tab" compeleted and cancelled service will be show
            $query_part = 'AND service_order_bookings.order_status IN(0,1,2,3)';
        } else {
            #pending tab for service provider
            $query_part = 'AND service_order_bookings.order_status = 0';
            # "on going" services tab. services will be visible from booked to until accepted. After accepted it will be show in "on going service" tab.
            
        }
    } else if ($order_view_mode == 2 && $user_role == 3) {
        #on going untill before complete
        $query_part = 'AND service_order_bookings.order_status IN(1,2,3)';
        # "on going" services tab. services will be visible from started to until before the service is completed. After completed it will be show in "Past service" tab.
        
    } else if ($order_view_mode == 3) {
        # "Past" services tab. completed  and cancelled services will be visible only.
        if ($user_role == 4) {
            $query_part = 'AND service_order_bookings.order_status IN(4,5,6)';
        } else {
            //for service provider cancelled data will come from "service_cancel_by_service_providers" table
            $query_part = 'AND service_order_bookings.order_status IN(4,5,6)';
        }
    } /*else if($order_view_mode == 4){//Cancelled
    $get_data_query_part = ',service_order_bookings.cancel_reason';
    $query_part = 'AND service_order_bookings.order_status = 6';
    # "Cancelled" services tab. Cancelled services will be visible here.
    }*/
    else if ($order_view_mode == 5) {
        #Detail page - for ongoing and completed
        $query_part = 'AND service_order_bookings.order_status IN(1,2,3,4)';
    }
    #$db_$user_role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
    if ($user_role == 4) {
        $select_data_role_wise_query_part = 'service_order_bookings.customer_id = ' . $user_id . '';
    } else if ($user_role == 3 && $order_view_mode > 1) {
        $select_data_role_wise_query_part = 'service_order_bookings.order_accept_by = ' . $user_id . '';
    }
    if ($user_role == 3 && $order_view_mode == 1) { #$order_view_mode == order_Status == pending
        #Getting service skills of the service provider if service provider has that service skills (service id) then only that services  will be visible in pending to the service proivder
        $service_provider_key_skills = $ci->Common->getData('service_provider_key_skills', ' service_id', 'service_provider_id = ' . $user_id . '');
        $provider_pending_service_array = array();
        if (!empty($service_provider_key_skills)) {
            foreach ($service_provider_key_skills as $service_id_value) {
                
                #check service provider ignored this order (or service) if ignored then ignored servies(order) will be not be visible in pending list for only that service provider but other service provider can visible untill order accpet by someone
                #get all order id to check it exist in ignore table
                $get_pending_order_ids_by_service_id = $ci->Common->getData('service_order_bookings', 'order_id', 'order_status = 0 AND service_id=' . $service_id_value['service_id'] . '');
                $ignored_orders = '';
                if (!empty($get_pending_order_ids_by_service_id)) {
                    foreach ($get_pending_order_ids_by_service_id as $order_id_value) {
                        $check_exit_ignore_service_data = $ci->Common->getData('service_ignore_by_service_providers', 'order_id', 'service_provider_id = ' . $user_id . ' AND order_id=' . $order_id_value['order_id'] . ' AND service_id = ' . $service_id_value['service_id'] . '');
                        if (!empty($check_exit_ignore_service_data)) {
                            $ignored_orders.= $order_id_value['order_id'] . ',';
                        }
                    }
                }
                #remove last comma
                $ignored_orders = rtrim($ignored_orders, ',');
                if (!empty($ignored_orders)) {
                    #this orders/servic will not visible to service provider
                    $select_data_unignore_order_wise = 'AND service_order_bookings.order_id NOT IN(' . $ignored_orders . ')';
                } else {
                    $select_data_unignore_order_wise = '';
                }
                $service_history_data_array = $ci->Common->getData('service_order_bookings', 'service_order_bookings.order_id,service_order_bookings.order_number_id,service_order_bookings.service_price_type,service_order_bookings.customer_id,service_order_bookings.order_number_id,service_order_bookings.service_id,service_order_bookings.customer_name,service_order_bookings.customer_contact,service_order_bookings.latitude,service_order_bookings.longitude,service_order_bookings.google_pin_address,customer_contact,service_order_bookings.order_accept_by,service_order_bookings.last_till_time_of_service_accept,service_order_bookings.order_status,service_order_bookings.order_accept_by,service_order_bookings.taken_time,service_order_bookings.duration,service_order_bookings.visiting_price,service_order_bookings.created_at as booking_time,service_order_bookings.updated_at as order_status_update_time,services.' . $service_name . ' as service_name ' . $get_data_query_part . ',service_order_bookings.total_amount', ' service_order_bookings.service_id = ' . $service_id_value['service_id'] . ' ' . $select_data_unignore_order_wise . ' ' . $query_part . ' ' . $query_part_detail_page . '', array('services'), array('service_order_bookings.service_id = services.service_id'), 'service_order_bookings.order_id', 'DESC', '');
                #db_order_status = (0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled )
                

                array_push($provider_pending_service_array, $service_history_data_array);
            }
        } else {
            $service_history_data = array();
        }
        #convert in single array from multidimantion array
        $service_history_data = array();
        foreach ($provider_pending_service_array as $outer_array) {
            foreach ($outer_array as $inner_array) {
                array_push($service_history_data, $inner_array);
            }
        }
        if (!empty($service_history_data)) {
            foreach ($service_history_data as $key => $value) {
                #passing country code
                $service_history_data[$key]['country_code'] = COUNTRY_CODE;
                #passing currency
                $service_history_data[$key]['currency'] = CURRENCY;

                 //start = Getting distance between customer and service provider Ex.- 2 km away, 3 km away
                 $customer_latitude =  $value['latitude'];
                 $customer_longitude =  $value['longitude'];
                 $get_service_provider_lat_lng = $ci->Common->getData('users', 'latitude,longitude', 'user_id = "' . $user_id . '" ');
                 $provider_latitude = $get_service_provider_lat_lng[0]['latitude'];
                 $provider_longitude = $get_service_provider_lat_lng[0]['longitude'];
                 $distance = get_distance_between_customer_and_service_provider($customer_latitude,$customer_longitude,$provider_latitude,$provider_longitude);
                 $service_history_data[$key]['distance'] = $distance;
                 //end = Getting distance between customer and service provider Ex.- 2 km away, 3 km away
            }
        }
    } else {
        $service_history_data = $ci->Common->getData('service_order_bookings', 'service_order_bookings.order_id,service_order_bookings.order_number_id,service_order_bookings.service_price_type,service_order_bookings.service_id,service_order_bookings.order_number_id,service_order_bookings.customer_id,service_order_bookings.customer_name,service_order_bookings.customer_contact,service_order_bookings.latitude,service_order_bookings.longitude,service_order_bookings.google_pin_address,customer_contact,service_order_bookings.order_accept_by,service_order_bookings.taken_time,service_order_bookings.order_accept_by,service_order_bookings.duration,service_order_bookings.visiting_price,service_order_bookings.order_status,service_order_bookings.created_at as booking_time,service_order_bookings.accepted_status_time,service_order_bookings.on_the_way_status_time,service_order_bookings.start_service_time,service_order_bookings.end_service_time,services.' . $service_name . ' as service_name ' . $get_data_query_part . ',service_order_bookings.total_amount', ' ' . $select_data_role_wise_query_part . ' ' . $query_part_detail_page . ' ' . $query_part . '', array('services'), array('service_order_bookings.service_id = services.service_id'), 'service_order_bookings.order_id', 'DESC', '');
        #db_order_status = (0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled )
        //getting order accpected by name (service provider name) -----START-----
        #only customer will get service provider rating
        //if($user_role == 4){
        if (!empty($service_history_data)) {
            foreach ($service_history_data as $key => $value) {
                #passing country code
                $service_history_data[$key]['country_code'] = COUNTRY_CODE;
                #passing currency
                $service_history_data[$key]['currency'] = CURRENCY;
                if ($user_role == 3) {
                    //start = Getting distance between customer and service provider Ex.- 2 km away, 3 km away
                    $customer_latitude =  $value['latitude'];
                    $customer_longitude =  $value['longitude'];
                    $get_service_provider_lat_lng = $ci->Common->getData('users', 'latitude,longitude', 'user_id = "' . $value['order_accept_by'] . '" ');
                    $provider_latitude = $get_service_provider_lat_lng[0]['latitude'];
                    $provider_longitude = $get_service_provider_lat_lng[0]['longitude'];
                    $distance = get_distance_between_customer_and_service_provider($customer_latitude,$customer_longitude,$provider_latitude,$provider_longitude);
                    $service_history_data[$key]['distance'] = $distance;
                    //end = Getting distance between customer and service provider Ex.- 2 km away, 3 km away

                    // checking provider has given rating to customer or not for particular order
                    $check_already_give_rating = $ci->Common->getData('ratings', '*', 'from_user_id = "' . $user_id . '" AND order_id = ' . $value['order_id'] . ' AND to_user_id = ' . $value['customer_id'] . '');
                    if (!empty($check_already_give_rating)) {
                        $service_history_data[$key]['has_given_rating'] = 1; //given
                        
                    } else {
                        $service_history_data[$key]['has_given_rating'] = 0; //not given
                        
                    }
                } else if ($user_role == 4) { #for customer only
                    // checking customer has given rating to provider or not for particular order
                    $check_already_give_rating = $ci->Common->getData('ratings', '*', 'from_user_id = "' . $user_id . '" AND order_id = ' . $value['order_id'] . ' AND to_user_id = ' . $value['order_accept_by'] . '');
                    if (!empty($check_already_give_rating)) {
                        $service_history_data[$key]['has_given_rating'] = 1; //given
                        
                    } else {
                        $service_history_data[$key]['has_given_rating'] = 0; //not given
                        
                    }
                    #service provider rating data
                    $service_provider_data = $ci->Common->getData('users', 'fullname,profile_image,mobile', 'user_id = "' . $value['order_accept_by'] . '"', '', '');
                    #order_accept_by is a service provider id
                    if (!empty($service_provider_data)) {
                        #getting service provider rating
                        $average_rating = calculate_average_rating_of_service_provider($value['order_accept_by']);
                        $service_provider_data[0]['average_rating'] = $average_rating;
                        $service_provider_data[0]['img_base_path'] = img_upload_path()[0];
                        $service_history_data[$key]['service_provider_detail'] = $service_provider_data;

                    } else {
                        $service_history_data[$key]['service_provider_detail'] = array();
                    }
                }
            }
        } else {
            $service_history_data = array();
        }
        //getting order accpected by name (service provider name) ------END-----
        //}
        
    }
    return $service_history_data;
}
//comman getting data "customer order history" and service provider service history ----END------

// Order booked send email on customer email account -------START---------------
#this function is use for API and Admin controller 
function comman_send_mail_on_order_booked_success($customer_fullname,$customer_email,$customer_contact,$or_display_id,$customer_location,$service_name_english){
    $ci=& get_instance();

    # mail_send code start. This mail sends just a thankyou mail to merchant email Id
    $mail_data['name'] = trim($customer_fullname);
    $mail_data['header_title'] = APP_NAME . ' : Thank you for your intrest !';
    $mail_data['email'] = $customer_email;
    $mail_data['mobile'] = $customer_contact;
    $mail_data['order_number_id'] = $or_display_id;
    $mail_data['address'] = $customer_location;
    $mail_data['booked_service_name'] = $service_name_english;
    $email = $customer_email;
    $subject = "Welcome to " . APP_NAME;
    # Get Social urls from Database settings table
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
    $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    # load template view
    $message = $ci->load->view('email/service_booking_by_admin_or_customerApi', $mail_data, TRUE);
    send_mail($customer_email, $subject, $message);
    # mail send code end
}
// Order booked  send email on customer email account -------END---------------

//comman api user verfiy check -----------START--------------
function comman_check_user_verify_api($user_id,$data){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $token_user_status = $ci->Common->getData('users', 'user_status', 'user_id = "' . $user_id . '"');
    if (empty($token_user_status))
    # DB_user_status = 1- Enable, 2- Disable/Block, 3- Delete
    {
        return $data = '203';
    }
    if ($token_user_status[0]['user_status'] == 2) {
        return $data = '202'; //if user is disabled from the admin
    }
    if ($token_user_status[0]['user_status'] == 3) {
        return $data = '204'; //if user is deleted
    } else {
        return $data;
    }
}
//comman api user verfiy check -----------END--------------

//Send mail for admin  or customer profile update ------------START-----------
function send_email_on_admin_profile_updated($fullname, $email){
    $ci=& get_instance();
    $mail_data['name'] = trim($fullname);
    $mail_data['header_title'] = APP_NAME . ' :  Your Profile Details are Updated !';
    $mail_data['email'] = $email;
    $email = $email;
    $subject = "Admin Profile updated " . APP_NAME;
    # Get Social urls from Database settings table
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
    $mail_data['insta_url_status'] = $social_urls['insta_status'];
   
    $mail_data['website_url'] = $social_urls['website'];
    # load template view
    $message = $ci->load->view('email/admin_profile_update', $mail_data, TRUE);
    return send_mail($email, $subject, $message);
}
//Send mail for admin  or customer profile update ------------END-------------

//Send mail for update password ----------------START--------------------
function send_email_on_admin_update_password($name, $email){
    $ci=& get_instance();
    $mail_data['first_name'] = $name;
    $email = $email;
    $subject = APP_NAME . " Account Password Reset";
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
    $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    //load template view
    $message = $ci->load->view('email/reset_password_success', $mail_data, TRUE);
    //send mail
    return send_mail($email, $subject, $message);
}
//Send mail for update password ----------------END--------------------

//Send mail for forgot password --------------START--------------------
function send_email_for_forgot_password($fullname,$email,$role,$token){
    $ci=& get_instance();
    $mail_data['url'] = base_url() . 'admin/reset_password/' . $token . '/' . $role;
    $mail_data['user_name'] = $fullname;
    $mail_data['header_title'] = APP_NAME . ' Password Reset Instructions';
    $email = $email;
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
    $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    $subject = APP_NAME . " Password Reset Instructions";
    //load template view
    $message = $ci->load->view('email/forgot_password', $mail_data, TRUE);
    send_mail($email, $subject, $message);
}
//Send mail for forgot password --------------END--------------------

//Send mail for reset password ------------START-------------------
function send_email_on_update_reset_password($name,$email){
    $ci=& get_instance();
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
            $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    $mail_data['first_name'] = $name;
    $email = $email;
    $subject = APP_NAME . " Account Password Reset";
    //load template view
    $message = $ci->load->view('email/reset_password_success', $mail_data, TRUE);
    //send mail
    send_mail($email, $subject, $message);
}
//Send mail for reset password------------END-------------------

//send mail when customer details update by admin----------------START-----------
function send_email_on_create_update_customer_detail($customer_fullname,$customer_email,$customer_mobile,$mode){
    $ci=& get_instance();
    $mail_data['name'] = trim($customer_fullname);
    $mail_data['header_title'] = APP_NAME . ' : Thank you for your intrest !';
    $mail_data['email'] = $customer_email;
    $mail_data['mobile'] = $customer_mobile;
    $mail_data['mode'] = $mode; //1 - add , 2 - edit
    $email = $customer_email;
    $subject = "Welcome to " . APP_NAME;
    # Get Social urls from Database settings table
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
                $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    # load template view
    $message = $ci->load->view('email/customer_add_edit_by_admin', $mail_data, TRUE);
    // echo $message;die;
    send_mail($customer_email, $subject, $message);
    # mail send code end
}
//send mail when customer details update by admin----------------END-------------

//send mail  when provider details updated by admin----------------START----------
function send_email_on_create_update_provider_detail($service_provider_fullname,$service_provider_email,$service_provider_mobile,$mode){
    $ci=& get_instance();
    $mail_data['name'] = trim($service_provider_fullname);
    $mail_data['header_title'] = APP_NAME . ' : Thank you for your intrest !';
    $mail_data['email'] = $service_provider_email;
    $mail_data['mobile'] = $service_provider_mobile;
    $mail_data['mode'] = $mode; //1 - add , 2 - edit
    $email = $service_provider_email;
    $subject = "Welcome to " . APP_NAME;
    # Get Social urls from Database settings table
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
                $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    # load template view
    $message = $ci->load->view('email/service_provider_add_edit_by_admin', $mail_data, TRUE);
    // echo $message;die;
    send_mail($service_provider_email, $subject, $message);
}
//send mail  when provider details updated by admin----------------END------------

//send mail when customer service (User )create by super admin----------START----------
function send_email_on_user_create_update ($admin_user_fullname,$admin_user_email,$GeneratedPassword,$admin_user_mobile,$mode){
    $ci=& get_instance();
    $mail_data['name'] = trim($admin_user_fullname);
    $mail_data['header_title'] = APP_NAME . ' : Thank you for your intrest !';
    $mail_data['email'] = $admin_user_email;
    $mail_data['password'] = $GeneratedPassword;
    $mail_data['mobile'] = $admin_user_mobile;
    $mail_data['mode'] = $mode; //1 - add , 2 - edit
    $email = $admin_user_email;
    $subject = "Welcome to " . APP_NAME;
    # Get Social urls from Database settings table
    $social_urls = get_social_urls();
    $mail_data['facebook_url'] = $social_urls['facebook'];
    $mail_data['fb_url_status'] = $social_urls['fb_status'];
    $mail_data['google_url'] = $social_urls['google'];
    $mail_data['google_url_status'] = $social_urls['google_status'];
    $mail_data['insta_url'] = $social_urls['insta'];
                $mail_data['insta_url_status'] = $social_urls['insta_status'];
    $mail_data['website_url'] = $social_urls['website'];
    # load template view
    $message = $ci->load->view('email/user_add_edit_by_admin ', $mail_data, TRUE);
    $mail_success_status = send_mail($admin_user_email, $subject, $message);
    # mail send code end
}
//send mail when customer service (User )create by super admin----------END------------

#send notification when order cancel by customer by mobile -------START------------
function send_notification_on_order_cancel_by_customer($order_id,$user_id,$order_status,$device_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    #Send notification To Customer------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id,order_accept_by', 'order_id=' . $order_id . '', '', '', '', '');
    $order_accept_by = $customer_data[0]['order_accept_by'];
    $order_number_id = $customer_data[0]['order_number_id'];
    if($device_id != ""){
        $query_part = ' AND device_id ="'.$device_id.'"';
    }else{
        $query_part = '';
    }
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $user_id . ' AND device_id ="'.$device_id.'" '.$query_part.'');
    $customer_selected_language =  get_user_selected_language($user_id);
    if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_amharic');
    }else{
        $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_english');
        $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_english');
    }
    $notification_msg = $ci->lang->line('order_cancelled_success_english');
    $notification_msg_amharic = $ci->lang->line('order_cancelled_success_amharic');
   
    if (!empty($token)) {
        foreach ($token as  $token_value) {
            $customer_token = $token[0]['device_token'];
            $customer_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_CANCELLED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $order_number_id, 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($customer_token != "") {
                sendPushNotification($customer_token, $customer_notification_data_fields, IOS_CUSTOMER_BUNDLE_ID, CUSTOMER_API_SERVER_KEY);
            }
        }
        
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $order_number_id . " " . $notification_msg, 'to_user_id' => $user_id, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $order_number_id . " " . $notification_msg_amharic, 'amharic_message' => $notification_msg_amharic,'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 4, //Customer
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Customer---------------------end-------------------
    #Send notification To Service Provider------------------start-----------------

    if($order_accept_by != 0){
        $token2 = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $order_accept_by . '');

        $provider_selected_language =  get_user_selected_language($order_accept_by);
        if($provider_selected_language == 2){
            $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_amharic');
            $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_amharic');
        }else{
            $NOTIFICATION_TITLE_CANCELLED = $ci->lang->line('NOTIFICATION_TITLE_CANCELLED_english');
            $notification_msg_language_wise = $ci->lang->line('order_cancelled_success_english');
        }
        if (!empty($token2)) {
            foreach ($token2 as  $token_value) {
                $provider_token = $token_value['device_token'];
                $provider_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_CANCELLED, 'order_status' => $order_status,
                #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                'order_number' => $order_number_id, 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
                if ($provider_token != "") {
                    sendPushNotification($provider_token, $provider_notification_data_fields, IOS_PROVIDER_BUNDLE_ID, PROVIDER_API_SERVER_KEY);
                }
            }
            
        }
        # Now insert notification to Database
        $insertData = ['title' => "Order " . $order_number_id . " " . $notification_msg, 'to_user_id' => $order_accept_by, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $order_number_id . " " . $notification_msg_amharic, 'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
        #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
        'role' => 3, //Service Provider
        'created_at' => time(), 'updated_at' => time(), ];
        $ci->Common->insertData('notifications', $insertData);
    }
    #Send notification To Service Provider---------------------end-------------------
}
#send notification when order cancel by customer by mobile -------END------------


#send notification when order accepted by provider ----------START-----------
function send_notification_on_order_accept_by_provider($provider_id,$order_id,$order_status,$provider_device_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    #Send notification To Service Provider------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    
    if($provider_device_id != ""){
        $query_part = ' AND device_id ="'.$provider_device_id.'"';
    }else{
        $query_part = '';
    }
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $provider_id. ' '.$query_part.'');

    $provider_selected_language =  get_user_selected_language($provider_id);
    if($provider_selected_language == 2){
        $NOTIFICATION_TITLE_ACCEPTED = $ci->lang->line('NOTIFICATION_TITLE_ACCEPTED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_accept_success_amharic');
    }else{
        $NOTIFICATION_TITLE_ACCEPTED = $ci->lang->line('NOTIFICATION_TITLE_ACCEPTED_english');
        $notification_msg_language_wise = $ci->lang->line('order_accept_success_english');
    };
    
    $notification_msg = $ci->lang->line('order_accept_success_english');
    $notification_msg_amharic = $ci->lang->line('order_accept_success_amharic');
    
    if (!empty($token)) {
        foreach ($token as  $token_value) {
            $provider_token = $token_value['device_token'];
            $provider_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_ACCEPTED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($token != "") {
                 sendPushNotification($provider_token, $provider_notification_data_fields, IOS_PROVIDER_BUNDLE_ID, PROVIDER_API_SERVER_KEY);
            } 
        }
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $provider_id, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $notification_msg_amharic,'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 3, //Service Provider
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Service Provider---------------------end-------------------

    #Send notification To Customer------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');

    $token2 = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $customer_data[0]['customer_id'] . '');
   

    $customer_selected_language =  get_user_selected_language($customer_data[0]['customer_id']);
    if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_ACCEPTED = $ci->lang->line('NOTIFICATION_TITLE_ACCEPTED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_accept_success_amharic');
    }else{
        $NOTIFICATION_TITLE_ACCEPTED = $ci->lang->line('NOTIFICATION_TITLE_ACCEPTED_english');
        $notification_msg_language_wise = $ci->lang->line('order_accept_success_english');
    }
    $notification_msg = $ci->lang->line('order_accept_success_english');
    $notification_msg_amharic = $ci->lang->line('order_accept_success_amharic');
    if (!empty($token2)) {
        foreach ($token2 as  $token_value2) {
           // echo $token_value['device_token'].'<br>';
            $customer_token = trim($token_value2['device_token']);
            $customer_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_ACCEPTED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
           
             sendPushNotification($customer_token, $customer_notification_data_fields, IOS_CUSTOMER_BUNDLE_ID, CUSTOMER_API_SERVER_KEY);
            
        }
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $customer_data[0]['customer_id'], 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $notification_msg_amharic,'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 4, //Customer
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Customer---------------------end-------------------
    
}
#send notification when order accepted by provider ----------END-----------

#send notificaiton when update order status for on the way---------------START-----------
function send_notification_on_the_way_provider($provider_id,$order_id,$order_status,$provider_device_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");

    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    #Send notification To Service Provider------------------start------------------
    
    if($provider_device_id != ""){
        $query_part = ' AND device_id ="'.$provider_device_id.'"';
    }else{
        $query_part = '';
    }
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $provider_id. ' '.$query_part.'');

    $provider_selected_language =  get_user_selected_language($provider_id);
    if($provider_selected_language == 2){
        $NOTIFICATION_TITLE_ON_THE_WAY = $ci->lang->line('NOTIFICATION_TITLE_ON_THE_WAY_amharic');
        $notification_msg_language_wise = $ci->lang->line('service_on_the_way_success_amharic');
    }else{
        $NOTIFICATION_TITLE_ON_THE_WAY = $ci->lang->line('NOTIFICATION_TITLE_ON_THE_WAY_english');
        $notification_msg_language_wise = $ci->lang->line('service_on_the_way_success_english');
    }

    $notification_msg = $ci->lang->line('service_on_the_way_success_english');
    $notification_msg_amharic = $ci->lang->line('service_on_the_way_success_amharic');
    
    if (!empty($token)) {
        foreach ($token as  $token_value) {
            $provider_token = $token_value['device_token'];
            $provider_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_ON_THE_WAY, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($provider_token != "") {
                sendPushNotification($provider_token, $provider_notification_data_fields, IOS_PROVIDER_BUNDLE_ID, PROVIDER_API_SERVER_KEY);
            } 
        }
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $ci->lang->line('order_on_the_way_success'), 'to_user_id' => $provider_id, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $ci->lang->line('order_on_the_way_success_amharic'),'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 3, //Service Provider
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Service Provider---------------------end-------------------

    #Send notification To Customer------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    $token2 = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $customer_data[0]['customer_id'] . '');
    //echo $ci->db->last_query(); 

    $customer_selected_language =  get_user_selected_language($customer_data[0]['customer_id']);
    if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_ON_THE_WAY = $ci->lang->line('NOTIFICATION_TITLE_ON_THE_WAY_amharic');
        $notification_msg_language_wise = $ci->lang->line('service_on_the_way_success_for_customer_amharic');
    }else{
        $NOTIFICATION_TITLE_ON_THE_WAY = $ci->lang->line('NOTIFICATION_TITLE_ON_THE_WAY_english');
        $notification_msg_language_wise = $ci->lang->line('service_on_the_way_success_for_customer_english');
    }

    if (!empty($token2)) {
        foreach ($token2 as  $token_value2) {
            $customer_token = $token_value2['device_token'];
            $customer_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_ON_THE_WAY, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($customer_token != "") {
                //echo '=='.$customer_token.'';
                 sendPushNotification($customer_token, $customer_notification_data_fields, IOS_CUSTOMER_BUNDLE_ID, CUSTOMER_API_SERVER_KEY);
            }
        }
        
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $ci->lang->line('order_on_the_way_success'), 'to_user_id' => $customer_data[0]['customer_id'], 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $ci->lang->line('order_on_the_way_success_amharic'),'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 4, //Customer
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    $ci->db->last_query();
    #Send notification To Customer---------------------end-------------------
}
#send notification when order accepted by provider ----------END-----------

#send notification when update order status for started---------------START-----------
function send_notification_for_service_started_by_provider($provider_id,$order_id,$order_status,$provider_device_id){
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
 
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    $notification_msg = $ci->lang->line('order_start_success_english');
    $notification_msg_amharic = $ci->lang->line('order_start_success_amharic');
    
    #Send notification To Service Provider------------------start------------------
    if($provider_device_id != ""){
        $query_part = ' AND device_id ="'.$provider_device_id.'"';
    }else{
        $query_part = '';
    }
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $provider_id. ' '.$query_part.'');

    $provider_selected_language =  get_user_selected_language($provider_id);
    if($provider_selected_language == 2){
        $NOTIFICATION_TITLE_STARTED = $ci->lang->line('NOTIFICATION_TITLE_STARTED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_start_success_amharic');
    }else{
        $NOTIFICATION_TITLE_STARTED = $ci->lang->line('NOTIFICATION_TITLE_STARTED_english');
        $notification_msg_language_wise = $ci->lang->line('order_start_success_english');
    }
    
    if (!empty($token)) {
        foreach ($token as  $token_value) {
            $provider_token = $token_value['device_token'];
            $provider_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_STARTED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($provider_token != "") {
                sendPushNotification($provider_token, $provider_notification_data_fields, IOS_PROVIDER_BUNDLE_ID, PROVIDER_API_SERVER_KEY);
            } 
        }
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $provider_id, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $ci->lang->line('order_on_the_way_success_amharic'),'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 3, //Service Provider
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Service Provider---------------------end-------------------

    #Send notification To Customer------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    $token2 = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $customer_data[0]['customer_id'] . '');
    //echo $ci->db->last_query(); 

    $customer_selected_language =  get_user_selected_language($customer_data[0]['customer_id']);
    if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_STARTED = $ci->lang->line('NOTIFICATION_TITLE_STARTED_amharic');
        $notification_msg_language_wise = $ci->lang->line('order_start_success_amharic');
    }else{
        $NOTIFICATION_TITLE_STARTED = $ci->lang->line('NOTIFICATION_TITLE_STARTED_english');
        $notification_msg_language_wise = $ci->lang->line('order_start_success_english');
    }

    if (!empty($token2)) {
        foreach ($token2 as  $token_value2) {
            $customer_token = $token_value2['device_token'];
            $customer_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_STARTED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($customer_token != "") {
                //echo '=='.$customer_token.'';
                 sendPushNotification($customer_token, $customer_notification_data_fields, IOS_CUSTOMER_BUNDLE_ID, CUSTOMER_API_SERVER_KEY);
            }
        }
        
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $customer_data[0]['customer_id'], 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $notification_msg,'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 4, //Customer
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    $ci->db->last_query();
    #Send notification To Customer---------------------end-------------------
}
#send notification when update order status for started---------------END-----------

#send notification when update order status for completed ------------START---
function send_notification_for_service_compeleted_by_provider($provider_id,$order_id,$order_status,$provider_device_id){
     
$ci=& get_instance();
    $ci->load->database();
    $ci->load->model("Common");
 
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    $notification_msg = $ci->lang->line('service_complete_success_english');
    $notification_msg_amharic = $ci->lang->line('service_complete_success_amharic');
    
    #Send notification To Service Provider------------------start------------------
    if($provider_device_id != ""){
        $query_part = ' AND device_id ="'.$provider_device_id.'"';
    }else{
        $query_part = '';
    }
    $token = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $provider_id. ' '.$query_part.'');

    $provider_selected_language =  get_user_selected_language($provider_id);
    if($provider_selected_language == 2){
        $NOTIFICATION_TITLE_COMPLETED = $ci->lang->line('NOTIFICATION_TITLE_COMPLETED_amharic');
        $notification_msg_language_wise = $ci->lang->line('service_complete_success_amharic');
    }else{
        $NOTIFICATION_TITLE_COMPLETED = $ci->lang->line('NOTIFICATION_TITLE_COMPLETED_english');
        $notification_msg_language_wise = $ci->lang->line('service_complete_success_english');
    }
    
    
    if (!empty($token)) {
        foreach ($token as  $token_value) {
            $provider_token = $token_value['device_token'];
            $provider_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_COMPLETED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($provider_token != "") {
                sendPushNotification($provider_token, $provider_notification_data_fields, IOS_PROVIDER_BUNDLE_ID, PROVIDER_API_SERVER_KEY);
            } 
        }
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $provider_id, 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $notification_msg,'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 3, //Service Provider
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    #Send notification To Service Provider---------------------end-------------------

    #Send notification To Customer------------------start------------------
    $customer_data = $ci->Common->getData('service_order_bookings', 'customer_id,order_number_id', 'order_id=' . $order_id . '', '', '', '', '');
    $token2 = $ci->Common->getData('user_devices', 'device_token', 'user_id=' . $customer_data[0]['customer_id'] . '');
    //echo $ci->db->last_query(); 

    $customer_selected_language =  get_user_selected_language($customer_data[0]['customer_id']);
     if($customer_selected_language == 2){
        $NOTIFICATION_TITLE_COMPLETED = $ci->lang->line('NOTIFICATION_TITLE_COMPLETED_amharic');
        $notification_msg_language_wise = $ci->lang->line('service_complete_success_amharic');
    }else{
        $NOTIFICATION_TITLE_COMPLETED = $ci->lang->line('NOTIFICATION_TITLE_COMPLETED_english');
        $notification_msg_language_wise = $ci->lang->line('service_complete_success_english');
    }

    if (!empty($token2)) {
        foreach ($token2 as  $token_value2) {
            $customer_token = $token_value2['device_token'];
            $customer_notification_data_fields = array('message' => $notification_msg_language_wise, 'title' => $NOTIFICATION_TITLE_COMPLETED, 'order_status' => $order_status,
            #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            'order_number' => $customer_data[0]['order_number_id'], 'order_id' => $order_id, 'notification_type' => 'ORDER_STATUS_UPDATED');
            if ($customer_token != "") {
                //echo '=='.$customer_token.'';
                 sendPushNotification($customer_token, $customer_notification_data_fields, IOS_CUSTOMER_BUNDLE_ID, CUSTOMER_API_SERVER_KEY);
            }
        }
        
    }
    # Now insert notification to Database
    $insertData = ['title' => "Order " . $customer_data[0]['order_number_id'] . " " . $notification_msg, 'to_user_id' => $customer_data[0]['customer_id'], 'message' => $notification_msg,'amharic_title' => $ci->lang->line('order_word_in_amharic') . $customer_data[0]['order_number_id'] . " " . $notification_msg,'amharic_message' => $notification_msg_amharic, 'type' => 1, 'order_id' => $order_id, 'order_status' => $order_status,
    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
    'role' => 4, //Customer
    'created_at' => time(), 'updated_at' => time(), ];
    $ci->Common->insertData('notifications', $insertData);
    $ci->db->last_query();
    #Send notification To Customer---------------------end-------------------
}
#send notification when update order status for completed ------------end---

#Wallet history for showing on service provider details and wallet creation page--START--
function wallet_history_data($all_transactions){
    $recharge_list_tr = "";
    $count = 1;
     
    if(!empty($all_transactions)){
        foreach ($all_transactions as $value) {
            $amount = $value['amount'];
            $created_at = $value['created_at'];

            date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia cuntry
            $money_added_date  = date("d-m-Y",$created_at);// convert UNIX timestamp to PHP DateTime

            $amount_type = $value['type'];#1 -Credit, 2 -Debit
            if($amount_type == 1){
                $credited_amount = $amount.' '.CURRENCY;
                $debited_amount = '-';
            }else if($amount_type == 2){
                $debited_amount = $amount.' '.CURRENCY;
                $credited_amount = '-';
            }

            $order_id = $value['order_id'];
            $order_number_id = $value['order_number_id'];
            if($order_number_id  == ''){
                $order_number_id = "-";
            }

            $recharge_list_tr .='<tr>
                                    <td> '.$count.' </td>';
        if(isset($value['provider_name'])){
            $recharge_list_tr .='   <td><a href="'.base_url('admin/service_provider_detail/'.$value['provider_id'].'').'"> '.$value['provider_name'].' </a></td>';
        }
            $recharge_list_tr .='   
                                    <td> '.$money_added_date.' </td>
                                    <td><a href="'.base_url().'admin/order_detail/'.$order_id.'">'.$order_number_id.'</a></td>
                                    <td>'.$credited_amount.'  </td>
                                    <td>'.$debited_amount.'  </td>
                                </tr>'; 

            $count++;
        } 
        }else{
        $recharge_list_tr = "<tr><td colspan='5' class='no-records text-center'>No Records Found </td></tr>";
        }
        return $recharge_list_tr;
    }
#Wallet history for showing on service provider details and wallet creation page--END--





