<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title><?php echo APP_NAME .' | '?> <?php if(isset($pageTitle)){echo $pageTitle;}?></title>

        <!-- General CSS Files -->
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>fontawesome/css/all.min.css">

        <!-- CSS Libraries -->
        <?php if(isset($pageName) && $pageName == "admin/dashboard") {?> 
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>jqvmap/dist/jqvmap.min.css">
        <?php } ?>
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>weather-icon/css/weather-icons.min.css">
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>weather-icon/css/weather-icons-wind.min.css">
          <?php if(isset($pageName) && $pageName == "admin/terms-and-conditions" || $pageName == "admin/privacy-policy") {?> 
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>summernote/summernote-bs4.css">
        <?php } ?>
       
        <!-- Date picker-->
        <link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap-daterangepicker/daterangepicker.css">

        <!-- Template CSS -->
        <link rel="stylesheet" href="<?php echo css_url(); ?>style.css">
        <link rel="stylesheet" href="<?php echo css_url(); ?>components.css">
        
        <!-- favicon image -->

        <link rel="icon" href="<?php echo img_path(); ?>favicon.png" type="image/gif">
        <!-- Start GA -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-94034622-3');

        </script>
         <script type="text/javascript">
            var BASE_URL = "<?php echo base_url();?>";
            var IMG_PATH = "<?php echo img_path();?>";
            var IMG_UPLOAD_PATH = "<?php echo img_upload_path()[1];?>";
            var CHART_DATA = <?php echo json_encode($this->chart_array); ?>;
        </script>
        <!-- /END GA -->

        <script src="<?php echo js_module_path(); ?>jquery.min.js"></script>
    </head>
    <body>
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                <!-- navbar Start-->
                <?php  $this->load->view('admin/partials/navbar');?>
                <!-- navbar End-->
                <!--Side Menu Convert Start-->
                 <?php  $this->load->view('admin/partials/sidemenu');?>
                <!--Side Menu Convert End-->
                 <?php if(isset($pageName)){
                    $this->load->view($pageName);
                 }?>
                <footer class="main-footer">
                    <div class="footer-left">
                        Copyright© <?php echo date("Y"); ?> Sery | All Right Reserved
                    </div>
                    <div class="footer-right">

                    </div>
                </footer>
            </div>
        </div>
       
        <!-- General and common JS Scripts -->
        
        <script src="<?php echo js_module_path(); ?>popper.js"></script>
        <script src="<?php echo js_module_path(); ?>tooltip.js"></script>
        <script src="<?php echo js_module_path(); ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo js_module_path(); ?>nicescroll/jquery.nicescroll.min.js"></script>
        <script src="<?php echo js_module_path(); ?>moment.min.js"></script>
        <script src="<?php echo js_path(); ?>stisla.js"></script>

        <!-- JS Libraies -->
        <script src="<?php echo js_module_path(); ?>simple-weather/jquery.simpleWeather.min.js"></script>

        <?php //if(isset($pageName) && $pageName == "admin/terms-and-conditions" || $pageName == "admin/privacy-policy") {?> 
        <script src="<?php echo js_module_path(); ?>summernote/summernote-bs4.js"></script>
        <?php // }?> 
        <?php if(isset($pageName) && $pageName == "admin/dashboard") {?> 
        <script src="<?php echo js_module_path(); ?>chart.min.js"></script>
       

        <script src="<?php echo js_module_path(); ?>jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo js_module_path(); ?>jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo js_module_path(); ?>chocolat/dist/js/jquery.chocolat.min.js"></script>
        <!-- Page Specific JS File -->
        <script src="<?php echo js_path(); ?>page/index-0.js"></script>
         <?php  }?> 
     
        <!--Date picker--->
        <script src="<?php echo js_module_path(); ?>bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Template JS File -->
        <script src="<?php echo js_path(); ?>scripts.js"></script>
 
        <script src="<?php echo js_path(); ?>custom.js"></script>
        <script src="<?php echo js_module_path(); ?>sweetalert/sweetalert.min.js"></script>


        <!-- filter from and to  date -------------START---------->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
        <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
        <script type="text/javascript">
           var $j = jQuery.noConflict();
            $j("#fromdate").datepicker({
                dateFormat: 'yy-mm-dd',
                // numberOfMonths: 2,
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    $j("#todate").datepicker("option", "minDate", dt);
                }
            });
            $j("#todate").datepicker({
                dateFormat: 'yy-mm-dd',
                // numberOfMonths: 2,
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate() - 1);
                    // $j("#fromdate").datepicker("option", "maxDate", dt);
                }
            });
        </script>
        <!-- filter from and to  date -------------END---------->

    </body>
</html>