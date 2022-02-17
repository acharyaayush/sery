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
            <h1>Service Management</h1>
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
                                    <label>Type</label>
                                    <select class="form-control" id="service_price_type">
                                        <option value="">Select type</option>
                                        <option value="1" <?php if($service_price_type == '1' && $service_price_type != 'all'){echo "selected";} ?>>Fixed</option>
                                        <option value="2" <?php if($service_price_type == '2' && $service_price_type != 'all'){echo "selected";} ?>>Hourly</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Service Category</label>
                                    <select class="form-control" id="service_category_id">
                                        <option value="">Select category</option>
                                          <?php echo $category_list_select_option;?>
                                    </select>
                                 </div>
                            </div>
                             <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Status</label>
                                    <select class="form-control" id="service_status">
                                        <option  value="">Select Status</option>
                                        <option  value="1" <?php if($service_status == '1' && $service_status != 'all'){echo "selected";} ?>> Enable </option>
                                        <option value="2" <?php if($service_status == '2' && $service_status != 'all'){echo "selected";} ?>> Disable </option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>Search</label>
                                    <div class="input-group-btn">
                                        <input type="text" class="form-control srch-bar search_key" placeholder="Search" value="<?php if(isset($search) && $search != '' && $search != 'all'){echo $search;} ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn" id="search_services">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/service_management/');?>"> Clear </a>
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
                        <h4>Service Management</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
 
                                     <a href="#add_edit_Service" data-toggle="modal" class="ml-3 btn btn-primary add_edit_Service_mode modal_click" data-backdrop="static" data-keyboard="false" mode='1'>Add Service</a>
 
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                         <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="service_table">
                                 <?php 
                                      $this->load->view("admin/service-list-table");
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
<div class="modal" id="add_edit_Service">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url('admin/Create_Update_Service')?>" class="needs-validation" novalidate="" enctype="multipart/form-data" id="ServiceFormSubmit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title service_modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>English Name</label>
                        <input type="text" class="form-control check_space name_validation text-capitalize" required="" name="  service_name_english" placeholder="Enter English Name" id="service_name_english">
                        <div class="invalid-feedback">
                          Please enter service name in "English"
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Amharic Name</label>
                        <input type="text" class="form-control check_space amharic_name_validation" required="" name="service_name_amharic" placeholder="Enter Amharic Name" id="service_name_amharic">
                        <div class="invalid-feedback" >
                          Please enter service name in "Amharic"
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Service Category</label>
                                <select class="form-control" required="" name="category_id" id="select_category_id">
                                    <option value="">Select Category</option>
                                     <?php echo $category_list_select_option;?>
                                </select>
                                <div class="invalid-feedback">
                                  Please select category
                                </div>
                            </div>
                        </div>
                        <?php if($this->role == 1){?>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Commission</label>
                                <input type="number" class="form-control" required="" name="commision" id="commision" value="<?php echo $this->default_commision;?>">
                                 <div class="invalid-feedback">
                                  Please enter commission
                                </div>
                            </div>
                        </div>
                       <?php }?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Open Time</label>
                                <input type="time" class="form-control" required="" name="open_time" id="open_time">
                                <div class="invalid-feedback">
                                  Please select open Time
                                </div>
                                <span class="text-danger input_error" id="open_time_error"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Close Time</label>
                                <input type="time" class="form-control" required="" name="close_time" id="close_time">
                                <div class="invalid-feedback">
                                  Please select close Time
                                </div>
                                 <span class="text-danger input_error" id="close_time_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Price Type</label>
                                <select class="form-control" required="" name="service_price_type" id="selected_price_type">
                                    <option value="">Select Type</option>
                                    <option value="1">Fixed</option>
                                    <option value="2">Hourly</option>
                                </select>
                                <div class="invalid-feedback">
                                  Please select price type
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" maxlength="8" class="form-control number_float" placeholder="Price" required="" name="service_price" id="service_price">
                                <div class="invalid-feedback">
                                  Please enter price
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Visting Price</label>
                                <input type="text" maxlength="8"  class="form-control number_float" placeholder="Visting Price" required="" name="visiting_price" id="visiting_price">
                                 <div class="invalid-feedback">
                                  Please enter visting price
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>English Description</label>
                        <textarea class="form-control check_space" required="" name="service_description_english" placeholder="English Description" id="service_description_english"></textarea>
                        <div class="invalid-feedback">
                          Please enter description in "English" 
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Amharic Description</label>
                        <textarea class="form-control check_space" required="" name="   service_description_amharic" placeholder="Amharic Description" id="service_description_amharic"></textarea>
                        <div class="invalid-feedback">
                          Please enter description in "Amharic" 
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control check_space" placeholder="Enter  Message" id="service_note" name="service_note"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Service icon image</label>
                        <div class="selectedProfileImage">
                            <img  id="selected_img" alt="image" src="<?php echo img_path(); ?>example-image.jpg"  width="500">
                            <p> <label for="file"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                        </div>
                        <input  accept="image/*" name="service_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" value="" required="">
                        <div class="invalid-feedback">
                        Please select image
                        </div>
                    </div>
                    <div class="form-group service-banner-uploadicon">
                        <label>Service banner image (375 * 282) </label>
                        <img  class="w-100" id="selected_mobile_banner_img" alt="image" src="<?php echo img_path(); ?>example-image.jpg"class="img-responsive" width="300" height="250">
                        <p> <label for="file2"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                        <input  accept="image/*" name="selected_mobile_banner_img" id="file2"  onchange="document.getElementById('selected_mobile_banner_img').src = window.URL.createObjectURL(this.files[0])"  type="file" class="form-control d-none" value="" required="">
                        <div class="invalid-feedback">
                        Please select image
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" id="service_form_mode" name="mode"/>
                    <input type="hidden" id="edit_service_id" name="edit_service_id"/>
                    <input type="hidden" id="exist_service_image" name="exist_service_image"/>
                    <input type="hidden" id="exist_service_mobile_banner_image" name="exist_service_mobile_banner_image"/>
                    <button type="submit" class="btn btn-primary submit_form">Add</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

  $current_url =  current_url();
  $parameter_url = explode("service_management/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/service_management/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/service_management/table/';
  }

?>
<script type="text/javascript">
  var service_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>