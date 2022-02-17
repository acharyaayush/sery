<?php
$service_provider_select_option = "";
    if(!empty($service_provider_list)){
        foreach ($service_provider_list as $value) {
            $user_id = $value['user_id'];
            $provider_name = $value['fullname'];
            
            $service_provider_select_option .= '<option value="'.$user_id.'" '.$user_id.' class="text-capitalize">'.$provider_name.'</option>';
        }
    }else{
        $service_provider_select_option .= '<option value="">No service provider available</option>';
    }
?>
<?php
    $recharge_list_tr = wallet_history_data($admin_all_transactions);
?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Wallet Management</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Transfer Amount</h4>
                    </div>
                    <div class="card-body">
                        <?php $this->load->view("admin/validation");?>
                        <form method="POST" action="<?php echo base_url('admin/Add_Amount_In_Provider_Wallet')?>" class="needs-validation" novalidate=""
                            >
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-12 col-md-2 col-lg-2 offset-md-1">Select Provider</label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="form-group">
                                        <select class="form-control select2 text-capitalize" required="" name="service_provider_id">
                                            <option value="">Select Service Provider</option>
                                            <?php echo $service_provider_select_option ;?>
                                        </select>
                                        <div class="invalid-feedback">
                                          Please select service provider
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-12 col-md-2 col-lg-2 offset-md-1" >Amount</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control number_float" placeholder="Enter Amount" required="" maxlength="8" name="amount">
                                    <div class="invalid-feedback">
                                      Please enter amount
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a class="btn btn-primary" href="<?php echo base_url('admin/wallet_management')?>"> Cancel</a>
                            </div>   
                        </form>

                        <br>
                        <div class="cust-details-head">
                                <h5><span><img src="<?php echo img_path(); ?>recharge.jpg"> </span> Wallet Transaction</h5>
                            </div>

                           <div>
                                <div class="row m-0">
                                    <div class="col-sm-6 text-center">
                                         <!-- Provider Credit will be debit for the admin -->
                                        <h6><strong>Total Debit </strong></h6>
                                        <p><strong> <?php  echo $total_credited[0]['total_credited'].' '.CURRENCY;?></strong></p>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        <!-- Provider debit will be credit for the admin -->
                                        <h6><strong>Total Credit </strong></h6>
                                        <p><strong> <?php  echo $total_debited[0]['total_debited'].' '.CURRENCY;?></strong></p>
                                    </div>
                                </div>
                           </div>
                        <div class="card cust-card-info">

                        <div class="custm-info-section">

                            <div class="cust-details-content table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> S No. </th>
                                            <th> Provider Name </th>
                                            <th> Date </th>
                                            <th> Order ID </th>
                                            <th> Debit </th>
                                            <th> Credit </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $recharge_list_tr;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
