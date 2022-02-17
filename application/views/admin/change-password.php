<!-- Main Content -->
 <div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Password</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Change Password</h4>
                    </div>
                     <div id="hide_after_match_old_pwd">
                        <div class="card-body">
                            <?php $this->load->view("admin/validation");?>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Old Password</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="password" class="form-control old_password" name="old_password" placeholder="Old Password" id="old_password" required="">
                                    <span class="text-danger input_error" id="old_password_error"></span>
                                     <span class="text-success input_error" id="old_password_success"></span>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="MatchOldPasswordSubmit" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                    </div>
                    <form class="needs-validation d-none" novalidate="" id="ChangePasswordSubmit" method="POST" action="<?php echo base_url('admin/UpdatePassword')?>">
                        <div class="card-body">
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-12 col-md-3 col-lg-3 col-xl-2 offset-xl-1">New Password</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="password" name="new_password" class="form-control np_password" placeholder="New Password" id="new_password " required="">
                                    <div class="invalid-feedback">
                                      Please enter your new password
                                    </div>
                                    <span class="text-danger input_error" id="np_password_error"></span>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label  col-12 col-md-3 col-lg-3 col-xl-2 offset-xl-1">Confirm Password</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="password" name="confirm_password" class="form-control cnp_password" placeholder="Confirm Password" id="confirm_password" required="">
                                    <div class="invalid-feedback">
                                      Please enter your  password and confrim
                                    </div>
                                    <span class="text-danger input_error" id="cnp_password_error"></span>
                                     <span class="text-success input_error" id="confirm_password_success"></span>
                                </div>
                            </div>
                            <div class="alert d-none">
                                 <span id="pwd_error"></span><br>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group text-center">
                                        <button type="submit" id="submit_password_btn" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>