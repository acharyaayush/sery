<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/Bcrypt.php';
require_once (APPPATH . 'libraries/send_sms.php'); 
require APPPATH.'core/Comman_Admin_Controller.php';
class Admin extends Comman_Admin_Controller {
    
    /*createBreadcrumb function START*/
    /* This Function is used to create breadcrumb by adding title and value of href*/
    public function createBreadcrumb($arr) {
        foreach ($arr as $key => $value) {
            $this->breadcrumbcomponent->add($key, $value);
        }
    }
    /*createBreadcrumb function END*/

    //Login -----------View----------START-----------------------
    public function index() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            header('location:' . base_url('admin/dashboard'));
        } else {
            $this->load->view('admin/login');
        }
    }
    //Login ------------View---------END------------------------

    //Dashboard ----------View-----------START-----------------------
    public function dashboard($fromdate = 'all', $todate = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= 'AND created_at >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= 'AND created_at <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " AND (created_at between '$fromDateNew' AND '$toDateNew')";
                }
            }
            #getting total order
            $pagedata['total_order'] = count($this->Common->getData('service_order_bookings', 'order_id', 'order_status IN(0,1,2,3,4,6)' . $query_part . ''));
            #getting total customer
            $pagedata['total_customer'] = count($this->Common->getData('users', 'user_id', 'role = 4 AND  user_status != 3  ' . $query_part . ''));
            #role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            #user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            #getting total service provider
            $pagedata['total_service_provider'] = count($this->Common->getData('users', 'user_id', 'role = 3 AND  user_status != 3 ' . $query_part . ''));
            #role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            #user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            #getting total service category
            $pagedata['total_category'] = count($this->Common->getData('service_categories', 'cat_id', 'cat_status != 3 ' . $query_part . ''));
            #cat_status =  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            #getting total service (sub category)
            $pagedata['total_service'] = count($this->Common->getData('services', 'service_id', ' service_status != 3 ' . $query_part . ''));
            #service_status =  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            #getting total service (sub category)
            $pagedata['total_banners'] = count($this->Common->getData('banners', 'banner_id', ' banner_status = 1'));
            #service_status =  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            #getting today sales
            $start_today_date = strtotime(date("d-m-Y") . ' 00:00:00');
            $end_today_date = strtotime(date("d-m-Y") . ' 23:59:59');
            $today_sale_query = 'SELECT SUM(`total_amount`) AS today_sale_totel FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "' . $start_today_date . '" AND "' . $end_today_date . '")';
            $pagedata['today_sale'] = $this->Common->custom_query($today_sale_query, "get");
            #getting this week sales
            $start_date_from_start_day = get_date_by_day_name();
            $start_day_date = strtotime($start_date_from_start_day . ' 00:00:00');
            $this_week_sale_query = 'SELECT SUM(`total_amount`) AS this_week_sale_totel FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "' . $start_day_date . '" AND "' . $end_today_date . '")';
            $pagedata['this_week_sale'] = $this->Common->custom_query($this_week_sale_query, "get");
            #getting this month sales
            $first_date_of_month = get_first_date_of_month();
            $start_month_date = strtotime($first_date_of_month . ' 00:00:00');
            $this_month_sale_query = 'SELECT SUM(`total_amount`) AS this_month_sale_totel FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "' . $start_month_date . '" AND "' . $end_today_date . '")';
            $pagedata['this_month_sale'] = $this->Common->custom_query($this_month_sale_query, "get");
            #getting this year sales
            $start_year_date = strtotime(' 01-01-' . date('Y') . ' 00:00:00');
            $this_year_sale_query = 'SELECT SUM(`total_amount`) AS this_year_sale_totel FROM `service_order_bookings` WHERE order_status = 4 AND ( `updated_at` between "' . $start_year_date . '" AND "' . $end_today_date . '")';
            $pagedata['this_year_sale'] = $this->Common->custom_query($this_year_sale_query, "get");
            #getting all zone data
            $pagedata['all_zone_data']  = $this->Common->getData('zones', 'zones.*', 'status = 1');
            #getting order customer latlng 
            $pagedata['customer_latlng_data']  = $this->Common->getData('   service_order_bookings', 'latitude,longitude', 'order_status = 0');//  pending orders getting
            $array = array('Dashboard' => base_url('admin/dashboard'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Dashboard';
            $pagedata['pageName'] = 'admin/dashboard';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Dashboard ------------View---------END------------------------

    //Orders ---------------View----------START----------------------
    #Service booking list
    public function orders($table_data = '', $order_page_value = '', $fromdate = 'all', $todate = 'all', $order_status = 'all', $service_id_for_search = 'all', $search_key = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['order_page_value'] = $order_page_value;
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['order_status'] = $order_status; //(1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            $pagedata['service_id_for_search'] = $service_id_for_search;
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            $page_query_part = "";
            if ($order_page_value != "all" || $fromdate != "all" || $todate != "all" || $search_key != "all" || $order_status != "all" || $service_id_for_search != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= 'AND  `service_order_bookings`.`created_at` >= "' . strtotime($fromdate) . '"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= 'AND `service_order_bookings`.`created_at` <= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " AND  (`service_order_bookings`.`created_at` between '$fromDateNew' and '$toDateNew')";
                }
                if ($order_page_value != "all") {
                    if ($order_page_value == READY_FOR_ASSIGN_ORDER_PAGE_VALUE) {
                        #completed order will be show
                        $page_query_part.= '`service_order_bookings`.`order_status` IN(0) AND `service_order_bookings`.`order_accept_by` = 0';
                        //order_accept_by` = 0 service not accepted  by service provider or assign yet and need to assign
                        
                    } else if ($order_page_value == ONGOING_ORDER_PAGE_VALUE) {
                        #pending and ongoing order will be show
                        $page_query_part.= '`service_order_bookings`.`order_status` IN(0,1,2,3)  AND `service_order_bookings`.`order_accept_by` != 0';
                        //order_accept_by` != 0  service assigned or accpeted
                        
                    } else if ($order_page_value == COMPLETED_ORDER_PAGE_VALUE) {
                        #completed order will be show
                        $page_query_part.= '`service_order_bookings`.`order_status` IN(4)';
                    } else if ($order_page_value == CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE) {
                        #completed order will be show
                        $page_query_part.= '`service_order_bookings`.`order_status` IN(6)';
                    }
                }
                if ($order_status != "all") {
                    $query_part.= ' AND  `service_order_bookings`.`order_status` = "' . $order_status . '"';
                }
                if ($service_id_for_search != "all") {
                    $query_part.= ' AND  `service_order_bookings`.`service_id` = "' . $service_id_for_search . '"';
                }
                if ($search_key != "all") {
                    if ($search_key == 'Fixed' || $search_key == 'Hourly') {
                        if ($search_key == 'Fixed') {
                            $search_key = 1;
                        } else if ($search_key == 'Hourly') {
                            $search_key = 2;
                        }
                        $query_part.= ' AND  `service_order_bookings`.`service_price_type` = "' . $search_key . '"';
                    } else {
                        $query_part.= ' AND (`service_order_bookings`.`customer_name` LIKE "%' . $search_key . '%" OR `service_order_bookings`.`customer_email` LIKE "%' . $search_key . '%" OR `service_order_bookings`.`customer_contact` LIKE "%' . $search_key . '%" OR `service_order_bookings`.`google_pin_address` LIKE "%' . $search_key . '%" OR `service_order_bookings`.`order_number_id` LIKE "%' . $search_key . '%"  OR `service_order_bookings`.`service_name` LIKE "%' . $search_key . '%" OR `service_order_bookings`.`taken_time` LIKE "%' . $search_key . '%"  OR `service_order_bookings`.`total_amount` LIKE "%' . $search_key . '%")';
                    }
                }
            }
            if ($order_page_value == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE) {
                #completed order will be show
                $common_query = 'SELECT `service_cancel_by_service_providers`.* ,`services`.`service_name_english`,`services`.`service_price_type` FROM `service_cancel_by_service_providers`INNER JOIN `services` ON  `service_cancel_by_service_providers`.`service_id` = `services`.`service_id`  ORDER BY `order_id` DESC';
                #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                
            } else {
                $common_query = 'SELECT `service_order_bookings`.* FROM `service_order_bookings` WHERE  ' . $page_query_part . ' ' . $query_part . ' ORDER BY `order_id` DESC';
                #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
            }
            //pagination  ---start----
            $page_segment = 10;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $booked_data = $this->Common->custom_query($query, "get");
            if ($order_page_value == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE) {
                //getting orders details and customer details----------START----------
                if (!empty($booked_data)) {
                    foreach ($booked_data as $key => $value) {
                        $order_data = $this->Common->getData('service_order_bookings', 'service_order_bookings.*,services.service_price_type ', 'service_order_bookings.order_id = ' . $value['order_id'] . '', array('services'), array('service_order_bookings.service_id = services.service_id'), '', '', '');
                        if (!empty($order_data)) {
                            $booked_data[$key]['order_number_id'] = $order_data[0]['order_number_id'];
                            $booked_data[$key]['customer_name'] = $order_data[0]['customer_name'];
                            $booked_data[$key]['created_at'] = $order_data[0]['created_at'];
                            $booked_data[$key]['service_name'] = $order_data[0]['service_name'];
                            $booked_data[$key]['order_accept_by'] = $order_data[0]['order_accept_by'];
                        } else {
                            $booked_data[$key]['order_number_id'] = '';
                            $booked_data[$key]['customer_name'] = '';
                            $booked_data[$key]['created_at'] = '';
                            $booked_data[$key]['order_accept_by'] = '';
                        }
                    }
                }
                //getting orders details and customer details----------END----------
            }
            //getting order accpected by name (service provider name) -----START-----
            if (!empty($booked_data)) {
                foreach ($booked_data as $key => $value) {
                    $service_provider_data = $this->Common->getData('users', 'fullname,', 'user_id = "' . $value['order_accept_by'] . '"', '', '');
                    #order_accept_by = service provider id
                    if (!empty($service_provider_data)) {
                        $booked_data[$key]['service_provider_name'] = $service_provider_data[0]['fullname'];
                    } else {
                        if ($order_page_value == CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE || $order_page_value == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE) {
                            $booked_data[$key]['service_provider_name'] = 'N/A';
                        } else {
                            $booked_data[$key]['service_provider_name'] = 'Assign Order';
                        }
                    }
                }
            } else {
                $booked_data = array();
            }
            //getting order accpected by name (service provider name) ------END-----
            $pagedata['booked_data'] = $booked_data;
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/orders/0/' . $order_page_value . '/' . $fromdate . '/' . $todate . '/' . $order_status . '/' . $service_id_for_search . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            //getting service category----start----
            $pagedata['service_categories'] = $this->Common->getData('service_categories', '*', 'cat_status != 3', '', '', 'cat_id', 'DESC'); //  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //getting service category----end----
            //getting services for search filter----start----
            $pagedata['services_for_search'] = $this->Common->getData('services', 'service_id,service_name_english', 'service_status != 3', '', '', 'service_id', 'DESC'); //  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //getting services for search filter----end----
            $array = array('Orders' => base_url('admin/order'));
            $this->createBreadcrumb($array);
            $pagedata['order_page_value'] = $order_page_value;
            $pagedata['pageTitle'] = 'Orders';
            $pagedata['pageName'] = 'admin/order';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/order-list-table', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Orders ----------------View---------END----------------------

    //Order Detail ------------View-------------START----------------------
    public function order_detail($order_id = "") {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            if ($order_id != "") {
                #getting order details
                $order_detail = getting_order_details($order_id, 2); #this function is also working for invoice page and api
                $pagedata['order_details'] = $order_detail;
                //Getting service provider list -------START---------
                if ($order_detail[0]['order_accept_by'] > 0) {
                    $query_part = 'AND notifications.to_user_id != ' . $order_detail[0]['order_accept_by'] . '';
                }
                #who got the service request but not accepted the service
                $pagedata['who_got_service_order'] = $this->Common->getData('notifications', 'users.fullname as service_provider_name,users.user_id, users.number_id', 'notifications.order_id = ' . $order_id . ' AND notifications.role = 3 AND notifications.order_status = 0 ' . $query_part . '', array('users'), array('notifications.to_user_id = users.user_id'), '', '', '');
                #role in user table - 3 for service provider
                //Getting service provider list -------END---------
                $array = array('order_details' => base_url('admin/order_details'));
                $this->createBreadcrumb($array);
                $pagedata['pageTitle'] = 'Order Details';
                $pagedata['pageName'] = 'admin/order_details';
                $this->load->view('admin/masterpage', $pagedata);
            } else {
                redirect(base_url('admin/errors_404'));
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Order Detail ------------View-------------END----------------------

    //Customer Management ---------View----------------START----------------------
    public function customer_Management($table_data = '', $fromdate = 'all', $todate = 'all', $user_status = 'all', $gender = 'all', $search_key = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['user_status'] = $user_status; //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            $pagedata['gender'] = $gender; //1- male, 2- female, 3- other
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $user_status != "all" || $gender != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " and  (created_at between '$fromDateNew' and '$toDateNew')";
                }
                if ($user_status != "all") {
                    $query_part.= ' AND `user_status` = "' . $user_status . '"';
                }
                if ($gender != "all"){
                    $query_part.= ' AND `gender` = "' . $gender . '"';
                }
                if ($search_key != "all") {
                    if($search_key == 'Female' || $search_key == 'female'  || $search_key == 'male' || $search_key == 'Male'){
                        if($search_key == 'Female' || $search_key == 'female'){
                            $gender = 2;
                        }else if($search_key == 'male' || $search_key == 'Male'){
                            $gender = 1;
                        }
                        $query_part.= ' AND `gender` = "' . $gender . '"';
                    }else{
                        $query_part.= ' AND (`fullname` LIKE "%' . $search_key . '%" OR `mobile` LIKE "%' . $search_key . '%" OR `email` LIKE "%' . $search_key . '%" OR `age` LIKE "%' . $search_key . '%")';
                    }
                }
            }
            //get service data
            $common_query = 'SELECT * FROM `users` WHERE `user_status` != 3 AND `role` = 4 ' . $query_part . 'ORDER BY `user_id` DESC ';
            //  role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            //pagination  ---start----
            $page_segment = 9;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['customer_data'] = $this->Common->custom_query($query, "get");

            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/customer_Management/0/' . $fromdate . '/' . $todate . '/' . $user_status . '/' . $gender . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('customer_Management' => base_url('admin/customer_Management'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Customer Management';
            $pagedata['pageName'] = 'admin/customer-management';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/customer-list-table', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Customer Management ---------View----------------END----------------------

    //Customer Detail ------------View-------------START----------------------
    public function customer_detail($user_id = "") {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            if ($user_id != "") {
                #getting customer details
                $customer_detail = $this->Common->getData('users', '*', 'user_id = ' . $user_id . ' AND user_status != 3'); //   (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
                $pagedata['customer_detail'] = $customer_detail;
                #customers order details
                $pagedata['customer_order_details'] = $this->Common->getData('service_order_bookings', 'service_order_bookings.*,services.service_name_english,services.service_image', 'service_order_bookings.customer_id = ' . $user_id . '', array('services'), array('service_order_bookings.service_id = services.service_id'), 'service_order_bookings.order_id', 'DESC', '');
                $pagedata['customer_total_orders'] = count($pagedata['customer_order_details']);
                $array = array('customer_detail' => base_url('admin/customer_detail'));
                $this->createBreadcrumb($array);
                $pagedata['pageTitle'] = 'Customer Details';
                $pagedata['pageName'] = 'admin/customer-details';
                $this->load->view('admin/masterpage', $pagedata);
            } else {
                redirect(base_url('admin/errors_404'));
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Customer Detail ------------View-------------END----------------------

    public function comman_check_skills_exist_in_service_table($skills_name = ""){
        $geting_service_id = $this->Common->getData('services', 'service_id', 'service_name_english  LIKE "%' .$skills_name . '%"');

        $geting_provider_id = $this->Common->getData('service_provider_key_skills', 'service_provider_id', 'service_id ='.$geting_service_id[0]['service_id'].'');
       

        foreach ($geting_provider_id as $key1 => $value1) {
           $skills_provider_ids[] = $geting_provider_id[$key1]['service_provider_id'];
        }
        return $skills_provider_ids;
    }
    //Service Provider -------------View------------START---------------------
    public function service_provider_management($table_data = '', $fromdate = 'all', $todate = 'all', $user_status = 'all', $service_provider_rating = 'all', $search_key = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['user_status'] = $user_status; //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            $pagedata['service_provider_rating'] = $service_provider_rating; //1- male, 2- female, 3- other
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            $rating_query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $user_status != "all" || $service_provider_rating != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " and  (created_at between '$fromDateNew' and '$toDateNew')";
                }
                if ($user_status != "all") {
                    $query_part.= ' AND `user_status` = "' . $user_status . '"';
                }
                 
                if ($search_key != "all") {
                    if (strpos($search_key, ',') !== false) {#if multiskills is searched with , by url
                        $searched_skills_array = explode(',', $search_key);
                        foreach ($searched_skills_array as $key => $value) {
                            $value = trim($value);
                            $skills_provider_ids = $this->comman_check_skills_exist_in_service_table($value);
                        }
                    }else{
                        $skills_provider_ids = $this->comman_check_skills_exist_in_service_table($search_key);
                    }

                    $user_ids = "";
                    $counter = 1;
                    $total_users = count($skills_provider_ids);
                    if(isset($skills_provider_ids) && !empty($skills_provider_ids)){
                        foreach ($skills_provider_ids as $key => $value) {
                            if($counter < $total_users){
                                $comma = ', ';
                            }else{
                                $comma = '';
                            }
                            $user_ids .= $value.$comma;
                            $counter++;
                        }

                       $query_part.= ' AND `user_id` IN('.$user_ids.')';
                    }else{
                        $query_part.= ' AND (`fullname` LIKE "%' . $search_key . '%" OR `mobile` LIKE "%' . $search_key . '%" OR `email` LIKE "%' . $search_key . '%")';
                    }
                }
            }

            //get provider data
            $common_query = 'SELECT * FROM `users` WHERE `user_status` != 3 AND `role` = 3 ' . $query_part . 'ORDER BY `user_id` DESC ';
            // role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            //pagination  ---start----
            $page_segment = 9;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $service_provider_data = $this->Common->custom_query($query, "get");
            $filterate_provivder_data = [];
            #Getting rating and skills------------START----------------
            if (!empty($service_provider_data)) {
                foreach ($service_provider_data as $key => $value) {
                    //getting average rating for showing in the table list ---start---
                    $average_rating = calculate_average_rating_of_service_provider($value['user_id']);
                    if($service_provider_rating == $average_rating || $service_provider_rating == 'all'){
                        $filterate_provivder_data[$key] = $value;
                        $filterate_provivder_data[$key]['avg_rating'] = $average_rating;

                        //getting key skills of service provider -----START-----------
                        $key_skills = $this->Common->getData('service_provider_key_skills', 'service_provider_key_skills.*,services.service_name_english,service_categories.cat_name_english', 'service_provider_key_skills.service_provider_id = ' . $value['user_id'] . '', array('services', 'service_categories'), array('services.service_id = service_provider_key_skills.service_id', 'service_categories.cat_id = service_provider_key_skills.category_id'));
                        if (!empty($key_skills)) {
                            $filterate_provivder_data[$key]['skills'] = $key_skills;
                        } else {
                            $filterate_provivder_data[$key]['skills'] = array();
                        }
                        $filterate_provivder_data[$key]['wallet_balance'] = get_wallet_balance($value['user_id']);
                        //getting key skills of service provider -----END-----------
                    }
                }
            }
            
            #Getting rating and skills------------END----------------
            $pagedata['service_provider_data'] = $filterate_provivder_data;
            //getting service category----start----
            $pagedata['service_categories'] = $this->Common->getData('service_categories', '*', 'cat_status != 3', '', '', 'cat_id', 'DESC'); //  (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //getting service category----end----
            if (!empty($service_provider_data)) {
                $query = "" . $common_query . "";
                $total_records = count($this->Common->custom_query($query, "get"));
                $url = base_url('admin/service_provider_management/0/' . $fromdate . '/' . $todate . '/' . $user_status . '/' . $service_provider_rating . '/' . $search_key . '/'); //by default table value is 0
                # Pass parameter to common pagination and create pagination function start
                create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
                $pagedata['links'] = $this->pagination->create_links();
                $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            }
            //pagination  ---End----
            $array = array('service_provider_management' => base_url('admin/service_provider_management'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Service Provider';
            $pagedata['pageName'] = 'admin/service-provider-management';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/service-provider-list-table', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Service Provider ---------------View----------END---------------------

    //Service Provider Detail ------------View-------------START----------------------
    public function service_provider_detail($user_id = "") {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            if ($user_id != "") {
                #getting Service Provider details
                $service_provider_detail = $this->Common->getData('users', '*', 'user_id = ' . $user_id . ' AND user_status != 3'); //   (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
                if (!empty($service_provider_detail)) {
                    $key_skills = comman_get_service_provider_skills($user_id, 2, 1);
                    if (!empty($key_skills)) {
                        $service_provider_detail[0]['skills'] = $key_skills;
                    } else {
                        $service_provider_detail[0]['skills'] = array();
                    }
                }
                $pagedata['service_provider_detail'] = $service_provider_detail;
                #getting service provider wallet balance
                $wallet_balance = get_wallet_balance($user_id);
                $pagedata['wallet_balance'] = $wallet_balance;
                #getting all added (credited)
                $pagedata['total_money_added'] = get_total_credited_amount_for_service_provider($user_id);
                #getting all deducted (debited) amount
                $pagedata['total_debited'] = get_total_debited_amount_from_provider_wallet($user_id);
                #Getting all transactions details of the provider
                $pagedata['all_transactions'] = get_service_provider_all_transactions($user_id,'','',3);//second paramete Role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer)
                #Provider order details
                $pagedata['provider_order_details'] = $this->Common->getData('service_order_bookings', 'service_order_bookings.*,services.service_name_english,services.service_image', 'service_order_bookings.order_accept_by = ' . $user_id . '', array('services'), array('service_order_bookings.service_id = services.service_id'), 'service_order_bookings.order_id', 'DESC', '');
                $pagedata['provider_total_orders'] = count($pagedata['provider_order_details']);
                $array = array('service_provider_detail' => base_url('admin/service_provider_detail'));
                $this->createBreadcrumb($array);
                $pagedata['pageTitle'] = 'Service Provider Details';
                $pagedata['pageName'] = 'admin/service-provider-details';
                $this->load->view('admin/masterpage', $pagedata);
            } else {
                redirect(base_url('admin/errors_404'));
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Service Provider Detail ------------View-------------END----------------------

    //Service Category -------------View------------START---------------------
    public function service_categories($table_data = '', $fromdate = 'all', $todate = 'all', $cat_status = 'all', $search_key = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['cat_status'] = $cat_status; //(1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $cat_status != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " AND  (created_at between '$fromDateNew' AND '$toDateNew')";
                }
                if ($cat_status != "all") {
                    $query_part.= ' AND (`cat_status` = "' . $cat_status . '")';
                }
                if ($search_key != "all") {
                    $query_part.= ' AND (`cat_name_english` LIKE "%' . $search_key . '%" OR `cat_name_amharic` LIKE "%' . $search_key . '%" )';
                }
            }
            $common_query = 'SELECT * FROM `service_categories` WHERE `cat_status` != 3 ' . $query_part . 'ORDER BY `cat_id` DESC '; //(1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //pagination  ---start----
            $page_segment = 8;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['category_data'] = $this->Common->custom_query($query, "get");
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/service_categories/0/' . $fromdate . '/' . $todate . '/' . $cat_status . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('service_categories' => base_url('admin/service_categories'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Service Categories';
            $pagedata['pageName'] = 'admin/service-category';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/service-catgory-list', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Service Category  ---------------View----------END---------------------

    //Service Management -------------View------------START---------------------
    public function service_management($table_data = '', $fromdate = 'all', $todate = 'all', $service_price_type = 'all', $service_category_id = 'all', $service_status = 'all', $search_key = 'all') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['service_price_type'] = $service_price_type; //(1- fixed, 2- hourly) Default value - 1
            $pagedata['service_category_id'] = $service_category_id;
            $pagedata['service_status'] = $service_status; //(1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $service_price_type != "all" || $service_category_id != "all" || $service_status != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `services`.`created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `services`.`created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " and  (services.created_at between '$fromDateNew' and '$toDateNew')";
                }
                if ($service_status != "all") {
                    $query_part.= ' AND `services`.`service_status` = "' . $service_status . '"';
                }
                if ($service_price_type != "all") {
                    $query_part.= ' AND `services`.`service_price_type` = "' . $service_price_type . '"';
                }
                if ($service_category_id != "all") {
                    $query_part.= ' AND `services`.`category_id` = "' . $service_category_id . '"';
                }
                if ($search_key != "all") {
                    if ($search_key == 'Fixed' || $search_key == 'Hourly') {
                        if ($search_key == 'Fixed') {
                            $search_key = 1;
                        } else if ($search_key == 'Hourly') {
                            $search_key = 2;
                        }
                        $query_part.= ' AND  `services`.`service_price_type` = "' . $search_key . '"';
                    } else {
                    $query_part.= ' AND (`services`.`service_name_english` LIKE "%' . $search_key . '%" OR  `services`.`service_name_amharic` LIKE "%' . $search_key . '%" OR  `services`.`service_price` LIKE "%' . $search_key . '%"  OR  `services`.`visiting_price` LIKE "%' . $search_key . '%" OR  `services`.`commision` LIKE "%' . $search_key . '%" OR  `services`.`open_time` LIKE "%' . $search_key . '%"  OR  `services`.`commision` LIKE "%' . $search_key . '%" OR  `services`.`open_time` LIKE "%' . $search_key . '%" OR  `services`.`service_description_english` LIKE "%' . $search_key . '%" OR  `services`.`service_description_amharic` LIKE "%' . $search_key . '%"  OR  `service_categories`.`cat_name_english` LIKE "%' . $search_key . '%"  OR  `service_categories`.`cat_name_amharic` LIKE "%' . $search_key . '%")';
                    }
                }
            }
            //geting service category
            $pagedata['service_categories'] = $this->Common->getData('service_categories', '*', 'cat_status != 3', '', '', 'cat_id', 'DESC'); // (1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //get service data
            $common_query = 'SELECT `services`.`service_id`,`services`.`service_name_english`,`services`.`service_name_amharic`,`services`.`service_price`,`services`.`service_price_type`,`services`.`visiting_price`,`services`.`commision`,`services`.`open_time`,`services`.`close_time`,`services`.`service_image`,`services`.`service_mobile_banner`,`services`.`service_description_english`,`services`.`service_description_amharic`,`services`.`service_status`,`service_categories`.`cat_name_english` FROM `services` JOIN `service_categories` ON `service_categories`.`cat_id` = `services`.`category_id`  WHERE `services`.`service_status` != 3 ' . $query_part . ' ORDER BY `service_id` DESC'; //(1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
            //pagination  ---start----
            $page_segment = 10;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['service_data'] = $this->Common->custom_query($query, "get");
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/service_management/0/' . $fromdate . '/' . $todate . '/' . $service_price_type . '/' . $service_category_id . '/' . $service_status . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('service_management' => base_url('admin/service_management/'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Service Management';
            $pagedata['pageName'] = 'admin/service-management';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/service-list-table', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Service Management ---------------View----------END---------------------

    //Wallet Management -------------View------------START---------------------
    public function wallet_management() {
        #only admin can add banner
        if ($this->id && $this->role == 1) { #only super admin can see
            #getting service provider list ---START-----
            $pagedata['service_provider_list'] = $this->Common->getData('users', 'user_id,fullname', 'role = 3 AND user_status = 1', '', '', 'user_id', 'DESC');
            #db_role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0)
            #ub_user_status = 1- Enable, 2- Disable/Block, 3- Delete
            #getting service provider list ---END-----
            #Getting all transactions details of the provider --- START----
            $query_credited = "SELECT  sum(amount) AS total_credited FROM wallet WHERE type = 1";
            $pagedata['total_credited']=  $this->Common->custom_query($query_credited,"get");

            $query_debited = "SELECT  sum(amount) AS total_debited FROM wallet WHERE type = 2";
            $pagedata['total_debited']=  $this->Common->custom_query($query_debited,"get");

            $pagedata['admin_all_transactions'] = get_service_provider_all_transactions($this->id,'','',1);//second paramete Role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer)
            #Getting all transactions details of the provider --- END----

            $array = array('Wallet Management' => base_url('admin/wallet-management/'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Wallet Management';
            $pagedata['pageName'] = 'admin/wallet-management';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Wallet Management ---------------View----------END---------------------

    //Terms and Condition-------------View------------START---------------------
    public function terms_and_conditions() {
        #only admin can add banner
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            $pagedata['cms_data'] = $this->Common->getData('cms', '*', 'id = 1'); //1 for Terms and conditions in table
            $array = array('terms_and_conditions' => base_url('admin/terms_and_conditions'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Terms and Condition';
            $pagedata['pageName'] = 'admin/terms-and-conditions';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Terms and Condition ---------------View----------END---------------------

    //Privacy Policy -------------View------------START---------------------
    public function privacy_policy() {
        #only admin can add banner
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            $pagedata['cms_data'] = $this->Common->getData('cms', '*', 'id = 2'); //2 for privacy policy in table
            $array = array('privacy_policy' => base_url('admin/privacy_policy'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Privacy Policy';
            $pagedata['pageName'] = 'admin/privacy-policy';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Privacy Policy  ---------------View----------END---------------------
    public function user_management($table_data = '', $fromdate = 'all', $todate = 'all', $user_status = 'all', $search_key = 'all') {
        #only admin can add banner
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['user_status'] = $user_status; //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $user_status != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " and  (created_at between '$fromDateNew' and '$toDateNew')";
                }
                if ($user_status != "all") {
                    $query_part.= ' AND `user_status` = "' . $user_status . '"';
                }
                if ($search_key != "all") {
                    if($search_key == 'Customer Service' || $search_key == 'Admin'){
                        if($search_key == 'Admin'){
                            $role = 1;
                        }else{
                            $role = 2;
                        }
                        $query_part.= ' AND `role` = '.$role.' '; 
                    }else{
                        $query_part.= ' AND (`number_id` LIKE "%' . $search_key . '%" OR `fullname` LIKE "%' . $search_key . '%" OR `mobile` LIKE "%' . $search_key . '%" OR `email` LIKE "%' . $search_key . '%")';
                    }
                    
                }
            }
             $common_query = 'SELECT * FROM `users` WHERE `user_status` != 3 AND `role` = 2 ' . $query_part . 'ORDER BY `user_id` DESC ';
            //  role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            //pagination  ---start----
            $page_segment = 8;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['customer_data'] = $this->Common->custom_query($query, "get");
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/user_management/0/' . $fromdate . '/' . $todate . '/' . $user_status . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('user_management' => base_url('admin/user_management'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'User Management';
            $pagedata['pageName'] = 'admin/user-management';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/user-table-list', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    // add banners ----------------- View -------------------start------
    public function banners($banner_list_load_mode = "") {
        #only admin can add banner
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            $pagedata['banner_data'] = $this->Common->getData('banners', '*', 'banner_status != 2', '', '', 'banner_id', 'DESC', '');
            #banner_status = (1 - Enable, 2 - Deleted) Default value - 1
            $array = array('Banners' => base_url('admin/banners'));
            $this->createBreadcrumb($array);
            if ($banner_list_load_mode == 1) {
                $this->load->view('admin/banner-list', $pagedata);
            } else {
                $pagedata['pageTitle'] = 'Banners';
                $pagedata['pageName'] = 'admin/banners';
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    // add banners ----------------- View -------------------end------

    // settings ----------------- View -------------------start------
    public function setting() {
        #only admin can update
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            //Basic Settings
            $pagedata['settings_data'] = $this->Common->getData('settings', '*');
            $pagedata['city_data'] = $this->Common->getData('cities', 'id,name', 'status != 3', '', '', 'id', 'DESC', '');
            $array = array('Banners' => base_url('admin/setting'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'setting';
            $pagedata['pageName'] = 'admin/setting';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    // setting ----------------- View -------------------end------

    // 404 page ------ view --------------------START----------------------
    public function errors_404() {
        $this->load->view('admin/errors-404');
    }
    // 404 page ------ view --------------------END----------------------

    //Profile-------------View------------START---------------------
    public function profile() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $pagedata['admin_profile'] = $this->Common->getData('users', 'user_id,fullname,email,mobile,profile_image,password', 'user_id ="' . $this->id . '" AND role = ' . $this->role . ''); //Super Admin role - 1
            $array = array('profile' => base_url('admin/profile'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Profile';
            $pagedata['pageName'] = 'admin/features-profile';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Profile-------------View------------END---------------------

    //Forgot Password-------------View------------START---------------------
    public function forgot_password() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service){
            header('location:' . base_url('admin/dashboard'));
        } else {
            $pagedata['pageTitle'] = 'Forgot Password';
            $pagedata['pageName'] = 'forgot-password';
            $this->load->view('admin/forgot-password', $pagedata);
        }
    }
    //Forgot Password---------------View----------END---------------------

    //Reset Password Message ------------View----------START--------------
    public function reset_password_message() {
        $this->load->view('admin/reset-password-message');
    }
    //Reset Password Message ------------View----------END--------------

    //Change Password-------------View------------START---------------------
    public function change_password() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $array = array('change_password' => base_url('admin/change_password'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Change Password';
            $pagedata['pageName'] = 'admin/change-password';
            $this->load->view('admin/masterpage', $pagedata);
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Change Password---------------View----------END---------------------

    //Authantication --------------------START-----------------------
    #Login  Funcation --------------------START-----------------------
    public function auth() {
        # This function used for administrator Authentication
        if (!$this->id) {
            if ($this->input->post('email')) {
                $email = $this->db->escape_str(trim($this->input->post('email')));
                $check_email = $this->Common->getData("users", "*", "email = '$email' and role IN (1,2) and user_status NOT IN(2,3)");
                //  user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                // role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
                if (!empty($check_email)) {
                    if (!$this->bcrypt->check_password($this->input->post('password'), $check_email[0]['password'])) {
                        $this->session->set_flashdata('error', 'Incorrect login details');
                        $this->load->view('admin/login');
                    } else {
                        # Do not login store admin if not assigned to any store
                        if ($check_email[0]['email'] !== $email) {
                            $this->session->set_flashdata('error', 'Email id not found');
                            header('location:' . base_url('admin'));
                        } else {
                            //session array
                            $admin_data = array('adminId' => $check_email[0]['user_id'], 'email' => $check_email[0]['email'], 'role' => $check_email[0]['role'], 'fullname' => $check_email[0]['fullname'], 'profile_image' => $check_email[0]['profile_image'],);
                            // remember me
                            if (!empty($this->input->post("remember"))) {
                                setcookie("loginId", $email, time() + (10 * 365 * 24 * 60 * 60));
                                setcookie("loginPass", $this->input->post('password'), time() + (10 * 365 * 24 * 60 * 60));
                            } else {
                                setcookie("loginId", "");
                                setcookie("loginPass", "");
                            }
                            $this->session->set_userdata($admin_data);
                            header('location:' . base_url('admin/dashboard'));
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', 'Incorrect login details');
                    $this->load->view('admin/login');
                }
            } else {
                $this->load->view('admin/login');
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    #Login Funcation --------------------END-----------------------

    #Logout Funcation --------------------START-----------------------
    # This function is used to destroy all the session values associated to admin session
    public function logout() {
        $this->session->sess_destroy();
        //$this->session->set_flashdata('success', 'Logged Out Successfully');
        header('location:' . base_url('admin'));
    }
    #Logout Funcation --------------------END-----------------------
    //Authantication --------------------END-----------------------
   
    // Admin / Customer Service Profile Update ---------START------------
    public function UpdateProfile() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) {
            $fullname = $this->db->escape_str(trim($this->input->post('fullname')));
            $email = $this->db->escape_str(trim($this->input->post('email')));
            $mobile = $this->db->escape_str(trim($this->input->post('mobile')));
            if (empty($_FILES['admin_profile_image']['name'])) {
                $exist_admin_profile_image = $this->db->escape_str(trim($this->input->post('exist_profile_image')));
            } else {
                $exist_admin_profile_image = $_FILES['admin_profile_image']['name'];
            }
            $check_email = $this->Common->getData('users', 'user_id', '(email = "' . $email . '" OR mobile = "' . $mobile . '") AND user_status != 3 AND user_id != "' . $this->id . '"'); # user_status 3 for deleted
            // CHECK IF given email is already exist
            if (!empty($check_email)) {
                $this->session->set_flashdata('error', 'User already exists');
                header("location:" . base_url('admin/profile'));
            } else { //!empty($check_email
                // check somthing is changed or not
                $get_admin_data = $this->Common->getData("users", "fullname,email,mobile,profile_image", "user_id = " . $this->id . " and role = 1");
                $target = array(array('fullname' => $fullname, 'email' => $email, 'mobile' => $mobile, 'profile_image' => $exist_admin_profile_image));
                if ($target == $get_admin_data) {
                    // nothing changed
                    $this->session->set_flashdata('warning', 'Nothing Changed !!!');
                    header("location:" . base_url('admin/profile'));
                } else if ($fullname != "" && $email != "" && $mobile != "") {
                    //echo $_FILES['admin_profile_image'];
                    if (!empty($_FILES['admin_profile_image']['name'])) { // if new image is upload
                        #delete previous uploaded profile image------- START --------
                        $get_previous_profile_pic = $get_admin_data[0]['profile_image'];
                        if (!empty($get_previous_profile_pic && file_exists($get_previous_profile_pic))) {
                            //echo "The file $get_previous_profile_pic exists";
                            unlink($get_previous_profile_pic);
                        }
                        #delete previous uploaded profile image------- END -------
                        //upload image -------start
                        $exp = explode(".", $_FILES['admin_profile_image']['name']);
                        $ext = end($exp);
                        $st1 = substr(date('ymd'), 0, 3);
                        $st2 = $st1 . rand(1, 100000);
                        $fileName = $st2 . time() . date('ymd') . "." . $ext;
                        $image_path = img_upload_path()[0].AdminProfileFolder; //orignal image path
                        /* Image upload  */
                        $config['upload_path'] = $image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('admin_profile_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        } else {
                            $update_array = ['fullname' => $fullname, 'email' => $email, 'mobile' => $mobile, 'profile_image' => AdminProfileFolder . $fileName, 'updated_at' => time() ];
                            $this->Common->updateData('users', $update_array, "user_id = " . $this->id . ' AND role = ' . $this->role . '');
                            # Resize only if NOT SVG
                            if ($ext !== 'svg') {
                                /*Image resize function starts here*/
                                $source_image = $image_path . $fileName;
                                $new_image = $resize_image_path . $fileName;
                                $width = PROFILE_IMAGE_RESIZE_WIDTH;
                                $height = PROFILE_IMAGE_RESIZE_WIDTH;
                                # Call this function to rezise the image and place in a new path
                                image_resize($source_image, $new_image, $width, $height);
                                /*Image resize function ends here*/
                            } else {
                                /* Image upload */
                                $config['upload_path'] = $resize_image_path;
                                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                                $config['file_name'] = $fileName;
                                $this->upload->initialize($config);
                                if (!$this->upload->do_upload('admin_profile_image')) {
                                    $error_msg = $this->upload->display_errors();
                                    $message = strip_tags($error_msg);
                                    $this->session->set_flashdata('error', $message);
                                }
                            }
                            //echo 'success';
                            $update_status = true;
                        }
                    }
                    // if no new image upload
                    if (empty($_FILES['admin_profile_image']['name'])) {
                        $update_array = ['fullname' => $fullname, 'email' => $email, 'mobile' => $mobile, 'updated_at' => time() ];
                        $this->Common->updateData('users', $update_array, "user_id = " . $this->id . ' AND role = ' . $this->role . '');
                        //echo 'success';
                        $update_status = true;
                    }
                    // Final status --------
                    if ($update_status == true) {
                        //user data ,set in session after update
                        //session array
                        $get_updated_admin_data = $this->Common->getData("users", "fullname,email,mobile,profile_image", "user_id = " . $this->id . " and role = " . $this->role . "");
                        $admin_data = array('email' => $get_updated_admin_data[0]['email'], 'fullname' => $get_updated_admin_data[0]['fullname'], 'profile_image' => $get_updated_admin_data[0]['profile_image'],);
                        $this->session->set_userdata($admin_data);

                        #send mail
                        $mail_success_status = send_email_on_admin_profile_updated($fullname, $email);
                        
                        if ($mail_success_status == 1) {
                            //echo 'success';
                            $this->session->set_flashdata('success', 'Profile updated successfully !');
                        } else {
                            //echo 'success';
                            $this->session->set_flashdata('success', 'Profile updated successfully but Email not sent !');
                        }
                    } //end  if $update_status == true)
                    header("location:" . base_url('admin/profile'));
                }
            }
        }
    }
    // Admin / Customer Service Profile Update ---------END------------

    //Admin/ Customer Service Update Password ----------START---------
    public function UpdatePassword() {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service){
            $new_password = $this->db->escape_str(trim($this->input->post('new_password')));
            if ($new_password != "") {
                $password = $this->bcrypt->hash_password(trim($new_password));
                $update_array = ['password' => $password, 'updated_at' => time(), ];
                /*password update*/
                $update_status = $this->Common->updateData("users", $update_array, "user_id = " . $this->id . ' AND role = ' . $this->role . '');
                if ($update_status > 0) {
                    // getting email for send email
                    $get_user_data = $this->Common->getData('users', 'fullname,email', 'user_id ="' . $this->id . '" AND role = ' . $this->role . ''); //Super Admin role - 1, 2 - cutomer service
                    $name = split_name($get_user_data[0]['fullname']);

                    #mail send
                    $mail_success_status = send_email_on_admin_update_password($name[0],$get_user_data[0]['email']);
                    
                    if ($mail_success_status == 1) {
                        //echo 'success';
                        $this->session->set_flashdata('success', 'Password updated successfully !');
                        header("location:" . base_url('admin/change_password'));
                    } else {
                        //echo 'success';
                        $this->session->set_flashdata('success', 'Password updated successfully but Email not sent !');
                        header("location:" . base_url('admin/change_password'));
                    }
                }
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Admin/ Customer Service Update Password ----------END---------

    //Forgot Password -----Functionality-----START-----------------
    public function ForgotPasswordSendEmail() {
        $email = $this->db->escape_str(trim($this->input->post('email')));
        //check email  its exist or not  for role
        $check_email = $this->Common->getData('users', 'user_id,email,role,fullname', "email='" . $email . "' AND role IN (1,2)  AND user_status NOT IN (2,3)");
        // # role = (1 - admin, 2 - customer-service){
        // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        if (!empty($check_email)) {
            # generate code for forgot password
            $token = generate_token();
            # add 1 day in current date time
            $token_expire_time = strtotime('+1 day');
            # make update array
            $update_array = ['token' => $token, 'token_expire_time' => $token_expire_time, 'updated_at' => time() ];
            # update data in users table
            $this->Common->updateData('users', $update_array, "user_id = " . $check_email[0]['user_id']);
            
            #send email
            send_email_for_forgot_password($check_email[0]['fullname'],$check_email[0]['email'],$check_email[0]['role'],$token);
            
            $this->session->set_flashdata('success', 'A link to reset password has been sent to your email. Please check');
            header("location:" . base_url('admin'));
        } else {
            $this->session->set_flashdata('error', 'Invalid Email');
            header("location:" . base_url('admin/forgot_password'));
        }
    }
    //Forgot Password -----Functionality-----END------------------

    # Reset Pssword----View-------------START------------
    # This function is used to open the view page for reseting password
    public function reset_password($token, $role) {
        $this->session->set_flashdata('success', '');
        $this->session->set_flashdata('error', '');
        if ($token == '' || $role == '') {
            $data['message'] = 'Invalid link.';
            $this->load->view("admin/reset_password_message", $data);
        } else {
            //get user details
            $user_details = $this->Common->getData('users', 'email, token, token_expire_time,role', 'token="' . $token . '" and role = ' . $role . ' and user_status NOT IN (2,3)');
            // # role = (1 - admin, 2 - customer-service){
            // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            if (!empty($user_details)) {
                if ($user_details[0]['token_expire_time'] < time()) {
                    $data['message'] = 'Reset password link expired.';
                    $this->load->view("reset_password_message", $data);
                } else {
                    /*make user data array*/
                    $data['user_data'] = ['email' => $user_details[0]['email'], 'token' => $user_details[0]['token'], 'role' => $user_details[0]['role']];
                    /*load reset_password_change for set new password*/
                    $this->load->view('admin/reset-password', $data);
                }
            } else {
                $data['message'] = 'Reset password link expired.';
                $this->load->view('admin/reset-password-message', $data);
            }
        }
    }
    # Reset password function--------- END ----------

    //Reset Password Update Functionality-----------------START--------------
    # This function used for set new password
    public function ResetPasswordUpdate() {
        $email = $this->db->escape_str(trim($this->input->post('email')));
        $new_password = $this->db->escape_str(trim($this->input->post('new_password')));
        $token = $this->input->post('token');
        $role = $this->input->post('role');
        if ($email != "" && $new_password != "" && $token != "" && $role != "") {
            //get user details
            $user_details = $this->Common->getData("users", "user_id, email, fullname", "email = '$email' and token = '$token' and role = '$role'");
            $password = $this->bcrypt->hash_password(trim($new_password));
            if (!empty($user_details)) {
                $update_array = ['password' => $password, 'token' => '', 'token_expire_time' => '', 'updated_at' => time(), ];
                $id = $user_details[0]['user_id'];
                /*password update*/
                $this->Common->updateData('users', $update_array, 'user_id="' . $id . '"');
                $name = split_name($user_details[0]['fullname']);
                /*mail send data code start */
                send_email_on_update_reset_password($name[0],$user_details[0]['email']);
                /*mail send data code end */
                $data['message'] = 'Password reset successfully';
                $this->load->view('admin/reset-password-message', $data);
            } else {
                /*make user data array*/
                $data['user_data'] = ['email' => $email, 'token' => $token, 'role' => $role];
                $this->session->set_flashdata('error', 'Something went wrong!');
                $this->load->view('admin/reset-password-message', $data);
            }
        } else {
            $data['message'] = 'Something went wrong!';
            $this->load->view('admin/reset-password-message', $data);
        }
    }
    //Reset Password Update Functionality-----------------END---------------

    //Update CMS Functionality--------------START-----------
    #for  Terms and Condtion
    #for  Privacy Policy
    public function Update_CMS() {
        if ($this->id && ($this->role == 1)) { // role = (1 - admin)// only admin can do
            $page_name = $this->db->escape_str(trim($this->input->post('page_name')));
            $page_value = $this->db->escape_str(trim($this->input->post('page_value')));
            $page_value_amharic = $this->db->escape_str(trim($this->input->post('page_value_amharic')));
            $page_key = $this->db->escape_str(trim($this->input->post('page_key')));
            $page_primary_id = $this->db->escape_str(trim($this->input->post('page_primary_id')));
            $view_landing_page = $this->db->escape_str(trim($this->input->post('view_landing_page'))); // after update will go on this page
            if ($page_name != "" && $page_key != "" && $page_primary_id != "" && $view_landing_page != "") {
                $update_array = ['page_name' => $page_name, 'page_value' => $page_value, 'page_value_amharic' => $page_value_amharic,  'updated_at' => time(), ];
                $update_status = $this->Common->updateData('cms', $update_array, 'page_key = "' . $page_key . '" AND id = "' . $page_primary_id . '"');
                if ($update_status > 0) {
                    if ($view_landing_page == 'terms_and_conditions') {
                        $message = 'Terms and Conditions';
                    } else if ($view_landing_page == 'privacy_policy') {
                        $message = 'Privacy Policy';
                    }
                    $this->session->set_flashdata('success', '' . $message . ' updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Internal Server Error !');
                }
                header("location:" . base_url('admin/' . $view_landing_page . ''));
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Update CMS Functionality--------------END------------

    //Create Service Category -------------START--------------
    public function Create_Update_ServiceCategory() {
        $cat_name_english = $this->db->escape_str(trim($this->input->post('cat_name_english')));
        $cat_name_amharic = $this->db->escape_str(trim($this->input->post('cat_name_amharic')));
        $mode = $this->db->escape_str(trim($this->input->post('mode'))); //1 = add , 2 - edit
        if ($cat_name_english != "" && $cat_name_amharic != "" && $mode != "") {
            $array = ['cat_name_english' => $cat_name_english, 'cat_name_amharic' => $cat_name_amharic, 'updated_at' => time() ];
            //check image file uplaod add/edit time
            if (!empty($_FILES['category_image']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['category_image']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'category_images/'; //orignal image path
                $resize_image_path = img_upload_path()[0].ServiceCategoryImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('category_image')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName;
                        $new_image = $resize_image_path . $fileName;
                        $width = SERVICE_IMAGE_RESIZE_WIDTH;
                        $height = SERVICE_IMAGE_RESIZE_HIGHT;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('category_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        }
                    }
                    $array['category_image'] = ServiceCategoryImgFolder . $fileName;
                }
            }
            if ($mode == 2) { //edit mode
                #if image not upload at the time of edit then we can overight the existing image path
                #if image is uploaded then old path name will be overight with new image path one
                ;
                if (!empty($_FILES['category_image']['name'])) { //file uplaodd
                    $exist_service_image = ServiceCategoryImgFolder . $fileName;
                    $upload_status = true;
                } else {
                    $exist_service_image = $this->db->escape_str(trim($this->input->post('exist_category_image')));
                    $upload_status = false;
                }
                $array['category_image'] = $exist_service_image;
            }
            if ($mode == 1) { //add
                //check if category name is alreday exist in category table
                $check_exist_cat_name = $check_email = $this->Common->getData('service_categories', 'cat_id', '(cat_name_english = "' . $cat_name_english . '" OR cat_name_amharic = "' . $cat_name_amharic . '") AND  cat_status != 3 ');
                if (!empty($check_exist_cat_name)) {
                    //exist name
                    $this->session->set_flashdata('error', 'Category is already exist!');
                } else {
                    $array['created_at'] = time();
                    $insert_status = $this->Common->insertData('service_categories', $array);
                    if ($insert_status > 0) {
                        $this->session->set_flashdata('success', 'Category created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Internal Server Error !');
                    }
                }
            } else if ($mode == 2) { //edit
                $cat_id = $this->db->escape_str(trim($this->input->post('cat_id')));
                //check if category name is alreday exist in category table
                $check_exist_cat_name = $check_email = $this->Common->getData('service_categories', 'cat_id', '(cat_name_english = "' . $cat_name_english . '" OR cat_name_amharic = "' . $cat_name_amharic . '") AND  cat_status != 3 AND cat_id !=' . $cat_id . ' ');
                if (!empty($check_exist_cat_name)) {
                    //exist name
                    $this->session->set_flashdata('error', 'Category is already exist!');
                } else {
                    if ($cat_id != "") {
                        //getting exist category image
                        $category_exist_data = $this->Common->getData('service_categories', 'category_image', 'cat_id = ' . $cat_id . ''); //cat_status = 1 - Enable, 2 - Disable, 3 = delete
                        //unlink previous image if new image upload
                        if ($upload_status == true) {
                            $get_previous_category_image = $category_exist_data[0]['category_image'];
                            if (!empty($get_previous_category_image && file_exists($get_previous_category_image))) {
                                //echo "The file $get_previous_category_image exists";
                                //from resize folder
                                unlink($get_previous_category_image);
                                //from actual image
                                $orginal_image_path = str_replace("resize_category_images/", "", $get_previous_category_image);
                                unlink($orginal_image_path);
                            }
                        }
                        $update_status = $this->Common->updateData('service_categories', $array, 'cat_id = "' . $cat_id . '"');
                        if ($update_status > 0) {
                            $this->session->set_flashdata('success', 'Category updated successfully');
                        } else {
                            $this->session->set_flashdata('error', 'Internal Server Error !');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Internal Server Error !');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please fill category name!');
        }
        header("location:" . base_url('admin/service_categories'));
    }
    //Create Service Category -------------END---------------

    //Create Service(Sub Category)-------------START------------------
    public function Create_Update_Service() {
        $service_name_english = $this->db->escape_str(trim($this->input->post('service_name_english')));
        $service_name_amharic = $this->db->escape_str(trim($this->input->post('service_name_amharic')));
        $category_id = $this->db->escape_str(trim($this->input->post('category_id')));
        $open_time = $this->db->escape_str(trim($this->input->post('open_time')));
        $close_time = $this->db->escape_str(trim($this->input->post('close_time')));
        $service_price_type = $this->db->escape_str(trim($this->input->post('service_price_type')));
        $service_price = $this->db->escape_str(trim($this->input->post('service_price')));
        $visiting_price = $this->db->escape_str(trim($this->input->post('visiting_price')));
        $service_note = $this->db->escape_str(trim($this->input->post('service_note')));
        $service_description_english = $this->db->escape_str(trim($this->input->post('service_description_english')));
        $service_description_amharic = $this->db->escape_str(trim($this->input->post('service_description_amharic')));
        $mode = $this->db->escape_str(trim($this->input->post('mode'))); //1 = add , 2 - edit
        $edit_service_id = $this->db->escape_str(trim($this->input->post('edit_service_id')));
        if ($this->role == 1) { //only admin can add /edit and view commision
            $commision = $this->db->escape_str(trim($this->input->post('commision')));
        } else {
            $commision = $this->default_commision; //at the time customer service we give default value of commision
            
        }
        if ($service_name_english != "" && $service_name_amharic != "" && $category_id != "" && $commision != "" && $open_time != "" && $close_time != "" && $service_price_type != "" && $service_price != "" && $visiting_price != "" && $service_description_english != "" && $service_description_amharic != "") {
            $array = ['service_name_english' => $service_name_english, 'service_name_amharic' => $service_name_amharic, 'category_id' => $category_id, //(primary id of the service_categories table)
            'service_price' => $service_price, 'service_price_type' => $service_price_type, 'visiting_price' => $visiting_price, 'service_note' => $service_note, 'open_time' => $open_time, 'close_time' => $close_time, 'service_description_english' => $service_description_english, 'service_description_amharic' => $service_description_amharic, 'updated_at' => time() ];
            //check image file uplaod add/edit time
            if (!empty($_FILES['service_image']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['service_image']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'service_images/'; //orignal image path
                $resize_image_path = img_upload_path()[0].ServiceImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('service_image')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName;
                        $new_image = $resize_image_path . $fileName;
                        $width = SERVICE_IMAGE_RESIZE_WIDTH;
                        $height = SERVICE_IMAGE_RESIZE_HIGHT;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('service_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        }
                    }
                    $array['service_image'] = ServiceImgFolder . $fileName;
                }
            }
            //check MOBILE BANNER image file uplaod add/edit time
            if (!empty($_FILES['selected_mobile_banner_img']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['selected_mobile_banner_img']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName2 = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'service_banner_images/service_mobile_banners/'; //orignal image path
                $resize_image_path = img_upload_path()[0].ServiceBannerImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName2;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('selected_mobile_banner_img')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName2;
                        $new_image = $resize_image_path . $fileName2;
                        $width = SERVICE_MOBILE_BANNER_IMAGE_RESIZE_WIDTH;
                        $height = SERVICE_MOBILE_BANNER_IMAGE_RESIZE_HIGHT;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName2;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('selected_mobile_banner_img')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        }
                    }
                    $array['service_mobile_banner'] = ServiceBannerImgFolder . $fileName2;
                }
            }
            if ($mode == 2) { //edit mode
                #if image not upload at the time of edit then we can overight the existing image path
                #if image is uploaded then old path name will be overight with new image path one
                ;
                #service icon image
                if (!empty($_FILES['service_image']['name'])) { //file uplaodd
                    $exist_service_image = ServiceImgFolder . $fileName;
                    $upload_status = true;
                } else {
                    $exist_service_image = $this->db->escape_str(trim($this->input->post('exist_service_image')));
                    $upload_status = false;
                }
                $array['service_image'] = $exist_service_image;
                #service mobile banner image
                if (!empty($_FILES['selected_mobile_banner_img']['name'])) { //file uplaodd
                    $exist_service_mobile_banner_image = ServiceBannerImgFolder . $fileName2;
                    $mobile_banner_upload_status = true;
                } else {
                    $exist_service_mobile_banner_image = $this->db->escape_str(trim($this->input->post('exist_service_mobile_banner_image')));
                    $mobile_banner_upload_status = false;
                }
                $array['service_mobile_banner'] = $exist_service_mobile_banner_image;
            }
            if ($mode == 1) { //add
                $array['commision'] = $commision;
                $array['created_at'] = time();
                $insert_status = $this->Common->insertData('services', $array);
                if ($insert_status > 0) {
                    $this->session->set_flashdata('success', 'Service created successfully');
                } else {
                    $this->session->set_flashdata('error', 'Internal Server Error !');
                }
            } else if ($mode == 2) { //edit
                //getting exist commision value
                /*$service_exist_commision = $this->Common->getData('services','commision,service_image,service_mobile_banner','service_id = '.$edit_service_id.'');//service_status = 1 - Enable, 2 - Disable, 3 - Deleted
                
                $commision = $service_exist_commision[0]['commision'];*/
                //unlink previous image if new image upload
                if ($upload_status == true) {
                    $get_previous_service_image = $service_exist_commision[0]['service_image'];
                    if (!empty($get_previous_service_image && file_exists($get_previous_service_image))) {
                        //echo "The file $get_previous_service_image exists";
                        //from resize folder
                        unlink($get_previous_service_image);
                        //from actual image
                        $orginal_image_path = str_replace("resize_service_images/", "", $get_previous_service_image);
                        unlink($orginal_image_path);
                    }
                }
                //unlink previous mobile banner image if new image upload
                if ($mobile_banner_upload_status == true) {
                    $get_previous_service_image = $service_exist_commision[0]['service_mobile_banner'];
                    if (!empty($get_previous_service_image && file_exists($get_previous_service_image))) {
                        //echo "The file $get_previous_service_image exists";
                        //from resize folder
                        unlink($get_previous_service_image);
                        //from actual image
                        $orginal_image_path = str_replace("resize_service_mobile_banners/", "", $get_previous_service_image);
                        unlink($orginal_image_path);
                    }
                }
                $array['commision'] = $commision;
                $update_status = $this->Common->updateData('services', $array, 'service_id = "' . $edit_service_id . '"');
                if ($update_status > 0) {
                    $this->session->set_flashdata('success', 'Service updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Internal Server Error !');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
            
        }
        header("location:" . base_url('admin/service_management'));
    }
    //Create Service(Sub Category)-------------END-------------------
    
    //genrate password
    public function GeneratePassword($name, $phone) {
        $alphabet = $name . "mnopqrstuwxyzABCDEFGHINOPQRSTUWXYZ0123456789" . $phone;
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0;$i < 10;$i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    // Create and update customer ----------------START--------------
    public function Create_Update_Customer() {
        $customer_fullname = $this->db->escape_str(trim($this->input->post('customer_fullname')));
        $customer_email = $this->db->escape_str(trim($this->input->post('customer_email')));
        $customer_mobile = $this->db->escape_str(trim($this->input->post('customer_mobile')));
        $gender = $this->db->escape_str(trim($this->input->post('gender')));
        $age = $this->db->escape_str(trim($this->input->post('age')));
        $mode = $this->db->escape_str(trim($this->input->post('mode'))); //1 = add , 2 - edit
        if ($customer_fullname != "" && $customer_mobile != "") {
            //insert update values array
            $array = ['fullname' => $customer_fullname, 'email' => $customer_email, 'gender' => $gender, 'age' => $age, 'role' => 4, //(1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            'updated_at' => time() ];
            //check image file uplaod add/edit time
            if (!empty($_FILES['customer_image']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['customer_image']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'customer_images/'; //orignal image path
                $resize_image_path = img_upload_path()[0].CustomerImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('customer_image')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName;
                        $new_image = $resize_image_path . $fileName;
                        $width = PROFILE_IMAGE_RESIZE_WIDTH;
                        $height = PROFILE_IMAGE_RESIZE_WIDTH;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('customer_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        }
                    }

                    $array['profile_image'] = CustomerImgFolder . $fileName;
                }
            }
            if ($mode == 2) { //edit mode
                #if image not upload at the time of edit then we can overight the existing image path
                #if image is uploaded then old path name will be overight with new image path one
                if (!empty($_FILES['customer_image']['name'])) { //file uplaodd
                    $exist_customer_image = CustomerImgFolder . $fileName;
                    $upload_status = true;
                } else {
                    $exist_customer_image = $this->db->escape_str(trim($this->input->post('exist_customer_image')));
                    $upload_status = false;
                }
                $array['profile_image'] = $exist_customer_image;
            }
            // if image not given
            if ($customer_email != "") {
                $email_check_query_part = 'email = "' . $customer_email . '" OR ';
            } else {
                $email_check_query_part = '';
            }
            if ($mode == 1) { //add
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $check_email = $this->Common->getData('users', 'user_id', '(' . $email_check_query_part . ' mobile = "' . $customer_mobile . '") AND user_status != 3 '); // # role = (1 - admin, 2 - customer-service){
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                //echo $this->db->last_query();
                if (!empty($check_user_if_exist)) {
                    // user is already exist
                    $this->session->set_flashdata('error', 'User already exists');
                } else {
                    //new user enter
                    $array['country_code'] = COUNTRY_CODE;
                    $array['mobile'] = $customer_mobile;
                    $array['created_at'] = time();
                    $insert_status = $this->Common->insertData('users', $array);
                    #create number id and update it will be unique---START---
                    $user_id = $this->db->insert_id();
                    $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
                    $sr_display_id = $display_id_start + $user_id;
                    $update_array = ['number_id' => $sr_display_id, ];
                    $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                    #create number id and update ---END---
                    if ($insert_status > 0) {
                        $this->session->set_flashdata('success', 'Customer created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Internal Server Error !');
                    }
                }
            } else if ($mode == 2) { //edit
                #mobile number not editable after final by client side we will update the code
                #below code is already created before when mobile was editable
                $edit_customer_id = $this->db->escape_str(trim($this->input->post('edit_customer_id')));
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $this->Common->getData('users', 'user_id', '(' . $email_check_query_part . ' mobile = "' . $customer_mobile . '") AND user_status != 3 AND user_id != ' . $edit_customer_id . ''); // # role = (1 - admin, 2 - customer-service){
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                //check if any changes do --------START--------------
                $check_user = $this->Common->getData('users', 'fullname,email,mobile,gender,age,profile_image', '(' . $email_check_query_part . ' mobile = "' . $customer_mobile . '") AND user_status != 3'); // # role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
                //create array for check if any changes done for only match with exist data
                $target = array(array('fullname' => $customer_fullname, 'email' => $customer_email, 'mobile' => $customer_mobile, 'gender' => $gender, 'age' => $age, 'profile_image' => $exist_customer_image));
                //check if any changes do --------END----------------
                //unlink exist image if newly upload --------START----------
                if ($upload_status == true) {
                    $get_previous_profile_pic = $check_user[0]['profile_image'];
                    if (!empty($get_previous_profile_pic && file_exists($get_previous_profile_pic))) {
                        //echo "The file $get_previous_profile_pic exists";
                        //from resize folder
                        unlink($get_previous_profile_pic);
                        //from actual image
                        $orginal_image_path = str_replace("resize_customer_images/", "", $get_previous_profile_pic);
                        unlink($orginal_image_path);
                    }
                }
                //unlink exist image if newly upload --------START----------
                /* print_r($check_user);
                 print_r($target); */
                $change_status = true; // if any changes done then mail should be send
                if ($check_user == $target) {
                    // no changes done
                    $this->session->set_flashdata('error', 'Nothing Changed');
                    $change_status = false;
                } else {
                    if (!empty($check_user_if_exist)) {
                        // user is already exist
                        $this->session->set_flashdata('error', 'User already exists');
                    } else {
                        $update_status = $this->Common->updateData('users', $array, 'user_id = "' . $edit_customer_id . '"');
                        echo $this->db->last_query();
                        if ($update_status > 0) {
                            $this->session->set_flashdata('success', 'Customer updated successfully');
                        } else {
                            $this->session->set_flashdata('error', 'Internal Server Error !');
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
        }
        // send email on customer email account -------START---------------
        if ((isset($insert_status) && $insert_status > 0) || (isset($update_status) && $update_status > 0 && $change_status == true) && $customer_email != "") {
            send_email_on_create_update_customer_detail($customer_fullname,$customer_email,$customer_mobile,$mode);
        }
        // send email on customer email account -------END---------------
        header("location:" . base_url('admin/customer_Management'));
    }
    // Create and update customer ----------------END--------------

    //Update Service provider skills -----------START--------------------
    public function Update_ServiceProvider_skills($service_provider_id, $service_provider_skills_array) {
        #first -  we have to check if service and category id exist then only we need to update skills
        #second - check if new category or service id is selected then we need to insert new values
        #third  - check pervious category and service is not selected now(means select another one now at that place) then we  need to remove from the table which prevoisly exist but now changed
        //getting exist service id according to service provider id
        $exist_key_skills = $this->Common->getData('service_provider_key_skills', 'category_id as cat_id,service_id,key_skill_english as skill_english,key_skill_amharic as skill_amharic', 'service_provider_id = ' . $service_provider_id . '');
        #create simple array that contain  only service id
        $exist_service_ids = array();
        foreach ($exist_key_skills as $value) {
            array_push($exist_service_ids, $value['service_id']);
        }
        /* echo '<pre>';
        print_r($service_provider_skills_array);
        print_r($exist_key_skills);*/
        //print_r($exist_service_ids);
        if ($service_provider_skills_array == $exist_key_skills) {
            $skill_update_status = 2; //nothing changed in skills
            
        } else {
            $now_selected_service_ids = array();
            foreach ($service_provider_skills_array as $skills_array) {
                #for match with exist service ids for remove if exist service id is not in the current selected service ids
                array_push($now_selected_service_ids, $skills_array['service_id']);
                $cat_id = $skills_array['cat_id'];
                $service_id = $skills_array['service_id'];
                $key_skill_english = $skills_array['skill_english'];
                $key_skill_amharic = $skills_array['skill_amharic'];
                $skills_array = ['category_id' => $cat_id, 'key_skill_english' => $key_skill_english, 'key_skill_amharic' => $key_skill_amharic, 'updated_at' => time(), ];
                #check select current service ids is exist in table with the help of array($exist_service_ids)
                #if does not exist that means new value will be insert
                #if exist that means we need to do only update
                if (in_array($service_id, $exist_service_ids)) {
                    $insert_update_status = $this->Common->updateData('service_provider_key_skills', $skills_array, 'service_provider_id = "' . $service_provider_id . '" AND service_id = "' . $service_id . '"');
                } else {
                    $skills_array['service_provider_id'] = $service_provider_id;
                    $skills_array['service_id'] = $service_id;
                    $skills_array['created_at'] = time();
                    $insert_update_status = $this->Common->insertData('service_provider_key_skills', $skills_array);
                }
            }
            //remove service skills which is not exist now accroding to current selected service ids
            foreach ($exist_service_ids as $key => $exist_service_id) {
                // if not exist
                if (in_array($exist_service_id, $now_selected_service_ids) == false) {
                    //echo $exist_service_id;
                    $insert_update_status = $this->Common->deleteData('service_provider_key_skills', 'service_provider_id =' . $service_provider_id . ' AND service_id = "' . $exist_service_id . '"');
                }
            }
        }
        if (isset($insert_update_status) && $insert_update_status > 0) {
            $skill_update_status = 1; //updated
            
        } else if (isset($skill_update_status) && $skill_update_status == 2) {
            $skill_update_status = 2; //no changes done in skills
            
        } else {
            $skill_update_status = 0; // somthing went worng
            
        }
        return $skill_update_status;
    }
    //Update Service provider skills -----------END-----------------------

    //Create/Update Service Provider --------------START----------------
    public function Create_Update_ServiceProvider() {
        $service_provider_fullname = $this->db->escape_str(trim($this->input->post('service_provider_fullname')));
        $service_provider_email = $this->db->escape_str(trim($this->input->post('service_provider_email')));
        $service_provider_mobile = $this->db->escape_str(trim($this->input->post('service_provider_mobile')));
        $service_provider_gender = $this->db->escape_str(trim($this->input->post('service_provider_gender')));
        $service_provider_age = $this->db->escape_str(trim($this->input->post('service_provider_age')));
        $provider_location = $this->db->escape_str(trim($this->input->post('provider_location')));
        $provider_latitude = $this->db->escape_str(trim($this->input->post('provider_latitude')));
        $provider_longitude = $this->db->escape_str(trim($this->input->post('provider_longitude')));
        if ($service_provider_age == "") {
            $service_provider_age = 0;
        }
        $service_provider_note = $this->db->escape_str(trim($this->input->post('service_provider_note'))); //notes for service provider only, and its visible only to super admin
        $service_provider_skills_array = $this->db->escape_str($this->input->post('service'));
        $mode = $this->db->escape_str(trim($this->input->post('mode'))); //1 = add , 2 - edit
        if ($service_provider_fullname != "" && $service_provider_mobile != "" && $service_provider_gender != "" && !empty($service_provider_skills_array)) { //&& $service_provider_email != ""
            //insert update values array
            $array = ['fullname' => $service_provider_fullname, 'email' => $service_provider_email, 'gender' => $service_provider_gender, 'age' => $service_provider_age, 'note' => $service_provider_note, 'role' => 3, //(1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            'google_map_pin_address'=>$provider_location,'latitude'=>$provider_latitude,'longitude'=>$provider_longitude,'updated_at' => time() ];
            //check image file uplaod add/edit time
            if (!empty($_FILES['service_provider_image']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['service_provider_image']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'service_provider_images/'; //orignal image path
                $resize_image_path = img_upload_path()[0].ProviderImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('service_provider_image')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName;
                        $new_image = $resize_image_path . $fileName;
                        $width = PROFILE_IMAGE_RESIZE_WIDTH;
                        $height = PROFILE_IMAGE_RESIZE_WIDTH;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('service_provider_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            $this->session->set_flashdata('error', $message);
                        }
                    }
                    $array['profile_image'] = ProviderImgFolder. $fileName;
                }
            }
            if ($mode == 2) { //edit mode
                #if image not upload at the time of edit then we can overight the existing image path
                #if image is uploaded then old path name will be overight with new image path one
                //edit time
                ;
                if (!empty($_FILES['service_provider_image']['name'])) { //file uplaodd
                    $exist_service_provider_image = ProviderImgFolder . $fileName;
                    $upload_status = true;
                } else {
                    $exist_service_provider_image = $this->db->escape_str(trim($this->input->post('exist_service_provider_image')));
                    $upload_status = false;
                }
                $array['profile_image'] = $exist_service_provider_image;
            }
            if ($service_provider_email != "") {
                $email_check_query_part = 'email = "' . $service_provider_email . '" OR ';
            } else {
                $email_check_query_part = '';
            }
            if ($mode == 1) { //add
                #mobile number not editable after final by client side we will update the code
                #below code is already created when mobile number was editable
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $this->Common->getData('users', 'user_id', '(' . $email_check_query_part . ' mobile = "' . $service_provider_mobile . '") AND user_status != 3 '); // # role = (1 - admin, 2 - customer-service){
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                if (!empty($check_user_if_exist)) {
                    // user is already exist
                    $this->session->set_flashdata('error', 'User already exists');
                } else {
                    //new user enter
                    $array['country_code'] = COUNTRY_CODE;
                    $array['mobile'] = $service_provider_mobile;
                    $array['created_at'] = time();
                    $insert_status = $this->Common->insertData('users', $array);
                    $last_insert_provider_id = $this->db->insert_id();
                    #create number id and update it will be unique---START---
                    $user_id = $last_insert_provider_id;
                    $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
                    $sr_display_id = $display_id_start + $user_id;
                    $update_array = ['number_id' => $sr_display_id, ];
                    $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                    #create number id and update ---END---
                    if ($insert_status > 0) {
                        //insert service provider skills accroding to selected service id
                        foreach ($service_provider_skills_array as $skills_array) {
                            $service_id = $skills_array['service_id'];
                            $cat_id = $skills_array['cat_id'];
                            $key_skill_english = $skills_array['skill_english'];
                            $key_skill_amharic = $skills_array['skill_amharic'];
                            $skills_array = ['service_provider_id' => $last_insert_provider_id, 'category_id' => $cat_id, 'service_id' => $service_id, 'key_skill_english' => $key_skill_english, 'key_skill_amharic' => $key_skill_amharic, 'created_at' => time(), 'updated_at' => time(), ];
                            $insert_status = $this->Common->insertData('service_provider_key_skills', $skills_array);
                        }
                        $this->session->set_flashdata('success', 'Service Provider created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Internal Server Error !');
                    }
                }
            } else if ($mode == 2) { //edit mode
                $edit_service_provider_id = $this->db->escape_str(trim($this->input->post('edit_service_provider_id')));
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $this->Common->getData('users', 'user_id', '(' . $email_check_query_part . ' mobile = "' . $service_provider_mobile . '") AND user_status != 3 AND user_id != ' . $edit_service_provider_id . ''); // # role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                //check if any changes do --------START--------------
                $check_user = $this->Common->getData('users', 'fullname,email,mobile,gender,age,note,profile_image,google_map_pin_address,latitude,longitude', '(email = "' . $service_provider_email . '" OR mobile = "' . $service_provider_mobile . '") AND user_status != 3'); // # role = (1 - admin, 2 - customer-service){
                //create array for check if any changes done for only match with exist data
                $target = array(array('fullname' => $service_provider_fullname, 'email' => $service_provider_email, 'mobile' => $service_provider_mobile, 'gender' => $service_provider_gender, 'age' => $service_provider_age, 'note' => $service_provider_note, 'profile_image' => $exist_service_provider_image,'google_map_pin_address'=>$provider_location,'latitude'=>$provider_latitude,'longitude'=>$provider_longitude));
                //check if any changes do --------END----------------
                //unlink previous image if new image uploaded
                if ($upload_status == true) {
                    $get_previous_profile_pic = $check_user[0]['profile_image'];
                    if (!empty($get_previous_profile_pic && file_exists($get_previous_profile_pic))) {
                        //echo "The file $get_previous_profile_pic exists";
                        //from resize folder
                        unlink($get_previous_profile_pic);
                        //from actual image
                        $orginal_image_path = str_replace("resize_service_provider_images/", "", $get_previous_profile_pic);
                        unlink($orginal_image_path);
                    }
                }
                #first we check skills  that any changes do or not
                # if any changes done then we will update skills
                # if did not do any changes in skills then we get status 2 , then we need to check "service provider" update status
                //update skills according to service provider id and service id
                $skill_update_status = $this->Update_ServiceProvider_skills($edit_service_provider_id, $service_provider_skills_array);
                $change_status = true; // if any changes done then mail should be send
                
                if ($check_user == $target && $skill_update_status == 2) {
                    // no changes done
                    $this->session->set_flashdata('error', 'Nothing Changed');
                    $change_status = false;
                } else {
                    if (!empty($check_user_if_exist)) {
                        // user is already exist
                        $this->session->set_flashdata('error', 'User already exists');
                    } else {
                        $update_status = $this->Common->updateData('users', $array, 'user_id = "' . $edit_service_provider_id . '"');
                        if ($update_status > 0) {
                            $this->session->set_flashdata('success', 'Service Provider updated successfully');
                        } else {
                            $this->session->set_flashdata('error', 'Internal Server Error !');
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
            
        }
        // send email on service provider email account -------START---------------
        if ((isset($insert_status) && $insert_status > 0) || (isset($update_status) && $update_status > 0 && $change_status == true)) {
            send_email_on_create_update_provider_detail($service_provider_fullname,$service_provider_email,$service_provider_mobile,$mode);
        }
        // send email on service provider email account -------END---------------
        header("location:" . base_url('admin/service_provider_management'));
    }
    //Create/Update Service Provider --------------END-----------------

    // Create Order ------------------------START--------------------------
    #firstly we need to check  if given customer email or mobile number is already exist in users table then we create order only according to user id
    #if customer is not exist  in users table then first we have to create new user in users table then create order accoding to last insterd user id
    #comman function for check exit customer and registerd ------START--
    public function check_customer_is_exist_or_not_for_order($customer_fullname = "", $customer_email = "", $customer_mobile = "", $gender = "", $age = "", $customer_location = "", $customer_latitude = "", $customer_longitude = "", $customer_image = "") {
        //echo $customer_email;
        //echo $customer_mobile;
        //insert update values array
        $array = ['fullname' => $customer_fullname, 'email' => $customer_email, 'mobile' => $customer_mobile, 'gender' => $gender, 'age' => $age, 'google_map_pin_address' => $customer_location, 'latitude' => $customer_latitude, 'longitude' => $customer_longitude, 'role' => 4, //(1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
        'updated_at' => time() ];
        //check image file uplaod add/edit time
        if (!empty($customer_image)) {
            //upload image -------start
            $exp = explode(".", $customer_image);
            $ext = end($exp);
            $st1 = substr(date('ymd'), 0, 3);
            $st2 = $st1 . rand(1, 100000);
            $fileName = $st2 . time() . date('ymd') . "." . $ext;
            $original_image_path = img_upload_path()[0].'customer_images/'; //orignal image path
            $resize_image_path = img_upload_path()[0].'customer_images/resize_customer_images/'; //orignal image path
            /* Image upload  */
            $config['upload_path'] = $original_image_path;
            $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
            $config['file_name'] = $fileName;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('customer_image')) {
                $error_msg = $this->upload->display_errors();
                $message = strip_tags($error_msg);
                $this->session->set_flashdata('error', $message);
            } else {
                # Resize only if NOT SVG
                if ($ext !== 'svg') {
                    /*Image resize function starts here*/
                    $source_image = $original_image_path . $fileName;
                    $new_image = $resize_image_path . $fileName;
                    $width = SERVICE_IMAGE_RESIZE_WIDTH;
                    $height = SERVICE_IMAGE_RESIZE_HIGHT;
                    # Call this function to rezise the image and place in a new path
                    image_resize($source_image, $new_image, $width, $height);
                    /*Image resize function ends here*/
                } else {
                    /* Image upload */
                    $config['upload_path'] = $resize_image_path;
                    $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                    $config['file_name'] = $fileName;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('customer_image')) {
                        $error_msg = $this->upload->display_errors();
                        $message = strip_tags($error_msg);
                        $this->session->set_flashdata('error', $message);
                    }
                }
                $array['profile_image'] = $resize_image_path . $fileName;
            }
        } else {
            $array['profile_image'] = "";
        }
        //if email not given
        if ($customer_email != "") {
            $email_check_query_part = 'email = "' . $customer_email . '" OR ';
        } else {
            $email_check_query_part = '';
        }
        //checking email and mobile number if exist in users table
        $check_user_if_exist = $this->Common->getData('users', 'user_id,role', '(' . $email_check_query_part . ' mobile = "' . $customer_mobile . '") AND user_status != 3 '); // # role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
        // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        if (!empty($check_user_if_exist)) {
            // user is already exist
            # role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
            if ($check_user_if_exist[0]['role'] == 4) {
                $customer['customer_status'] = 4;
            } else if ($check_user_if_exist[0]['role'] == 3) {
                $customer['customer_status'] = 3;
            } else if ($check_user_if_exist[0]['role'] == 2) {
                $customer['customer_status'] = 2;
            } else if ($check_user_if_exist[0]['role'] == 1) {
                $customer['customer_status'] = 1;
            }
            $customer['customer_id'] = $check_user_if_exist[0]['user_id'];
            return $customer;
        } else {
            //new user enter
            $array['created_at'] = time();
            $insert_status = $this->Common->insertData('users', $array);
            //last inserted id
            $customer_id = $this->db->insert_id();
            #create number id and update it will be unique---START---
            $user_id = $customer_id;
            $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
            $sr_display_id = $display_id_start + $user_id;
            $update_array = ['number_id' => $sr_display_id, ];
            $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
            #create number id and update ---END---
            $customer['customer_id'] = $customer_id;
            $customer['customer_status'] = 4;
            if ($insert_status > 0) {
                return $customer;
            } else {
                return 0;
            }
        } 
    }
    #comman function for check exit customer and registerd ------END--
    public function Create_Order() {
        #customer details
        $customer_fullname = $this->db->escape_str(trim($this->input->post('customer_fullname')));
        $customer_email = $this->db->escape_str(trim($this->input->post('customer_email')));
        $customer_mobile = $this->db->escape_str(trim($this->input->post('customer_mobile')));
        $gender = $this->db->escape_str(trim($this->input->post('gender')));
        $age = $this->db->escape_str(trim($this->input->post('age')));
        $customer_location = $this->db->escape_str(trim($this->input->post('customer_location')));
        $customer_latitude = $this->db->escape_str(trim($this->input->post('customer_latitude')));
        $customer_longitude = $this->db->escape_str(trim($this->input->post('customer_longitude')));
        #service detail
        $service_category_id = $this->db->escape_str(trim($this->input->post('service_category_id')));
        $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
        $service_price_type = $this->db->escape_str(trim($this->input->post('service_price_type')));
        $service_price = $this->db->escape_str(trim($this->input->post('service_price')));
        $visiting_price = $this->db->escape_str(trim($this->input->post('visiting_price')));
        $service_name = $this->db->escape_str(trim($this->input->post('service_name')));
        #note for admin if any
        $note = $this->db->escape_str(trim($this->input->post('note')));
        
        //Note -
        #check at the time order that  selected service is  already ongoing or not
        #Check service id when it is ordering for the customer. It will be unique until order status completed or ignored or canceled) means the user can't order for the same service until the selected service is not finished. But customers can choose other
        //if email not given
        if ($customer_email != "") {
            $email_check_query_part = 'customer_email = "' . $customer_email . '" OR ';
        } else {
            $email_check_query_part = '';
        }
        $is_service_on_going_or_complete_data = $this->Common->getData('service_order_bookings', 'order_id', '(' . $email_check_query_part . ' customer_contact = ' . $customer_mobile . ') AND service_id=' . $service_id . ' AND order_status IN(0,1,2,3)', '');
        #db_order_Status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
        if (count($is_service_on_going_or_complete_data) > 0) {
            $this->session->set_flashdata('error', 'The selected service is already running for this customer!'); //Some fields are missing
            
        }else  if ($customer_fullname != "" && $customer_mobile != "" && $service_category_id != "" && $service_id != "" && $service_price_type != "" && $service_price != "" && $visiting_price != "" && $service_name != "" && $customer_location != "" && $customer_latitude != "" && $customer_longitude != "") { //&& $customer_email !=""
            #check customer is exit in users table ----START----
            if (!empty($_FILES['customer_image']['name'])) {
                $customer_image = $_FILES['customer_image']['name'];
            } else {
                $customer_image = array();
            }
            $customer_status = $this->check_customer_is_exist_or_not_for_order($customer_fullname, $customer_email, $customer_mobile, $gender, $age, $customer_location, $customer_latitude, $customer_longitude, $customer_image);
            #check customer is exit in users table ----END----
            //service order data insert-------------START---------------
            if ($customer_status['customer_status'] == 4 && $customer_status['customer_id'] > 0) {
                //getting admin commission on which order service
                $service_data = $this->Common->getData('services', 'commision,service_name_english', 'service_id = ' . $service_id . '');
                //Create last  accpet service time for service provider----START---
                $last_till_time_of_service_accept = generate_service_accept_till_time();
                #DB_last_till_time_of_service_accept = This time will create according to order current time with duration time which is set by admin (settings table). Ex. order time 12:00 and durtion time 0:30 then service provider has last accept time will be 12:30 after that "customer service or super admin" will be assign service to service provider. because after complete duration no one service provider cant accept service. (12:30 time will be go here in timestamp formate)
                //Create last accpet service time for service provider----END---
                $order_array = ['customer_id' => $customer_status['customer_id'], 'customer_name' => $customer_fullname, 'customer_email' => $customer_email, 'customer_contact' => $customer_mobile, 'service_id' => $service_id, 'service_name' => $service_name, 'service_price_type' => $service_price_type, 'service_price' => $service_price, 'visiting_price' => $visiting_price, 'latitude' => $customer_latitude, 'longitude' => $customer_longitude, 'google_pin_address' => $customer_location, 'note' => $note, 'order_status' => 0, //0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                'order_booked_by' => $this->role, //role 1 - super admin, role 2 - customer-service
                'last_till_time_of_service_accept' => $last_till_time_of_service_accept, 'admin_commission' => $service_data[0]['commision'], 'created_at' => time(), 'updated_at' => time() ];
                $insert_status = $this->Common->insertData('service_order_bookings', $order_array);
                //last inserted id
                $order_id = $this->db->insert_id();
                $or_display_id = generate_order_number_id($order_id);
                $update_array = ['order_number_id' => $or_display_id, ];
                $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                if ($insert_status > 0) {
                    #send notification  to customer and all service related providers
                    send_notification_while_service_book($customer_status['customer_id'], $or_display_id, $order_id, $service_id,"");
                    if ($customer_email != "") {
                        // send email on customer email account -------START---------------
                        comman_send_mail_on_order_booked_success($customer_fullname,$customer_email,$customer_mobile,$or_display_id,$customer_location,$service_data[0]['service_name_english']);
                        // send email on customer email account -------END---------------
                    }
                    $this->session->set_flashdata('success', 'Order Created successfully !');
                } else {
                    $this->session->set_flashdata('error', 'Internal Server Error !');
                }
            } else if ($customer_status['customer_status'] == 3) {
                $this->session->set_flashdata('error', 'User is not applicable for order, as it is registered as a service provider!');
            } else if ($customer_status['customer_status'] == 2) {
                $this->session->set_flashdata('error', 'User is not applicable for order, as it is registered as a customer service!');
            } else if ($customer_status['customer_status'] == 1) {
                $this->session->set_flashdata('error', 'User is not applicable for order, as it is registered as a Super admin!');
            }
            //service order data insert--------------END----------------
        } else {
            $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
        }
        header("location:" . base_url('admin/orders/0/' . READY_FOR_ASSIGN_ORDER_PAGE_VALUE . ''));
    }
    // Create Order ------------------------END----------------------------
    
    //Update settings------------------START--------------------
    public function UpdateSetting() {
        #only admin can update
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            # Get all post values
            $facebook_value = $this->input->post('facebook_value');
            $fb_status = $this->input->post('fb_status');
            $insta_value = $this->input->post('insta_value');
            $insta_status = $this->input->post('insta_status');
            $google_plus = $this->input->post('google_plus');
            $google_status = $this->input->post('google_status');
            $customer_android_version = $this->input->post('customer_android_version');
            $customer_ios_version = $this->input->post('customer_ios_version');
            $provider_android_version = $this->input->post('provider_android_version');
            $provider_ios_version = $this->input->post('provider_ios_version');
            $support_call = $this->input->post('support_call');
            $support_email = $this->input->post('support_email');
            $service_provider_play_store_url = $this->input->post('service_provider_play_store_url');
            $service_provider_app_store_url = $this->input->post('service_provider_app_store_url');
            $customer_play_store_value = $this->input->post('customer_play_store_value');
            $customer_app_store_value = $this->input->post('customer_app_store_value');
            $website_url = $this->input->post('website_url');
            $sery_commission = $this->input->post('sery_commission');
            $service_provider_commission = $this->input->post('service_provider_commission');
            $smtp_email = $this->input->post('smtp_email');
            $smtp_password = $this->input->post('smtp_password');
            $company_name = $this->db->escape_str(trim($this->input->post('company_name')));
            $country_name = $this->db->escape_str(trim($this->input->post('country_name')));
            $service_accept_duration = $this->db->escape_str(trim($this->input->post('service_accept_duration')));
            //$selected_city = json_encode($this->input->post('selected_city'));
            
            # Here we need to update value as well as status hence need to update array. Below array is for updating values
            $update_value = array('facebook' => $facebook_value, 'instagram' => $insta_value, 'google' => $google_plus, 'customer_android_version' => $customer_android_version, 'customer_ios_version' => $customer_ios_version, 'provider_android_version'=> $provider_android_version, 'provider_ios_version' => $provider_ios_version, 'support_call' => $support_call, 'support_email' => $support_email, 'service_provider_play_store_url' => $service_provider_play_store_url, 'service_provider_app_store_url' => $service_provider_app_store_url, 'customer_play_store_url' => $customer_play_store_value, 'customer_app_store_url' => $customer_app_store_value, 'website_url' => $website_url, 'sery_commission' => $sery_commission, 'service_provider_commission' => $service_provider_commission, 'smtp_email' => $smtp_email, 'smtp_password' => $smtp_password, 'company_name' => $company_name, 'country_name' => $country_name, 'service_provider_service_accept_duration' => $service_accept_duration,
            //'selected_city' => $selected_city
            );
            foreach ($update_value as $key => $value) {
                $this->Common->updateData('settings', array('value' => $value), 'name = "' . $key . '"');
            }
            $update_status = array('facebook' => $fb_status, 'instagram' => $insta_status, 'google' => $google_status,);
            # Below array is for updating status
            foreach ($update_status as $key => $value) {
                $this->Common->updateData('settings', array('status' => $value), 'name = "' . $key . '"');
            }
            $this->session->set_flashdata('success', 'Setting updated successfully');
           header("location:" . base_url('admin/setting'));
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Update settings------------------END--------------------

    //Add amount in service provider wallet------------START--------
    public function Add_Amount_In_Provider_Wallet() {
        if ($this->id && $this->role == 1) { #only super admin can add
            $service_provider_id = $this->db->escape_str(trim($this->input->post('service_provider_id')));
            $credit_amount = $this->db->escape_str(trim($this->input->post('amount')));
            if ($service_provider_id != "" && $credit_amount != "") {
                $insert_wallet_table = ['service_provider_id' => $service_provider_id, //primary id of the user table, who role is - 3
                'type' => 1, //(1 -Credit, 2 -Debit)
                'payment_type' => 1, //1 - offline, 2 - online
                'amount' => $credit_amount, 'created_at' => time(), ];
                $insert_status = $this->Common->insertdata('wallet', $insert_wallet_table);
                if ($insert_status > 0) {
                    $this->session->set_flashdata('success', 'Amount added successfully');
                } else {
                    $this->session->set_flashdata('error', 'Internal Server Error');
                }
            } else {
                $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
                
            }
            header('location:' . base_url('admin/wallet_management'));
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Add amount in service provider wallet------------END-----------

    //Navbar Notification list--------------START-----------------
    public function navbar_notification_list() {
        $this->load->view("admin/partials/navbar-notification-list");
    }
    //Navbar Notification list--------------END-------------------

    //Service Category -------------View------------START---------------------
    public function all_notification($table_data = '') {
        if ($this->id && ($this->role == 1 || $this->role == 2)) { // role = (1 - admin, 2 - customer-service)
            $common_query = 'SELECT * FROM `notifications` WHERE  ((role = 3 AND order_status != 0) OR (role = 1 AND order_status = 0)) AND `is_read_by_admin`= 0 ' . $query_part . ' ORDER BY `id` DESC '; //(1 - Enable, 2 - Disable, 3 - Deleted)Default value - 1
            //pagination  ---start----
            $page_segment = 4;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['notification_data'] = $this->Common->custom_query($query, "get");
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/all_notification/0/' . $fromdate . '/' . $todate . '/' . $cat_status . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('all_notification' => base_url('admin/all_notification'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Notifications';
            $pagedata['pageName'] = 'admin/all-notification';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/all-notification-list-table', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Service Category  ---------------View----------END---------------------

    // Create and update admin user----------------START--------------
    public function Create_Update_Admin_User() {
        $admin_user_fullname = $this->db->escape_str(trim($this->input->post('fullname')));
        $admin_user_email = $this->db->escape_str(trim($this->input->post('email')));
        $admin_user_mobile = $this->db->escape_str(trim($this->input->post('mobile')));
        $mode = $this->db->escape_str(trim($this->input->post('mode'))); //1 = add , 2 - edit
        if ($admin_user_fullname != "" && $admin_user_mobile != "" && $admin_user_email != "") {
            $GeneratedPassword = $this->GeneratePassword($admin_user_fullname, $admin_user_mobile);
            //insert update values array
            $array = ['fullname' => $admin_user_fullname, 'email' => $admin_user_email, 'mobile' => $admin_user_mobile, 'role' => 2, //(1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
            'updated_at' => time() ];
            if ($mode == 1) { //add
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $this->Common->getData('users', 'user_id', '(email = "' . $admin_user_email . '" OR mobile = "' . $admin_user_mobile . '") AND user_status != 3 '); // # role = (1 - admin, 2 - customer-service){
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                //echo $this->db->last_query();
                if (!empty($check_user_if_exist)) {
                    // user is already exist
                    $this->session->set_flashdata('error', 'User already exists');
                } else {
                    //new user enter
                    $array['country_code'] = COUNTRY_CODE;
                    $array['password'] = $this->bcrypt->hash_password(trim($GeneratedPassword));;
                    $array['created_at'] = time();
                    $insert_status = $this->Common->insertData('users', $array);
                    #create number id and update it will be unique---START---
                    $user_id = $this->db->insert_id();
                    $display_id_start = 10000; # Static value and it will be added by the last Id in increasing way
                    $sr_display_id = $display_id_start + $user_id;
                    $update_array = ['number_id' => $sr_display_id, ];
                    $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                    #create number id and update ---END---
                    if ($insert_status > 0) {
                        $this->session->set_flashdata('success', 'User created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Internal Server Error !');
                    }
                }
            } else if ($mode == 2) { //edit
                $edit_user_id = $this->db->escape_str(trim($this->input->post('edit_user_id')));
                //checking email and mobile number if exist in users table
                $check_user_if_exist = $this->Common->getData('users', 'user_id', '(email = "' . $admin_user_email . '" OR mobile = "' . $admin_user_mobile . '") AND user_status != 3 AND user_id != ' . $edit_user_id . ''); // # role = (1 - admin, 2 - customer-service){
                // DB_user_status (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
                //check if any changes do --------START--------------
                $check_user = $this->Common->getData('users', 'fullname,email,mobile', '(email = "' . $admin_user_email . '" OR mobile = "' . $admin_user_mobile . '") AND user_status != 3'); // # role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
                //create array for check if any changes done for only match with exist data
                $target = array(array('fullname' => $admin_user_fullname, 'email' => $admin_user_email, 'mobile' => $admin_user_mobile));
                //check if any changes do --------END----------------
                $change_status = true; // if any changes done then mail should be send
                if ($check_user == $target) {
                    // no changes done
                    $this->session->set_flashdata('error', 'Nothing Changed');
                    $change_status = false;
                } else {
                    if (!empty($check_user_if_exist)) {
                        // user is already exist
                        $this->session->set_flashdata('error', 'User already exists');
                    } else {
                        $update_status = $this->Common->updateData('users', $array, 'user_id = "' . $edit_user_id . '"');
                        if ($update_status > 0) {
                            $this->session->set_flashdata('success', 'User updated successfully');
                        } else {
                            $this->session->set_flashdata('error', 'Internal Server Error !');
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Some fields are missing!'); //Some fields are missing
        }
        // send email on user email account -------START---------------
        if ((isset($insert_status) && $insert_status > 0) || (isset($update_status) && $update_status > 0 && $change_status == true) && $admin_user_email != "") {
            send_email_on_user_create_update($admin_user_fullname,$admin_user_email,$GeneratedPassword,$admin_user_mobile,$mode);
        }
        // send email on user email account -------END---------------
        header("location:" . base_url('admin/user_management'));
    }
    // Create and update admin user-----------------END--------------
   
    //Zone list-------------View------------START---------------------
    public function zone_management($table_data = '', $fromdate = 'all', $todate = 'all', $status = 'all', $search_key = 'all') {
        #only admin can add banner
        if ($this->id && $this->role == 1) { // role = (1 - admin, 2 - customer-service)
            $pagedata['fromdate'] = $fromdate;
            $pagedata['todate'] = $todate;
            $pagedata['zone_status'] = $status; //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
            $pagedata['search_key'] = $search_key;
            $search_key = urldecode($search_key);
            $search_key = trim($search_key);
            $pagedata['search'] = $search_key;
            $query_part = "";
            if ($fromdate != "all" || $todate != "all" || $search_key != "all" || $status != "all") {
                if ($fromdate != "all" && $todate == "all") {
                    $query_part.= ' AND  `created_at` >= "' . strtotime($fromdate) . ' 00:00:00"';
                }
                if ($todate != "all" && $fromdate == "all") {
                    $query_part.= ' AND  `created_at` <= "' . strtotime($todate) . ' 23:59:59"';
                }
                if ($fromdate != "all" && $todate != "all") {
                    $fromDateNew = strtotime($fromdate . ' 00:00:00');
                    $toDateNew = strtotime($todate . ' 24:00:00');
                    $query_part.= " and  (created_at between '$fromDateNew' and '$toDateNew')";
                }
                if ($status != "all") {
                    $query_part.= ' AND `status` = "' . $status . '"';
                }
                if ($search_key != "all") {
                    $query_part.= ' AND (`zone_name` LIKE "%' . $search_key . '%")';
                }
            }
            $common_query = 'SELECT * FROM `zones` WHERE `status` != 3' . $query_part . ' ORDER BY `id` DESC ';
            //pagination  ---start----
            $page_segment = 8;
            $query = comman_get_query_accroding_page_segment($page_segment, $common_query);
            $pagedata['zone_data'] = $this->Common->custom_query($query, "get");
             
            $query = "" . $common_query . "";
            $total_records = count($this->Common->custom_query($query, "get"));
            $url = base_url('admin/zone_management/0/' . $fromdate . '/' . $todate . '/' . $status . '/' . $search_key . '/'); //by default table value is 0
            # Pass parameter to common pagination and create pagination function start
            create_pagination($url, $total_records, ADMIN_PER_PAGE_RECORDS);
            $pagedata['links'] = $this->pagination->create_links();
            $pagedata['start'] = ($page * ADMIN_PER_PAGE_RECORDS) + 1;
            //pagination  ---End----
            $array = array('user_management' => base_url('admin/zone_management'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Zone Management';
            $pagedata['pageName'] = 'admin/zone-management';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/zone-table-list', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        } else {
            header('location:' . base_url('admin'));
        }
    }
    //Zone liste -------------View------------END---------------------

    //Create/UPDATE Zone -------------View------------START---------------------
    public function add_edit_zone($zone_id = ""){
        if ($this->id && $this->role == 1) { 
            $pagedata['zone_data'] = $this->Common->getData('zones', 'zones.*', 'id = ' . $zone_id . '');

            $array = array('user_management' => base_url('admin/add_edit_zone'));
            $this->createBreadcrumb($array);
            $pagedata['pageTitle'] = 'Zone Management';
            $pagedata['pageName'] = 'admin/zone-create-edit';
            if ($table_data == "1" || $table_data == "2") {
                // if any action tiriger like, delete or enable disable then is url excute by ajax
                $this->load->view('admin/zone-table-list', $pagedata);
            } else {
                $this->load->view('admin/masterpage', $pagedata);
            }
        }
    }
    //Create/UPDATE  Zone ---------------View----------END---------------------

    //Create/UPDATE Zone ----------functionality ----------START----------------
    public function Create_Update_Zone_Controller(){
        if ($this->id && $this->role == 1) {
            $zone_name = $this->db->escape_str(trim($this->input->post('zone_name')));
            $zones_lat_long = $this->db->escape_str(trim($this->input->post('zones_lat_long')));
            $mode = $this->db->escape_str(trim($this->input->post('mode')));
            $zone_id = $this->db->escape_str(trim($this->input->post('zone_id')));
            $zones_array =  explode('<br>', $zones_lat_long);
            array_pop($zones_array);
            $zones_json  = json_encode($zones_array);

            $insert_update_array = ["zone_name"=>$zone_name,"zone_latitude_longitude"=>$zones_json,'updated_at'=> time()];

            if($mode == 1){
                #check if zone name is already exist in zone table
                $zone_data = $this->Common->getData('zones', 'id', 'zone_name = "'.$zone_name.'" ');
                if(count($zone_data)>0){
                    $zone_name_already_exist = 1;
                }else{
                    $insert_update_array['created_at'] = time();
                    $insert_update_status = $this->Common->insertData('zones', $insert_update_array);
                    $msg = 'created';
                    $zone_name_already_exist = 0;
                }
                
            }else if($mode == 2 && $zone_id>0){
                #check if zone name is already exist in zone table
                $zone_data = $this->Common->getData('zones', 'id','zone_name = "'.$zone_name.'" AND id != '.$zone_id.'');
                if(count($zone_data)>0){
                    $zone_name_already_exist = 1;
                }else{
                    $insert_update_status = $this->Common->updateData('zones', $insert_update_array, 'id = "' . $zone_id . '"');
                    $msg = 'updated';
                    $zone_name_already_exist = 0;
                }
            }

            if($insert_update_status>0 && $zone_name_already_exist == 0){
                $this->session->set_flashdata('success', 'Zone '.$msg.'  successfully');
            }else if($zone_name_already_exist == 1){
                $this->session->set_flashdata('error', 'Zone name already exist !');
            }else{
                $this->session->set_flashdata('error', 'Internal Server Error !');
            }
            header("location:" . base_url('admin/zone_management'));
        }else{
            header('location:' . base_url('admin'));
        }
    }
    //Create/UPDATE Zone ----------functionality ----------END----------------

    //Add City ----------functionality ----------START----------------
    public function Add_City_Controller(){
        if ($this->id && $this->role == 1) {
            $city_name = ucwords($this->db->escape_str(trim($this->input->post('city_name'))));
            
            $insert_array = ["name"=>$city_name,'created_at'=> time(),'updated_at'=> time()];
            $insert_status = $this->Common->insertData('cities', $insert_array);

            if($insert_status>0){
                $this->session->set_flashdata('success', 'City added successfully');
            }else{
                $this->session->set_flashdata('error', 'Internal Server Error !');
            }
           header("location:" . base_url('admin/setting'));
        }else{
            header('location:' . base_url('admin'));
        }
    }
    //Add City ----------functionality ----------END----------------
}