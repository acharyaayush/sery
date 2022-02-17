<?php
//header view load
$this->load->view('email/includes/header');
?>

<tr>
    <td>Hi <?php echo $user_name; ?></td>
</tr>
<tr>
    <td>We've received a request to reset your password. If you didn't make the request, just ignore this email. Otherwise, you can reset your password using this link</td>
</tr>
<tr>
    <td style="text-align: center;"><a href="<?php echo $url;?>" class="mt_button" style="color: white;">Reset Password</a></td>
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