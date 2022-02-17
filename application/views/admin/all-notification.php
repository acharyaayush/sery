<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?php echo $pageTitle;?></h1>
        </div>
        <div class="row">
            <!--
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
            </div>-->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><?php echo $pageTitle;?></h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <a href="javascript:void(0)"  class="ml-3 btn btn-primary add_edit_category" <?php if(!empty($this->unread_notification)){echo 'id="notification_mark_as_read"';}?> >Mark All As Read</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php $this->load->view("admin/validation");?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" id="notification_table_list">
                                 <?php 
                                      $this->load->view("admin/all-notification-list-table");
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
 
<?php

  $current_url =  current_url();
  $parameter_url = explode("all_notification/0", $current_url);// if action is load like enable/disable or delete
  if(isset($parameter_url[1])){
     $new_url_for_mode = 'admin/all_notification/table/'.$parameter_url[1].'';
  }else{
     $new_url_for_mode = 'admin/all_notification/table/';
  }

?>
<script type="text/javascript">
  var  notification_table_url = '<?php echo  base_url(''.$new_url_for_mode .''); ?>';
</script>
