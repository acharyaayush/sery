<?php
$customer_list_tr = "";
if(!empty($customer_data)){
    foreach ($customer_data as $value) {

         $user_id = $value['user_id'];
         $number_id = $value['number_id'];
         $fullname = $value['fullname'];
         $email = $value['email'];
         $mobile = $value['mobile'];
         //$age = $value['age'];
         $gender = $value['gender'];//1- male, 2- female, 3- other
         $user_status = $value['user_status'];//(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1

        if( $value['age'] == 0){
            $age = 'N/A';
        }else{
             $age = $value['age']. 'Y';
        }
        
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

        //gender   
        if($gender == 1){
            $gender_name = 'Male';
        }else if($gender == 2){
            $gender_name = 'Female';
        }else if($gender == 2){
           $gender_name = 'Other'; 
        }else{
            $gender_name = '-'; 
        }

        //customer image
        if($value['profile_image'] != ""){
            $customer_image = img_upload_path()[1].$value['profile_image'];
        }else{
            $customer_image = img_path().'avatar/avatar-1.png';
        }

        $country_code = $value['country_code'];
        if($country_code == ""){
            $country_code = COUNTRY_CODE;
        }
        $mobile = $value['mobile'];
        $contact = '+'.$country_code.' '.$mobile;
        
        $customer_list_tr.= '<tr>
                                <td><a class="text-capitalize" href="'.base_url('admin/customer_detail/'.$user_id.'').'">'.$number_id.'</a></td>
                                <td><img alt="image" src="'.$customer_image.'" class="rounded-circle" width="35" ></td><!--data-toggle="tooltip" title="'.$fullname.'"-->
                                <td> '.$fullname.'</td>
                                <td>'.$email.'</td>
                                <td>'.$contact.'</td>
                                <td>'.$gender_name.'</td>
                                <td>'.$age.'</td>
                                <td>
                                    <div class="actionbtns">
                                        <button class="btn '.$status_btn_cls.' customer_status_change" status="'.$change_status.'" user_id= "'.$user_id.'">'.$status_value.'</button>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="actionbtns">
                                        <a href="#add_edit_Customer_Modal" data-toggle="modal" class="btn btn-info add_edit_customer_mode" mode="2" user_id= "'.$user_id.'"  data-backdrop="static" data-keyboard="false">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger customer_delete" user_id="'.$user_id.'">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>';
    }
}else{
    $customer_list_tr = "<tr><td class='text-center' colspan='9' class='no-records'>No Records Found </td></tr>";
}
?>

<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Gender</th>
    <th>Age</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $customer_list_tr;?>

