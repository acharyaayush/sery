<?php
$count = 1;
$customer_service_list_tr = "";
if(!empty($zone_data)){
    foreach ($zone_data as $value) {
        $zone_id = $value['id'];
        $zone_name = $value['zone_name'];
        $zone_status = $value['status'];//(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
         
        //zone status
        if($zone_status == 1){
            $status_btn_cls = 'btn-info';
            $status_value = 'Disable';
            $change_status = 2;// go for disable when click on button
         }else{
            $status_btn_cls = 'btn-secondary';
            $status_value = ' Enable';
            $change_status = 1;// go for enable when click on button
        }
       
        $customer_service_list_tr.= '<tr>
                                        <td>'.$count.'</td>
                                        <td> '.$zone_name.'</td>
                                        <td>
                                            <div class="actionbtns">
                                                <button class="btn '.$status_btn_cls.' zone_status_change" status="'.$change_status.'" zone_id= "'.$zone_id.'">'.$status_value.'</button>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="actionbtns">
                                                <a href="'.base_url('admin/add_edit_zone/'.$zone_id).'"  class="btn btn-info add_edit_admin_user">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-danger zone_delete" zone_id="'.$zone_id.'">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                            $count++;
    }
}else{
    $customer_service_list_tr = "<tr><td class='text-center' colspan='9' class='no-records'>No Records Found </td></tr>";
}
?>
<tr>
    <th>S No.</th>
    <th>Zone Name</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $customer_service_list_tr;?>

