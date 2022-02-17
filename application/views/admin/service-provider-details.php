<?php
if(!empty($service_provider_detail)){
    //print_r($service_provider_detail);
   
    $fullname = $service_provider_detail[0]['fullname'];
    $email = $service_provider_detail[0]['email'];

    if($fullname == ""){
        $fullname = 'N/A';
    }

    if($email == ""){
        $email = 'N/A';
    }

    $age = $service_provider_detail[0]['age'];

    if($age == 0){
        $age = 'N/A';
    }

    $gender = $service_provider_detail[0]['gender'];//1- male, 2- female, 3- other

    if($gender == 1){
        $gender = 'Male';
    }else if($gender == 2){
        $gender = 'Female';
    }else if($gender == 3){
        $gender = 'Other';
    }else{
        $gender = 'N/A';
    }

    $country_code = $service_provider_detail[0]['country_code'];
    $mobile = $service_provider_detail[0]['mobile'];
    $contact = $country_code.' '.$mobile;

    $profile_image = $service_provider_detail[0]['profile_image'];
    $note = $service_provider_detail[0]['note'];

    if($note == ""){
        $note = 'N/A';
    }

    if($profile_image != ""){ 
        $service_provider_image = img_upload_path()[1].$profile_image;
    }else{
        $service_provider_image = img_path().'avatar/avatar-1.png';
    } 

    //service provider services/skills
    $services_list_selelect_option_for_show_skills = "";
    if(!empty($service_provider_detail[0]['skills'])){
        foreach ($service_provider_detail[0]['skills'] as $key => $value) {
            $services_list_selelect_option_for_show_skills .= '<option value="'.$value['key_skill_id'].'" class="text-capitalize">'.$value['service_name_english'].'</option>';
        }
        $default_skills_showing_accroding_to_selected_service = $service_provider_detail[0]['skills'][0]['key_skill_english'];
    }else{
        $services_list_selelect_option_for_show_skills .= '<option value="">No 
        Service available</option>';
        $default_skills_showing_accroding_to_selected_service = "";
    }

}else{
    redirect(base_url('admin/service_provider_management'));
}
?>
<?php
    $recharge_list_tr = wallet_history_data($all_transactions);
?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Service Provider Details</h1>
        </div>
        <div class="row">
                <div class="col-md-5">
                    <div class="card cust-card">
                        <div class="customer-img">
                            <img src="<?php echo $service_provider_image;?>">
                        </div>
                        <div class="user-info">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td class="text-capitalize"><?php echo $fullname;?></td>
                                    </tr>
                                    <tr>
                                        <td>Email id</td>
                                        <td><?php echo $email;?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>+<?php echo $contact;?></td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td><?php echo $age;?>Y</td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td><?php echo $gender;?></td>
                                    </tr>
                                    <tr>
                                        <td>Services</td>
                                        <td> 
                                            <select class="form-control text-capitalize" id="show_skills">
                                                <?php echo $services_list_selelect_option_for_show_skills;?>
                                             </select> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Skills</td>
                                        <td id="get_skills"><?php echo $default_skills_showing_accroding_to_selected_service;?> </td>
                                    </tr>
                                    <?php if($this->role == 1){#only super admin can see?>
                                    <tr>
                                        <td>Note</td>
                                        <td><?php echo $note;?></td>
                                    </tr>
                                    <?php }?>
                                    <!-- <tr>
                                        <td>Balance</td>
                                        <td><?php //echo $wallet_balance.' '.CURRENCY;?></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card cust-card-info">
                        <div class="custm-info-section">
                            <div class="cust-details-head">
                                <h5><span><img src="<?php echo img_path(); ?>recharge.jpg"> </span> Wallet History</h5>
                            </div>

                            <div class="cust-details-content table-responsive text-nowrap">
                                <div class="row m-0">
                                    <div class="col-sm-4 text-center">
                                        <h6><strong>Total Credit </strong></h6>
                                        <p><strong> <?php echo $total_money_added.' '.CURRENCY;?></strong></p>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <h6><strong>Total Debit </strong> </h6>
                                        <p><strong><?php echo $total_debited.' '.CURRENCY;?></strong></p>
                                    </div>
                                     <div class="col-sm-4 text-center">
                                        <h6><strong>Balance </strong></h6>
                                        <p><strong> <?php echo $wallet_balance.' '.CURRENCY;?></strong></p>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> S No. </th>
                                            <th> Date </th>
                                            <th> Order ID </th>
                                            <th> Credit </th>
                                            <th> Debit </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $recharge_list_tr;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-details-section">
                <div class="cust-ord-lble">
                    <h6> Order History  <span> <?php echo $provider_total_orders;?> </span> </h6>
                </div>
                <div class="table-responsive">
                    <table class="table tble-prodct-section">
                        <?php 
                          $this->load->view("admin/service-provider-order-list-table");
                          //this is seprate for only table load by js on any action event
                        ?>
                    </table>
                </div>
            </div>
    </section>
</div>
  
<!-- block user modal start -->
<div class="modal" id="blockUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="blckmd">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Inactive</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">

                    <h5> Are you sure you want to Inactive this status? </h5>
                    <div class="form-group">
                        <label> Reason </label>
                        <textarea class="form-control" placeholder="Enter Reason"></textarea>
                    </div>
                </div>

                <!-- Modal footer -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- block user modal end -->