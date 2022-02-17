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
    $service_provider_list_select_option = "";
    if(!empty($service_provider_data)){
        foreach ($service_provider_data as $value) {
            $service_provider_id = $value['user_id'];
            $service_provider_name = $value['fullname'];
            $service_provider_list_select_option .= '<option value="'.$service_provider_id.'">'.$service_provider_name.'</option>';
        }
    }else{
        $service_provider_list_select_option .= '<option value="">No service provider available</option>';
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
                                        <option value="6" <?php if($order_status == '6' && $order_status != 'all'){echo "selected";} ?>>Canceled </option>
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
                                <button class="btn btn-primary filtrBtn" id="search_order">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/orders')?>"> Clear</a>
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
                        <h4>Orders</h4>
                        <div class="card-header-form">
                            <form>
                                
                                <div class="input-group">
                                    
                                    <img src="<?php echo img_path(); ?>calender-icon.png" class="datepicker" width="30px">
                                    
                                    <a href="#addOrder" data-toggle="modal" class="ml-3 btn btn-primary">Add Orders</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                         <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="order_table">
                                <?php 
                                      $this->load->view("admin/order-list-table");
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
                        <input type="text" class="form-control check_space" name="customer_fullname" required="" id="customer_fullname"> 
                        <div class="invalid-feedback">
                            Please  enter customer name
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Customer Email</label>
                               <input type="email" class="form-control check_space" name="customer_email" required="" id="customer_email">
                                <div class="invalid-feedback">
                                  Please enter customer email
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Customer Mobile number</label>
                                <input type="tel" maxlength="9" minlength="7"  class="form-control contact_number check_space" name="customer_mobile" required="" id="customer_mobile">
                                <div class="invalid-feedback">
                                  Please enter customer contact number
                                </div>
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
                                <input type="text" maxlength="3" minlength="2" class="form-control check_space user_age" name="age" id="age">
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
                               <select class="form-control service_list"  required="" name="service_id"  append_id="0"  id="service_list_0">
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
                                <input type="hidden" name="visiting_price" class="visiting_price_for_order" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Customer Location</label>
                                <input type="text" class="form-control" id="customer_location" name="customer_location" required="">
                                <div class="invalid-feedback">
                                  Please enter customer location
                                </div>
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
                        <textarea class="form-control" name="note" id="note"></textarea>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                        <select class="form-control select2" required="" name="service_provider_id">
                            <option value="">Select Service Provider</option>
                             <?php echo $service_provider_list_select_option;?>
                        </select>
                        <div class="invalid-feedback">
                          Please select service provider
                        </div> 
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="order_id" id="selected_order_id"/>
                    <button type="submit" class="btn btn-primary">Assign</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- cancel order modal start -->

<div class="modal" id="cncelorder">
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

<!-- cancel user modal end -->
