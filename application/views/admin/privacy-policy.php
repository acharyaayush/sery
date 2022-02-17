
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Privacy  Policy</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Privacy  Policy</h4>
                    </div>
                    <form method="POST" action="<?php echo base_url('admin/Update_CMS')?>" class="needs-validation" novalidate="">
                        <div class="card-body">
                            <?php $this->load->view("admin/validation");?>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="page_name" class="form-control" value="<?php if(!empty($cms_data)){echo $cms_data[0]['page_name'];} ?>" required="" placeholder="Enter title">
                                    <div class="invalid-feedback">
                                        Please enter title
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                <div class="col-sm-12 col-md-7">

                                    <textarea class="summernote" id="summernote" name="page_value">
                                        <?php if(!empty($cms_data)){echo $cms_data[0]['page_value'];} ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Amharic Content</label>
                                <div class="col-sm-12 col-md-7">

                                    <textarea class="summernote" id="summernote" name="page_value_amharic">
                                        <?php if(!empty($cms_data)){echo stripslashes($cms_data[0]['page_value_amharic']);} ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="hidden" name="page_key" value="<?php if(!empty($cms_data)){echo stripslashes($cms_data[0]['page_key']);} ?>" />
                                    <input type="hidden" name="page_primary_id" value="<?php if(!empty($cms_data)){echo $cms_data[0]['id'];} ?>" />
                                    <input type="hidden" name="view_landing_page" value="privacy_policy" />
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>