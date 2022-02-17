<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comman_Admin_Controller extends CI_Controller
{
    function __construct() {
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->library('session');
        $this->id = $this->session->userdata('adminId');
        $this->role = $this->session->userdata('role');
        $this->profile_image = $this->session->userdata('profile_image');
        $this->fullname = $this->session->userdata('fullname');
        $this->load->model('Common');
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        //getting default commision value
        $rating_data = $this->Common->getData('settings', 'value', 'name = "sery_commission"'); // 13 id for sery_commission
        $this->default_commision = $rating_data[0]['value'];

        //getting unread notification in navbar-------start-------
        $this->unread_notification = $this->Common->getData('notifications', 'notifications.*', '( (role = 3 AND order_status != 0) OR (role = 1 AND order_status = 0)) AND is_read_by_admin = 0', '', '', 'id', 'DESC', '15');
        //getting unread notification in navbar-------end-------

        //getting dashboard chart data ----------start--------
        $this->chart_array = get_dashboard_chart_data();
        //getting dashboard chart data ----------end--------

        //for notification 
        $this->lang->load('english', 'english');
        $this->lang->load('amharic', 'amharic');
    }
}
