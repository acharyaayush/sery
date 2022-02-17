
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?php echo $pageTitle;?></h1>
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
                                    <label>Search</label>
                                    <div class="input-group-btn">
                                        <input type="text" class="form-control srch-bar search_key" placeholder="Search"  value="<?php if(isset($search) && $search != '' && $search != 'all'){echo $search;} ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn" id="search_admin_user">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/user_management')?>"> Clear</a>
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
                        <h4><?php echo $pageTitle;?></h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <a href="#add_edit_AdminUserModal" data-toggle="modal" class="ml-3 btn btn-primary add_edit_admin_user modal_click" data-backdrop="static" data-keyboard="false" mode="1">Add User Details</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-w" id="user_list">
                               <?php 
                                $this->load->view("admin/user-table-list");
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

<!-----modal----start----->
<div class="modal" id="add_edit_AdminUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
             <form method="POST" action="<?php echo base_url('admin/Create_Update_Admin_User')?>" class="needs-validation" novalidate="" enctype="multipart/form-data" id="UserFormSubmit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title admin_user_modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" maxlength="80" class="form-control check_space text-capitalize name_validation" name="fullname" required="" id="admin_user_fullname" placeholder="Enter User Name"> 
                         <div class="invalid-feedback">
                              Please enter name
                         </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control check_space check_email_if_exist" placeholder="Enter User Email" name="email" id="admin_user_email"  required="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                         <div class="invalid-feedback">
                              Please enter email
                         </div>
                         <span class="text-danger input_error" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label> Mobile Number </label>
                            <div class="input-group">
                                <div class="input-group-prepend custom-countrycode">
                                    <button type="button"  class="form-control country-codeBtn" data-toggle="dropdown">
                                      +251
                                    </button> 
                                </div>
                                <input autocomplete="off" type="tel" maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>"  class="form-control contact_number  check_contact_number_exist check_space" name="mobile" placeholder="Mobile Number" required="" id="admin_user_mobile">
                                <div class="invalid-feedback">
                                    Please enter contact number
                                </div>
                            </div>
                            <span class="text-danger input_error" id="mobile_no_error"></span>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" id="check_mobile_if_exist" value="false"/>
                    <input type="hidden" id="check_email_if_exist" value="false"/>
                    <input type="hidden" id="user_form_mode" value="" name="mode"/>
                    <input type="hidden" id="edit_user_id" value="" name="edit_user_id"/>
                    <button type="submit" class="btn btn-primary submit_form" data-backdrop="static" data-keyboard="false">Add</button>
                    <button type="reset" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

  $current_url =  current_url();
  $parameter_url = explode("user_management/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/user_management/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/user_management/table/';
  }

?>
<script type="text/javascript">
  var  admin_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>
