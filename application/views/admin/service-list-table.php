<?php

function charlimit($string, $limit) {
		
	$overflow = (strlen($string) > $limit ? true : false);
	
	return substr($string, 0, $limit) . ($overflow === true ? "..." : '');
}

//Service Data 
$service_list_tr = "";
if(!empty($service_data)){
	foreach ($service_data as $value) {
		

		$service_id = $value['service_id'];
		$cat_name_english = $value['cat_name_english'];
		$service_name_english = $value['service_name_english'];
		//$service_name_amharic = $value['service_name_amharic'];
		$service_price = $value['service_price'];
		$service_price_type = $value['service_price_type'];
		$visiting_price = $value['visiting_price'];
		$commision = $value['commision'];
		$open_time = $value['open_time'];
		$close_time = $value['close_time'];
		$service_description_english = $value['service_description_english'];
		//$service_description_amharic = $value['service_description_amharic'];
		$service_image = img_upload_path()[1].$value['service_image'];
		$service_mobile_banner = img_upload_path()[1].$value['service_mobile_banner'];
		$service_status = $value['service_status'];

		//price type
		if($service_price_type == 1){//1- fixed, 2- hourly) Default value - 1
			$service_price_type_name  = 'Fixed';
		}else if($service_price_type == 2){
			$service_price_type_name  = 'Hourly';
		}

		 if($service_status == 1){//(1 - Enable, 2 - Disable, 3 - Deleted)(online/offline) Default value - 1
	        $status_btn_cls = 'btn-info';
	        $status_value = 'Disable';

	        $change_status = 2;// go for disable when click on button
	     }else{
	        $status_btn_cls = 'btn-secondary';
	        $status_value = ' Enable';

	        $change_status = 1;// go for enable when click on button
	     }

	    #only showing few charcater if the discription
	    $service_description_english =  charlimit($service_description_english, 35);

		$service_list_tr .='<tr>
					            <td>
					                <img alt="image" src="'.$service_image.'" width="35" data-toggle="tooltip"><!--title="'.$service_name_english.'"-->
					            </td>
					            <td>
					                <img alt="image" src="'.$service_mobile_banner.'" width="35" data-toggle="tooltip"><!--title="'.$service_name_english.'"-->
					            </td>
					            <td>'.$service_name_english.'</td>
					            <td>'.$cat_name_english.'</td>
					            <td>'.$service_price.' ' .CURRENCY.'</td>
					            <td>'.$service_price_type_name.'</td>
					            <td>'.$visiting_price.'</td>';

		if($this->role == 1){
			 $service_list_tr .='<td>'.$commision.'%</td>';
		}
					          
		$service_list_tr .='<td>'.$service_description_english.'</td>
					            <td>
                                    <div class="actionbtns">
                                        <button class="btn '.$status_btn_cls.' service_status_change" status="'.$change_status.'" service_id= "'.$service_id.'">'.$status_value.'</button>
                                    </div>
                                </td>
					            <td class="text-right">
					                <div class="actionbtns">
					                    <a href="#add_edit_Service" data-toggle="modal" class="btn btn-info add_edit_Service_mode" service_id="'.$service_id.'" mode="2">
					                        <i class="fas fa-pencil-alt"></i>
					                    </a>
					                    <a href="#" class="btn btn-danger service_delete" service_id="'.$service_id.'" >
					                        <i class="fa fa-trash"></i>
					                    </a>
					                </div>
					            </td>
					        </tr>';
	}
}else{
     $service_list_tr = "<tr><td colspan='10' class='no-records text-center'>No Records Found </td></tr>";
}


?>
<tr>
    <th>Image</th>
    <th>Banner </th>
    <th>Name</th>
    <th>Service Category</th>
    <th>Price</th>
    <th>Type</th>
    <th>Visting Price</th>
    <?php if($this->role == 1){ ?>
    <th>Commission</th>
    <?php } ?>
    <th>Description</th>
    <th>Status</th>
    <th class="text-right">Action</th>
</tr>
<?php echo $service_list_tr;?>