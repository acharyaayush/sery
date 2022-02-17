<?php 

   if(!empty($admin_profile)){
    
        $fullname = $admin_profile[0]['fullname'];
        $fullname = $admin_profile[0]['fullname'];
        $email = $admin_profile[0]['email'];
        $mobile = $admin_profile[0]['mobile'];

        if($admin_profile[0]['profile_image'] == ""){
            $profile_image = img_path()."avatar/avatar-1.png";
            $exist_profile_image = "";
        }else{
            $exist_profile_image =  $admin_profile[0]['profile_image'];
            $profile_image  = img_upload_path()[1].$admin_profile[0]['profile_image'];
        }
        
   }else{
        $fullname = "";
        $email = "";
        $mobile = "";
        $profile_image = "";
        $exist_profile_image = "";
   }
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Profile</h4>
                    </div>

                    <form method="POST" action="<?php echo base_url('admin/UpdateProfile')?>"  class="needs-validation" novalidate="" enctype="multipart/form-data" id="AdminProfileFormSubmit">
                        <div class="card-body">
                            <?php $this->load->view("admin/validation");?>
                            <div class="form-group row align-items-center mb-4">
                                <label class="col-form-label  col-12 col-md-2 col-lg-2 offset-md-1">Profile Pic</label>
                                <div class="col-sm-12 col-md-7">
                                      <div class="selectedProfileImage">
                                          <img  id="selected_img" alt="image" src="<?php echo $profile_image;?>"  width="200">
                                          <p><label for="file"> <i class="fa fa-plus"></i> Upload Image</label></p>
                                          <input  accept="image/*" name="admin_profile_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" name="fullname" value="<?php echo $fullname;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center mb-4">
                                <label class="col-form-label  col-12 col-md-2 col-lg-2 offset-md-1">Full Name</label>
                                <div class="col-sm-12 col-md-7">
                                    <input id="fullname" type="text" placeholder="Enter Yout Name" class="form-control check_space" name="fullname" tabindex="1"  required="" value="<?php echo $fullname;?>">
                                    <div class="invalid-feedback">
                                      Please fill in your full Name
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center mb-4">
                                <label class="col-form-label  col-12 col-md-2 col-lg-2 offset-md-1">Email</label>
                                <div class="col-sm-12 col-md-7">
                                    <input  class="form-control check_space check_email_if_exist" type="email" name="email" placeholder="Enter Your Email" required="" value="<?php echo $email;?>"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                    <div class="invalid-feedback">
                                      Please fill in your email
                                    </div>
                                    <span class="text-danger input_error" id="email_error"></span>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-4">
                                <label class="col-form-label  col-12 col-md-2 col-lg-2 offset-md-1">Mobile No.</label>
                                <div class="col-sm-12 col-md-7">
                                    <!-- <input type="tel"  maxlength="9" minlength="7" type="tel" class="form-control contact_number check_space" name="mobile" required="" value="<?php echo $mobile;?>">
                                     <div class="invalid-feedback">
                                      Please fill in  your mobile No.
                                    </div> -->
                                    <div class="input-group">
                                    <div class="input-group-prepend custom-countrycode">
                                        <button type="button"  class="form-control country-codeBtn" data-toggle="dropdown">
                                          +251
                                        </button><!--class="dropdown-toggle"-->
                                        <!-- <div class="dropdown-menu">
                                          <a class="dropdown-item" href="#"> 1</a>
                                          <a class="dropdown-item" href="#"> 2</a>
                                          <a class="dropdown-item" href="#"> 3</a>
                                        </div> -->
                                    </div>
                                    <input type="tel"  maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>" placeholder="Enter Mobile Number" type="tel" class="form-control contact_number check_space check_contact_number_exist" name="mobile" required="" value="<?php echo $mobile;?>">
                                    <div class="invalid-feedback">
                                        Please enter contact number
                                    </div>
                                </div>
                                <span class="text-danger input_error" id="mobile_no_error"></span>
                                </div>
                            </div>
                            
                            <div class="form-group text-center">
                                <input type="hidden" id="check_mobile_if_exist" value="false"/>
                                <input type="hidden" id="check_email_if_exist" value="false"/>
                                <input type="hidden" value="<?php echo $exist_profile_image;?>" name="exist_profile_image"/>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
  var  if_page_admin_profle = true;
</script>
 

 