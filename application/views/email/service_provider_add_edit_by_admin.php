<?php $this->load->view('email/includes/header'); ?>
<tr>
    <td>
        Dear <?php echo $name;?> ,
    </td>
</tr>
<tr>
    <?php
        if($mode == 1 ){
    ?>
    <td>Greetings from <?php echo APP_NAME;?>. You're receiving this mail because you are registered by <?php echo APP_NAME;?>  administrator as a Service Provider.</td>
    <?php
    }
    ?>
    <?php
        if($mode == 2){
    ?>
    <td>Greetings from <?php echo APP_NAME;?>. You're receiving this mail because your profile update successfully by <?php echo APP_NAME;?>.</td>
    <?php
    }
    ?>
</tr>
 <?php
        if($mode == 1 ){
    ?>
<tr>
    <td><?php echo APP_NAME;?> welcome and thank you for your interest with us.</td>
</tr>
<?php
    }
    ?>
<tr>
    <td>Your Account Details Are:</td>
</tr>
<tr>
     <td>Name: <?php echo $name;?><br>Mobile No. :<?php echo $mobile;?> </td>
</tr>
 
<tr>
    <td>Kind Regards,<br>Team <?php echo APP_NAME;?><br><a href="<?php echo $website_url;?>"><?php echo $website_url;?></a></td>
</tr>

<?php
//footer view load
$this->load->view('email/includes/footer');
?>