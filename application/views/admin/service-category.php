<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Service Category</h1>
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
                                    <select class="form-control" id="cat_status">
                                        <option  value="">Select Status</option>
                                        <option  value="1" <?php if($cat_status == '1' && $cat_status != 'all'){echo "selected";} ?>> Enable </option>
                                        <option value="2" <?php if($cat_status == '2' && $cat_status != 'all'){echo "selected";} ?>> Disable </option>
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
                                <button class="btn btn-primary filtrBtn" id="search_service_cat">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/service_categories')?>"> Clear</a>
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
                        <h4>Service Category</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <a href="#add_edit_ServiceCategory" data-toggle="modal" class="ml-3 btn btn-primary add_edit_category modal_click" data-backdrop="static" data-keyboard="false" mode="1">Add Service Category</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="category_list">
                                 <?php 
                                      $this->load->view("admin/service-catgory-list");
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
<div class="modal" id="add_edit_ServiceCategory">
    <div class="modal-dialog">
        <div class="modal-content">
             <form method="POST" action="<?php echo base_url('admin/Create_Update_ServiceCategory')?>" class="needs-validation" novalidate="" enctype="multipart/form-data">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title modal_title_name"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>English Name</label>
                        <input type="text" class="form-control check_space name_validation" placeholder="Enter English Name" name="cat_name_english" id="cat_name_english" required="">
                         <div class="invalid-feedback">
                              Please enter service category name in "English"
                         </div>
                    </div>
                    <div class="form-group">
                        <label>Amharic Name</label>
                        <input type="text" class="form-control check_space amharic_name_validation" placeholder="Enter Amharic Name" name="cat_name_amharic" id="cat_name_amharic"  required=""><!--name_validation-->
                         <div class="invalid-feedback">
                              Please enter service category name in "Amharic"
                         </div>
                    </div>
                     <div class="form-group">
                        <label>Service category icon image</label>
                            <div class="selectedProfileImage">
                              <img  id="selected_img" alt="image" src="<?php echo img_path(); ?>example-image.jpg"  width="500">
                              <p> <label for="file"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                           </div>
                          <input  accept="image/*" name="category_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" value="" required="">
                            <div class="invalid-feedback">
                                Please select image
                            </div>
                   
                    </div>
                    
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" id="form_mode" value="" name="mode"/>
                    <input type="hidden" id="edit_cat_id" value="" name="cat_id"/>
                    <input type="hidden" id="exist_category_image" name="exist_category_image"/>
                    <button type="submit" class="btn btn-primary submit_form" data-backdrop="static" data-keyboard="false">Add</button>
                    <button type="reset" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

  $current_url =  current_url();
  $parameter_url = explode("service_categories/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/service_categories/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/service_categories/table/';
  }

?>
<script type="text/javascript">
  var  service_cat_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>
