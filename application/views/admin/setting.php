<?php
 
if (isset($settings_data) && $settings_data != "") {

    //print_r($settings_data);

   $smtp_email = $settings_data[0]['value'];
   $smtp_password = $settings_data[1]['value'];

   
   $customer_ios_version = $settings_data[2]['value'];
   $customer_android_version = $settings_data[3]['value'];

   $service_provider_play_store_url = $settings_data[4]['value'];
   $service_provider_app_store_url = $settings_data[5]['value'];

   $customer_play_store_url = $settings_data[6]['value'];
   $customer_app_store_url = $settings_data[7]['value'];

   $support_email = $settings_data[8]['value'];
   $support_call = $settings_data[9]['value'];

   $sery_commission = $settings_data[10]['value'];
   $service_provider_commission = $settings_data[11]['value'];

   // socail media url and enable / disable --START----
   #1 = Enable , 2= Disable 
   $facebook_url = $settings_data[12]['value'];
   $fb_status = $settings_data[12]['status'];

   $instagram_url = $settings_data[13]['value'];
   $insta_status = $settings_data[13]['status'];

   $google_url = $settings_data[14]['value'];
   $google_status = $settings_data[14]['status'];
    // socail media url and enable / disable --END----

   $website_url = $settings_data[15]['value'];

   $company_name = $settings_data[16]['value'];
   $country_name = $settings_data[17]['value'];

   //Service Provider service accept duration
   $service_accept_duration = $settings_data[18]['value'];

   //Selected cities
   $selected_city_array = json_decode($settings_data[19]['value']);
   $provider_android_version = $settings_data[20]['value'];
   $provider_ios_version = $settings_data[21]['value'];
  
}

