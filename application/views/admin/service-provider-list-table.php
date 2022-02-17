<?php
$service_provider_list_tr = "";

if(!empty($service_provider_data)){
    foreach ($service_provider_data as $value) {
         $user_id = $value['user_id'];
         $number_id = $value['number_id'];
         $fullname = $value['fullname'];
         $email = $value['email'];
         $wallet_balance = $value['wallet_balance'];

         $country_code = $value['country_code'];
         $mobile = $value['mobile'];
         $contact = $country_code.' '.$mobile;

         $avg_rating = number_format($value['avg_rating'],1);

         $is_online = $value['is_online'];//(1 - User is logged in, 0 - On logout. Affected on success of registration and success of login and also service provider can do offline/online after login by self

         if($is_online == 1){
            $is_online_value = 'Yes';
            $offline_cls = 'text-success';
         }else{
            $is_online_value = 'No';
             $offline_cls = 'text-danger';
         }

         $user_status = $value['user_status'];//(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        
        if($fullname == ""){
            $fullname = "N/A";
        }

         if($email == ""){
            $email = "N/A";
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

        //customer image
        if($value['profile_image'] != ""){
            $service_provider_image = img_upload_path()[1].$value['profile_image'];
        }else{
            $service_provider_image = img_path().'avatar/avatar-1.png';
        }

        // for start rating
        switch ($avg_rating) {
         case 0://0
        
          $star = '<span class="fa fa-star" ></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star "></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';

          break;
        case 1://1
        
          $star = '<span class="fa fa-star checked" ></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star "></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';

          break;
        case ($avg_rating >1 && $avg_rating < 2)://ex. 1.5
        
         $star = '
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star-half-alt checked"></span>
                  <span class="fa fa-star "></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';
          break;
        case 2:
        
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star "></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';
          break;
        case ($avg_rating >2 && $avg_rating < 3)://ex. 2.5
        
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star-half-alt checked"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';
          break;
        case 3:
        
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';
          break;
        case ($avg_rating >3 && $avg_rating < 4)://ex.3.5
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star-half-alt checked"></span>
                  <span class="fa fa-star"></span>';
          break;
        case 4:
         
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star"></span>';
          break;
        case ($avg_rating >4 && $avg_rating < 5)://ex. 4.5
        
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star-half-alt checked"></span>';
                  
          break;
        case 5:
        
         $star = '<span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>
                  <span class="fa fa-star checked"></span>';
          break;

        default:
        
           $star = '<span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star "></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>';
        }
        //For restaurant enable disable // -- 1  - Enable , 2 - Disable , 3 - Delete

        #service provider skills
        $services = "";
        $counter = 1;
        $total_skills = count($value['skills']);
        if(!empty($value['skills'])){
            foreach ($value['skills'] as $key => $value2) {

                if($counter < $total_skills){
                    $comma = ', ';
                }else{
                    $comma = '';
                }
                $services .= $value2['service_name_english'].$comma;
                $counter++;
            }
        }else{
            $services = 'N/A';
        }

        $service_provider_list_tr.= '<tr>
                                        <td><a href="'.base_url('admin/service_provider_detail/'.$user_id.'').'"> '.$number_id.'</a></td>
                                        <td><img alt="image" src="'.$service_provider_image.'" class="rounded-circle" width="35" data-toggle="tooltip" ></td><!--title="'.$fullname.'"-->
                                        <td class="text-capitalize">'.$fullname.'</td>
                                        <td>'.$email.'</td>
                                        <td>+'.$contact.'</td>
                                        <td class="skills_td text-capitalize"> '.$services.' </td>
                                        <td class="srprovid-bl">
                                            <p> '.$wallet_balance.' '.CURRENCY.'</p>
                                        </td>
                                        <td>
                                            <div class="servicePro-starrating">
                                               '.$star.'
                                                <span>'.$avg_rating.'</span>
                                            </div>
                                        </td>   
                                        <td class="text-center"><span class="'.$offline_cls.'"><strong>'.$is_online_value.'</strong></span></td>                             
                                        <td>
                                            <div class="actionbtns">
                                                <button class="btn '.$status_btn_cls.' service_provider_status_change" status="'.$change_status.'" user_id= "'.$user_id.'">'.$status_value.'</button>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="actionbtns">
                                                <a href="#add_edit_ServiceProvider" data-toggle="modal" class="btn btn-info add_edit_service_provider_mode" mode="2" user_id= "'.$user_id.'"  data-backdrop="static" data-keyboard="false">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-danger service_provider_delete" user_id="'.$user_id.'">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <!--<a href="#blockUser" data-toggle="modal" class="btn btn-warning">
                                                    <i class="fa fa-unlock"></i>
                                                </a>-->
                                            </div>
                                        </td>
                                    </tr>';
    }
}else{
    $service_provider_list_tr = "<tr><td colspan='15' class='no-records text-center'>No Records Found </td></tr>";
}
?>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Skills</th>
    <th>Balance</th>
    <th>Rating</th>
    <th>Is Online</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $service_provider_list_tr;?>
 
