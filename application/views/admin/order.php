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

<?php 
    $service_list_select_option = "";
    if(!empty($services_for_search)){
        foreach ($services_for_search as $value) {
            $service_id = $value['service_id'];
            $service_name_english = $value['service_name_english'];

            if($service_id_for_search == $service_id ){
                $selected = 'selected';
            }else{
                $selected = '';
            }

            $service_list_select_option .= '<option value="'.$service_id.'" '.$selected.' class="text-capitalize">'.$service_name_english.'</option>';
        }
    }else{
        $service_list_select_option .= '<option value="">No service available</option>';
    }
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Orders</h1>
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
                                    <select class="form-control" id="order_status">
                                        <option  value="">Select Status</option>
                                        <option  value="0" <?php if($order_status == '0' && $order_status != 'all'){echo "selected";} ?>> Pending </option>
                                        <option value="1" <?php if($order_status == '1' && $order_status != 'all'){echo "selected";} ?>> Accepted</option>
                                        <option value="2" <?php if($order_status == '2' && $order_status != 'all'){echo "selected";} ?>> On the way </option>
                                        <option value="3" <?php if($order_status == '3' && $order_status != 'all'){echo "selected";} ?>>  Started </option>
                                        <option value="4" <?php if($order_status == '4' && $order_status != 'all'){echo "selected";} ?>>  Completed </option>
                                        <option value="5" <?php if($order_status == '5' && $order_status != 'all'){echo "selected";} ?>>  Rejected </option>
                                        <option value="6" <?php if($order_status == '6' && $order_status != 'all'){echo "selected";} ?>>Cancelled </option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Serivce</label>
                                    <select class="form-control" id="service_id_for_search">
                                        <option  value="">Select Service</option>
                                        <?php echo $service_list_select_option;?>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-md-3  col-sm-6">
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
                                <!-- For getting page status value means which page is opened (ongoing, completed,cancelled). which is open when click in sidmenu we are passing page status value in 4th segement. so we need to pass it in 4th segement always when we are  search or use pagination or do any status changes (order status changes-->
                                <input type="hidden" id="current_order_page_value" value="<?php echo $this->uri->segment(4);?>"/> 
                                <button class="btn btn-primary filtrBtn" id="search_order">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/orders/0/'.$order_page_value.'')?>"> Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if($this->uri->segment(4) == CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE || $this->uri->segment(4) == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE){
                ?>
                <div class="text-center">
                    <a href="<?php echo base_url();?>admin/orders/0/<?php echo CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE;?>/" class="btn btn-primary" <?php if($this->uri->segment(4) == CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE){ echo 'style="padding:9px;"';}?>>Cancelled By Customer</a>
                    <a href="<?php echo base_url();?>admin/orders/0/<?php echo CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE;?>/"  class="btn btn-primary "  <?php if($this->uri->segment(4) == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE){ echo 'style="padding:9px;"';}?>>Cancelled By Service Provider</a>
                </div>
                <?php 
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Orders</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    
                                   <!--  <img src="<?php echo img_path(); ?>calender-icon.png" class="datepicker" width="30px"> -->
                                    
                                    <a href="#addOrder" data-toggle="modal" class="ml-3 btn btn-primary add_order_btn modal_click"  data-backdrop="static" data-keyboard="false">Add Orders</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                         <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="order_table">
                                <?php 
                                      //this is seprate for only table load by js on any action event
                                      if($this->uri->segment(4) == CANCELLED_BY_CUSTOMER_ORDER_PAGE_VALUE || $this->uri->segment(4) == CANCELLED_BY_PROVIDER_ORDER_PAGE_VALUE){
                                        $this->load->view("admin/order-cancel-table");
                                      }else{
                                         $this->load->view("admin/order-list-table");
                                      }
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


<div class="modal" id="addOrder">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="OrderFormSubmit" action="<?php echo base_url('admin/Create_Order');?>" class="needs-validation" novalidate="" enctype="multipart/form-data">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>Customer Name</label>
                        
                      <!--   <input type="text" maxlength="80" class="form-control check_space" name="customer_fullname" required="" id="customer_fullname">  -->

                        <input type="text"  maxlength="80"  class="form-control check_space text-capitalize name_validation" name="customer_fullname" placeholder="Enter Customer Name" required="" id="customer_fullname"> 

                        <div class="invalid-feedback">
                            Please  enter customer name
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Customer Email</label>
                               <input type="email" class="form-control check_space" name="customer_email" placeholder="Enter Customer Email" id="customer_email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                               <!--  required=""-->
                                <div class="invalid-feedback">
                                  Please enter customer email
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Customer Mobile number</label>
                             
                                <div class="input-group custom-countrycode">
                                    <div class="input-group-prepend">
                                        <button type="button"  class="form-control country-codeBtn" data-toggle="dropdown">
                                          +251
                                        </button><!--class="dropdown-toggle"-->
                                        <!-- <div class="dropdown-menu">
                                          <a class="dropdown-item" href="#"> 1</a>
                                          <a class="dropdown-item" href="#"> 2</a>
                                          <a class="dropdown-item" href="#"> 3</a>
                                        </div> -->
                                    </div>
                                    
                                   <input type="tel" onclick="SearchDropdownFunction('SelectMobileDropdown')"  maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>"  placeholder="Mobile No." id="SelectMobileInput" onkeyup="filterFunction('SelectMobileDropdown','SelectMobileInput')" class="form-control contact_number check_space customer_contact_check_for_service check_mobile_number_valid" required="" autocomplete="off" name="customer_mobile" autocomplete="off">
                                   <div id="SelectMobileDropdown" class="w-100 d-none">
                                   </div>

                                    <!-- <input type="tel" maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>" minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>"  class="form-control contact_number check_space customer_contact_check_for_service" name="customer_mobile" placeholder="Mobile Number" required="" id="customer_mobile">
                                    <div class="invalid-feedback">
                                        Please enter customer contact number
                                    </div> -->
                                </div>
                                <span class="text-danger input_error" id="mobile_no_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Customer Gender</label>
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
                               <!--  <label>Date of Birth</label>
                                <input type="text" class="form-control datepicker"> -->
                                <label>Age</label>
                                <input type="text" maxlength="3" placeholder="Age" minlength="2" class="form-control check_space user_age" name="age" id="age">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Service Category</label>
                                <select class="form-control category_list_for_order" required="" name="service_category_id" id="category_list_0" append_id="0">
                                    <!-- #Note - id="category_list_0" append_id="0" and class="category_list_for_order"  is also used for service provider and function is used same for append service according to selected category id-->
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
                               <select class="form-control service_list check_booked_service"  required="" name="service_id"  append_id="0"  id="service_list_0">
                                    <option value="">Select Service</option>
                                </select>
                                <div class="invalid-feedback">
                                  Please select service
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Price Type</label>
                                <input type="text"  class="form-control check_space user_age service_price_type_for_order" disabled="">
                                <input type="hidden" name="service_price_type"  id="price_type_for_order_actual_value" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text"  class="form-control check_space user_age service_price_for_order"  disabled="">
                                <input type="hidden"  name="service_price" class="service_price_for_order" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Visting Price</label>
                                <input type="text" class="form-control check_space user_age visiting_price_for_order"  disabled="">
                                <input type="hidden" name="visiting_price" class="visiting_price_for_order"/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control check_space  actual_service_name_for_order" name="service_name">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Customer Location</label>
                                <input type="text" class="form-control select_google_location" id="customer_location" name="customer_location" placeholder="Enter Customer Location" required="">
                                <div class="invalid-feedback hide_this_error">
                                  Please select location
                                </div>
                                 <span class="text-danger input_error" id="cus_location_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Profile image</label>
                        <div class="selectedProfileImage">
                          <img  id="selected_img" alt="image" src="<?php echo img_path(); ?>avatar/avatar-1.png"  width="500">
                          <p> <label for="file"><i class="fa fa-plus" aria-hidden="true"></i> Upload Image </label> </p>
                          <input  accept="image/*" name="customer_image" id="file"  onchange="loadFile(event)"  type="file" class="form-control d-none" value="">
                    </div>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control" placeholder="Enter Message" name="note" id="note"></textarea>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <!--submit_status is for check selected service is not ongoing if true means when it false form should not be submit-->
                    <input type="hidden" id="submit_status" value="true" />

                    <input type="hidden" value="" id="customer_latitude" name="customer_latitude" />
                    <input type="hidden" value="" id="customer_longitude" name="customer_longitude"/>
                    <button type="submit" class="btn btn-primary submit_form">Add</button>
                    <button type="button" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="assignOrder">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST"  action="<?php echo base_url('admin/Assign_Order');?>" class="needs-validation" novalidate="">
                <!-- Modal Header -->
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Assign Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <div>
                            <label>Select Provider</label>
                        </div>
                        <select class="form-control select2 text-capitalize" required="" id="service_provider_id" name="service_provider_id">
                            <option value="">Select Service Provider</option>
                        </select>
                        <span class="text-danger input_error" id="select_provider_error"></span>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="order_id" id="selected_order_id"/>
                    <button type="button" class="btn btn-primary submit_form" id="AssignOrder_submit">Assign</button>
                    <button type="button" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- cancel order modal start -->

<div class="modal" id="cancelordermodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="blckmd">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">

                    <h5> Are you sure you want to cancel this order ? </h5>
                    <div class="form-group">
                        <label>For Customer or Service Provider?</label>
                        <select class="form-control select2" required="" id="cancel_for_whom" name="cancel_for_whom">
                            <option value="">Select Role</option>
                            <option value="4">Customer</option>
                            <option value="3" id="no_one_assigned"> Service Provider</option>
                            <!--
                                #db_user_role = 1 - admin, 2 - customer-service, 3- service-provider, 4 - customer
                                #For only check purpose we can give other value too but we are using db match value
                            -->
                        </select>
                        <span class="text-danger input_error" id="select_role_error"></span>
                    </div>
                    <div class="form-group">
                        <label> Reason </label>
                        <textarea class="form-control" placeholder="Enter Reason" id="cancel_reason"> </textarea>
                         <span class="text-danger input_error" id="cancel_reason_error"></span>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="order_id" id="order_id_for_cancel"/>
                    <input type="hidden" name="service_provider_id" id="service_provider_id_for_cancel"/>
                    <input type="hidden" name="service_id" id="service_id_for_cancel"/>
                    <button type="button" class="btn btn-primary submit_form" id="cancel_order_status_save">Submit</button>
                    <button type="button" class="btn btn-primary modal_click" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- cancel user modal end -->

<?php

  $current_url =  current_url();
  $parameter_url = explode("orders/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/orders/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/orders/table/';
  }

?>
<script type="text/javascript">
  var order_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyCXAPSpjmIu_a1ZcAHiw9oB_pJozpbWTyM" async defer></script>
<script type="text/javascript">
  function initAutocomplete() {
    var address = document.getElementById('customer_location');
    var autocomplete = new google.maps.places.Autocomplete(address);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();
      document.getElementById('customer_latitude').value = latitude;
      document.getElementById('customer_longitude').value = longitude;
    });
  }
</script>

