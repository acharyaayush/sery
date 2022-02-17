<?php
require APPPATH.'core/Comman_Api_Controller.php';
class Api extends Comman_Api_Controller {

    //Service Category listing - getting category data for showing in mobile app ----START---
    public function service_categories_listing_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1;
            #language = (1 - English 2. Amharic) default 1
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;

            if (isset($this->tokenData->user_id)) {
                if ($language_type == 1) {
                    $cat_name = 'cat_name_english';
                } else if ($language_type == 2) {
                    $cat_name = 'cat_name_amharic';
                }
                $category_data = $this->Common->getData('service_categories', 'cat_id,' . $cat_name . ' as cat_name,cat_status,category_image', 'cat_status = 1', '', '', 'cat_id', 'DESC', $limit, $page);

                #cat_status = (1 - Enable, 2 - Disable, 3 - Deleted) Default value - 1
                if (!empty($category_data)) {
                    foreach ($category_data as $key => $value) {
                        $category_data[$key]['img_base_path'] = img_upload_path()[0];
                    }
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $category_data;
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
    //Service Category listing - getting category data for showing in mobile app ----END---
    //Sub Category Listing (Service) -----------START-----------------
    # sub categries(service) will show when click on any category
    # sub category or service is same
    public function service_sub_categories_listing_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;
            $service_category_id = !empty($_GET['category_id']) ? $this->db->escape_str($_GET['category_id']) : '';
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1;
            #language = (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                if ($service_category_id == "") {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('service_category_id_is_missing');
                    $data['data'] = array();
                } else {
                    if ($language_type == 1) {
                        $service_name = 'service_name_english';
                        $service_description = 'service_description_english';
                    } else if ($language_type == 2) {
                        $service_name = 'service_name_amharic';
                        $service_description = 'service_description_amharic';
                    }
                    $sub_categories  = $this->Common->getData('services','services.service_id,services.' . $service_name . ' as service_name,services.service_price,services.service_price_type,services.visiting_price,services.commision,services.open_time,services.close_time,services.service_image,services.service_mobile_banner,services.' . $service_description . ' as service_description,services.service_status,service_categories.cat_name_english','services.service_status = 1 AND services.category_id = '.$service_category_id.'',array('service_categories'),array('service_categories.cat_id = services.category_id'),'service_id', 'DESC', $limit, $page);
                    
                    if (!empty($sub_categories)) {
                        foreach ($sub_categories as $key => $value) { //passing currency
                            $sub_categories[$key]['img_base_path'] = img_upload_path()[0];
                            $sub_categories[$key]['currency'] = CURRENCY;
                        }
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $sub_categories;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    //Sub Category Listing (Service) -----------END-------------------

    // Service detail ---------------------------SATRT------------------------
    #geting service data according to selected service id
    public function get_service_detail_get() {
        try {
            $service_id = !empty($_GET['service_id']) ? trim($this->db->escape_str($_GET['service_id'])) : '';
            if (isset($this->tokenData->user_id)) {
                if ($service_id == "") {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('service_id_missing');
                    $data['data'] = array();
                } else {
                    $service_data = $this->Common->getData('services', '*', 'service_status = 1 AND service_id = ' . $service_id . ''); //service_status = (1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
                    if (!empty($service_data)) {
                        //passing currency
                        foreach ($service_data as $key => $value) {
                            $service_data[$key]['currency'] = CURRENCY;
                        }
                        $response['service_data'] = $service_data;
                        $response['category_data'] = $this->Common->getData('service_categories', '*', 'cat_id = ' . $service_data[0]['category_id'] . '');
                        //cat_status = 1 - Enable, 2 - Disable, 3 = delete
                        //cat_status != 3 AND
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $response;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    // Service detail ---------------------------END------------------------
    //Service Booking ------------------START-------------------------
    public function service_booking_post() {
        try {
            #customer info
            $role = !empty($_POST['service_id']) ? trim($this->db->escape_str($_POST['role'])) : '';
            $customer_fullname = !empty($_POST['customer_email']) ? trim($this->db->escape_str($_POST['customer_fullname'])) : '';
            $customer_email = !empty($_POST['customer_email']) ? trim($this->db->escape_str($_POST['customer_email'])) : '';
            $customer_fullname = !empty($_POST['customer_fullname']) ? trim($this->db->escape_str($_POST['customer_fullname'])) : '';
            $customer_contact = !empty($_POST['customer_contact']) ? trim($this->db->escape_str($_POST['customer_contact'])) : '';
            $latitude = !empty($_POST['latitude']) ? trim($this->db->escape_str($_POST['latitude'])) : '';
            $longitude = !empty($_POST['longitude']) ? trim($this->db->escape_str($_POST['longitude'])) : '';
            $customer_location = !empty($_POST['google_pin_address']) ? trim($this->db->escape_str($_POST['google_pin_address'])) : '';
            #book service info
            $service_id = !empty($_POST['service_id']) ? trim($this->db->escape_str($_POST['service_id'])) : '';
            $service_price_type = !empty($_POST['service_price_type']) ? trim($this->db->escape_str($_POST['service_price_type'])) : '';
            $service_price = !empty($_POST['service_price']) ? trim($this->db->escape_str($_POST['service_price'])) : '';
            $visiting_price = !empty($_POST['visiting_price']) ? trim($this->db->escape_str($_POST['visiting_price'])) : '';
            $city_name = !empty($_POST['city_name']) ? trim($this->db->escape_str($_POST['city_name'])) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;
            
            if (isset($this->tokenData->user_id)) {
                if ($role == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('role_missing_amharic'); }else{$data['message'] = $this->lang->line('role_missing');}
                    $data['data'] = array();
                } else if ($role != 4) {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('user_is_not_eligible_for_booking_amharic'); }else{$data['message'] = $this->lang->line('user_is_not_eligible_for_booking');}
                    $data['data'] = array();
                } else if ($customer_fullname == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('customer_fullname_is_missing_amharic'); }else{$data['message'] = $this->lang->line('customer_fullname_is_missing');}
                    $data['data'] = array();
                } else if ($customer_contact == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('contact_missing_amharic'); }else{$data['message'] = $this->lang->line('contact_missing');}
                    $data['data'] = array();
                } else if ($latitude == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('latitude_missing_amharic'); }else{$data['message'] = $this->lang->line('latitude_missing');}
                    $data['data'] = array();
                } else if ($longitude == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('longitude_missing_amharic'); }else{$data['message'] = $this->lang->line('longitude_missing');}
                    $data['data'] = array();
                } else if ($customer_location == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('longitude_missing_amharic'); }else{$data['message'] = $this->lang->line('customer_location_is_missing');}
                    $data['data'] = array();
                } else if ($service_id == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('service_id_missing_amharic'); }else{$data['message'] = $this->lang->line('service_id_missing');}
                    $data['data'] = array();
                } else if ($service_price_type == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('service_price_type_missing_amharic'); }else{$data['message'] = $this->lang->line('service_price_type_is_missing');}
                    $data['data'] = array();
                } else if ($service_price == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('service_price_missing_amharic'); }else{$data['message'] = $this->lang->line('service_price_is_missing');}
                    $data['data'] = array();
                } else if ($visiting_price == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('visiting_price_is_missing_amharic'); }else{$data['message'] = $this->lang->line('visiting_price_is_missing');}
                    $data['data'] = array();
                // }else if ($city_name == '') { //for check service avialable on that particular city or not
                //     $data['status'] = 201;
                //     if($language_type == 2){$data['message'] = $this->lang->line('city_name_missing_amharic'); }else{$data['message'] = $this->lang->line('city_name_missing');}
                //     $data['data'] = array();
                } else if ($this->tokenData->user_id > 0 && $role == 4) {                    
                    // #checking serivce is aviable 
                    // $setting_data = $this->Common->getData('settings', 'value', "name = 'selected_city'");
                    // foreach ($setting_data as $key => $cities_data) {
                    //     $cities_array = json_decode($cities_data['value']);
                    //     if(in_array($city_name,$cities_array) == false){//if city is exist in array
                    //         $is_service_available = false;
                    //     }else{
                    //         $is_service_available = true;
                            
                    //     }
                    // }

                    // if( $is_service_available == false){ #service not available
                    //     $data['status'] = 201;
                    //     if($language_type == 2){$data['message'] = $this->lang->line('service_does_not_exist_for_this_city_amharic'); }else{$data['message'] = $this->lang->line('service_does_not_exist_for_this_city');}
                    //     $data['data'] = array();
                    // }else{
                        //Note -
                        #check at the time order that  selected service is  already ongoing or not
                        #Check service id when it is ordering for the customer. It will be unique until order status completed or ignored or canceled) means the user can't order for the same service until the selected service is not finished. But customers can choose other
                        //if email not given
                        if ($customer_email != "") {
                            $email_check_query_part = 'customer_email = "' . $customer_email . '" OR ';
                        } else {
                            $email_check_query_part = '';
                        }
                        $is_service_on_going_or_complete_data = $this->Common->getData('service_order_bookings', 'order_id', 'customer_contact = ' . $customer_contact . ' AND service_id=' . $service_id . ' AND order_status IN(0,1,2,3)', '');
                        #db_order_Status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                        if (count($is_service_on_going_or_complete_data) > 0) {
                            $is_service_on_going_or_complete_status = 1; //cant book
                            
                        } else {
                            $is_service_on_going_or_complete_status = 0; // can book
                        }
                        if ($is_service_on_going_or_complete_status == 0) {
                            #tokenData->user_id is a user_id(cusromer_id)
                            #role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
                            #getting admin commission on which order service
                            $service_data = $this->Common->getData('services', 'commision,service_name_english', 'service_id = ' . $service_id . '');
                            //Create last  accpet service time for service provider----START---
                            $last_till_time_of_service_accept = generate_service_accept_till_time();
                            #DB_last_till_time_of_service_accept = This time will create according to order current time with duration time which is set by admin (settings table). Ex. order time 12:00 and durtion time 0:30 then service provider has last accept time will be 12:30 after that "customer service or super admin" will be assign service to service provider. because after complete duration admin can also assign provider
                            //Create last accpet service time for service provider----END---
                            $order_array = ['customer_id' => $this->tokenData->user_id, 'customer_name' => $customer_fullname, 'customer_email' => $customer_email, 'customer_contact' => $customer_contact, 'service_id' => $service_id, 'service_price_type' => $service_price_type, //(1- fixed, 2- hourly) Default value - 1
                            'service_price' => $service_price, 'visiting_price' => $visiting_price, 'service_name' => $service_data[0]['service_name_english'], 'latitude' => $latitude, 'longitude' => $longitude, 'google_pin_address' => $customer_location, 'order_status' => 0, //0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                            'order_booked_by' => $role, //role 1 - super admin, role 2 - customer-service
                            'last_till_time_of_service_accept' => $last_till_time_of_service_accept, 'admin_commission' => $service_data[0]['commision'], 'created_at' => time(), 'updated_at' => time() ];
                            $insert_status = $this->Common->insertData('service_order_bookings', $order_array);
                            //last inserted id
                            $order_id = $this->db->insert_id();
                            $or_display_id = generate_order_number_id($order_id);
                            $update_array = ['order_number_id' => $or_display_id, ];
                            $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                            if ($insert_status > 0) {
                                #send notification to customer and all service related providers
                                send_notification_while_service_book($this->tokenData->user_id, $or_display_id, $order_id, $service_id,$this->tokenData->device_id);
                                #send mail if email id available
                                if ($customer_email != "") {
                                comman_send_mail_on_order_booked_success($customer_fullname,$customer_email,$customer_contact,$or_display_id,$customer_location,$service_data[0]['service_name_english']);
                                }
                                #last inserted order data
                                $response['booked_data'] = $this->Common->getData('service_order_bookings', '   order_number_id,customer_email,customer_name,customer_contact,latitude,longitude,service_id,service_price,service_price_type,visiting_price,google_pin_address, created_at as ordered_date,last_till_time_of_service_accept', 'order_id = ' . $order_id . '');
                                $data['status'] = 200;
                                if($language_type == 2){$data['message'] = $this->lang->line('service_booked_success_amharic'); }else{$data['message'] = $this->lang->line('service_booked');}
                                $data['data'] = $response;
                            } else {
                                $data['status'] = 201;
                                if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                                $data['data'] = array();
                            }
                        } else {
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('service_already_running_amharic'); }else{$data['message'] = $this->lang->line('service_already_running');}
                            $data['data'] = array();
                        }
                   // }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Service Booking ------------------END-------------------------
    //Update Location-----------------START-------------------------
    public function update_user_location_post() {
        try {
            #user info
            #user can be customer or service provider (role - 4(customer), 3(service provider))
            $latitude = !empty($_POST['latitude']) ? trim($this->db->escape_str($_POST['latitude'])) : '';
            $longitude = !empty($_POST['longitude']) ? trim($this->db->escape_str($_POST['longitude'])) : '';
            $pin_address = !empty($_POST['pin_address']) ? trim($this->db->escape_str($_POST['pin_address'])) : ''; //Address 1
            $street_address = !empty($_POST['street_address']) ? trim($this->db->escape_str($_POST['street_address'])) : ''; //Address 2
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;
            if (isset($this->tokenData->user_id)) {
                if ($latitude == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('latitude_missing_amharic'); }else{$data['message'] = $this->lang->line('latitude_missing');}
                    $data['data'] = array();
                } else if ($longitude == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('longitude_missing_amharic'); }else{$data['message'] = $this->lang->line('longitude_missing');}
                    $data['data'] = array();
                } else if ($pin_address == "") {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('pin_address_missing_amharic'); }else{$data['message'] = $this->lang->line('pin_address_missing');}
                    $data['data'] = array();
                } else {
                    $update_user_array = ['latitude' => $latitude, 'longitude' => $longitude, 'google_map_pin_address' => $pin_address, //Address 1
                    'street_address' => $street_address, //Address 2
                    'updated_at' => time() ];
                    $user_update_status = $this->Common->updateData('users', $update_user_array, "user_id = " . $this->tokenData->user_id . '');
                    if ($user_update_status > 0) {
                        $response['user_location'] = $this->Common->getData('users', 'latitude,longitude,google_map_pin_address,street_address', 'user_id = ' . $this->tokenData->user_id . '');
                        $data['status'] = 200;
                        if($language_type == 2){$data['message'] = $this->lang->line('location_update_success_amharic'); }else{$data['message'] = $this->lang->line('location_update_success');}
                        $data['data'] = $response;
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
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
    //Update Location-----------------END---------------------------
    
    //Banner Slider for home page -----------------START-----------
    public function get_slider_banner_for_home_page_get() {
        try {
            if (isset($this->tokenData->user_id)) {
                $banner_data = $this->Common->getData('banners', 'banner_image', 'banner_status != 2', '', '', 'banner_id', 'DESC', '5');
                #As we  now getting 5 latest image
                #banner_status = (1 - Enable, 2 - Deleted) Default value - 1
                if (!empty($banner_data)) {
                    foreach ($banner_data as $key => $value) { $banner_data[$key]['img_base_path'] = img_upload_path()[0];}
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $banner_data;
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
    //Banner Slider for home page -----------------END------------

    //Getting "On Going" Service detail for customer app-------START-------------
    public function get_customer_on_going_services_get() {
        try {$language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                #getting all ongoing service
                $customer_all_on_going_service_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, '', '1', '4');
                #third paramter for order id which will not passing by here
                #fourth parameter for only  query part checking in "on going services" tab. only  services will be visible from started to until before the service is completed. After completed it will be show in past service tab.
                #fifth parameter is user role (4 - customer)
                if (!empty($customer_all_on_going_service_data)) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $customer_all_on_going_service_data;
                } else {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('location_update_success_amharic'); }else{$data['message'] = $this->lang->line('no_data_found');}
                    $data['data'] = array();
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Getting "On Going" Serivce for customer app-------END---------------
    //Getting "On Going" Service detail for customer app-------START-------------
    public function get_customer_on_going_service_detail_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            $order_id = !empty($_GET['order_id']) ? $this->db->escape_str($_GET['order_id']) : '';
            if (isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #getting ongoing service detail when click on user read more button of the particuar order
                    $customer_on_going_service_detail_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, $order_id, '2', '4');
                    #fifth parameter is user role (4 - customer)
                    $support_call_number = get_support_call_number();
                    $customer_on_going_service_detail_data[0]['support_call_number'] = $support_call_number;
                    if (!empty($customer_on_going_service_detail_data)) {
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $customer_on_going_service_detail_data[0];
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('location_update_success_amharic'); }else{$data['message'] = $this->lang->line('no_data_found');}
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
    //Getting "On Going" Serivce for customer app-------END---------------
    //Getting Past Completed booked services of customer -------------START-----------
    public function get_customer_past_services_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                #getting all ongoing service
                $customer_all_completed_service_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, '', '3', '4');
                #third paramter for order id which will not passing by here
                #fourth parameter for only  query part checking in "Past services" tab. only  Completed services will be visible here.
                #fifth parameter is user role (4 - customer)
                if (!empty($customer_all_completed_service_data)) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $customer_all_completed_service_data;
                } else {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('location_update_success_amharic'); }else{$data['message'] = $this->lang->line('no_data_found');}
                    $data['data'] = array();
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Getting Past Completed booked services of customer -------------END-------------
    //Cancel Service/Order by Customer --------------------START-------------------
    public function order_cancel_by_customer_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $role = !empty($_POST['role']) ? $this->db->escape_str($_POST['role']) : '';
            $cancel_reason = !empty($_POST['cancel_reason']) ? $this->db->escape_str($_POST['cancel_reason']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if(isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('order_id_missing_amharic'); }else{$data['message'] = $this->lang->line('order_id_missing');}
                    $data['data'] = array();
                } else if ($role == '' || $role != 4) {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('role_missing_amharic'); }else{$data['message'] = $this->lang->line('role_missing');}
                    $data['data'] = array();
                } else if ($cancel_reason == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('cancel_reason_missing_amharic'); }else{$data['message'] = $this->lang->line('cancel_reason_missing');}
                    $data['data'] = array();
                } else {
                    #Customer can cancel order till order status is on_the_way after service started order can not be cancel
                    #So we have to check current order status
                    $check_order_status = $this->Common->getData('service_order_bookings', 'order_status', 'order_id = ' . $order_id . '', '', '', '', '');
                    if (count($check_order_status)>0 && $check_order_status[0]['order_status'] > 2) { #3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('cant_cancel_order_amharic'); }else{$data['message'] = $this->lang->line('cant_cancel_order');}
                        $data['data'] = array();
                    } else {
                        $order_status = 6;
                        $update_array = ['order_status' => $order_status,
                        #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                        #order cancel by only contacted customer service or super admin with reason
                        #order can cancel till order status "on the way"
                        'cancel_by' => $role, //role = 4 of customer in users table
                        'cancel_reason' => $cancel_reason,
                        #If order_status = 6 and cancelled by customer (cancel_by = 4)then reason will be enter in this column. If cancel by admin/customer-service for any service provider then cancel data insert will be in "service_cancel_by_service_providers" table because order will assign other service provider again and order_status should be 1 and order_accepted_by value will be change before assign to any one order_status = 0.
                        'updated_at' => time(), ];
                        $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                        if ($update_status > 0) {
                            send_notification_on_order_cancel_by_customer($order_id,$this->tokenData->user_id,$order_status,$this->tokenData->device_id);#send notification
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('order_cancelled_success_amharic'); }else{$data['message'] = $this->lang->line('order_cancel_success');}
                            $data['data'] = array();
                        } else {
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                            $data['data'] = array();
                        }
                    }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Cancel Service/Order by Customer --------------------END-------------------
    //Booked Service status mangment by service provider----------------START-------
    public function on_the_way_status_by_service_provider_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            
            if (isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    $order_status = 2;
                    $update_array = ['order_status' => $order_status,
                    #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    #order cancel by only contacted customer service or super admin with reason
                    #order can cancel till order status "on the way"
                    'on_the_way_status_time' => time(),
                    #updated time(timestamp) when service provider change order status for on_the_way
                    'updated_at' => time(), ];
                    $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                    if ($update_status > 0) {
                        #send notification
                        send_notification_on_the_way_provider($this->tokenData->user_id,$order_id,$order_status,$this->tokenData->device_id);
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('order_on_the_way_success');
                        $data['data'] = array();
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('something_went_wrong');
                        $data['data'] = array();
                    }
                    $this->response($data, $data['status']);
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Booked Service status mangment by service provider----------------END--------
    //Getting Pending  services of service provider -------------START-----------
    public function get_service_provider_pending_services_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                #first we need to check, if service provider is offline then they cant see newly services(cant get services)
                $check_is_offline = $this->Common->getData('users', 'is_online', 'user_id = ' . $this->tokenData->user_id . '', '', '', '', '');
                if ($check_is_offline[0]['is_online'] == 0) { #service provider is offline
                    $data['status'] = 201;
                    $data['message'] = 'You are currently offline';
                    $data['data'] = array();
                } else if ($check_is_offline[0]['is_online'] == 1) { #service provider is online
                    #getting all ongoing service
                    $service_provider_all_on_going_service_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, '', '1', '3');
                    #third paramter for order id which will not passing by here
                    #fourth parameter for only  query part checking in "Pending services" tab. only  services will be visible from booked to until accepted. After accepted it will be show in on going service tab.
                    #fifth parameter is user role (3 - service provider)
                    $provider_wallet_balance = get_wallet_balance($this->tokenData->user_id);//if  wallet balance is negetive then service will not show
                    if (!empty($service_provider_all_on_going_service_data) && $provider_wallet_balance > 0) {
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $service_provider_all_on_going_service_data;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
                        $data['data'] = array();
                    }
                } else {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('something_went_wrong');
                    $data['data'] = array();
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Getting Pending services of service provider -------------END-------------
    //Getting "On Going" Service detail for service_provider app-------START-------------
    public function get_service_provider_on_going_services_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                #getting all ongoing service
                $service_provider_all_on_going_service_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, '', '2', '3');
                #third paramter for order id which will not passing by here
                #fourth parameter for only  query part checking in "on going services" tab. only  services will be visible from started to until before the service is completed. After completed it will be show in past service tab.
                #fifth parameter is user role (3 - service provider)
                if (!empty($service_provider_all_on_going_service_data)) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $service_provider_all_on_going_service_data;
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
    //Getting "On Going" Serivce for service_provider app-------END------------
    //Getting "On Going" Service detail for service_provider app-------START-------------
    public function get_service_provider_on_going_service_detail_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            $order_id = !empty($_GET['order_id']) ? $this->db->escape_str($_GET['order_id']) : '';

            if (isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #getting ongoing service detail when click on user read more button of the particuar order
                    $service_provider_on_going_service_detail_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, $order_id, '5', '3');
                    #fourth parameter for only  query part checking in "on going services" tab. only  services will be visible from started to until before the service is completed. After completed it will be show in past service tab.
                    #fifth parameter is user role (3 - service provider)
                    if (!empty($service_provider_on_going_service_detail_data)) {
                        $support_call_number = get_support_call_number();
                        $service_provider_on_going_service_detail_data[0]['support_call_number'] = $support_call_number;
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $service_provider_on_going_service_detail_data[0];
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    //Getting "On Going" Serivce for service_provider app-------END---------------
    //Getting Past Completed  services of service_provider -------------START-----------
    public function get_service_provider_past_services_get() {
        try {
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
            if (isset($this->tokenData->user_id)) {
                #getting all ongoing service
                $service_provider_all_completed_service_data = comman_getting_user_service_history_for_API($language_type, $this->tokenData->user_id, '', '3', '3');
                #third paramter for order id which will not passing by here
                #fourth parameter for only  query part checking in "Past services" tab. only  Completed services will be visible here.
                #fifth parameter is user role (3 - service provider)
                if (!empty($service_provider_all_completed_service_data)) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $service_provider_all_completed_service_data;
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
    //Getting Past Completed  services of service_provider -------------END-----------
    //Service Provider- Accept service functionality-----------------START----------------
    public function accept_service_by_service_provider_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if(isset($this->tokenData->user_id)) {
                if ($order_id == "") {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #First we need to check , condition if the service provider has any ongoing service then they can't accept any service.
                    $booked_service_data = $this->Common->getData('service_order_bookings', 'created_at as order_time,last_till_time_of_service_accept', 'order_status IN(1,2,3) AND order_accept_by=' . $this->tokenData->user_id . '', '', '', '', '');
                    #DB_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    if (!empty($booked_service_data)) {
                        #service ongoing, can not accept other service on same time
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('already_has_ongoing_service');
                        $data['data'] = array();
                    } else {
                        $order_status = 1;
                        $update_array = ['order_status' => $order_status,
                        #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                        'order_accept_by' => $this->tokenData->user_id, //service provider id
                        'accepted_status_time' => time(),
                        #update current time (timestamp) of when service provider accepting the service or assign by admin
                        'updated_at' => time(), ];
                        $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                        if ($update_status > 0) {
                            send_notification_on_order_accept_by_provider($this->tokenData->user_id,$order_id,$order_status,$this->tokenData->device_id);
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('order_accept_success_amharic'); }else{$data['message'] = $this->lang->line('service_accepted_success');}
                            $data['data'] = array();
                        } else {
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                            $data['data'] = array();
                        }
                    }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Service Provider- Accept service functionality-----------------END----------------
    //Service Provider- Ignore service functionality-----------------START--------------
    public function ignore_service_by_service_provider_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $service_id = !empty($_POST['service_id']) ? $this->db->escape_str($_POST['service_id']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if(isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else if ($service_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('service_id_missing');
                    $data['data'] = array();
                } else {
                    #here we manage all ignore services according to service provider id,service id and order id because after ignoring service, ignored services should not be visible to the particular service provider. But another service provider can be visible in the pending list.
                    #If admin reject/ignore service then no one can see ignored/rejected services
                    #we need to check if already have order id and service id is table then don't need to insert again
                    $check_exit_ignore_service_data = $this->Common->getData('service_ignore_by_service_providers', 'id', 'service_provider_id = ' . $this->tokenData->user_id . ' AND order_id=' . $order_id . ' AND service_id = ' . $service_id . '', '', '', '', '');
                    if (empty($check_exit_ignore_service_data)) {
                        $insert_array = ['order_id' => $order_id, 'service_id' => $service_id, 'service_provider_id' => $this->tokenData->user_id, 'created_at' => time() ];
                        $insert_status = $this->Common->insertData('service_ignore_by_service_providers', $insert_array);
                    } else {
                        $insert_status = 2;
                    }
                    if ($insert_status > 0 && $insert_status != 2) {
                        $data['status'] = 200;
                        if($language_type == 2){$data['message'] = $this->lang->line('order_ignored_amharic'); }else{$data['message'] = $this->lang->line('order_ignored');}
                        $data['data'] = array();
                    } else if ($insert_status == 2) {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('already_ignored_amharic'); }else{$data['message'] = $this->lang->line('already_ignored');}
                        $data['data'] = array();
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
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
    //Service Provider- Ignore service functionality-----------------END----------------
    //Service Proivder - Start service functionality-------------START-----------
    public function start_service_by_service_provider_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            
            if(isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    $order_status = 3;
                    $update_array = ['order_status' => $order_status,
                    #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    #order cancel by only contacted customer service or super admin with reason
                    #order can cancel till order status "on the way"
                    'start_service_time' => time(), 'updated_at' => time(), ];
                    $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                    if ($update_status > 0) {
                        send_notification_for_service_started_by_provider($this->tokenData->user_id,$order_id,$order_status,$this->tokenData->device_id);
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('order_started_success'); //success
                        $data['data'] = array();
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('something_went_wrong');
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
    //Service Proivder - Start service functionality--------------END------------
    //Service Proivder - Complete service functionality-----------START-----------
    public function complete_service_by_service_provider_post() {
        try {
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if(isset($this->tokenData->user_id)) {
                if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #getting time when service start by service provider to calcaulate final complete duration
                    $start_time = comman_getting_service_start_time($order_id);
                    $stop_time = time();
                    $total_time_taken = comman_getting_time_between_duration($start_time, $stop_time);
                    #getting total amount
                    $total_amount = generate_total_order_amount($order_id, $start_time, $stop_time);
                    $order_status = 4;
                    $update_array = ['order_status' => $order_status,
                    #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    #order cancel by only contacted customer service or super admin with reason
                    #order can cancel till order status "on the way"
                    'end_service_time' => time(), 'taken_time' => $total_time_taken, 'total_amount' => $total_amount, 'updated_at' => time(), ];
                    $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                    if ($update_status > 0) {
                        #send notification
                        send_notification_for_service_compeleted_by_provider($this->tokenData->user_id,$order_id,$order_status,$this->tokenData->device_id);
                        #Amount will be deduct on order complete -------------START----------
                        deduct_amount_from_provider_wallet($order_id, $total_amount);
                        #Amount will be deduct on order complete -------------END-----------
                        #generate invoice pdf ----start
                        comman_generate_and_download_order_invoice_pdf($order_id);
                        #generate invoice pdf ----end
                        $data['status'] = 200;
                        if($language_type == 2){$data['message'] = $this->lang->line('service_complete_success_amharic'); }else{$data['message'] = $this->lang->line('order_completed_success');}
                        $data['data'] = array();
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
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
    //Service Proivder - Complete service functionality-----------END-----------
    //Service Provider - Go Offline/Online ---------------------START---------
    public function go_offline_online_by_service_provider_post() {
        try {
            $offline_online_status = $_POST['status']; // 1 - go online, 0- go offline //default 1 because after login this will be 1 in db
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;
           
            if ($offline_online_status != "") {
                $offline_online_status = trim($this->db->escape_str($_POST['status']));
            } else {
                $offline_online_status = '';
            }
            if (isset($this->tokenData->user_id)) {
                if ($offline_online_status == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('status_missing_amharic'); }else{$data['message'] = $this->lang->line('status_missing');}
                    $data['data'] = array();
                } else {
                    $update_user_array = ['is_online' => $offline_online_status, //1 - User is logged in, 0 - On logout. Affected on success of registration and success of login and also service provider can do offline/online after login by self
                    'updated_at' => time() ];
                    $update_status = $this->Common->updateData('users', $update_user_array, "user_id = " . $this->tokenData->user_id . '');
                    if ($update_status > 0) {
                        if ($offline_online_status == 1) {
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('success_online_amharic'); }else{$data['message'] = $this->lang->line('success_online');}
                            $data['data'] = array();
                        } else if ($offline_online_status == 0) {
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('success_offline_amharic'); }else{$data['message'] = $this->lang->line('success_offline');}
                            $data['data'] = array();
                        }
                    } else {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
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
    //Service Provider - Go Offline/Online ---------------------END----------
    //Getting Notifications-------------------------------START----------------------
    # List my notifications start
    # This method is used to list all the notifications received by logged in customer. Same API will be used for Service Provider app also
    public function list_all_notification_get() {
        try {
            $role = !empty($_GET['role']) ? $this->db->escape_str($_GET['role']) : 4;//role = 4 (customer), 3 = service provider, 1 = super admin
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;

            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : 1;
            if (isset($this->tokenData->user_id)) {
                if ($language_type == 1) {
                    $title = 'title';
                    $msg = 'message';
                } else if ($language_type == 2) {
                    $title = 'amharic_title';
                    $msg = 'amharic_message';
                }
                $all_notifications = $this->Common->getData('notifications', 'id,role,order_id,'.$title.' as title, '.$msg.' as message,created_at,updated_at', 'to_user_id = "' . $this->tokenData->user_id . '" AND role = '.$role.'', '', '', 'id', 'DESC', $limit, $page);
                if (count($all_notifications) > 0) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $all_notifications;
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
    //Getting Notifications-------------------------------END----------------------
    // Wallet detail of service provider --------------START--------------------
    # This function is used by the service provider to get service wallet details along with all transactions
    public function wallet_details_of_service_provider_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;
            if (isset($this->tokenData->user_id)) {
                $wallet_balance = get_wallet_balance($this->tokenData->user_id);
                $response['total_balance'] = $wallet_balance;
                $response['currency'] = CURRENCY;
                # Now check how much total of MONEY ADDED = type 1
                #credited
                $response['total_money_added'] = get_total_credited_amount_for_service_provider($this->tokenData->user_id);
                # Now check how much total of MONEY Deducted = type 2
                # debited
                $response['total_debited'] = get_total_debited_amount_from_provider_wallet($this->tokenData->user_id);
                # All transaction list
                $response['all_transactions'] = get_service_provider_all_transactions($this->tokenData->user_id, $limit, $page);
                $data['status'] = 200;
                $data['message'] = $this->lang->line('success');
                $data['data'] = $response;
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    // Wallet detail of service provider --------------END--------------------
    //Give Rating to Service Provider by customer-----------------START----------------
    #customer and service provider can  give rating to each other
    public function give_rating_to_service_provider_post() {
        try {
            $rating_value = !empty($_POST['rating_value']) ? $this->db->escape_str($_POST['rating_value']) : '';
            $comment = !empty($_POST['comment']) ? $this->db->escape_str($_POST['comment']) : '';
            $service_provider_id = !empty($_POST['service_provider_id']) ? $this->db->escape_str($_POST['service_provider_id']) : '';
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if (isset($this->tokenData->user_id)) {
                if ($rating_value == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('rate_value_missing');
                    $data['data'] = array();
                } else if ($service_provider_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('service_provider_id_missing');
                    $data['data'] = array();
                } else if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #checking customer give already rating for particuar order
                    $check_already_give_rating = $this->Common->getData('ratings', '*', 'from_user_id = "' . $this->tokenData->user_id . '" AND order_id = ' . $order_id . ' AND to_user_id = ' . $service_provider_id . '');
                    if (!empty($check_already_give_rating)) {
                        $data['status'] = 201;
                        if($language_type == 2){$data['message'] = $this->lang->line('rating_already_give_amharic'); }else{$data['message'] = $this->lang->line('rating_already_give');}
                        $data['data'] = array();
                    } else {
                        $insert_rating_array = ['order_id' => $order_id, 'from_user_id' => $this->tokenData->user_id, //customer id
                        'given_rate' => $rating_value, //value 1 to 5 (Ex. - 2.5 or 4)
                        'comment' => $comment, 'to_user_id' => $service_provider_id, //service provider id
                        'created_at' => time(), 'updated_at' => time() ];
                        $insert_status = $this->Common->insertdata('ratings', $insert_rating_array);
                        if ($insert_status > 0) {
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('rating_send_success_amharic'); }else{$data['message'] = $this->lang->line('rating_send_success');}
                            $data['data'] = array();
                        } else {
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                            $data['data'] = array();
                        }
                    }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Give Rating to Service Provider by customer-----------------END------------------
    //Getting Customers all reviews which is given by service provider------START------
    #customer and service provider can  give rating to each other
    public function customer_review_screen_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $role = !empty($_GET['role']) ? $this->db->escape_str($_GET['role']) : '';
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;
            
            if ($this->tokenData === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $data['status'] = $status;
                $data['message'] = $this->lang->line('unauthorized_access');
            } else if ($role == "") {
                $data['status'] = 201;
                $data['message'] = $this->lang->line('role_missing');
                $data['data'] = array();
            } else {
                $all_reviews = getting_review($this->tokenData->user_id, $role, $limit, $page);
                if (count($all_reviews) > 0) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $all_reviews;
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
    //Getting Customers all reviews which is given by service provider-------END--------
    //Give Rating to customer by Service Provider-----------------START----------------
    #customer and service provider can  give rating to each other
    public function give_rating_to_customer_post() {
        try {
            $rating_value = !empty($_POST['rating_value']) ? $this->db->escape_str($_POST['rating_value']) : '';
            $comment = !empty($_POST['comment']) ? $this->db->escape_str($_POST['comment']) : '';
            $customer_id = !empty($_POST['customer_id']) ? $this->db->escape_str($_POST['customer_id']) : '';
            $order_id = !empty($_POST['order_id']) ? $this->db->escape_str($_POST['order_id']) : '';
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;

            if (isset($this->tokenData->user_id)) {
                if ($rating_value == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('rate_value_missing');
                    $data['data'] = array();
                } else if ($customer_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('customer_id_missing');
                    $data['data'] = array();
                } else if ($order_id == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    #checking customer give already rating for particuar order
                    $check_already_give_rating = $this->Common->getData('ratings', '*', 'from_user_id = "' . $this->tokenData->user_id . '" AND order_id = ' . $order_id . ' AND to_user_id = ' . $customer_id . '');
                    if (!empty($check_already_give_rating)) {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('rating_already_give');
                        $data['data'] = array();
                    } else {
                        $insert_rating_array = ['order_id' => $order_id, 'from_user_id' => $this->tokenData->user_id, //service provider id
                        'given_rate' => $rating_value, //value 1 to 5 (Ex. - 2.5 or 4)
                        'comment' => $comment, 'to_user_id' => $customer_id, //customer id
                        'created_at' => time(), 'updated_at' => time() ];
                        $insert_status = $this->Common->insertdata('ratings', $insert_rating_array);
                        if ($insert_status > 0) {
                            $data['status'] = 200;
                            if($language_type == 2){$data['message'] = $this->lang->line('rating_send_success_amharic'); }else{$data['message'] = $this->lang->line('rating_send_success');}
                            $data['data'] = array();
                        } else {
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                            $data['data'] = array();
                        }
                    }
                }
                $this->response($data, $data['status']);
            }
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Give Rating to customer by Service Provider-----------------END------------------
    //Getting Service Provider's all reviews which is given by customer------START------
    #customer and service provider can  give rating to each other
    public function service_provider_review_screen_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $role = !empty($_GET['role']) ? $this->db->escape_str($_GET['role']) : '';
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;
            
            if (isset($this->tokenData->user_id)) {
                if ($role == "") {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('role_missing');
                    $data['data'] = array();
                } else {
                    $all_reviews = getting_review($this->tokenData->user_id, $role, $limit, $page);
                    if (count($all_reviews) > 0) {
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $all_reviews;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    //Getting Service Provider's all reviews which is given by customer-------END--------
    # CMS----------------------------START-----------------------------------
    # This function is used to get the content for page based on the predefined passed Keys
    # termsandconditions , privacypolicy , aboutus , contactus
    public function cms_content_get() {
        try {
            $page_key = !empty($_GET['page_key']) ? $this->db->escape_str($_GET['page_key']) : '';
            #db_page_key = termsandconditions , privacypolicy , aboutus , contactus
            $page_key = strtolower(trim($page_key));
            if ($page_key == '') {
                $data['status'] = 201;
                $data['message'] = $this->lang->line('give_page_key');
                $data['data'] = array();
            } else {
                $response = $this->Common->getData('cms', '*', 'page_key = "' . $page_key . '"');
                if (!empty($response)) {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('success');
                    $data['data'] = $response[0];
                } else {
                    $data['status'] = 200;
                    $data['message'] = $this->lang->line('invalid_page_key');
                    $data['data'] = array();
                }
            }
            return $this->response($data, $data['status']);
        }
        catch(\Exception $e) {
            log_message('error', $e);
            $data['status'] = 500;
            $data['message'] = $this->lang->line('internal_server_error');
            $data['data'] = array();
            $this->response($data, $data['status']);
        }
    }
    #CMS----------------------------START-----------------------------------
    //Get All Order Invoices list  -----------------------START------------------
    public function all_order_invoices_get() {
        try {
            $page = !empty($_GET['page']) ? $this->db->escape_str($_GET['page']) : 0;
            $limit = !empty($_GET['limit']) ? $this->db->escape_str($_GET['limit']) : MOBILE_PAGE_LIMIT;
            $page = $page * $limit;
            $role = !empty($_GET['role']) ? trim($this->db->escape_str($_GET['role'])) : '';
            if (isset($this->tokenData->user_id)) {
               if ($role == '') {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('role_missing');
                    $data['data'] = array();
                } else {
                    if ($role == 4) { #customer
                        $user = 'customer_id';
                    } else if ($role == 3) { #service provider
                        $user = 'order_accept_by';
                    }
                    $all_order_invoice = $this->Common->getData('service_order_bookings', 'service_order_bookings.order_id,service_order_bookings.order_number_id,service_order_bookings.created_at as booked_date,service_order_bookings.total_amount,service_order_bookings.invoice_path', 'service_order_bookings.' . $user . ' = ' . $this->tokenData->user_id . ' AND service_order_bookings.order_status = 4', array('users'), array('service_order_bookings.' . $user . ' = users.user_id'), 'service_order_bookings.order_id', 'DESC', $limit, $page);
                    #db_order_Status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    foreach ($all_order_invoice as $key => $value) {
                        $all_order_invoice[$key]['currency'] = CURRENCY;
                        $all_order_invoice[$key]['invoice_base_path'] = order_invoice_upload_path()[0];
                    }
                    if (count($all_order_invoice) > 0) {
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $all_order_invoice;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    //Get All Order Invoices list  --------------------------END-----------------
    //Get invoice details -------------------START---------------------
    public function order_invoice_detail_get() {
        try {
            $order_id = !empty($_GET['order_id']) ? $this->db->escape_str($_GET['order_id']) : 0;
            if (isset($this->tokenData->user_id)) {
                if ($order_id == "") {
                    $data['status'] = 201;
                    $data['message'] = $this->lang->line('order_id_missing');
                    $data['data'] = array();
                } else {
                    $invoice_data = getting_order_details($order_id, 1);
                    $invoice_data[0]['currency'] = CURRENCY;
                    $invoice_data[0]['invoice_base_path'] = order_invoice_upload_path()[0];
                    if (count($invoice_data) > 0) {
                        $data['status'] = 200;
                        $data['message'] = $this->lang->line('success');
                        $data['data'] = $invoice_data;
                    } else {
                        $data['status'] = 201;
                        $data['message'] = $this->lang->line('no_data_found');
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
    //Get invoice details -------------------END---------------------   

    //Update Language ---------------------START-------------------------
     public function update_user_language_post() {
        try {
            $language_type = !empty($_POST['language_type']) ? trim($this->db->escape_str($_POST['language_type'])) : 1;
             
           if (isset($this->tokenData->user_id)) {
                $update_user_array = ['language' => $language_type,
                'updated_at' => time() ];
                $user_update_status = $this->Common->updateData('users', $update_user_array, "user_id = " . $this->tokenData->user_id . '');
                if ($user_update_status > 0) {
                    $data['status'] = 200;
                    if($language_type == 2){$data['message'] = $this->lang->line('language_update_success_amharic'); }else{$data['message'] = $this->lang->line('language_update_success');}
                    $data['data'] = array();
                } else {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('something_went_wrong_amharic'); }else{$data['message'] = $this->lang->line('something_went_wrong');}
                    $data['data'] = array();
                }
            }
            # REST_Controller provide this method to send responses
            $this->response($data, $data['status']);
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    //Update Language ---------------------END-------------------------
    # Function to get minimum allowed os version--------- start-----------
    # This method used to get minimum allowed app version for ios and android
    public function app_version_get() {
        try {
            $setting_data = $this->Common->getData('settings', '*', "name = 'customer_ios_version' or name = 'customer_android_version' or name = 'provider_android_version' or  name = 'provider_ios_version' ");
            $setting_array = array();
            foreach ($setting_data as $value) {
                $setting_array[$value['name']] = $value['value'];
            }
            $data['status'] = 200;
            $data['message'] = $this->lang->line('success');
            $data['data'] = $setting_array;
            
            $this->response($data, $data['status']);
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    # Function to get minimum allowed os version---------- end--------
    # Function to get base url of the app---------- start-----------
    public function base_url_get() {
        try {
            $res_arr = ['app_base_url' => base_url(), ];
            $data['status'] = 200;
            $data['message'] = $this->lang->line('success');
            $data['data'] = $res_arr;
            $this->response($data, $data['status']);
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }
    # Function to get base url of the app------------ end--------------

    #Function to get check customer city is belong to exist city -----START----
    #which is choosed by admin panel from setting modual
   /*
    public function check_city_belong_to_exist_cities_get(){
        try {
            $city_name = !empty($_GET['city_name']) ? trim($this->db->escape_str($_GET['city_name'])) : "";
            $language_type = !empty($_GET['language_type']) ? $this->db->escape_str($_GET['language_type']) : '1'; //(there will be available two types of languages, English and Amharic ) its for only mobile application not for admin dashboard (1 - English 2. Amharic) default 1
             
           if (isset($this->tokenData->user_id)) {
                if ($city_name == '') {
                    $data['status'] = 201;
                    if($language_type == 2){$data['message'] = $this->lang->line('city_name_missing_amharic'); }else{$data['message'] = $this->lang->line('city_name_missing');}
                    $data['data'] = array();
                } else {

                    $setting_data = $this->Common->getData('settings', 'value', "name = 'selected_city'");

                    foreach ($setting_data as $key => $cities_data) {
                        $cities_array = json_decode($cities_data['value']);
                       
                        if(in_array($city_name,$cities_array) == false){//if city is exist in array
                            $data['status'] = 201;
                            if($language_type == 2){$data['message'] = $this->lang->line('service_does_not_exist_for_this_city_amharic'); }else{$data['message'] = $this->lang->line('service_does_not_exist_for_this_city');}
                            $data['data'] = array();
                        }else{
                            $data['status'] = 200;
                            $data['message'] = $this->lang->line('success');
                            $data['data'] = array();
                        }
                    }
                }
            }
            # REST_Controller provide this method to send responses
            $this->response($data, $data['status']);
        }
        catch(\Exception $e) {
            Api_Catch_Response($e);
        }
    }*/
    #Function to get check customer city is belong to exist city -----END----
}