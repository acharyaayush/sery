<!--Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <!-- <h1>Banner Details</h1> -->
            <h1>Banner Details</h1>
        </div>
        <!-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header headr-btn">
                        <button class="btn btn-primary" onclick="filtertoggle()"> Filter </button>
                    </div>
                    <div class="card-body" id="filter-togle-section">
                        <div class="row align-items-center">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>From Date</label>
                                    <input  type="text"  min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd" id="fromdate" name="fromdate" max="" class="form-control fromdate " value="<?php //if(isset($fromdate) && $fromdate != '' && $fromdate != 'all'){echo $fromdate;} ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>To Date</label>
                                    <input  type="text" min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd"  id="todate" name="todate" max="" class="form-control todate" value="<?php //if(isset($fromdate) && $todate != '' && $todate != 'all'){echo $todate;} ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Status</label>
                                    <select class="form-control">
                                        <option>Select status</option>
                                        <option> Enable </option>
                                        <option> Disable </option>
                                    </select>
                                 </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>Search</label>
                                    <div class="input-group-btn">
                                        <input type="text" class="form-control srch-bar" placeholder="Search">
                                    </div>
                                 </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    
                                    <button class="btn btn-primary filtrBtn">Search</button>
                                    <button class="btn btn-primary filtrBtn"> Clear </button>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn">Search</button>
                                <button class="btn btn-primary filtrBtn"> Clear </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-12">
                <div class="card banner-custom-file">
                    <div class="card-header">
                        <div class="card-Textheading">
                            <h4> Add Banner Image </h4>
                        </div> 
                        <div class="custom-file add_banner_img">
                            <img  class="" id="selected_home_banner_img" alt="image" src="<?php echo img_path();?>example-image.jpg"class="img-responsive" width="300" height="250">
                           <input  accept="image/*" name="selected_home_banner_img" id="banner_image"  onchange="document.getElementById('selected_home_banner_img').src = window.URL.createObjectURL(this.files[0])"  type="file" class="form-control d-none" value="" required="">
                            <label class="custom-file-icon" for="banner_image"> <i class="fa fa-plus"></i> </label>
                        </div>
                        <div class="bannerBtn">
                            <button type="button" id="upload_home_banner" class="btn btn-primary"> Add </button>
                            <a class="btn btn-primary " href="<?php echo base_url('admin/banners')?>"> Clear</a>
                        </div>        
                    </div>
                    <div class="card-body">

                        <div class="row" id="banner_list">
                            <?php
                                $this->load->view('admin/banner-list');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal" id="addServiceProvider">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Banner Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label>Heading</label>
                        <input type="text" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="5"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tag</label>
                                <select class="form-control">
                                    <option>Select Offer Category</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Discount Percentage </label>
                                <input type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Banner image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profileImage" name="filename">
                            <label class="custom-file-label" for="profileImage">Choose Banner Image</label>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal" id="editBannerDetails">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Banner Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body pb-0">
                <form>
                    <div class="form-group">
                        <label>Heading</label>
                        <input type="text" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="5"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tag</label>
                                <select class="form-control">
                                    <option>Select Offer Category</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Discount Percentage </label>
                                <input type="number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Banner image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profileImage" name="filename">
                            <label class="custom-file-label" for="profileImage">Choose file</label>
                        </div>
                    </div>

                    <div class="selectedProfileImage">
                        <img alt="image" src="assets/img/avatar/avatar-5.png">
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

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

<!-- block user modal end