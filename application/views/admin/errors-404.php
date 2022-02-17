<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>404 &mdash; Sery</title>
 
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo js_module_path(); ?>fontawesome/css/all.min.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo css_url(); ?>style.css">
  <link rel="stylesheet" href="<?php echo css_url(); ?>components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="page-error">
          <div class="page-inner">
            <h1>404</h1>
            <div class="page-description">
              The page you were looking for could not be found.
            </div>
            <div class="page-search">
              <form>
                <div class="form-group floating-addon floating-addon-not-append">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">                          
                        <i class="fas fa-search"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                      <button class="btn btn-primary btn-lg">
                        Search
                      </button>
                    </div>
                  </div>
                </div>
              </form>
              <div class="mt-3">
                <a href="<?php echo base_url();?>admin/dashboard">Back to Home</a>
              </div>
            </div>
          </div>
        </div>
        <div class="simple-footer mt-5">
          Copyright &copy; Sery 2021
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