?>
<?php
$select_options = "";
if(!empty($city_data)){
    foreach ($city_data as $value) {
        $city_id = $value['id'];
		$city_name = $value['name'];

        $selected_city_array = json_decode($settings_data[19]['value']);
        if(in_array($city_name,$selected_city_array)){
            $checked = "checked";
        }else{
            $checked = "";
        }
        
        $select_options .= '<div>
                                <input name="selected_city[]" type="checkbox" id="city_'.$city_id.'" value="'.$city_name.'" '.$checked .'>
                                <label for="city_'.$city_id.'" class="text-capitalize">'.$city_name.'</label>
                            </div>';
    }
}else{
    $select_options = "No City available";
}
?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1> Settings </h1>
        </div>
       <!--  <div class="setng-header">
            <h3>Basic</h3>
        </div> -->
        <div class="setting-detals">
            <div class="row">
                <div class="col-md-12">
                   <form method="POST" action="<?php echo base_url('admin/UpdateSetting')?>"  class="needs-validation" novalidate="" id="SettingFormSubmit">
                         <?php $this->load->view("admin/validation");?>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Facebook : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url"  name="facebook_value" class="form-control " placeholder="Facebook page url" value="<?php echo $facebook_url;?>"> 
                                <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="enable-disabled">
                                    <div class="row">
                                        <label class="enabled-label"> Enable
                                            <input type="radio"  value="1" checked="checked" name="fb_status" <?php if($fb_status == '1'){echo 'checked="checked"';} ?> name="fb_status">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="enabled-label"> Disable
                                            <input type="radio" value="2" <?php if($fb_status == '2'){echo 'checked="checked"';} ?> name="fb_status">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Instagram : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url"  name="insta_value"  class="form-control" placeholder="Instagram page url" value="<?php echo $instagram_url;?>"> 
                                 <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="enable-disabled">
                                    <div class="row">
                                        <label class="enabled-label"> Enable
                                            <input type="radio"  value="1" checked="checked" name="insta_status" <?php if($insta_status == '1'){echo 'checked="checked"';} ?> name="insta_status">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="enabled-label"> Disable
                                            <input type="radio"  value="2" <?php if($insta_status == '2'){echo 'checked="checked"';} ?> name="insta_status">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Google+ : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url"  name="google_plus" class="form-control" placeholder="Google+ page url" value="<?php echo $google_url;?>"> 
                                 <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="enable-disabled">
                                    <div class="row">
                                        <label class="enabled-label"> Enable
                                            <input type="radio"  value="1" checked="checked" name="google_status" <?php if($google_status == '1'){echo 'checked="checked"';} ?> name="google_status">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="enabled-label"> Disable
                                            <input type="radio" value="2" <?php if($google_status == '2'){echo 'checked="checked"';} ?> name="google_status">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Customer Android Version : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="10" name="customer_android_version" class="form-control number_float" placeholder="Ex. 1.1" value="<?php echo $customer_android_version;?>"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>Customer IOS Version : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="10" name="customer_ios_version" class="form-control number_float" placeholder="Ex. 1.2" value="<?php echo $customer_ios_version;?>"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Provider Android Version : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="10" name="provider_android_version" class="form-control number_float" placeholder="Ex. 1.1" value="<?php echo $provider_android_version;?>"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>Provider  IOS Version : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="10" name="provider_ios_version" class="form-control number_float" placeholder="Ex. 1.2" value="<?php echo $provider_ios_version;?>"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Support Email : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="email" name="support_email" class="form-control" placeholder="Ex. info@sery.com" value="<?php echo $support_email;?>"> 
                                <div class="invalid-feedback">
                                    Please enter support email
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Support Contact Number : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="<?php echo MOBILE_NUMBER_MAX_DIGIT;?>"  minlength="<?php echo MOBILE_NUMBER_MIN_DIGIT;?>" name="support_call" class="form-control contact_number check_mobile_number_valid" placeholder="Enter Support Contact Number" value="<?php echo $support_call;?>"> 
                                <div class="invalid-feedback">
                                    Please enter support number
                                </div>
                                <span class="text-danger input_error" id="mobile_no_error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> SMTP Email : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="email" name="smtp_email" class="form-control" placeholder="Enter SMTP Email" value="<?php echo $smtp_email;?>" required=""> 
                                <div class="invalid-feedback">
                                  Please enter smtp email
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> SMTP Password : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="password" name="smtp_password" class="form-control" placeholder="Enter SMTP Password" value="<?php echo $smtp_password;?>" required="">
                                 <div class="invalid-feedback">
                                  Please enter smtp password
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Company Name: </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" value="<?php echo $company_name;?>"> 
                            </div>
                        </div>
                         <div class="form-group row">
                            <div class="col-md-3">
                                <label> Country Name: </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="country_name" class="form-control" placeholder="Enter Country Name" value="<?php echo $country_name;?>"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Serivce Provider Play store Url : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url"  name="service_provider_play_store_url" class="form-control" placeholder=" Ex. https://www.google.com" value="<?php echo $service_provider_play_store_url;?>"> 
                                 <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                        </div>
                         <div class="form-group row">
                            <div class="col-md-3">
                                <label> Serivce Provider App store Url : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url"  name="service_provider_app_store_url" class="form-control" placeholder="Ex. https://www.google.com" value="<?php echo $service_provider_app_store_url;?>"> 
                                 <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Customer Play store Url : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url" name="customer_play_store_value" class="form-control" placeholder="Ex. https://www.google.com" value="<?php echo $customer_play_store_url;?>"> 
                                <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Customer App store Url : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url" name="customer_app_store_value" class="form-control" placeholder="Ex. https://www.google.com"  value="<?php echo $customer_app_store_url;?>"> 
                                <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Website Url : </label>
                            </div>
                            <div class="col-md-5">
                                <input type="url" name="website_url" class="form-control" placeholder="Ex. https://www.sery.com"  value="<?php echo $website_url;?>">
                                <div class="invalid-feedback">
                                    Please enter url
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Sery Commission: </label>
                            </div>
                            <div class="col-md-5">
                                <input required="" type="text" maxlength="3" name="sery_commission" class="form-control number_foramte" placeholder="Ex. 20" value="<?php echo $sery_commission;?>"> 
                                <div class="invalid-feedback">
                                  Please enter sery commission
                                </div>
                            </div>
                        </div>
                       <!--  <div class="form-group row">
                            <div class="col-md-3">
                                <label> Serivce Provider Commission: </label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" maxlength="3" name="service_provider_commission" class="form-control number_foramte" placeholder="Ex. 5" value="<?php echo $service_provider_commission;?>"> 
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>Serivce Provider Service accept duration: </label>
                            </div>
                            <div class="col-md-5">
                               <!--  <input type="text" maxlength="3" name="service_accept_duration" class="form-control number_foramte" placeholder="Ex. 5" value="<?php echo $service_accept_duration;?>">  -->
                                <?php
                                     $selectbox_hour = "";
                                    for ($hours = 1; $hours <= 24; $hours++) {
                                        
                                      if($service_accept_duration == $hours.':00'){
                                         $select_duration = 'selected';
                                      }else{
                                         $select_duration = '';
                                      }

                                      $selectbox_hour .=  '<option value="'.$hours.':00" '.$select_duration.'>'.$hours.':00</option>';
                                    }
                                ?>
                                <select required=""  class="form-control" name="service_accept_duration" >
                                  <option value="">Select Duration</option>
                                  <option value="0:30" <?php if($service_accept_duration == '0:30'){echo 'selected';}?>>0:30</option>
                                  <?php echo $selectbox_hour;?>
                                </select>
                                <div class="invalid-feedback">
                                  Please select duration
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label> Choose City </label>
                            </div>
                            <div class="col-lg-5 col-md-4">
                                
                                <div id='announce' class='visually-hidden' aria-live="assertive" aria-atomic="true"></div>
  
                                  <button id="selectedDBXP" type="button" aria-expanded="false" onclick="return DBXPclick();">
                                    Select City
                                    <span id="DBXPList"></span>
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                  </button>
                                <fieldset>
                                  <div id="choicelist">
                                  <div id="searchfield">
                                        <label for="search">Search:</label>
                                        <!-- Search field. aria-autocomplete is 'both' -->      
                                      <!--  <input placeholder="Search City" id="search" type="text" class="biginput" autocomplete="off">
                                </div>
                                
                                <div class="autocomplete-suggestions" id="search-autocomplete"></div>
                                <div class="all-choices">
                                    <?php
                                        //echo $select_options;
                                    ?>
                                 </div>
                                  </div>
                                </fieldset>

                            </div>
                            <div class="col-lg-1 col-md-2">
                                <button type="button" class="btn btn-primary modal_click" data-toggle="modal" data-target="#AddCityModal"> Add City </button>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <div class="mt-5">
                                <button type="submit" class="btn btn-primary"> Save </button>
                                <a class="btn btn-primary " href="<?php echo base_url('admin/setting/');?>"> Clear </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- add city modal start-->

