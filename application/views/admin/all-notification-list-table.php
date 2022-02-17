<?php
$category_list_tr = "";
 
if(!empty($notification_data)){
    foreach ($notification_data as $value) {
        $time_ago = get_time_ago($value['created_at']);
        $category_list_tr.= '<tr>
                                <td>'.$start.'</td>
                                <td><a href="'.base_url('admin/order_detail/'.$value['order_id'].'').'">'.$value['title'].'</a></td>
                                <td>'.$value['message'].'</td>
                                <td>'.$time_ago.'</td>
                            </tr>';
                            $start++;
                            
    }
}else{
    $category_list_tr = "<tr><td colspan='5' class='no-records text-center'>No Records Found </td></tr>";
}
?>
<tr>
    <th>S No.</th>
    <th>Title</th>
    <th>Message</th>
    <th>Time</th>
</tr>
<?php echo $category_list_tr;?>