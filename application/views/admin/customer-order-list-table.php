<?php
 
$customer_order_list_tr = "";
if(!empty($customer_order_details)){
    /*echo '<pre>';
    print_r($customer_order_details);*/
    foreach ($customer_order_details as $value) {
         $order_id = $value['order_id'];
         $order_number_id = $value['order_number_id'];
         $service_name_english = $value['service_name_english'];
         $service_image = base_url().$value['service_image'];
         $total_amount = $value['total_amount'];
         $order_status = $value['order_status'];
         $cancel_by = $value['cancel_by'];//role 1 - super admin, role 2 - customer-service
         $cancel_reason = $value['cancel_reason'];

         #db_order_status = 0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
        if($order_status == 6){//cancelled
            if($cancel_by == 1){
                $role_name = 'Super admin';
            }else if($cancel_by == 2){
                $role_name = 'Customer Service';
            }else if($cancel_by == 4){
                $role_name = 'Customer';
            }
            $note = 'Cancelled by:- '.$role_name.'<br> Cancelled reason:- '.$cancel_reason.'';
        }else{
            $note = 'Null';
        }
        
        switch ($order_status) {
        case 0:
           $order_status_name = 'Pending';
          break;
        case 1:
           $order_status_name = 'Accepted';
          break;
        case 2:
            $order_status_name = 'On the way';
          break;
        case 3:
           $order_status_name = 'Started';
          break;
        case 4:
           $order_status_name = 'Completed';
          break;
        case 5:
           $order_status_name = 'Rejected';
          break;
        case 6:
            $order_status_name = 'Cancelled';
          break;

        default:
          $order_status_name = 'Pending';
        }


         $customer_order_list_tr .= '<tr>
                                        <td>
                                            <img src="'.$service_image.'">
                                        </td>
                                        <td>
                                            <h6><a href="'.base_url().'admin/order_detail/'.$order_id.'">'.$order_number_id.'</a></h6>
                                        </td>
                                        <td>
                                            <p>'.$service_name_english.'</p>
                                        </td>
                                        <td>
                                            <p>'.$total_amount.' '.CURRENCY.'</p>
                                        </td>
                                        <td>
                                             <p> '.$order_status_name.' </p>
                                        </td>
                                        <td>
                                            <p>'.$note.' </p>
                                        </td>
                                    </tr>';

    }
}else{
    $customer_order_details = "<tr><td class='text-center' colspan='6' class='no-records'>No Records Found </td></tr>";
}

?>

<thead>
    <tr>
        <th>Order</th>
        <th>Order Id</th>
        <th>Service</th>
        <th>Price</th>
        <th>Status</th>
        <th>Note</th>
    </tr>
</thead>
<tbody>
    <?php echo  $customer_order_list_tr;?>
</tbody>