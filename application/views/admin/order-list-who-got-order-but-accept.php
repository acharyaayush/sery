<?php
 
$provider_list_tr = "";
if(!empty($who_got_service_order)){
    /*echo '<pre>';
    print_r($who_got_service_order);*/
    foreach ($who_got_service_order as $value) {
         $user_id = $value['user_id'];
         $number_id = $value['number_id'];
         $service_provider_name = $value['service_provider_name'];
     

        if($order_detail[0]['order_accept_by'] != $user_id){
 $provider_list_tr .= '<tr>    
                                <td>
                                    <h6><a href="'.base_url().'admin/service_provider_detail/'.$user_id.'">'.$number_id.'</a></h6>
                                </td>
                                <td>
                                    <p>'.$service_provider_name.'</p>
                                </td>
                                <td>Not Accepted</td>
                            </tr>';
        }
       

    }
}else{
    $who_got_service_order = "<tr><td class='text-center' colspan='5' class='no-records'>No Records Found </td></tr>";
}

?>

<thead>
    <tr>
        <th>ID</th>
        <th>Provider Name</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    <?php echo  $provider_list_tr;?>
</tbody>