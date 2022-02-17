<?php $this->load->view('email/includes/header'); ?>
<tr>
    <td>
        Dear <?php echo $name;?>,
    </td>
</tr>
<tr>
    <td>Greetings from <?php echo APP_NAME;?>. This is inform to you, your profile details is updated.</td>
</tr>
 
<tr>
    <td>You can check it:</td>
</tr>
<tr>
    <td>Login Url : <?php echo base_url('admin/login/')?></td>
</tr>
 
<tr>
    <td>Kind Regards,<br>Team <?php echo APP_NAME;?><br><a href="<?php echo $website_url;?>"><?php echo $website_url;?></a></td>
</tr>

<?php
//footer view load
$this->load->view('email/includes/footer');
?>