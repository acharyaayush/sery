 <?php

$banners = '';
if(!empty($banner_data)){
    foreach ($banner_data as $value) {
        $banners.= '<div class="col-lg-3 col-md-4 col-sm-6 mt-4">
                        <div class="bng-img">
                            <img src="'.img_upload_path()[1].$value['banner_image'].'" alt="banner-image">
                            <div class="bngClose-icon delete_banner" banner_id="'.$value['banner_id'].'">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>';
    }
}else{
    $banners = '<div class="col-sm-12">
                    <p>No data found</p>
                    </div>';
}
?>
<?php echo  $banners;?>