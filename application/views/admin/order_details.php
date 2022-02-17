<?php

if(!empty($order_details)){
    //print_r($order_details);
    $order_id = $order_details[0]['order_id'];
    $order_number_id = $order_details[0]['order_number_id'];
    $order_status = $order_details[0]['order_status'];
    $customer_id = $order_details[0]['customer_id'];
    $admin_commission = $order_details[0]['admin_commission'];
    $invoice_path = $order_details[0]['invoice_path'];

    if($order_status == 4){#completed
        $invoice_btn_class = "";
    }else{
        $invoice_btn_class = "disabled";
    }

    #customer detail
    $customer_email = $order_details[0]['customer_email'];

    if($customer_email == ""){
        $customer_email == 'N/A';
    }

    $customer_name = $order_details[0]['customer_name'];
    $customer_contact = $order_details[0]['customer_contact'];
    $member_since = $order_details[0]['member_since'];
    
    date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia cuntry
    $member_since_time_date  = date("d-m-Y",$member_since);// convert UNIX timestamp to PHP DateTime

    #customer location detail
    $google_pin_address = $order_details[0]['google_pin_address'];//Address of customer, where he/she want to take service
   
    if($order_details[0]['customer_image'] == ""){
        $customer_image = img_path().'avatar/avatar-1.png';
    }else{
         $customer_image = img_upload_path()[1].$order_details[0]['customer_image'];
    }

    #service detail
    $service_price = $order_details[0]['service_price'];

    $service_price_type = $order_details[0]['service_price_type'];
    if($service_price_type == 1){
        $service_price_type_name = 'Fixed';
    }else{
        $service_price_type_name = 'Hourly';
    }

    $visiting_price = $order_details[0]['visiting_price'];
    $taken_time = explode(':',$order_details[0]['taken_time']);
    $total_amount = $order_details[0]['total_amount'];

    $service_name = $order_details[0]['service_name'];

    $note = $order_details[0]['note'];
    if($note == ""){ 
        $note = "N/A";
    }
    $order_booked_by = $order_details[0]['order_booked_by'];//role 1 - super admin, role 2 - customer-service, role 4 - customer)

    #service provider detail
    $order_accept_by = $order_details[0]['order_accept_by'];//service_provider id
    $order_accept_by_name = $order_details[0]['order_accept_by_name'];//service_provider_name
    $service_provider_email = $order_details[0]['service_provider_email'];

    $service_provider_pin_address = $order_details[0]['pin_address'];

    $mobile = $order_details[0]['service_provider_mobile'];
    if($country_code == ""){
        //$country_code = COUNTRY_CODE;
        $plus_remove = "";
    }else if($country_code  !== "" && $mobile !=""){
        $plus_remove = "+";
    }
    $contact = $plus_remove.$country_code.' '.$mobile;
    $country_code = $order_details[0]['service_provider_country_code'];
}

?>


<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Orders-Details</h1>
        </div>
        <div class=" ">
            <div class="row">
                <div class="col-md-5">
                    <div class="card cust-card card-pd">
                        <div class="customer-img">
                            <img src="<?php echo $customer_image;?>">
                        </div>
                        <div class="user-info">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td class="text-capitalize"><a href="<?php echo base_url('admin/customer_detail/'.$customer_id.'');?>/"><?php echo $customer_name;?></a></td>
                                    </tr>
                                    <tr>
                                        <td>Orders id</td>
                                        <td><?php echo $order_number_id;?></td>
                                    </tr>
                                    <tr>
                                        <td>Member Since</td>
                                        <td><?php echo $member_since_time_date;?></td>
                                    </tr>
                                    <tr>
                                        <td>Accepted by</td>
                                        <td><a class="text-capitalize" href="<?php echo base_url('admin/service_provider_detail/'.$order_accept_by.'');?>/"><?php echo $order_accept_by_name;?></a></td>
                                    </tr>
                                     <tr>
                                        <td>Service name </td>
                                        <td class="text-capitalize"><?php echo $service_name;?></td>
                                    </tr>
                                    <?php  if($service_price_type == 1) {?>
                                    <tr>
                                        <td>Service price </td>
                                        <td><?php echo $service_price.' '.CURRENCY;?></td>
                                    </tr>
                                    <?php } ?>
                                     <tr>
                                        <td>Service type </td>
                                        <td><?php echo $service_price_type_name;?><?php  if($service_price_type == 2) {?>(<?php echo $service_price.' '.CURRENCY;?>/hour)<?php } ?></td>
                                    </tr>
                                    <tr>
                                        <td> Visting Price </td>
                                        <td> <?php echo $visiting_price.' '.CURRENCY;?> </td>
                                    </tr>
                                    <tr> 
                                        <td class="in-textbg">Service Duration</td>
                                        <td> <?php echo $taken_time[0];?> Hr <?php echo $taken_time[1];?> Min</td>
                                    </tr>
                                    <?php  if($this->role == 1) {?>
                                    <tr> 
                                        <td class="in-textbg">Sery Commission</td>
                                        <td> <?php echo $admin_commission;?>%</td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td> Total Amout </td>
                                        <td> <?php echo $total_amount.' '.CURRENCY;?> </td>
                                    </tr>
                                    <tr>
                                        <td> Invoice </td>
                                        <td>  <a class="btn btn-primary text-white <?php echo $invoice_btn_class;?>"  href="<?php echo order_invoice_upload_path()[1].$invoice_path;?>"  download > Download </a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card cust-card card-pdinfo">
                        <div class="custm-info-section">
                            <div class="cust-details-head">
                                <h5>Customer Information</h5>
                            </div>
                            <div class="cust-details-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6>Email</h6>
                                        <p> <?php if($customer_email == ''){echo 'N/A';}else{echo $customer_email;}?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Phone Number</h6>
                                        <p>+<?php echo COUNTRY_CODE.' '.$customer_contact;?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Address</h6>
                                        <p><?php echo $google_pin_address;?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pro-info-section">
                            <div class="cust-details-head">
                                <h5>Provider Information</h5>
                            </div>
                            <div class="cust-details-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6>Email</h6>
                                        <p> <?php if($service_provider_email == ''){echo 'N/A';}else{echo $service_provider_email;}?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Phone Number</h6>
                                        <p><?php echo $contact;?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Address</h6>
                                        <p><?php if($service_provider_pin_address == ''){echo 'N/A';}else{echo $service_provider_pin_address;}?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php  if($this->role == 1){#only super admin can see  this  note ?>
                        <div>
                            <form class="custNote">
                                <div class="form-group">
                                    <label>Note:</label>
                                    <p><?php echo $note;?></p>
                                </div>
                            </form>
                            
                        </div>
                        <?php }?>
                       <!--  <div class="text-right">
                            <a href="#cncelorder" data-toggle="modal" class="btn btn-info"> Cancel Order
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="cust-ord-lble">
            <h6>Service Provider List (Who got the service request but not accepted)
            <span> <?php echo count($who_got_service_order);?> </span></h6>
        </div>
        <div class="card card-details-section">
            <div class="table-responsive">
                <table class="table tble-prodct-section">
                    <?php 
                      $this->load->view("admin/order-list-who-got-order-but-accept");
                      //this is seprate for only table load by js on any action event
                    ?>
                </table>
            </div>
        </div>
    </section>
</div>