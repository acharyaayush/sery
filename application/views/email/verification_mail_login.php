<?php $this->load->view('email/includes/header'); ?>
<!-- // Will be used in resend otp also -->
<tr>
    <td>
        Dear <?php echo $user_name;?> ,
    </td>
</tr>
<tr>
    <td>Greetings from <?php echo APP_NAME;?>.</td>
</tr>
<tr>
    <td>Please enter <b><?php echo $verification_code;?></b> as your 4 digit verification code to login.</td>
</tr>
<tr>
    <td>Kind Regards,</td>
</tr>
<tr>
    <td>
        Team <?php echo APP_NAME;?>
    </td>
</tr>
<tr>
    <td>
        <a href="<?php echo $website_url;?>"><?php echo $website_url;?></a>
    </td>
</tr>

<?php
//footer view load
$this->load->view('email/includes/footer');
?>