<div class="modal" id="AddCityModal">
    <div class="modal-dialog">
       <div class="modal-content">
            <form method="POST" action="<?php echo base_url('admin/Add_City_Controller')?>" class="needs-validation" novalidate="" enctype="multipart/form-data" id="AddCityForm">
      
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title"> Add City </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>City Name</label>
                        <input type="text" class="form-control check_space text-capitalize name_validation  city_name_input" placeholder="enter city name" name="city_name" required="" id="check_if_exist_city_name" autocomplete="off">
                        <div class="invalid-feedback">
                              Please enter city name
                         </div>
                        <span class="text-danger input_error" id="city_name_error"></span>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden"  name="check_city_name_if_exist" id="check_city_name_if_exist" value="false"/>
                    <button type="submit" class="btn btn-primary submit_form" >Add</button>
                </div>
            </form>
      </div>
    </div>
  </div>

<!-- add city modal end-->

<script>

function DBXPclick() {
  var listbutton = $("#selectedDBXP");
  if ($(listbutton).attr("aria-expanded") == "true") {
    $(listbutton).attr("aria-expanded", "false");
    $("#choicelist").css("display", "none");
    $("#selectedDBXP .fa-chevron-down").removeClass("fa-chevron-down--open");
  } else {
    $(listbutton).attr("aria-expanded", "true");
    $("#choicelist").css("display", "block");
    $("#selectedDBXP .fa-chevron-down").addClass("fa-chevron-down--open");
  }
}




/*FROM AUTOCOMPLETE FUNCTIONS*/

