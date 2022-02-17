<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Comman_Api_Controller extends REST_Controller
{
   public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->model("Common");
        $this->lang->load('english', 'english'); # first param english specifies the file english_lang and second specfies the language. So english,english => language folder/english folder/english_lang.php file and english language. We won't get english_lang file in our code. _lang is added by CI itself
        # Below method is added to check the token in constructor because if user is logged in and then deleted by admin then in the next api calling action it will check the token and will return 203 if user is deleted.

        $this->lang->load('amharic', 'amharic'); #for push notification send in amharic
        
        if($this->router->fetch_method() != 'verify_otp' && $this->router->fetch_method() != 'user_login' && $this->router->fetch_method() != 'cms_content' && $this->router->fetch_method() != 'app_version'){
            $this->tokenData = $this->verify_request();
            
            if ($this->tokenData === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $data['status'] = $status;
                $data['message'] = $this->lang->line('unauthorized_access');
                echo json_encode($data);
                return $data;
            } else if ($this->tokenData == '203') {
                $data['status'] = 401;
                $data['message'] = "Unauthorized Access!";
                echo json_encode($data);
                die;
            } else if ($this->tokenData == '202') {
                $data['status'] = 202;
                $data['message'] = $this->lang->line('inactive_user');
                echo json_encode($data);
                die;
            } else if ($this->tokenData == '204') {
                $data['status'] = 202;
                $data['message'] = "User is no longer available you have to register again!";
                echo json_encode($data);
                die;
            }
        }
    }

    # verify_request method start
    # This method used to verify JWT token
    private function verify_request() {
        # Get all the headers
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            # Extract the token
            $token = $headers['Authorization'];
            # Use try-catch
            # JWT library throws exception if the token is not valid
            try {    # Validate the token
                # Successfull validation will return the decoded user data else returns false
                $data = AUTHORIZATION::validateToken($token);
                //check token is expire or not
                $tokeExpire = AUTHORIZATION::validateTimestamp($token);
                if ($data === false) {
                    return $data;
                } else if ($tokeExpire === false) {
                    return $tokeExpire;
                } else {
                    # Success; Now check user status ; if it is the status which is not allowed to login (ex 3 and 4) so send false
                    return comman_check_user_verify_api($data->user_id,$data);
                }
            }
            catch(\Exception $e) {
                # make error log
                log_message('error', $e);
                #  Token is invalid
                #  Send the unathorized access message
                return $data = false;
            }
        } else #for the apis where token is not mendatory (can be present or can not be present)
        {
            return $data = false;
        }
    }
    # verify_request method end
}
