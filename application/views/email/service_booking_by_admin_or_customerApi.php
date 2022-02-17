<?php $this->load->view('email/includes/header'); ?>
<tr>
    <td>
        Dear <?php echo $name;?> ,
    </td>
</tr>
<tr>
    <td>Greetings from <?php echo APP_NAME;?>. You're receiving this email because your order has successfully booked by <?php echo APP_NAME;?>.</td>
</tr>
 
<tr>
    <td>Your Orderd Details Are:</td>
</tr>
<tr>
    <td>Name: <?php echo $name;?><br>Mobile No. : <?php echo $mobile;?> <br>Order No. : <?php echo $order_number_id;?><br>Address : <?php echo $address;?><br>Order No. : <?php echo $order_number_id;?><br>Booked Service : <?php echo $booked_service_name;?></td>
</tr>
 
<tr>
    <td>Kind Regards,<br>Team <?php echo APP_NAME;?><br><a href="<?php echo $website_url;?>"><?php echo $website_url;?></a></td>
</tr>

<?php
//footer view load
$this->load->view('email/includes/footer');
?>