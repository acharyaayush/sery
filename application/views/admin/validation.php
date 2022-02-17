<?php
    if($this->session->flashdata('success')!='')
    { ?>
        <div class="alert alert-success in alert-dismissible">
             <a href="#" class="close close_alert" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>Success !</strong> <?php echo $this->session->flashdata('success');?>
        </div>               
    <?php
    }elseif($this->session->flashdata('error')!='')
    { ?>
        <div class="alert alert-danger  in alert-dismissible">
           <a href="#" class="close close_alert" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>Error !</strong> <?php echo $this->session->flashdata('error');?>
        </div> 
    <?php
    }elseif($this->session->flashdata('warning')!='')
    { ?>
        <div class="alert alert-warning  in alert-dismissible">
           <a href="#" class="close close_alert" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?php echo $this->session->flashdata('warning');?>
        </div> 
    <?php
    }
?>
 