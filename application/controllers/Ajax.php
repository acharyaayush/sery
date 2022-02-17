<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'core/Comman_Admin_Controller.php';
class Ajax extends Comman_Admin_Controller {

	#check old password
    public function CheckOldPassword() {
        if ($this->id) {
            $old_password = $this->db->escape_str(trim($this->input->post('old_password')));
            $get_old_password = $this->Common->getData('users', 'password', 'user_id ="' . $this->id . '" AND role = ' . $this->role . ''); //Super Admin role - 1, 2 - cutomer service
            $exist_password = $get_old_password[0]['password'];
            // match enter old password with exist old password
            if (!$this->bcrypt->check_password($old_password, $exist_password)) {
                echo 0;
            } else {
                echo 1;
            }
        } else {
            echo 2; //session out
        }
    }

    //Update Service Category Status(Enable/Disable) ------------START--------------
    public function UpdateCategoryStatus() {
        if ($this->id) {
            $cat_status = $this->db->escape_str(trim($this->input->post('cat_status')));
            $cat_id = $this->db->escape_str(trim($this->input->post('cat_id')));
            if ($cat_status != "" && $cat_id != "") {
                $update_array = ['cat_status' => $cat_status, //1 - Enable, 2 - Disable
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('service_categories', $update_array, 'cat_id = "' . $cat_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_categories'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Update Service Category Status(Enable/Disable) ------------END--------------

    //Delete Service Category ------------START--------------
    public function DeleteCategoryStatus() {
        if ($this->id) {
            $cat_id = $this->db->escape_str(trim($this->input->post('cat_id')));
            if ($cat_id != "") {
                $update_array = ['cat_status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('service_categories', $update_array, 'cat_id = "' . $cat_id . '"');
                if ($update_status > 0) {
                    #also set delete status of thare related services and also will be delete from provider skills
                    $update_array = ['service_status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                    'updated_at' => time(), ];
                    $this->Common->updateData('services', $update_array, 'category_id = "' . $cat_id . '"');
                    $this->Common->deleteData('service_provider_key_skills', 'category_id =' . $cat_id . '');
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_categories'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Service Category ------------END--------------

    //Get Service Category data for edit ------------------START-------
    public function GET_Category_Data() {
        $cat_id = $this->db->escape_str(trim($this->input->post('cat_id')));
        $category_data = $this->Common->getData('service_categories', '*', 'cat_status != 3 AND cat_id = ' . $cat_id . ''); //cat_status = 1 - Enable, 2 - Disable, 3 = delete
        if (count($category_data) > 0) {
            echo json_encode($category_data[0]);
        } else {
            echo 0;
        }
    }
    //Get Service Category data for edit ------------------END----------

    //Get Service data for edit ------------------START-------
    #this function is used in  below points so we need to use it carefully if do any changes-
    #1 - service edit time to show data for edit,
    #2 - at the time order to get service data according to seleted service id
    public function GET_Service_Data() {
        $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
        $service_data = $this->Common->getData('services', '*', 'service_status != 3 AND service_id = ' . $service_id . ''); //service_status = (1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
        if (count($service_data) > 0) {
            echo json_encode($service_data[0]);
        } else {
            echo 0;
        }
    }
    //Get Service data for edit ------------------END----------

    //Update Service(Sub category) Status(Enable/Disable) -------START----------
    public function UpdateServiceStatus() {
        if ($this->id) {
            $service_status = $this->db->escape_str(trim($this->input->post('service_status')));
            $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
            if ($service_status != "" && $service_id != "") {
                $update_array = ['service_status' => $service_status, //1 - Enable, 2 - Disable
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('services', $update_array, 'service_id = "' . $service_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Update Service(Sub category)  Status(Enable/Disable) --------END----------

    //Delete Service (Sub category)------------START--------------
    public function DeleteServiceStatus() {
        if ($this->id) {
            $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
            if ($service_id != "") {
                $update_array = ['service_status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('services', $update_array, 'service_id = "' . $service_id . '"');
                if ($update_status > 0) {
                    #also delete from provider skills
                    $this->Common->deleteData('service_provider_key_skills', 'service_id =' . $service_id . '');
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Service (Sub category)------------END--------------

    //Get Service data for edit ------------------START-------
    public function GET_User_Data() {
        $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
        $customer_data = $this->Common->getData('users', '*', 'user_status != 3 AND user_id = ' . $user_id . ''); //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        if (count($customer_data) > 0) {
            echo json_encode($customer_data[0]);
        } else {
            echo 0;
        }
    }
    //Get Service data for edit ------------------END----------

    //Getting duration for showing clock timer-------------START---------
    public function GET_Current_Duration_From_Start_Time() {
        if ($this->id) {
            $service_started_time = $this->db->escape_str(trim($this->input->post('service_started_time')));
            if ($service_started_time != "") {
                echo comman_getting_time_between_duration($service_started_time, time());
            } else {
                echo 3;
            }
        } else {
            echo 2; //session out
        }
    }
    //Getting duration for showing clock timer-------------END-----------

     //Getting skills accroding to selected service of service provider-------------START---------
    public function GET_Skills() {
        if ($this->id) {
            $skill_id = $this->db->escape_str(trim($this->input->post('skill_id')));
            if ($skill_id != "") {
                $skill_data = $this->Common->getData('service_provider_key_skills', 'key_skill_english', 'key_skill_id = ' . $skill_id . '');
                echo $skill_data[0]['key_skill_english'];
            } else {
                echo 3;
            }
        } else {
            echo 2; //session out
        }
    }
    //Getting skills accroding to selected service of service provider-------------END---

     //Update Notification read status (with mark as read)------------START--------------
    public function UpdateNotificationReadStatus() {
        if ($this->id) {
            $read_status = $this->db->escape_str(trim($this->input->post('read_status'))); //1
            if ($read_status != "") {
                $update_array = ['is_read_by_admin' => $read_status, //db_is_read = 0 - unread, 1- read
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('notifications', $update_array, 'role = 3');
                $this->Common->updateData('notifications', $update_array, 'role = 1');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_categories'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Update Notification read status (with mark as read)------------------------END--------------

    //Update User Status(Enable/Disable) -------START----------
    #as for now same funcation we are using  for customer and admin user status change
    public function UpdateUserStatus() {
        if ($this->id) {
            $user_status = $this->db->escape_str(trim($this->input->post('user_status')));
            $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
            if ($user_status != "" && $user_id != "") {
                $update_array = ['user_status' => $user_status, //1 - Enable, 2 - Disable
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                    
                } else {
                    echo 0; //not success
                }
            } else {
                if ($this->input->post('user_management')) {
                    header("location:" . base_url('admin/user_management'));
                } else {
                    header("location:" . base_url('admin/customer_Management'));
                }
            }
        } else {
            echo 2; //session out
        }
    }
    //Update User Status(Enable/Disable) --------END----------

    //Delete Customer------------START--------------
    #as for now same funcation we are using  for customer and admin user status change
    public function DeleteUserStatus() {
        if ($this->id) {
            $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
            if ($user_id != "") {
                $update_array = ['user_status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                if ($this->input->post('user_management')) {
                    header("location:" . base_url('admin/user_management'));
                } else {
                    header("location:" . base_url('admin/customer_Management'));
                }
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Customer------------END--------------

    //Get Service (Sub Category) according to selected category---START-----
    public function GET_Service_Data_By_Cat() {
        $cat_id = $this->db->escape_str(trim($this->input->post('cat_id')));
        $service_data = $this->Common->getData('services', '*', 'category_id = ' . $cat_id . ' AND service_status != 3', '', '', ' service_id', 'DESC');
        #service_status  = (1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
        if (count($service_data) > 0) {
            echo json_encode($service_data);
        } else {
            echo 0;
        }
    }
    //Get Service (Sub Category) according to selected category---END-----

    //Get Service data for edit ------------------START-------
    public function GET_ServiceProvider_Data() {
        $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
        $ServiceProvider_data = $this->Common->getData('users', '*', 'user_status != 3 AND user_id = ' . $user_id . ''); //user_status = (1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        //getting key skills of service provider -----START-----------
        $key_skills = $this->Common->getData('service_provider_key_skills', '*', 'service_provider_id = ' . $ServiceProvider_data[0]['user_id'] . '');
        if (!empty($key_skills)) {
            $ServiceProvider_data[0]['skills'] = $key_skills;
        } else {
            $ServiceProvider_data[0]['skills'] = array();
        }
        //getting key skills of service provider -----END-----------
        if (count($ServiceProvider_data) > 0) {
            echo json_encode($ServiceProvider_data[0]);
        } else {
            echo 0;
        }
    }
    //Get Service data for edit ------------------END----------

    //Update Service Provider Status(Enable/Disable) -------START----------
    public function UpdateServiceProviderStatus() {
        if ($this->id) {
            $service_provider_status = $this->db->escape_str(trim($this->input->post('service_provider_status')));
            $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
            if ($service_provider_status != "" && $user_id != "") {
                $update_array = ['user_status' => $service_provider_status, //1 - Enable, 2 - Disable
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_provider_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Update Service Provider Status(Enable/Disable) --------END----------

    //Delete Service Provider------------START--------------
    public function DeleteServiceProviderStatus() {
        if ($this->id) {
            $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
            if ($user_id != "") {
                $update_array = ['user_status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('users', $update_array, 'user_id = "' . $user_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/service_provider_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Service Provider------------END--------------

    //Upload and Save Banner image -------------START---------------------------
    public function Upload_Save_Banners() {
        if ($this->id) {
            if (!empty($_FILES['banner_image']['name'])) {
                //upload image -------start
                $exp = explode(".", $_FILES['banner_image']['name']);
                $ext = end($exp);
                $st1 = substr(date('ymd'), 0, 3);
                $st2 = $st1 . rand(1, 100000);
                $fileName = $st2 . time() . date('ymd') . "." . $ext;
                $original_image_path = img_upload_path()[0].'banner_images/'; //orignal image path
                $resize_image_path = img_upload_path()[0].HomeBannerImgFolder; //orignal image path
                /* Image upload  */
                $config['upload_path'] = $original_image_path;
                $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                $config['file_name'] = $fileName;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    $error_msg = $this->upload->display_errors();
                    $message = strip_tags($error_msg);
                    $this->session->set_flashdata('error', $message);
                } else {
                    # Resize only if NOT SVG
                    if ($ext !== 'svg') {
                        /*Image resize function starts here*/
                        $source_image = $original_image_path . $fileName;
                        $new_image = $resize_image_path . $fileName;
                        $width = MOBILE_HOME_BANNER_WIDTH;
                        $height = MOBILE_HOME_BANNER_HIGHT;
                        # Call this function to rezise the image and place in a new path
                        image_resize($source_image, $new_image, $width, $height);
                        /*Image resize function ends here*/
                    } else {
                        /* Image upload */
                        $config['upload_path'] = $resize_image_path;
                        $config['allowed_types'] = 'jpg|png|jpeg|bmp|svg';
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('banner_image')) {
                            $error_msg = $this->upload->display_errors();
                            $message = strip_tags($error_msg);
                            //$this->session->set_flashdata('error' , $message);
                            echo 2; //upload error
                        }
                    }
                    if (!empty($config)) {
                        // successfully uploaded image and now we can save in to the database table
                        $array['banner_image'] = HomeBannerImgFolder . $fileName;
                        $array['created_at'] = time();
                        $array['updated_at'] = time();
                        $insert_status = $this->Common->insertData('banners', $array);
                        if ($insert_status > 0) {
                            echo 1;
                        } else {
                            echo 0; //Internal Server Error !
                        }
                    } else {
                        echo 4; //not uploaded
                    }
                }
            } else {
                echo 3; //somthing went wrong
            }
        } else {
            echo 6; //session out
        }
    }
    //Upload and Save Banner image -------------START---------------------------

    //Delete Banner------------START--------------
    public function DeleteBanner() {
        if ($this->id) {
            $banner_id = $this->db->escape_str(trim($this->input->post('banner_id')));
            if ($banner_id != "") {
                $update_array = ['banner_status' => 2, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('banners', $update_array, 'banner_id = "' . $banner_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/banners'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Banner------------END--------------

    //Check Booked Service if on going at the time re order  ------------START----------
    public function Check_BookedService_If_OnGoing_or_completed() {
        if ($this->id) {
            //Note -
            #check at the time order that  selected service is  already ongoing or not
            #Check service id when it is ordering for the customer. It will be unique until order status completed or ignored or canceled) means the user can't order for the same service until the selected service is not finished. But customers can choose other
            $customer_mobile = $this->db->escape_str(trim($this->input->post('customer_mobile')));
            $customer_email = $this->db->escape_str(trim($this->input->post('customer_email')));
            $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
            //if email not given
            if ($customer_email != "") {
                $email_check_query_part = 'customer_email = "' . $customer_email . '" OR ';
            } else {
                $email_check_query_part = '';
            }
            if ($customer_mobile != "" && $service_id != "") {
                $is_service_on_going_or_complete_data = $this->Common->getData('service_order_bookings', 'order_id', '(' . $email_check_query_part . ' customer_contact = ' . $customer_mobile . ') AND service_id =' . $service_id . ' AND order_status IN(0,1,2,3)', '');
                #db_order_Status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                if (count($is_service_on_going_or_complete_data) > 0) {
                    echo 1;
                } else {
                    echo 0;
                }
            }
        } else {
            echo 2; //session expire
        }
    }
    //Check Booked Service if on going at the time re order------------END--------------

    //Getting Service Provider data for assign ------------START---------------
    #Service provider will show  in the list , only accroding to ordered service related
    public function GET_Service_Providers_For_Assign() {
        if ($this->id) {
            $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
            $service_provider_data = $this->Common->getData('users', 'users.user_id,users.fullname', 'service_provider_key_skills.service_id = ' . $service_id . ' AND users.is_online= 1', array('service_provider_key_skills'), array('service_provider_key_skills.service_provider_id = users.user_id'), 'users.user_id', 'DESC');
            if (count($service_provider_data) > 0) {
                echo json_encode($service_provider_data);
            } else {
                echo 0;
            }
        } else {
            echo 2; //session out
        }
    }
    //Getting Service Provider data for assign ------------START---------------

    //Assign order to service provider --------------END--------------------------
    public function Assign_Order() {
        if ($this->id) {
            $service_provider_id = $this->db->escape_str(trim($this->input->post('service_provider_id')));
            $order_id = $this->db->escape_str(trim($this->input->post('order_id')));
            if ($service_provider_id != "" && $order_id != "") {
                #First we need to check , condition if the service provider has any ongoing service then they can't accept any service.
                $booked_service_data = $this->Common->getData('service_order_bookings', 'created_at as order_time,last_till_time_of_service_accept', 'order_status IN(0,1,2,3) AND order_accept_by=' . $service_provider_id . '', '', '', '', '');
                #DB_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                if (!empty($booked_service_data)) {
                    echo 3;
                } else {
                    $update_array = ['order_accept_by' => $service_provider_id, //(primary id of the users (Service Provider id) table who role is - 3), Default - 0
                    'updated_at' => time(), ];
                    $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                    if ($update_status > 0) {
                        echo 1; //success
                    } else {
                        echo 0; //not success
                    }
                }
            } else {
                header("location:" . base_url('admin/orders'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Assign order to service provider --------------START--------------------------

    //Order Status Chanage ---------------------START--------------------------
    public function UpdateOrderStatus() {
        if ($this->id) {
            $order_id = $this->db->escape_str(trim($this->input->post('order_id')));
            $order_status = $this->db->escape_str(trim($this->input->post('order_status')));
            if ($order_status != "" && $order_id != "") {
                $update_array = ['order_status' => $order_status,
                #0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                #order cancel by "Update_Order_Cancel_Status" function with reasone
                #order can cancel till order status "on the way"
                'updated_at' => time(), ];
                #update time status wise because time will be show in mobile side
                if ($order_status == 1) { #accept
                    $update_array['accepted_status_time'] = time();
                } else if ($order_status == 2) { #on-the-way
                    $update_array['on_the_way_status_time'] = time();
                } else if ($order_status == 3) { #started
                    $update_array['start_service_time'] = time();
                } else if ($order_status == 4) { #end/completed
                    $update_array['end_service_time'] = time();
                    
                    #getting time when service start by service provider to calcaulate final complete duration
                    $start_time = comman_getting_service_start_time($order_id);
                    $stop_time = time();
                    $update_array['taken_time'] = comman_getting_time_between_duration($start_time, $stop_time);
                    $update_array['total_amount'] = $total_amount = generate_total_order_amount($order_id, $start_time, $stop_time);
                }
                $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                if ($update_status > 0) {
                    #Send notification To Customer and service provider
                    send_notification_when_order_status_change($order_id, $order_status);
                    if ($order_status == 4) {
                        #Amount will be deduct on order complete -------------START----------
                        deduct_amount_from_provider_wallet($order_id, $total_amount);
                        #Amount will be deduct on order complete -------------END-----------
                        #generate invoice pdf ----start
                        comman_generate_and_download_order_invoice_pdf($order_id);
                        #generate invoice pdf ----end
                    }
                    echo 1; //success
                    
                } else {
                    echo 0; //not success
                    
                }
            } else {
                header("location:" . base_url('admin/orders'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Order Status Chanage ---------------------END--------------------------

    //Order Cancel Status Update  ---------------------START--------------------------
    public function Update_Order_Cancel_Status() {
        if ($this->id) {
            $order_id = $this->db->escape_str(trim($this->input->post('order_id')));
            $cancel_for_whom = $this->db->escape_str(trim($this->input->post('cancel_for_whom'))); //4 = customer, 3 = service provider
            $service_provider_id = $this->db->escape_str(trim($this->input->post('service_provider_id')));
            $service_id = $this->db->escape_str(trim($this->input->post('service_id')));
            $cancel_reason = $this->db->escape_str(trim($this->input->post('cancel_reason')));
            if ($order_id != "" && $cancel_for_whom != "" && $cancel_reason != "") {
                #order can cancel till order status "on the way" but if service provider want to cancel so they will contact to customer service and with reason serivce can be cancel only by admin/customer service
                #customer can cancel by mobile application or cancel by customer service/ super admin with reason
                if ($cancel_for_whom == 3) { #cancel for service provider
                    if ($service_provider_id != "" && $service_id != "") {
                        $check_exit_cancel_service_data = $this->Common->getData('service_cancel_by_service_providers', 'id', 'service_provider_id = ' . $service_provider_id . ' AND order_id=' . $order_id . ' AND service_id = ' . $service_id . '', '', '', '', '');
                        if (empty($check_exit_cancel_service_data)) {
                            $insert_array = ['order_id' => $order_id, 'service_id' => $service_id, 'service_provider_id' => $service_provider_id, 'cancel_reason' => $cancel_reason,
                            #db_cancel_reason = If cancel by admin/customer-service after cantact to service provider then reason will be go here and in booking table order_status value will be 0 again
                            'cancel_by' => $this->role,
                            #db_cancel_by = logged role 1 - by super admin, 2 - customer service (Only admin can cancel for service provider)
                            'created_at' => time() ];
                            $insert_status = $this->Common->insertData('service_cancel_by_service_providers', $insert_array);
                            if ($insert_status > 0) {
                                $update_array = ['order_accept_by' => "", 'order_status' => "", 'order_status' => 0, 'start_service_time' => "", 'end_service_time' => "", 'on_the_way_status_time' => "", 'created_at' => time() ];
                                $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');

                                #send notification to service provider
                                $order_status = 0;
                                send_notification_when_service_cancel($order_id, $order_status, $service_provider_id);
                                echo 1; //success
                            } else {
                                echo 0; //not success
                            }
                        } else {
                            echo 3; //already cancelled
                        }
                    } else {
                        echo 4; //provider details missing
                    }
                } else if ($cancel_for_whom == 4) { #cancel for customer
                    $order_status = 6;
                    $update_array = ['order_status' => $order_status,
                    #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
                    #order cancel by "Update_Order_Cancel_Status" function with reasone
                    #order can cancel till order status "on the way"
                    'cancel_reason' => $cancel_reason,
                    #db_cancel_reason = If order_status = 6 and cancelled by customer (cancel_by = 4)then reason will be enter in this column. If cancel by admin/customer-service for any service provider then cancel data insert will be in "service_cancel_by_service_providers" table because order will assign other service provider again and order_status should be 1 and order_accepted_by value will be change before assign to any one order_status = 0.
                    'cancel_by' => $this->role,
                    #db_cancel_by = customer role - 4 , if cancel by customer (for service provider, only admin can cancel after cantact and cancel data will be insert in to table "service_cancel_by_service_providers"). If admin cancel service for customer then admin role will be go here
                    'updated_at' => time(), ];
                    $update_status = $this->Common->updateData('service_order_bookings', $update_array, 'order_id = "' . $order_id . '"');
                    if ($update_status > 0) {
                        #send notification to customer
                        $customer_data = $this->Common->getData('service_order_bookings', 'customer_id', 'order_id=' . $order_id . '', '', '', '', '');
                        send_notification_on_order_cancel_by_customer($order_id,$customer_data[0]['customer_id'],$order_status,"");
                        echo 1; //success
                        
                    } else {
                        echo 0; //not success
                    }
                }
            } else {
                header("location:" . base_url('admin/orders'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Order Cancel Status Update ---------------------END--------------------------

    //Order - Get Customer mobile number when admin enter digit----START------
    #when order create
    #At the time of enter customer mobile number if customer is exit then after select mobile number from the list or after match customer details will be auto filled
    public function Get_Customers_Mobile_Number() {
        if ($this->id) {
            if ($this->input->post('mobile_number')) {
                $mobile_number = $this->db->escape_str(trim($this->input->post('mobile_number')));
                $customer_contact_data = $this->Common->getData('users', 'user_id,mobile,email,age,gender,fullname,profile_image,google_map_pin_address, latitude,longitude', 'mobile  LIKE "%' . $mobile_number . '%" AND role = 4', '', '', 'user_id', 'DESC');
                if (count($customer_contact_data) > 0) {
                    echo json_encode($customer_contact_data);
                } else {
                    echo 0;
                }
            }
        } else {
            echo 2; //session out
        }
    }
    //Order - Get Customer mobile number when admin enter digit----END------

	//Check Mobile Number Eixt on enter -------START-------
    public function CheckMobileNoExist() {
        if ($this->id) {
            $mobile = $this->db->escape_str(trim($this->input->post('mobile')));
            $is_page_admin_profle = $this->db->escape_str(trim($this->input->post('is_page_admin_profle')));
            if ($mobile != "") {
                $query_part  = "";
                if($is_page_admin_profle == true){
                     $query_part = 'AND user_id != '.$this->id.'';
                }
                $data = $this->Common->getData('users', 'user_id', '  mobile = "' . $mobile . '" AND user_status != 3 '.$query_part.'');
                if(count($data)>0){
                    echo 1;
                }else{
                    echo 0;
                }
            } else {
                echo 3;
            }
        } else {
            echo 2; //session out
        }
    }
    //Check Mobile Number Eixt on enter -------END-------

    //Check Email on enter -------START-------
    public function CheckEmailExist() {
        if ($this->id) {
            $email = $this->db->escape_str(trim($this->input->post('email')));
            $user_id = $this->db->escape_str(trim($this->input->post('user_id')));
            $is_page_admin_profle = $this->db->escape_str(trim($this->input->post('is_page_admin_profle')));
            if ($email != "") {
                $query_part  = "";
                if($is_page_admin_profle == true){
                     $query_part = 'AND user_id != '.$this->id.'';
                }

                if($user_id != ""){
                     $query_part = 'AND user_id != '.$user_id.'';
                }
                $data = $this->Common->getData('users', 'user_id', '  email = "' . $email . '" AND user_status != 3 '.$query_part.''); 
                if(count($data)>0){
                    echo 1;
                }else{
                    echo 0;
                }
            } else {
                echo 3;
            }
        } else {
            echo 2; //session out
        }
    }
    //Check Email on enter -------END-------

    //Update zone Status(Enable/Disable) -------START----------
    #as for now same funcation we are using  for customer and admin user status change
    public function UpdateZoneStatus() {
        if ($this->id) {
            $zone_status = $this->db->escape_str(trim($this->input->post('zone_status')));
            $zone_id = $this->db->escape_str(trim($this->input->post('zone_id')));
            if ($zone_status != "" && $zone_id != "") {
                $update_array = ['status' => $zone_status, //1 - Enable, 2 - Disable
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('zones', $update_array, 'id = "' . $zone_id . '"');

                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/zone_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Update zone Status(Enable/Disable) --------END----------

    //Delete Zone------------START--------------
    #as for now same funcation we are using  for customer and admin user status change
    public function DeleteZoneStatus() {
        if ($this->id) {
            $zone_id = $this->db->escape_str(trim($this->input->post('zone_id')));
            if ($zone_id != "") {
                $update_array = ['status' => 3, //1 - Enable, 2 - Disable, 3 = delete
                'updated_at' => time(), ];
                $update_status = $this->Common->updateData('zones', $update_array, 'id = "' . $zone_id . '"');
                if ($update_status > 0) {
                    echo 1; //success
                } else {
                    echo 0; //not success
                }
            } else {
                header("location:" . base_url('admin/zone_management'));
            }
        } else {
            echo 2; //session out
        }
    }
    //Delete Zone------------END--------------

    //Check Mobile Number Eixt on enter -------START-------
    public function CheckCityExist() {
        if ($this->id) {
            $city_name = $this->db->escape_str(trim($this->input->post('city_name')));
            if ($city_name != "") {
                $data = $this->Common->getData('cities', 'id', '  name = "' . $city_name . '" AND status != 3 ');
                if(count($data)>0){
                    echo 1;
                }else{
                    echo 0;
                }
            } else {
                echo 3;
            }
        } else {
            echo 2; //session out
        }
    }
    //Check Mobile Number Eixt on enter -------END-------
}