$(document).ready(function() {

  $(document).on("change","input[name='DBXP']", function() {
    var itemCount = $(".all-choices input[name='DBXP']:checked").length;
    
    if (itemCount == 0)
      $("#DBXPList").html("");
    else if (itemCount == 1)
        $("#DBXPList").html(" - "+itemCount + " item selected");
     // $("#DBXPList").html($("label[for='" + $("input[name='DBXP']:checked")[0].id + "']").text());
    else
      $("#DBXPList").html(" - "+itemCount + " items selected");
  });
  
    $(document).on("keyup","input[name='DBXP'], #selectedDBXP, #search", function(e) {
      if( $("#selectedDBXP").attr("aria-expanded") == "true" ){
      if (e.keyCode == 27)
        {
          DBXPclick();
          $("#selectedDBXP").focus();
        }
      }
    });

    /*List of suburbs that will act as the list of suggestions for my auto-suggest widget*/
    
    var suburbs = new Array();
    $('.all-choices label').each(function(){
    suburbs.push($(this).html());
    })
    
    
    /*Counter used to set IDs for each of the suggestions.*/
    var counter = 1;
    /*Array of keys used for the keyboard interactions*/
    var keys = {
        ESC: 27,
        TAB: 9,
        RETURN: 13,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40
    };
    
    /*Event handlers on the search input. One to perform the search and the other to deal with the keyboard interaction*/
    
    $("#search").on("input", function(event) {
        doSearch(suburbs);
    });


});
/*This function performs the search based on the users input, and builds the list of suggestions*/
function doSearch(suburbs) {

    var query = $("#search").val();

    /*If statement to start the search only after 2 characters have been enter. This  number can be higher or lower depending on your preference*/
    if ($("#search").val().length >= 1) 
    {

        //Case insensitive search and return matches to build the  array of suggestions
        var results = $.grep(suburbs, function(item) {
            return item.search(RegExp("^" + query, "i")) != -1;

        });
            /*Make sure we have at least 1 suggestion*/
            if (results.length >= 1) 
            {
                    /*Start things fresh by removing the suggestions div and emptying the live region before we start*/
                    $(".all-choices").hide();
                    $("#res").remove();
                    $('#announce').empty();
                    $(".autocomplete-suggestions").show();
                    /*Create the listbox to store the suggestions*/
                    $(".autocomplete-suggestions").append('<div id="res"></div>');
                    counter = 1;
                



                //Add suggestions to the list, limiting the list of displayed suggestions to 5
                for (term in results) {

                    if (counter <= 5) {
                    
                        /*If items are checked before search, check the dynamic items */
                        var checked = "";
                        var defID = results[term];
                        defID = defID.replace(/ /g,'');
                        if($("#"+defID+"").is(':checked'))
                        {
                            checked="checked";
                        }

                        
                    
                        $("#res").append("<div><input name='DBXP' type='checkbox' id='checkbox-" + counter + "' "+checked+"> <label for='checkbox-" + counter + "'> " + results[term] + "</label></div>");
                        counter = counter + 1;
                    }

                }
                /*Count the number of suggestions available and annouce to screen readers via live region */
                var number = $("#res").children('div').length
                if (number >= 1) {
                    $("#announce").text(+number + " database(s) found");
                }
            /*Check the default items */    
            $("#res input[name='DBXP']").on("change", function(){
                var thisID = $(this).attr("id");
                allCheckID = $("#"+thisID+"").siblings("label").html();
                allCheckID = allCheckID.replace(/ /g,'');
                if($(".all-choices #"+allCheckID+"").is(':checked'))
                {
                $(".all-choices #"+allCheckID+""). prop("checked", false);
                }
                else{
                $(".all-choices #"+allCheckID+""). prop("checked", true);
                }
            });
            
        }
        
        else
        {
            $(".all-choices").hide();
            $("#res").remove();
            $('#announce').empty();
            $(".autocomplete-suggestions").show();
            /*Create the listbox to store the suggestions*/
            $(".autocomplete-suggestions").append('<div id="res"></div>');
            $("#res").text("no results found");
            $('#announce').text("no results found");
        }
    } 
    else {
    /*If no results make sure the list does not display*/
        $(".all-choices").show();
        $("#res").remove();
        $('#announce').empty();
        $(".autocomplete-suggestions").hide();
    }

}
</script>
 


