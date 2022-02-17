<?php
$category_list_tr = "";
if(!empty($category_data)){
    foreach ($category_data as $value) {
         $cat_id = $value['cat_id'];
         $cat_name_english = $value['cat_name_english'];
         $cat_name_amharic = $value['cat_name_amharic'];
         $cat_status = $value['cat_status'];
         $category_image = img_upload_path()[1].$value['category_image'];

         if($cat_status == 1){
            $status_btn_cls = 'btn-info';
            $status_value = 'Disable';

            $change_status = 2;// go for disable when click on button
         }else{
            $status_btn_cls = 'btn-secondary';
            $status_value = ' Enable';

            $change_status = 1;// go for enable when click on button
         }

         $category_list_tr.= '<tr>
                                <td>
                                    <img alt="image" src="'.$category_image.'" width="35" data-toggle="tooltip"><!--title="'.$cat_name_english.'"-->
                                </td>
                                <td>'.$cat_name_english.'</td>
                                <td>'.$cat_name_amharic.'</td>
                                <td>
                                    <div class="actionbtns">
                                        <button class="btn '.$status_btn_cls.' cat_status_change" status="'.$change_status.'" cat_id= "'.$cat_id.'">'.$status_value.'</button>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="actionbtns">
                                        <a href="#add_edit_ServiceCategory" data-toggle="modal" class="btn btn-info add_edit_category" mode="2" cat_id= "'.$cat_id.'"  data-backdrop="static" data-keyboard="false">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger cat_delete" cat_id="'.$cat_id.'">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>';
    }
}else{
    $category_list_tr = "<tr><td colspan='5' class='no-records text-center'>No Records Found </td></tr>";
}
?>
<tr>
    <th>Image</th>
    <th>English Name</th>
    <th>Amharic Name</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $category_list_tr;?>