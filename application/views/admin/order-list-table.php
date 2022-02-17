<?php

//Order Data 
$order_list_tr = "";
if(!empty($booked_data)){
    foreach ($booked_data as $value) {
        $order_id = $value['order_id'];
        $order_number_id = $value['order_number_id'];
        $customer_id = $value['customer_id'];
        $customer_name = $value['customer_name'];
        $customer_contact = $value['customer_contact'];
        $service_provider_id = $value['order_accept_by']; // (primary id of the users (Service Provider id) table who role is - 3), Default - 0 #means no one accepted or assign

        #service details
        $service_id = $value['service_id']; 
        $service_name = $value['service_name']; 
        $service_price_type = $value['service_price_type']; //1- fixed, 2- hourly)
        if($service_price_type == 1){
            $price_type  = 'Fixed';
            $default_timer_value = '-';
        }else{
            $price_type  = 'Hourly';
            $default_timer_value = '00:00:00';
        }

        $service_provider_name = $value['service_provider_name']; 

        $order_status = $value['order_status'];
        $cancel_reason = $value['cancel_reason'];
        $last_till_time_of_service_accept = $value['last_till_time_of_service_accept'];

        #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
        #order can cancel till order status "on the way"
        switch ($order_status) {
        case 0:#pending

           $pending_order_status = 'selected';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';

          break;
        case 1:#assined/accepted
          
           $pending_order_status = '';
           $accept_order_status = 'selected';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';

            #after order status change previous status should be disable
            $disable_pending = "disabled=''";
          break;
        case 2:#on the way
          
           $pending_order_status = '';
           $accept_order_status = '';
           $on_the_way_order_status = 'selected';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';

            #after order status change previous status should be disable
            $disable_accepted = "disabled=''";
            $disable_pending = "disabled=''";
          break;
        case 3:#started
          
           $pending_order_status = '';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= 'selected';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';

            #after order status change previous status should be disable
            $disable_ontheway = "disabled=''";
            $disable_accepted = "disabled=''";
            $disable_pending = "disabled=''";
          break;
        case 4:#completed
          
           $pending_order_status = '';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= 'selected';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';

            #after order status change previous status should be disable
            $disable_started = "disabled=''";
            $disable_ontheway = "disabled=''";
            $disable_accepted = "disabled=''";
            $disable_pending = "disabled=''";
          break;
        case 5:#rejected by admin
          
           $pending_order_status = '';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= 'selected';
           $cancel_order_status= '';

            #after order status change previous status should be disable
            $disable_completed = "disabled=''";
            $disable_started = "disabled=''";
            $disable_ontheway = "disabled=''";
            $disable_accepted = "disabled=''";
            $disable_pending = "disabled=''";
          break;

        case 6: #cancelled by customer only
          
           $pending_order_status = '';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= 'selected';

           #after order status change previous status should be disable
            $disable_completed = "disabled=''";
            $disable_completed = "disabled=''";
            $disable_started = "disabled=''";
            $disable_ontheway = "disabled=''";
            $disable_accepted = "disabled=''";
            $disable_pending = "disabled=''";
          break;

        default:
           $pending_order_status = 'selected';
           $accept_order_status = '';
           $on_the_way_order_status = '';
           $serive_started_order_status= '';
           $serive_complete_order_status= '';
           $reject_ignore_order_status= '';
           $cancel_order_status= '';
        }



       /* if($order_status <= 2){ //order can cancel till "on the way"
            $cancel_btn_modal = 'href="#cancelordermodal" data-toggle="modal"';
            $cancel_cls = 'btn-info';
            $disable ="";
            $tool_tip = "";
            $btn_text = '<i class="fa fa-times">';
        }else if($order_status > 2 && $order_status < 6){
             $cancel_btn_modal = 'href="javascript:void(0)"';
             $cancel_cls = 'btn-secondary';
             $disable = 'style ="pointer-event: none"';
             $tool_tip  = 'data-toggle="tooltip" title="Can not cancel this order"';
             $btn_text = '<i class="fa fa-times">';
        }else if($order_status == 6){//order cancelled
            $cancel_btn_modal = 'href="javascript:void(0)"';
            $cancel_cls = 'btn-danger';
            $disable = 'style ="pointer-event: none"';
            $tool_tip = 'data-toggle="tooltip" title="'.$cancel_reason.'"';
            $btn_text = 'Cancelled';
        }*/

        $cancel_btn_modal = 'href="#cancelordermodal" data-toggle="modal"';
        $cancel_cls = 'btn-info';
        $disable ="";
        $tool_tip = "";
        $btn_text = '<i class="fa fa-times">';


        $ordered_date = $value['created_at'];

        date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia country
        $order_place_time_date  = date("d-m-Y  H:i A",$ordered_date);// convert UNIX timestamp to PHP DateTime

        # Admin set duration, if no one service provider accept the service/order in given duration then after this completed duration admin will recevice notification and can assign too.  Ex. Order time 10:00 and duration set at that time is 00:30 or 1:15 then the service provider has time to service accept is (00:30 - 10:30, 1:15 - 11:15) . After that admin also can assign  the service to the service provider.(Service Provider also can accept  after completed the duration if want other wise admin will assign with contact).
        #list will be show in "need to assign" tab.

        $start_service_time = $value['start_service_time']; 
       
        $invoice_path = $value['invoice_path']; 
        $taken_time = $value['taken_time']; 
        $total_amount = $value['total_amount'].' '.CURRENCY;

        $order_list_tr .= '<tr>
                            <td><a href="'.base_url('admin/order_detail/'.$order_id.'').'"> '.$order_number_id.'</a></td>
                            <td class="text-capitalize"><a href="'.base_url('admin/customer_detail/'.$customer_id.'').'"> '.$customer_name.'</a></td>
                            <td>'.$order_place_time_date.'</td>
                            <td class="text-capitalize">'.$service_name.' </td>
                            <td>'.$price_type.' </td>';
                            
        if($this->uri->segment(4) == READY_FOR_ASSIGN_ORDER_PAGE_VALUE){

        $order_list_tr .=  '<td><a href="#assignOrder" data-toggle="modal" class="assign_order modal_click" order_id="'.$order_id.'"  data-backdrop="static" data-keyboard="false" service_provider_id="'.$service_provider_id.'" service_id="'.$service_id.'">'.$service_provider_name.'</a></td>';

        }else{
        $order_list_tr .=  '<td><a href="'.base_url('admin/service_provider_detail/'.$service_provider_id.'').'" class="text-capitalize">'.$service_provider_name.'</a></td>';
        }

        if($this->uri->segment(4) == COMPLETED_ORDER_PAGE_VALUE){
           $order_list_tr .= '<td class="text-center">'.$taken_time.'</td>
                              <td>'.$total_amount.'</td>
                              <td><a href="'.order_invoice_upload_path()[1].$invoice_path.'" class="btn btn-info" download/><i class="fa fa-download"></td>
                            '; 
        }

        if($this->uri->segment(4) == READY_FOR_ASSIGN_ORDER_PAGE_VALUE || $this->uri->segment(4) == ONGOING_ORDER_PAGE_VALUE){

        $order_list_tr .= '
                            <td class="text-center"><span class="clock_timer" started_time="'.$start_service_time.'" id="order_'.$order_id.'" service_type="'.$service_price_type.'">'.$default_timer_value.'</span></td>
                            <td class="width-25">
                                <select class="form-control order_status" order_id="'.$order_id.'" service_provider_id="'.$service_provider_id.'">
                                    <option value="0" '.$pending_order_status.' '.$disable_pending.'> Pending </option>
                                    <option value="1" '.$accept_order_status.' '.$disable_accepted .'> Accepted</option>
                                    <option value="2" '.$on_the_way_order_status.' '.$disable_ontheway.'> On the way </option>
                                    <option value="3" '.$serive_started_order_status.' '.$disable_started.'>  Started </option>
                                    <option value="4" '.$serive_complete_order_status.' '.$disable_completed.'>  Completed </option>
                                    <option value="5" '.$reject_ignore_order_status.'>  Rejected </option>
                                   <option value="6" '.$cancel_order_status.' disabled=""> Cancelled </option>
                                </select>
                            </td>
                            <td class="text-center">
                                <a '.$cancel_btn_modal.' class="btn '. $cancel_cls .' cancel_order modal_click" order_id="'.$order_id.'" service_provider_id="'.$service_provider_id.'" service_id="'.$service_id.'" '. $disable.' '.$tool_tip .'>
                                   '.$btn_text.'</i>
                                </a>
                            </td>
                        </tr>
                        ';
        }  
    }
}else{
    $order_list_tr = "<tr><td colspan='10' class='no-records text-center'>No Records Found </td></tr>";
}

?>
<tr>
    <th>ID</th>
    <th>Customer Name</th>
    <th>Ordered Date</th>
    <th>Service Name</th>
    <th>Price Type</th>
    <th>Accepted By</th>
    
    <?php 
    if($this->uri->segment(4) == COMPLETED_ORDER_PAGE_VALUE){?>
    <th>Duration Time Taken </th>
    <th>Total Amount</th>
    <th>Invoice</th>
    <?php }?>
    <?php 
    if($this->uri->segment(4) == READY_FOR_ASSIGN_ORDER_PAGE_VALUE  || $this->uri->segment(4) == ONGOING_ORDER_PAGE_VALUE){?>
    <th>Timer</th>    
    <th>Status</th>
    <th>Action</th>
    <?php }?>
</tr>
<?php echo $order_list_tr;?>