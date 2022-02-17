<?php
$customer_service_list_tr = "";
if(!empty($customer_data)){
    foreach ($customer_data as $value) {

        $user_id = $value['user_id'];
        $number_id = $value['number_id'];
        $fullname = $value['fullname'];
        $email = $value['email'];
        $mobile = $value['mobile'];
        $role = $value['role'];
        #db_role = (1 - admin, 2 - customer-service, 3- service-provider, 4 - customer),(Default - 0) Service provider added or edit by only super admin
        if($role = 2){#customer service
            $role_name = "Customer Service";
        }else if($role = 1){
            $role_name = "Super Admin";
        }

        $user_status = $value['user_status'];//(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        
        if($fullname == ""){
            $fullname = 'N/A';
        }

        if($email == ""){
            $email = 'N/A';
        }

        //user status
        if($user_status == 1){
            $status_btn_cls = 'btn-info';
            $status_value = 'Disable';

            $change_status = 2;// go for disable when click on button
         }else{
            $status_btn_cls = 'btn-secondary';
            $status_value = ' Enable';

            $change_status = 1;// go for enable when click on button
        }

        $country_code = $value['country_code'];
        if($country_code == ""){
            $country_code = COUNTRY_CODE;
        }
        $mobile = $value['mobile'];
        if($mobile == ""){
            $contact = "-";
        }else{
            $contact = '+'.$country_code.' '.$mobile;
        }
        
        
        $customer_service_list_tr.= '<tr>
                                <td>'.$number_id.'</td>
                                <td> '.$fullname.'</td>
                                <td>'.$email.'</td>
                                <td>'.$contact.'</td>
                                <td>'.$role_name.'</td>
                                <td>
                                    <div class="actionbtns">
                                        <button class="btn '.$status_btn_cls.' user_status_change" status="'.$change_status.'" user_id= "'.$user_id.'">'.$status_value.'</button>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="actionbtns">
                                        <a href="#add_edit_AdminUserModal" data-toggle="modal" class="btn btn-info add_edit_admin_user" mode="2" user_id= "'.$user_id.'"  data-backdrop="static" data-keyboard="false">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger admin_user_delete" user_id="'.$user_id.'">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>';
    }
}else{
    $customer_service_list_tr = "<tr><td class='text-center' colspan='9' class='no-records'>No Records Found </td></tr>";
}
?>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Role</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $customer_service_list_tr;?>

