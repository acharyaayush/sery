<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
       <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg  <?php if(!empty($this->unread_notification)){echo 'beep';}?>"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Notifications
                    <div class="float-right <?php if(empty($this->unread_notification)){echo 'd-none';}?>">
                        <a href="javascript:void(0)" id="notification_mark_as_read">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons" id="notification_list">
                   <?php  $this->load->view("admin/partials/navbar-notification-list");?>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="<?php echo base_url('admin/all_notification')?>">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li> 
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="<?php   if(isset($this->profile_image) && $this->profile_image !=""){echo img_upload_path()[1].$this->profile_image;}else{ echo img_path().'avatar/avatar-1.png';}?>" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, <?php if(isset($this->fullname) && $this->fullname !=""){echo $this->fullname;}?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- <div class="dropdown-title">Logged in 5 min ago</div> -->
                <a href="<?php echo base_url(); ?>admin/profile" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="<?php echo base_url(); ?>admin/change_password" class="dropdown-item has-icon">
                    <i class="fas fa-key"></i> Change Password
                </a>

                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url(); ?>admin/logout" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>