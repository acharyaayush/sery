<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Customer Management</h1>
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
                                    <input  type="text"  min="2020-01-01" autocomplete="off"placeholder="yyyy-mm-dd" id="fromdate" name="fromdate" max="" class="form-control fromdate " value="<?php if(isset($fromdate) && $fromdate != '' && $fromdate != 'all'){echo $fromdate;} ?>" /> -
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>To Date</label>
                                    <input  type="text" min="2020-01-01" autocomplete="off"placeholder="yyyy-mm-dd"  id="todate" name="todate" max="" class="form-control todate" value="<?php if(isset($fromdate) && $todate != '' && $todate != 'all'){echo $todate;} ?>" />
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
                                    <label>Gender</label>
                                    <select class="form-control" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="1" <?php if($gender == '1' && $gender != 'all'){echo "selected";} ?>> Male </option>
                                        <option value="2" <?php if($gender == '2' && $gender != 'all'){echo "selected";} ?>> Female </option>
                                        <option value="3" <?php if($gender == '3' && $gender != 'all'){echo "selected";} ?>> Other </option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>Search</label>
                                    <div class="input-group-btn">
                                        <input type="text" class="form-control srch-bar search_key" placeholder="Search" value="<?php if(isset($search) && $search != '' && $search != 'all'){echo $search;} ?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn" id="search_customer">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/customer_Management')?>"> Clear</a>
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
                        <h4>Customer Management</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <a href="#add_edit_Customer_Modal" data-toggle="modal" class="ml-3 btn btn-primary add_edit_customer_mode modal_click" data-backdrop="static" data-keyboard="false" mode="1">Add Customer</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="customer_table">
                                <?php 
                                      $this->load->view("admin/customer-list-table");
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
       
<div class="modal" id="add_edit_Customer_Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url('admin/Create_Update_Customer')?>" class="needs-validation" novalidate="" enctype="multipart/form-data" id="CustomerFormSubmit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title customer_modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">

                    <div class="form-group">
                        <label>Name</label>

                            <input type="text" maxlength="80" class="form-control check_space text-capitalize name_validation" name="customer_fullname" required="" id="customer_fullname" placeholder="Enter Customer Name"> 

                         <div class="invalid-feedback">
                              Please enter customer name
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control check_space check_email_if_exist" name="customer_email"  placeholder="Enter Customer Email" id="customer_email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                <!--required=""-->
                                <div class="invalid-feedback">
                                  Please enter customer email
                                </div> 
                                <span class="text-danger input_error" id="email_error"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Mobile number</label>
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
                                    <input type="tel" maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>"  class="form-control contact_number check_space check_contact_number_exist" name="customer_mobile" placeholder="Mobile Number" required="" id="customer_mobile" autocomplete="off">
                                    <div class="invalid-feedback">
                                        Please enter customer contact number
                                    </div>
                                </div>
                                <span class="text-danger input_error" id="mobile_no_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2"> Female</option>
                                    <option value="3"> Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <!-- <label>Date of Birth</label>
                                <input type="date" class="form-control datepicker" name="birth_date"> -->
                                <label>Age</label>
                                <input type="text"  min="1" max="100"  maxlength="3"  class="form-control check_space user_age user_age_valid" placeholder="Age" name="age" id="age">
                                <span class="text-danger input_error" id="age_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="selectedProfileImage">
                          <img  id="selected_img" alt="image" src="<?php echo img_path();?>avatar/avatar-1.png"  width="500">
                          <p> <label for="file"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                          <input  accept="image/*" name="customer_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" value="">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" id="check_mobile_if_exist" value="false"/>
                    <input type="hidden" id="check_email_if_exist" value="false"/>
                    <button type="submit" class="btn btn-primary submit_form">Add</button>
                    <input type="hidden" id="customer_form_mode" name="mode"/>
                    <input type="hidden" id="exist_customer_image" name="exist_customer_image"/>
                    <input type="hidden" id="edit_customer_id" name="edit_customer_id"/>
                    <button type="button" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

  $current_url =  current_url();
  $parameter_url = explode("customer_Management/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/customer_Management/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/customer_Management/table/';
  }

?>
<script type="text/javascript">
  var customer_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>

