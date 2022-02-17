<?php
require APPPATH.'core/Comman_Api_Controller.php';
class Auth_Api extends Comman_Api_Controller {
    //comman get user details
    public function get_user_details($where = '') {
        # DB_user_status 0 - Pending 1 - Approved by admin 2 - Rejected by admin 3 - Inactive 4 - OTP verified and approval is pending 5 - Deleted
        $mendatory_where = '(user_status NOT IN (3))';
        $q_where = '';
        if ($where != '') {
            $q_where.= $where;
        }
        if ($q_where != '') {
            $final_where = $q_where . $mendatory_where;
        } else {
            $final_where = $mendatory_where;
        }
        $query = "SELECT * FROM users WHERE $final_where";
        return $detail = $this->Common->custom_query($query, 'get');
    }
    #comman function for login (service provider and customer login)
    public function comman_login_function($country_code = "", $contact = "", $role = "", $language_type = 1) {
        if ($country_code == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('country_code_missing_amharic'); }else{$data['message'] = $this->lang->line('country_code_missing');}
            $data['data'] = array();
        } else if ($contact == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('contact_missing_amharic'); }else{$data['message'] = $this->lang->line('contact_missing');}
            $data['data'] = array();
        } else if ($role == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('role_missing_amharic'); }else{$data['message'] = $this->lang->line('role_missing');}
            $data['data'] = array();
        } else {
            #get user detail according to given mobile number
            $where = '(mobile = ' . $contact . ') AND ';
            $check_mobile = $this->get_user_details($where);
            //role  = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            // user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            # First we need to check mobile number is exist in user table , if it is exist then we will do  only  update other wise it will be insert
            #service provider will registerd by only admin
            if (count($check_mobile) > 0) {
                if ($check_mobile[0]['user_status'] == 3) { // if user  is deleted
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('user_does_not_exit_amharic'); }else{$data['message'] = $this->lang->line('user_does_not_exit');}
                    $data['data'] = array();
                } else if ($check_mobile[0]['user_status'] == 2) { // if user  is disable
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('inactive_user_amharic'); }else{$data['message'] = $this->lang->line('inactive_user');}
                    $data['data'] = array();
                } else if (($check_mobile[0]['role'] != $role) || ($check_mobile[0]['role'] < 3 || $check_mobile[0]['role'] > 4)) {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('user_does_not_exit_amharic'); }else{$data['message'] = $this->lang->line('user_does_not_exit');}//user_not_eligible
                    $data['data'] = array();
                } else {
                    # Mobile number exists in Database AND status is NOT 3(deleted) and NOT 3(In-active) AND role is 3(customer-service);
                    #So send sms and update Database
                    $otp_code = generate_verification_code();
                    // $otp_code = 1234;
                    send_otp_on_mobile($otp_code , $contact);
                    $update_array = ['country_code' => $country_code, 'mobile_otp' => $otp_code, 'otp_verification' => 0,
                    //'is_online' => 0,
                    'updated_at' => time(), ];//, 'user_status' => 1,
                    # update record in users table
                    $insert_update_status = $this->Common->updateData('users', $update_array, 'mobile = "' . $contact . '" AND 	user_status != 3');
                }
            } else if ($role == 4) { //only if customer
                #service provider will registerd by only admin
                //genrate otp
                $otp_code = generate_verification_code();
                send_otp_on_mobile($otp_code , $contact);
                $insert_array = ['mobile' => $contact, 'mobile_otp' => $otp_code, 'otp_verification' => 0, 'role' => $role, 'user_status' => 1,
                'created_at' => time(), 'updated_at' => time(), ];
                $insert_update_status = $this->Common->insertData('users', $insert_array);
                #create number id and update it will be unique---START---
                $user_id = $this->db->insert_id();
                $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
                $sr_display_id = $display_id_start + $user_id;
                $update_array = ['number_id' => $sr_display_id, ];
                $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"  AND user_status != 3');
                #create number id and update ---END---
                
            } else if ($role == 3) { //if service provider
                $data['status'] = 201;
                if($language_type == 2){$data['message'] = $this->lang->line('user_does_not_exit_amharic'); }else{$data['message'] = $this->lang->line('user_does_not_exit');}
                $data['data'] = array();
            }
            if (isset($insert_update_status) && $insert_update_status > 0) {
                $res_arr = ['mobile_otp' => $otp_code, 'mobile' => $contact, ];
                // DEV_PENDING : SMS
                $data['status'] = 200;
                if($language_type == 2){$data['message'] = $this->lang->line('otp_sent_success_amharic'); }else{$data['message'] = $this->lang->line('otp_sent_success');}
                $data['data'] = $res_arr;
            } else if (isset($insert_update_status)) {
                $data['status'] = 201;
                if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                $data['data'] = array();
            }
        }
        return $data;
    }

    # User (Customer/Service Provider)Login----------START-------------
    # When user is put his mobile number then otp will genrate mobile number
    public function user_login_post() {
       try {
            $country_code = !empty($_POST['country_code']) ? $this->db->escape_str($_POST['country_code']) : '';
            $contact = !empty($_POST['contact']) ? trim($this->db->escape_str($_POST['contact'])) : '';
            $role = !empty($_POST['role']) ? $this->db->escape_str($_POST['role']) : '';//role  = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            $language_type = !empty($_POST['language_type']) ? $this->db->escape_str($_POST['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            $data = $this->comman_login_function($country_code, $contact, $role, $language_type);
            $this->response($data, $data['status']);
        } # Try End
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    # User (Customer/Service Provider)Login----------END-------------

    //comman function for verify otp (service provider / customer)
    public function comman_function_for_verify_user_otp($country_code, $contact, $role, $code, $device_id, $device_type, $device_token, $latitude, $longitude, $pin_address, $language_type) {
        if ($country_code == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('country_code_missing_amharic');}else{$data['message'] = $this->lang->line('country_code_missing');}
            $data['data'] = array();
        } else if ($contact == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('contact_missing_amharic');}else{$data['message'] = $this->lang->line('contact_missing');}
            $data['data'] = array();
        } elseif ($code == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('verification_code_missing_amharic');}else{$data['message'] = $this->lang->line('verification_code_missing');}
            $data['data'] = array();
        } else if ($device_id == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('device_id_missing_amharic');}else{$data['message'] = $this->lang->line('device_id_missing');}
            $data['data'] = array();
        } else if ($device_type == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('device_type_amharic');}else{$data['message'] = $this->lang->line('device_type_missing');}
            $data['data'] = array();
        } else if ($device_token == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('device_token_missing_amharic');}else{$data['message'] = $this->lang->line('device_token_missing');}
            $data['data'] = array();
        } else if ($role == '') {
            $data['status'] = 201;
            if($language_type == 2){$data['message'] = $this->lang->line('role_missing_amharic');}else{$data['message'] = $this->lang->line('role_missing');}
            $data['data'] = array();
        } else {
         
            $where = '(mobile = ' . $contact . ') AND ';
            $check_mobile = $this->get_user_details($where);
            if (count($check_mobile) > 0) {
                if ($check_mobile[0]['user_status'] == 2) {
                    #disable user
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('inactive_user_amharic');}else{$data['message'] = $this->lang->line('inactive_user');}//if user is disabled from the admin
                    $data['data'] = array();
                } else if ($check_mobile[0]['user_status'] == 3) {
                    #deleted user
                    $data['status'] = 202;
                    if($language_type == 2){$data['message'] = $this->lang->line('user_does_not_exit_amharic'); }else{$data['message'] = $this->lang->line('user_does_not_exit');} //if user is deleted from the admin
                    $data['data'] = array();
                } else {
                    $check_insert_or_update = "";
                    
                    if ($code == $check_mobile[0]['mobile_otp']) {
                        $update_user_array = ['country_code' => $country_code, 'otp_verification' => 1, 'is_online' => 1 //1 - User is logged in 0 - On logout. Affected on success of registration and success of login
                        , 'updated_at' => time() ];
                        if($role == 4 ){ #customer
                            $update_user_array['latitude'] = $latitude;
                            $update_user_array['longitude'] = $longitude;
                            $update_user_array['google_map_pin_address'] = $pin_address;
                        }
                        
                        $user_update_status = $this->Common->updateData('users', $update_user_array, "mobile = " . $contact . ' AND role = ' . $role . ' AND `user_status` NOT IN (2,3)'); //for customer
                        
                        if ($user_update_status > 0) {
                            $where = '(mobile = ' . $contact . ') AND role = ' . $role . ' AND ';
                            $user_details = $this->get_user_details($where);
                            $device_data = $this->Common->getData('user_devices', '*', array('user_id' => $user_details[0]['user_id'], 'device_id' => $device_id));
                            # Create token
                            if (count($device_data) > 0) {
                                /*device info update*/
                                $updateArr = array('device_type' => $device_type, 'device_id' => $device_id, 'device_token' => $device_token, 'updated_at' => time());
                                //update data in user_device table
                                $device_insert_update_status = $this->Common->updateData('user_devices', $updateArr, "user_id = " . $user_details[0]['user_id']);
                                $check_insert_or_update = 2;
                            } else {
                                //make insert array for device info
                                $device_info_array = array('user_id' => $user_details[0]['user_id'], 'device_type' => $device_type, 'device_id' => $device_id, 'device_token' => $device_token, 'created_at' => time(), 'updated_at' => time());
                                //insert record in user_device table
                                $device_insert_update_status = $this->Common->insertData("user_devices", $device_info_array);
                                $check_insert_or_update = 1;
                            }
                        }
                        $token_data = ['user_id' => $check_mobile[0]['user_id'], 'fullname' => trim($check_mobile[0]['fullname']), # It will contain user full name as John Doe
                        'email' => trim($check_mobile[0]['email']), 'role' => $role, #1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
                        'timestamp' => time(), 'otp_verification' => 1,'device_id'=>$device_id];
                        # Create a token from the user data and send it as reponse
                        $token = AUTHORIZATION::generateToken($token_data);
                        if (trim($check_mobile[0]['age'] == 0)) {
                            $age = "";
                        } else {
                            $age = trim($check_mobile[0]['age']);
                        }
                        if (trim($check_mobile[0]['gender'] == 0)) {
                            $gender = "";
                        } else {
                            $gender = trim($check_mobile[0]['gender']);
                        }
                       
                        $res_arr = ['user_id' => $check_mobile[0]['user_id'], 'fullname' => trim($check_mobile[0]['fullname']), # It will contain user full name as John Doe
                        'email' => trim($check_mobile[0]['email']), 'age' => $age, 'gender' => $gender, 'country_code' => trim($check_mobile[0]['country_code']), 'mobile' => trim($check_mobile[0]['mobile']), 'profile_image' => trim($check_mobile[0]['profile_image']), 'latitude' => trim($check_mobile[0]['latitude']), 'longitude' => trim($check_mobile[0]['longitude']), 'street_address' => trim($check_mobile[0]['street_address']), 'google_map_pin_address' => trim($check_mobile[0]['google_map_pin_address']), 'role' => $role, #1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
                        'is_online' => 1, //1 - User is logged in 0 - On logout. Affected on success of registration and success of login
                        'is_user_registered' => $check_insert_or_update, // 1 - User first time registered, 2 - exist users only logged in
                        'language' => trim($check_mobile[0]['language']), 'token' => $token,'img_base_path' => img_upload_path()[0] ];
                        $data['status'] = 200;
                        if($language_type == 2){$data['message'] = $this->lang->line('login_success_amharic'); }else{$data['message'] = $this->lang->line('login_success');}
                        $data['data'] = $res_arr;
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('verification_fail_amharic'); }else{$data['message'] = $this->lang->line('verification_fail');}
                        $data['data'] = array();
                    }
                }
            } else {
                $data['status'] = 201;
                if($language_type == 2){$data['message'] = $this->lang->line('user_not_valid_amharic'); }else{$data['message'] = $this->lang->line('user_not_valid');}
                $data['data'] = array();
            }
        }
        return $data;
    }
    
    #Verify otp when service provider enter otp for login after enter mobile number
    public function verify_otp_post() {
        try {
            $country_code = !empty($_POST['country_code']) ? trim($this->db->escape_str($_POST['country_code'])) : '';
            $contact = !empty($_POST['contact']) ? trim($this->db->escape_str($_POST['contact'])) : '';
            $code = !empty($_POST['code']) ? trim($this->db->escape_str($_POST['code'])) : '';
            $device_id = !empty($_POST['device_id']) ? trim($this->db->escape_str($_POST['device_id'])) : '';
            $device_type = !empty($_POST['device_type']) ? trim($this->db->escape_str($_POST['device_type'])) : '';
            $device_token = !empty($_POST['device_token']) ? trim($this->db->escape_str($_POST['device_token'])) : '';
            $latitude = !empty($_POST['latitude']) ? trim($this->db->escape_str($_POST['latitude'])) : '';
            $longitude = !empty($_POST['longitude']) ? trim($this->db->escape_str($_POST['longitude'])) : '';
            $pin_address = !empty($_POST['pin_address']) ? trim($this->db->escape_str($_POST['pin_address'])) : '';
            $role = !empty($_POST['role']) ? trim($this->db->escape_str($_POST['role'])) : '';#role  = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            $language_type = !empty($_POST['language_type']) ? $this->db->escape_str($_POST['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            
            $data = $this->comman_function_for_verify_user_otp($country_code, $contact, $role, $code, $device_id, $device_type, $device_token, $latitude, $longitude, $pin_address, $language_type);
            $this->response($data, $data['status']);
        } # Try End
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }

    //Logout ------------------------START----------------------------
    # This function is used to logout the user
    public function logout_post() {
        try {
             
            if (isset($this->tokenData->user_id)) {
                $this->Common->updateData('users', array('is_online' => 0), array('user_id' => $this->tokenData->user_id));
                $this->Common->deleteData('user_devices', 'user_id =' . $this->tokenData->user_id . ' AND device_id = "'.$this->tokenData->device_id.'"');
                $data['status'] = 200;
                $data['message'] = $this->lang->line('logout_success');
                $data['data'] = array();
               
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Logout ------------------------END----------------------------

    //Customer My Profile -----------------START-----------
    public function get_customer_profile_detail_get() {
        try {

            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1

            if (isset($this->tokenData->user_id)) {
                $customer_data = get_comman_profile_details($this->tokenData->user_id, $language_type);
                if (!empty($customer_data)) {
                    $customer_data['img_base_path'] = img_upload_path()[0];
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $customer_data;
                } else {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('no_data_found');
                    $data['data'] = array();
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Customer My Profile -----------------END------------
    //Service Provider "My Profile"-----------------START-------------
    public function get_service_provider_profile_detail_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                $service_provider_data = get_comman_profile_details($this->tokenData->user_id, $language_type);
                
                #getting key skills of service provider -----START-----------
                if (!empty($service_provider_data)) {
                    $key_skills = comman_get_service_provider_skills($this->tokenData->user_id, 1, $language_type);
                    if (!empty($key_skills)) {
                        $service_provider_data['skills'] = $key_skills;
                    } else {
                        $service_provider_data['skills'] = array();
                    }
                }
                #getting key skills of service provider -----END-----------
                if (!empty($service_provider_data)) {
                    $service_provider_data['img_base_path'] = img_upload_path()[0];
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $service_provider_data;
                } else {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('no_data_found');
                    $data['data'] = array();
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Service Provider "My Profile"-----------------END-------------
    //Edit/Update Customer Profile ---------------START------------
    public function customer_profile_update_post() {
        try {
            $fullname = !empty($_POST['fullname']) ? $this->db->escape_str($_POST['fullname']) : '';
            $email = !empty($_POST['email']) ? $this->db->escape_str($_POST['email']) : '';
            $country_code = !empty($_POST['country_code']) ? $this->db->escape_str($_POST['country_code']) : '';
            $mobile = !empty($_POST['mobile']) ? $this->db->escape_str($_POST['mobile']) : '';
            $gender = !empty($_POST['gender']) ? $this->db->escape_str($_POST['gender']) : '';
            $age = !empty($_POST['age']) ? $this->db->escape_str($_POST['age']) : '';
            $language_type = !empty($_POST['language_type']) ? $this->db->escape_str($_POST['language_type']) : '1'; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            
            if (isset($this->tokenData->user_id)) {
                if ($fullname == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('fullname_missing_amharic'); }else{$data['message'] = $this->lang->line('fullname_missing');}
                    $data['data'] = array();
                } else if ($country_code == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('country_code_missing_amharic'); }else{$data['message'] = $this->lang->line('country_code_missing');}
                    $data['data'] = array();
                } else{
                    //if email not send
                    if ($email != "") {
                        $email_check_query_part = 'email = "' . $email . '" AND';
                    } else {
                        $email_check_query_part = '';
                    }
                    $check_user_if_exist = $this->Common->getData('users', 'user_id', '' . $email_check_query_part . '  user_status != 3 AND user_id != ' . $this->tokenData->user_id . ''); // # role = (1 - admin, 2 - customer-service)
                    if (count($check_user_if_exist) > 0 && $email != "") {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('email_already_exists_amharic'); }else{$data['message'] = $this->lang->line('email_already_exists');}
                        $data['data'] = array();
                    } else {
                        # First get old image to unlink
                        $where = '(user_id = ' . $this->tokenData->user_id . ') AND ';
                        $image = $this->get_user_details($where);
                        $get_previous_customer_image = $image[0]['profile_image'];
                        # Check whether image uploaded
                        if (isset($_FILES['customer_image']['name']) && $_FILES['customer_image']['name'] != '') {
                            $tmp_name = $_FILES['customer_image']['tmp_name'];
                            $extension = pathinfo($_FILES['customer_image']['name'], PATHINFO_EXTENSION);
                            $f_name = basename($_FILES['customer_image']['name'], '.' . $extension) . PHP_EOL;
                            $image_name = trim($f_name) . "_" . time() . '.' . $extension;
                            $file_path = img_upload_path()[0]."customer_images/" . $image_name;
                            # replace space with _ from image name
                            $file_path = str_replace(" ", "_", $file_path);
                            $moved = move_uploaded_file($_FILES['customer_image']['tmp_name'], $file_path);
                            if (!$moved) {
                                $data['status'] = 201;
                                if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                                $data['data'] = array();
                            } else {
                                # That means image uploaded successfully so we can unlink old imge
                                $profile_image_path = "customer_images/" . $image_name;
                                #unlink prevoius image -- START--
                                if (!empty($get_previous_customer_image && file_exists($get_previous_customer_image))) {
                                    //original image path without resize
                                    unlink($get_previous_customer_image);
                                }
                                #unlink prevoius image -- END--
                            }
                        } else {
                            $profile_image_path = $get_previous_customer_image;
                        }
                        $update_array = ['fullname' => $fullname, 'email' => $email, 'country_code' => $country_code,
                        'gender' => $gender, 'profile_image' => $profile_image_path, 'age' => $age, 'language' => $language_type,
                       
                        'updated_at' => time() ];
                        $this->Common->updateData('users', $update_array, 'user_id="' . $this->tokenData->user_id . '"');
                        $data['status'] = 200;
                        if($language_type == 2){$data['message'] = $this->lang->line('profile_update_success_amharic'); }else{$data['message'] = $this->lang->line('profile_update_success');}
                        $data['data'] = array();
                    }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Edit/Update Customer Profile ---------------END--------------
}