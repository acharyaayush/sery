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
        }else{
            $price_type  = 'Hourly';
        }

        $service_provider_name = $value['service_provider_name']; 

        $order_status = $value['order_status'];
        $cancel_reason = stripslashes($value['cancel_reason']);
        $last_till_time_of_service_accept = $value['last_till_time_of_service_accept'];

        $ordered_date = $value['created_at'];

        date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia country
        $order_place_time_date  = date("d-m-Y  H:i A",$ordered_date);// convert UNIX timestamp to PHP DateTime

        $cancel_by = $value['cancel_by']; 

        if($cancel_by == 1){
            $cancel_by_name = 'Super Admin';
        }else if($cancel_by == 2){
            $cancel_by_name = 'Customer Service';
        }else if($cancel_by == 4){
            $cancel_by_name = 'Customer';
        }else{
           $cancel_by_name = '-'; 
        }

        $total_amount = $value['total_amount'].' '.CURRENCY;

        $order_list_tr .= '<tr>
                            <td><a href="'.base_url('admin/order_detail/'.$order_id.'').'"> '.$order_number_id.'</a></td>
                            <td class="text-capitalize"><a href="'.base_url('admin/customer_detail/'.$customer_id.'').'"> '.$customer_name.'</a></td>
                            <td>'.$order_place_time_date.'</td>
                            <td class="text-capitalize">'.$service_name.' </td>
                            <td>'.$price_type.' </td>
                            <td class="text-capitalize">'.$service_provider_name.' </td>
                            <td>'.$cancel_by_name.'</td> 
                            <td>'.$cancel_reason.'</td> 
                            </tr>';
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
    <th>Cancel By</th>
    <th>Cancel Reason</th>
</tr>
<?php echo $order_list_tr;?>