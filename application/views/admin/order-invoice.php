<?php

if(!empty($order_details)){
    
    $order_id = $order_details[0]['order_id'];
    $order_number_id = $order_details[0]['order_number_id'];
    $order_date = $order_details[0]['created_at'];

    #customer detail
    $customer_email = $order_details[0]['customer_email'];

    if($customer_email == ""){
        $customer_email == 'N/A';
    }

    $customer_name = $order_details[0]['customer_name'];
    $customer_contact = $order_details[0]['customer_contact'];
    $member_since = $order_details[0]['member_since'];
    
    date_default_timezone_set('Africa/Addis_Ababa'); // East Africa Time (EAT) (UTC+03:00) for ethopia cuntry
    $member_since_time_date  = date("d-m-Y",$member_since);// convert UNIX timestamp to PHP DateTime
    $booked_date  = date("d-m-Y",$order_date);// convert UNIX timestamp to PHP DateTime
    $booked_date_time  = date("d-m-Y H:i A",$order_date);// convert UNIX timestamp to PHP DateTime

    #customer location detail
    $google_pin_address = $order_details[0]['google_pin_address'];//Address of customer, where he/she want to take service
   
    if($order_details[0]['customer_image'] == ""){
        $customer_image = img_path().'avatar/avatar-1.png';
    }else{
         $customer_image = base_url($order_details[0]['customer_image']);
    }

    #service detail
    $service_price = $order_details[0]['service_price'];

    $service_price_type = $order_details[0]['service_price_type'];
    if($service_price_type == 1){
    	$service_price_type_name = 'Fixed';
    }else{
    	$service_price_type_name = 'Hourly';
    }
    $visiting_price = $order_details[0]['visiting_price'];
    $taken_time = explode(':',$order_details[0]['taken_time']);
    $total_amount = $order_details[0]['total_amount'];

    $service_name = $order_details[0]['service_name'];
    
    #service provider detail
    $order_accept_by_name = $order_details[0]['order_accept_by_name'];//service_provider_name

    $country_code = $order_details[0]['service_provider_country_code'];

    $mobile = $order_details[0]['service_provider_mobile'];
    if($country_code == ""){
        //$country_code = COUNTRY_CODE;
        $plus_remove = "";
    }else if($country_code  !== "" && $mobile !=""){
        $plus_remove = "+";
    }
    $contact = $plus_remove.$country_code.' '.$mobile;
    $country_code = $order_details[0]['service_provider_country_code'];
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
 
	<link rel="stylesheet" href="<?php echo js_module_path(); ?>bootstrap/css/bootstrap.min.css">
	 
	<style>
    	.pnt-nm p{
    			margin-bottom: 4px;
    			font-size: 14px;
    			margin-top: 6px;
    	}
    	.in-header {
		    max-width: 600px;
		    margin: 25px auto;
		    background: #fff;
		    padding: 15px 10px;
		    border: 1px solid #e6e6e6;
		}
		.no-border {
		    border: none;
		    
		}
		.no-border .card-header {
		    background: none;
		    border-bottom: none;
		}
		.in-title-text {
			font-size: 22px !important;
		    background: linear-gradient(
		278.34deg
		, #412F8F -35.16%, #4CC4D3 139.86%);
		    -webkit-background-clip: text;
		    -webkit-text-fill-color: transparent;
		    font-weight: 700;
		}
		.invoice-user {
			font-size: 25px !important;
		    font-weight: 600;
		    position: relative;
		    margin-bottom: -8 !important;
			}

		.priceBg b {
			position: relative;
			    padding-right: 10px;
		}
		.table-responsive {
			display: block;
		    width: 100%;
		 
		    -webkit-overflow-scrolling: touch;
		    -ms-overflow-style: -ms-autohiding-scrollbar;
		}
		.table {
		    width: 100%;
		    margin-bottom: 1rem;
		    background-color: transparent;
		        border-collapse: collapse;
		}

		.table td, .table th {
		    padding: .75rem;
		    vertical-align: top;
		    border-top: 1px solid #dee2e6;
		}
		.main-content {
		font-family :
			'Nunito', 'Segoe UI';
			color: #3e3e3e;
		}
    </style>
</head>
<body>
	<div class="main-content">
		<div class="card no-border">
		    <div class="card-header">
		        <div class="in-header">
		        	<div class="title-head">
		        		<div class="">
		        			<img src="data:image/png;base64,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
" style="width: 80px; margin: 0px 0px 8px 12px;">
		        		</div>
		        		<div class="pnt-nm" style=" padding: 12px;    margin: 20px 0px;">
		        			<div style="width:50%; display:inline-block;">
		        				<p style="margin-bottom: 0px;">Billing to:- </p>
								<p class="invoice-user text-capitalize" style="" > <?php echo $customer_name;?> </p>
								<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASQAAAAICAIAAADfghVCAAAACXBIWXMAAC4jAAAuIwF4pT92AAAGe2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNi4wLWMwMDIgNzkuMTY0MzYwLCAyMDIwLzAyLzEzLTAxOjA3OjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTA4LTI1VDE1OjMwOjQ0KzA1OjMwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTA4LTI1VDE1OjMwOjQ0KzA1OjMwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0wOC0yNVQxNTozMDo0NCswNTozMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0YzdmY2JiMi1hMjcwLTcwNDMtYTg1Ny03MmI4NDQxNGEyMmIiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo1MWYwOTg5MS0wMjNlLWUyNDQtOWMwNC02M2JmYWJlZjU5NGMiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo4NDUxZTNjZC1kODVhLTBjNDYtODM2Zi1mYjY2MTQyNmQwNjkiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjg0NTFlM2NkLWQ4NWEtMGM0Ni04MzZmLWZiNjYxNDI2ZDA2OSIgc3RFdnQ6d2hlbj0iMjAyMS0wOC0yNVQxNTozMDo0NCswNTozMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo0YzdmY2JiMi1hMjcwLTcwNDMtYTg1Ny03MmI4NDQxNGEyMmIiIHN0RXZ0OndoZW49IjIwMjEtMDgtMjVUMTU6MzA6NDQrMDU6MzAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4xIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPHBob3Rvc2hvcDpUZXh0TGF5ZXJzPiA8cmRmOkJhZz4gPHJkZjpsaSBwaG90b3Nob3A6TGF5ZXJOYW1lPSJJTlZPSUNFIiBwaG90b3Nob3A6TGF5ZXJUZXh0PSJJTlZPSUNFIi8+IDwvcmRmOkJhZz4gPC9waG90b3Nob3A6VGV4dExheWVycz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6TLFCzAAAAmUlEQVRYhe2TQQ6DMAwE3X6P7/YZ/Gl7KCmp7SQgVT7NSAhhYGIn2sf22k2yD+1uZq6o7jo+U3v9/WtZOeu/KwazEk/QesnCGYVKha7Jy7a4LbJpq0tzuntNpX+pwryDs17PG5tMF9Jd27jVxNAeu82fOt2MUTupuLNO5V3xaQBQAmEDKIKwARRB2ACKIGwARRA2gCIIG0ARb9RrsyksH5e1AAAAAElFTkSuQmCC
" style="width: 120px;">
								<p> Contact No.:-  +<?php echo COUNTRY_CODE.' '.$customer_contact;?></p>
								<p> Address :- <?php echo $google_pin_address;?> </p>
		        			</div>
		        			<div style="width:50%; display:inline-block;text-align: right;">
		        				<!-- <p class="in-title-text">  INVOICE </p>  -->
		        				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAScAAAAtCAYAAADstq2TAAAACXBIWXMAAC4jAAAuIwF4pT92AAAGe2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNi4wLWMwMDIgNzkuMTY0MzYwLCAyMDIwLzAyLzEzLTAxOjA3OjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTA4LTI1VDE1OjMxOjMyKzA1OjMwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTA4LTI1VDE1OjMxOjMyKzA1OjMwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0wOC0yNVQxNTozMTozMiswNTozMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDphODZjMzVlNS1mY2M5LTczNGYtYWEwZi00YzY5MGE3MTE1MWMiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDpkMGE2NTUyNC1mYTM3LTNhNGYtOTllZi00MmFmODVkY2VlNDQiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpkOWUzMjJkMi1iZDJkLWIzNDgtYTQ4Mi00Yzg4YWQxMzA3MTIiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOmQ5ZTMyMmQyLWJkMmQtYjM0OC1hNDgyLTRjODhhZDEzMDcxMiIgc3RFdnQ6d2hlbj0iMjAyMS0wOC0yNVQxNTozMTozMiswNTozMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphODZjMzVlNS1mY2M5LTczNGYtYWEwZi00YzY5MGE3MTE1MWMiIHN0RXZ0OndoZW49IjIwMjEtMDgtMjVUMTU6MzE6MzIrMDU6MzAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4xIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPHBob3Rvc2hvcDpUZXh0TGF5ZXJzPiA8cmRmOkJhZz4gPHJkZjpsaSBwaG90b3Nob3A6TGF5ZXJOYW1lPSJJTlZPSUNFIiBwaG90b3Nob3A6TGF5ZXJUZXh0PSJJTlZPSUNFIi8+IDwvcmRmOkJhZz4gPC9waG90b3Nob3A6VGV4dExheWVycz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5uThp9AAAJR0lEQVR4nO2dzZnbNhCGX+dJA3IJSglKBYl883Vdwtod7JZgd5BVCdEph5wixwXELCEqISwBOYBYUhT/MAMSAKXvsnqwJAa/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
" style="width: 120px;">
								<p> Order Id:- <?php echo $order_number_id;?> </p>
								<p> Order Date:- <?php echo $booked_date;?></p>
		        			</div>
		        		</div>
		        	</div>
					<div class="table-responsive" style="overflow:inherit;">
						<table class="table">
							<tbody>
									<tr>
										<td class="in-textbg"> Service Name </td>
										<td class="text-capitalize"> <?php echo $service_name;?> </td>
									</tr>
									<tr>
										<td class="in-textbg"> Service Booking Date & Time </td>
										<td> <?php echo $booked_date_time;?></td>
									</tr>
									<tr>
										<td class="in-textbg"> Service Provider Name </td>
										<td class="text-capitalize"> <?php echo $order_accept_by_name;?> </td>
									</tr>
									<?php  if($service_price_type == 1) {?>
									<tr>
										<td class="in-textbg"> Service Price </td>
										<td> <?php echo $service_price.' '.CURRENCY;?> </td>
									</tr>
									<?php } ?>
									<tr>
										<td class="in-textbg"> Service Type </td>
										<td> <?php echo $service_price_type_name;?> <?php  if($service_price_type == 2) {?>(<?php echo $service_price.' '.CURRENCY;?>/hour)<?php } ?></td>
									</tr>
									<tr>
										<td class="in-textbg"> Visting Price </td>
										<td> <?php echo $visiting_price.' '.CURRENCY;?> </td>
									</tr>
									<tr>
										<td class="in-textbg">Service Duration</td>
										<td> <?php echo $taken_time[0];?> Hr <?php echo $taken_time[1];?> Min</td>
									</tr>
									<tr>
										<td></td>
										<td> <span> Total Amount -  </span> <b> <?php echo $total_amount.' '.CURRENCY;?>  </b> </td>
									</tr>
							</tbody>			
						</table>
					</div>	
					<div style="margin-top: 40px;">
						<p style="margin-bottom: 0px; text-align: center; font-size: 12px;"> 
							Copyright©  <?php echo date("Y"); ?> Sery | All Right Reserved 

						</p>
					</div>
				</div>
		    </div>
		</div>
	</div>
</body>
</html>	
