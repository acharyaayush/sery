<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Forgot Password &mdash; Sery</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo css_url(); ?>style.css">
  <link rel="stylesheet" href="<?php echo css_url(); ?>components.css">

  <!-- favicon image -->
  
  <link rel="icon" href="<?php echo img_path(); ?>favicon.png" type="image/gif">

</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="<?php echo img_path(); ?>logo.png" alt="logo">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Forgot Password</h4></div>

              <div class="card-body">
                <p class="text-muted">We will send a link to reset your password</p>
                 <form method="POST" action="<?php echo base_url('admin/ForgotPasswordSendEmail')?>" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" placeholder="Enter Your Email" name="email" tabindex="1" required="" autofocus="">
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>

                  <div class="form-group">
                     <?php $this->load->view("admin/validation");?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Forgot Password
                    </button>
                    <a href="<?php echo base_url(); ?>admin/" class="rtnn-link">Return to Login</a>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; Sery <?php echo date("Y");?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="<?php echo js_module_path(); ?>jquery.min.js"></script>
  <script src="<?php echo js_module_path(); ?>popper.js"></script>
  <script src="<?php echo js_module_path(); ?>tooltip.js"></script>
  <script src="<?php echo js_module_path(); ?>bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo js_module_path(); ?>nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?php echo js_module_path(); ?>moment.min.js"></script>
  <script src="<?php echo js_path(); ?>stisla.js"></script>
  
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="<?php echo js_path(); ?>scripts.js"></script>
  <script src="<?php echo js_path(); ?>custom.js"></script>
</body>
</html>