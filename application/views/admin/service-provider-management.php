<?php 
    $category_list_select_option = "";
    if(!empty($service_categories)){
        foreach ($service_categories as $value) {
            $cat_id = $value['cat_id'];
            $cat_name_english = $value['cat_name_english'];
            $category_list_select_option .= '<option value="'.$cat_id.'">'.$cat_name_english.'</option>';
        }
    }else{
        $category_list_select_option .= '<option value="">No Category available</option>';
    }
?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Service Provider</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header headr-btn">
                        <button class="btn btn-primary" onclick="filtertoggle()"> Filter </button>
                    </div>
                    <div class="card-body" id="filter-togle-section">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>From Date</label>
                                   <input  type="text"  min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd" id="fromdate" name="fromdate" max="" class="form-control fromdate " value="<?php if(isset($fromdate) && $fromdate != '' && $fromdate != 'all'){echo $fromdate;} ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>To Date</label>
                                    <input  type="text" min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd"  id="todate" name="todate" max="" class="form-control todate" value="<?php if(isset($fromdate) && $todate != '' && $todate != 'all'){echo $todate;} ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Status</label>
                                    <select class="form-control" id="user_status">
                                        <option  value="">Select Status</option>
                                        <option  value="1" <?php if($user_status == '1' && $user_status != 'all'){echo "selected";} ?>> Enable </option>
                                        <option value="2" <?php if($user_status == '2' && $user_status != 'all'){echo "selected";} ?>> Disable </option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Rating</label>
                                    <select class="form-control" id="service_provider_rating">
                                        <option value="">Select Rating</option>
                                        <option value="1" <?php if($service_provider_rating == '1'&& $service_provider_rating != 'all'){echo "selected";} ?>>1</option>
                                            <option value="1.5" <?php if($service_provider_rating == '1.5'&& $service_provider_rating != 'all'){echo "selected";} ?>>1.5</option>
                                            <option value="2" <?php if($service_provider_rating == '2'&& $service_provider_rating != 'all'){echo "selected";} ?>>2</option>
                                            <option value="2.5" <?php if($service_provider_rating == '2.5'&& $service_provider_rating != 'all'){echo "selected";} ?>>2.5</option>
                                            <option value="3" <?php if($service_provider_rating == '3'&& $service_provider_rating != 'all'){echo "selected";} ?>>3</option>
                                            <option value="3.5" <?php if($service_provider_rating == '3.5'&& $service_provider_rating != 'all'){echo "selected";} ?>>3.5</option>
                                            <option value="4" <?php if($service_provider_rating == '4'&& $service_provider_rating != 'all'){echo "selected";} ?>>4</option>
                                            <option value="4.5" <?php if($service_provider_rating == '4.5'&& $service_provider_rating != 'all'){echo "selected";} ?>>4.5</option>
                                            <option value="5" <?php if($service_provider_rating == '5'&& $service_provider_rating != 'all'){echo "selected";} ?>>5</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>Search</label>
                                    <div class="input-group-btn">
                                        <input type="text" class="form-control srch-bar search_key" placeholder="Search"  value="<?php if(isset($search) && $search != '' && $search != 'all'){echo $search;} ?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn" id="search_service_provider">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/service_provider_management')?>"> Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Service Provider</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <a href="#add_edit_ServiceProvider" data-toggle="modal" class="ml-3 btn btn-primary add_edit_service_provider_mode modal_click" data-backdrop="static" data-keyboard="false" mode="1">Add Service Provider</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="service_provider_table">
                                 <?php 
                                      $this->load->view("admin/service-provider-list-table");
                                      //this is seprate for only table load by js on any action event
                                  ?>
                            </table>
                            <nav class="text-xs-right ml-3">
                                <?php if (isset($links)) { ?>
                                    <?php echo $links; ?>
                                <?php } ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal" id="add_edit_ServiceProvider">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url('admin/Create_Update_ServiceProvider');?>" class="needs-validation" novalidate="" enctype="multipart/form-data" id="ServiceProviderFormSubmit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title service_provider_modal_title">Add Service Provider</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>Name</label>

                        <input type="text" maxlength="80" class="form-control check_space name_validation" placeholder="Enter Provider Name" required="" id="service_provider_fullname" name="service_provider_fullname">

                        <div class="invalid-feedback">
                              Please enter name
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control check_space check_email_if_exist"  id="service_provider_email" placeholder="Enter Provider Email" name="service_provider_email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                <div class="invalid-feedback">
                                      Please enter  email
                                </div>
                                <span class="text-danger input_error" id="email_error"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Mobile number</label>

                               <!--  <input type="text" class="form-control check_space" required="" maxlength="13" minlength="9" id="service_provider_mobile" name="service_provider_mobile">-->

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
                                    <input autocomplete="off" type="text" class="form-control check_space contact_number  check_contact_number_exist" required="" maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" placeholder="Mobile Number" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>" id="service_provider_mobile" name="service_provider_mobile">
                                    <div class="invalid-feedback">
                                      Please enter mobile number
                                    </div>
                                    <span class="text-danger input_error" id="mobile_no_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control check_space" required="" id="service_provider_gender" name="service_provider_gender">
                                    <option value="">Select Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2"> Female</option>
                                    <option value="3"> Other</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select gender
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="text" min="1" max="100"  maxlength="3" placeholder="Age" class="form-control check_space user_age_valid number_float" id="service_provider_age" name="service_provider_age">
                                <span class="text-danger input_error" id="age_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Provider Location</label>
                                <input type="text" class="form-control select_google_location" id="provider_location" name="provider_location" placeholder="Enter Provider Location" required="">
                                <div class="invalid-feedback hide_this_error">
                                  Please select location
                                </div>
                                 <span class="text-danger input_error" id="provider_location_error"></span>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Service Category</label>
                                <select class="form-control category_list_for_skills" required="" name="service[0][cat_id]" id="category_list_0" append_id="0">
                                    <option value="">Select Category</option>
                                     <?php echo $category_list_select_option;?>
                                </select>
                                <div class="invalid-feedback">
                                  Please select category
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Service</label>
                               <select class="form-control service_list"  required="" name="service[0][service_id]"  append_id="0"  id="service_list_0">
                                    <option value="">Select Service</option>
                                </select>
                                <div class="invalid-feedback">
                                  Please select service
                                </div> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Skills (in english)</label>
                        <input type="text" class="form-control check_space text_with_number_validation" id="service_provider_skill_english_0" placeholder="Enter Provider Skills" name="service[0][skill_english]" required="">
                        <div class="invalid-feedback">
                          Please enter skill in english
                        </div> 
                        <label>Skills (in amharic)</label>
                        <input type="text" class="form-control check_space" id="service_provider_skill_amharic_0" placeholder="Enter Provider Skills" name="service[0][skill_amharic]" required=""><!--text_with_number_validation-->
                        <div class="invalid-feedback">
                          Please enter skill in amharic
                        </div> 
                    </div>

                    <div class="append_skill_input">
                    </div>

                    <div class="row"> 
                         <div class="col-sm-12 text-right">
                            <i class="fa fa-plus btn btn-primary" aria-hidden="true" id="add_skill_input"> Add Skills</i>
                        </div>
                    </div>
                 
                    <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control check_space text_with_number_validation" placeholder="Enter Message" id="service_provider_note" name="service_provider_note"></textarea>
                    </div>
                    <div class="selectedProfileImage">
                          <img  id="selected_img" alt="image" src="<?php echo img_path(); ?>avatar/avatar-1.png"  width="500">
                          <p> <label for="file"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                          <input  accept="image/*" name="service_provider_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" value="">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" id="check_mobile_if_exist" value="false"/>
                    <input type="hidden" id="check_email_if_exist" value="false"/>
                    <input type="hidden" id="service_provider_form_mode" name="mode"/>
                    <input type="hidden" id="exist_service_provider_image" name="exist_service_provider_image"/>
                    <input type="hidden" id="edit_service_provider_id" name="edit_service_provider_id"/>
                    <input type="hidden" value="" id="provider_latitude" name="provider_latitude" />
                    <input type="hidden" value="" id="provider_longitude" name="provider_longitude"/>
                    
                    <button type="submit" class="btn btn-primary submit_form">Add</button>
                    <button type="button" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
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

<?php

  $current_url =  current_url();
  $parameter_url = explode("service_provider_management/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/service_provider_management/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/service_provider_management/table/';
  }

?>
<script type="text/javascript">
  var service_provider_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyCXAPSpjmIu_a1ZcAHiw9oB_pJozpbWTyM" async defer></script>
<script type="text/javascript">
  function initAutocomplete() {
    var address = document.getElementById('provider_location');
    var autocomplete = new google.maps.places.Autocomplete(address);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();
      document.getElementById('provider_latitude').value = latitude;
      document.getElementById('provider_longitude').value = longitude;
    });
  }
</script>