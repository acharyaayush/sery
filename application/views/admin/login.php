<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
   <title><?php echo APP_NAME .' | '?>   Login</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap-social/bootstrap-social.css">

   <!-- favicon image -->

  <link rel="icon" href="<?php echo img_path(); ?>favicon.png" type="image/gif">
  <!-- Template CSS --> 
  <link rel="stylesheet" href="<?php echo css_url(); ?>style.css">
  <link rel="stylesheet" href="<?php echo css_url(); ?>components.css">
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
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url('admin/auth')?>" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus autocomplete="" placeholder="Enter Your Email" value=" <?php if(isset($_COOKIE["loginId"])) { echo $_COOKIE["loginId"]; } ?>">
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control"  name="password" tabindex="2" required autocomplete="" placeholder="Enter Your Password" value="<?php if(isset($_COOKIE["loginPass"])) { echo $_COOKIE["loginPass"]; } ?>">
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>

                  <div class="form-group form-inline justify-content-between">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me"  <?php if(isset($_COOKIE["loginId"])) { ?> checked="checked" <?php } ?>>
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                    <div class="float-right">
                        <a href="<?php echo base_url('admin/forgot_password')?>" class="text-small">
                          Forgot Password?
                        </a>
                      </div>
                  </div>

                  <div class="form-group">
                    <?php $this->load->view("admin/validation");?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
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
 
  
  <script type="text/javascript">
      var BASE_URL = "<?php echo base_url();?>";
  </script>
 
  <script src="<?php echo js_path(); ?>custom.js"></script>
</body>
</html>