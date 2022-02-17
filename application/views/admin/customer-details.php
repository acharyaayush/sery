<?php
if(!empty($customer_detail)){

    //print_r($customer_detail);
    
    $fullname = $customer_detail[0]['fullname'];
    $email = $customer_detail[0]['email'];

    if($fullname == ""){
        $fullname = 'N/A';
    }

    if($email == ""){
        $email = 'N/A';
    }
   

    if($customer_detail[0]['age'] == 0){
        $age = 'N/A';
    }else{
        $age = $customer_detail[0]['age']. 'Y';
    }

    $gender = $customer_detail[0]['gender'];//1- male, 2- female, 3- other

    if($gender == 1){
        $gender = 'Male';
    }else if($gender == 2){
        $gender = 'Female';
    }else if($gender == 3){
        $gender = 'Other';
    }else{
        $gender = 'N/A';
    }

    $country_code = $customer_detail[0]['country_code'];
    if($country_code == ""){
        $country_code = COUNTRY_CODE;
    }
    $mobile = $customer_detail[0]['mobile'];
    $contact = '+'.$country_code.' '.$mobile;

    $profile_image = $customer_detail[0]['profile_image'];

    if($note == ""){
        $note = 'N/A';
    }

    if($profile_image != ""){
        $customer_image = base_url($profile_image);
    }else{
        $customer_image = img_path().'avatar/avatar-1.png';
    }

    $street_address = $customer_detail[0]['street_address'];
    $google_map_pin_address = $customer_detail[0]['google_map_pin_address'];

    $user_status = $customer_detail[0]['user_status'];//(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1

    if($user_status == 1){ 
        $user_status = 'Enable';
    }else if($user_status == 2){ 
        $user_status = 'Disable'; 
    }else{
        $user_status = 'Enable';
    }

}else{
    redirect(base_url('admin/customer_detail'));
}
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Customer-Details</h1>
        </div>
        <div class="card card-details-section">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div>
                        <h5>  <?php echo $fullname;?> </h5>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="cust-st">
                        <h6>Status : <?php echo $user_status;?> </h6>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div>
                        <p><b> Email id :- </b> <?php echo $email;?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <p><b> Phone :- </b> <?php echo $contact;?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <p> <b> Gender :- </b> <?php echo $gender;?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <p> <b> Age :- </b> <?php echo $age?></p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div id="img-preview">
                                <img src="<?php echo $customer_image;?>">
                            </div>
                        </div>
                        
                       <!--  <div class="col-md-8">
                            <div class="form-group margin-rem">
                                <div class="">
                                    <input type="file" class="custProfile" id="choose-file" name="choose-file" accept="image/*" />
                                    <label for="choose-file" class="ml-3 btn btn-primary">Choose File</label>
                                </div>
                            </div>
                        </div> -->
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- <div>
                        <form class="note-text">
                            <div class="form-group">
                                <label> Note: </label>
                                <textarea class="form-control"></textarea>
                            </div>
                            
                        </form>
                    </div> -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div>
                                <p> <b> Address :- </b> <?php echo $street_address;?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <p> <b> Pin Address :- </b> <?php echo $google_map_pin_address;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cust-ord-lble">
            <h6>Customer Order Summary <span> <?php echo $customer_total_orders;?> </span></h6>
        </div>
        <div class="card card-details-section">
            <div class="table-responsive">
                <table class="table tble-prodct-section">
                    <?php 
                      $this->load->view("admin/customer-order-list-table");
                      //this is seprate for only table load by js on any action event
                    ?>
                </table>
            </div>
        </div>
    </section>
</div>

