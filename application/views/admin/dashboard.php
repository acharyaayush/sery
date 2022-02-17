<?php
    if(isset($fromdate) && $fromdate != ''){
        $fromdate = $fromdate;
    } else if(isset($todate) && $todate != ''){
        $todate = $todate;
    }
    $filter_url  = ''.$fromdate.'/'.$todate.'/';
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header headr-btn">
                        <button class="btn btn-primary" onclick="filtertoggle()"> Filter </button>
                    </div>
                    <div class="card-body" id="filter-togle-section">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group fltr-section">
                                    <label>From Date</label>
                                     <input  type="text"  min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd" id="fromdate" name="fromdate" max="" class="form-control fromdate " value="<?php if(isset($fromdate) && $fromdate != '' && $fromdate != 'all'){echo $fromdate;} ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                 <div class="form-group fltr-section">
                                    <label>To Date</label>
                                    <input  type="text" min="2021-01-01" autocomplete="off"placeholder="yyyy-mm-dd"  id="todate" name="todate" max="" class="form-control todate" value="<?php if(isset($fromdate) && $todate != '' && $todate != 'all'){echo $todate;} ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary filtrBtn" id="filter_dashboard">Search</button>
                                <a class="btn btn-primary filtrBtn" href="<?php echo base_url('admin/dashboard')?>"> Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="<?php echo base_url('admin/orders/0/'.READY_FOR_ASSIGN_ORDER_PAGE_VALUE.'/'.$filter_url.'')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Orders</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $total_order;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
               <a href="<?php echo base_url('admin/customer_Management/0/'.$filter_url.'')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Customer</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $total_customer;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="<?php echo base_url('admin/service_provider_management/0/'.$filter_url.'')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Service Providers</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $total_service_provider;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="<?php echo base_url('admin/service_categories/0/'.$filter_url.'')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-list-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Categories</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $total_category;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="<?php echo base_url('admin/service_management/0/'.$filter_url.'')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Services</h4>
                            </div>
                            <div class="card-body">
                                 <?php echo $total_service;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php if($this->id && $this->role == 1){ ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="<?php echo base_url('admin/banners')?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon badge-primary">
                            <i class="far fa-file-image"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Banners</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $total_banners;?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
           <?php }?>
        </div>
       <div>
             <div class="card">
                <div class="card-header" style="justify-content: left;">
                    <h4>Zone</h4>
                </div>
                <div class="card-body">
                      <div id="map-canvas" style="height: 400px; width: auto;"></div>
                </div>
            </div>
           </div>
        <div class="row">
            <!-- <div class="col-lg-8 col-md-12 col-12 col-sm-12"> -->
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">  
                <div class="card">
                    <div class="card-header">
                        <h4>Statistics</h4>
                        <div class="card-header-action">
                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn btn-primary">Week</a>
                                <a href="#" class="btn d-none">Month</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" height="182"></canvas>
                        <div class="statistic-details mt-sm-4">
                            <div class="statistic-details-item">
                               <!--  <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 7%</span> -->
                                <div class="detail-value"><?php if($today_sale[0]['today_sale_totel'] == ""){echo '0.00';}else{echo $today_sale[0]['today_sale_totel'];} echo ' '.CURRENCY;?></div>
                                <div class="detail-name">Today's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                               <!--  <span class="text-muted"><span class="text-danger"><i class="fas fa-caret-down"></i></span> 23%</span> -->
                                <div class="detail-value"><?php if($this_week_sale[0]['this_week_sale_totel'] == ""){echo '0.00';}else{echo $this_week_sale[0]['this_week_sale_totel'];} echo ' '.CURRENCY;?></div>
                                <div class="detail-name">This Week's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                               <!--  <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span>9%</span> -->
                                <div class="detail-value"><?php if($this_month_sale[0]['this_month_sale_totel'] == ""){echo '0.00';}else{echo $this_month_sale[0]['this_month_sale_totel'];} echo ' '.CURRENCY;?></div>
                                <div class="detail-name">This Month's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                                <!-- <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 19%</span> -->
                                <div class="detail-value"><?php if($this_year_sale[0]['this_year_sale_totel'] == ""){echo '0.00';}else{echo $this_year_sale[0]['this_year_sale_totel'];} echo ' '.CURRENCY;?></div>
                                <div class="detail-name">This Year's Sales</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             
           <!--  <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Activities</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border">
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="<?php echo img_path(); ?>avatar/avatar-1.png" alt="avatar">
                                <div class="media-body">
                                    <div class="float-right text-primary">Now</div>
                                    <div class="media-title">Farhan A Mujib</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="<?php echo img_path(); ?>avatar/avatar-2.png" alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">12m</div>
                                    <div class="media-title">Ujang Maman</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="<?php echo img_path(); ?>avatar/avatar-3.png" alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">17m</div>
                                    <div class="media-title">Rizal Fakhri</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="<?php echo img_path(); ?>avatar/avatar-4.png" alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">21m</div>
                                    <div class="media-title">Alfa Zulkarnain</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center pt-1 pb-1">
                            <a href="#" class="btn btn-primary btn-lg btn-round">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div> -->
           </div>
           
    </section>
</div>
    
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyCXAPSpjmIu_a1ZcAHiw9oB_pJozpbWTyM"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/googlemaps/js-map-label/gh-pages/src/maplabel.js"></script>
<?php 
$zone_name_array = [];
$zone_latlng_array = "";
$zone_latlng_merge_array = "";
$latlng_array = [];
$latitiude_array = [];
$longtitude_array = [];
if(!empty($all_zone_data)){
    //print_r($all_zone_data);
    foreach ($all_zone_data as $key => $value) {
        $zone_lat_lng = json_decode($value['zone_latitude_longitude']);
        
         for ($i=0; $i < count($zone_lat_lng); $i++) { 
            $zone_latlng = explode(',', $zone_lat_lng[$i]);
            $latitude = $zone_latlng[0];
            $longitude = $zone_latlng[1];
           

            if($i == 0){
               // $zone_latlng  .= 'start';
                $zone_latlng_array .= '[';

            }
            #for show zones
            $zone_latlng_array.= '{lat: '.$latitude.', lng: '.$longitude.'},';

             #for checking customer latlng is exit in that latlngs
            $latitiude_array[] = $latitude;
            $longtitude_array[] = $longitude;

            if($i == count($zone_lat_lng)-1){
                #for show zones
                $zone_latlng_array .= ']';
                $zone_latlng_merge_array.= $zone_latlng_array.',';
                $zone_latlng_array = "";
                
                #for checking customer latlng is exit in that latlngs
                $latlng_array[$key] = array($latitiude_array,$longtitude_array);
                $latitiude_array = [];
                $longtitude_array = [];
            }
         }  
         $zone_name_array[]  = $value['zone_name'];
         
    } 
    
    //print_r($latlng_array);
   
    $zone_name_array = json_encode($zone_name_array);
    //echo $zone_latlng_merge_array;
}
?>

<?php

    $c = false; 

   /* $vertices_x = array(9.33135,9.08562,8.72744,8.30557);  //latitude points of polygon
    $vertices_y = array(40.54695,41.08268,40.73087,39.9934);   //longitude points of polygon
    $points_polygon = count($vertices_x); 
    $latitude =  8.60180340; //latitude of point to be checked
    $longitude =  40.33438270; //longitude of point to be checked

    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude, $longitude)){
        echo "Is in polygon!"."<br>";
    }
    else { 
        echo "Is not in polygon"; 
    }*/

   /* function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
        $i = $j = $c = 0;

        for ($i = 0, $j = $points_polygon-1; $i < $points_polygon; $j = $i++) {
            if (($vertices_y[$i] >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
                $c = !$c;
            }
        }

        return $c;
    }*/


    #checking customer latlng is exist in zone latlng if exist then we  will calculate order total
    $order_count_array = [];
    $order_count = 0;
    $inner_array_count = 1;
    if(count($latlng_array)>0){
        for ($i=0; $i < count($latlng_array); $i++) { 
            
            $c = false; 

            $vertices_x = $latlng_array[$i][0];  //latitude   points of polygon in array
            $vertices_y = $latlng_array[$i][1];   //longitude  points of polygon in array
            $points_polygon = count($vertices_x); 

            //print_r($vertices_y);

            if(count(!$customer_latlng_data) > 0){
                foreach ($customer_latlng_data as $c_latlng) {
                      $customer_latitude = $c_latlng['latitude']; //latitude of point to be checked
                      $customer_longitude =  $c_latlng['longitude']; //longitude of point to be checked

                    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $customer_latitude, $customer_longitude)){
                        //echo "Is in polygon!"."<br>";
                        $order_count++;
                    }
                    else { 
                        //echo "Is not in polygon"."<br>";
                    }

                    if($inner_array_count == count($customer_latlng_data)){
                        $order_count_array[] = $order_count;
                        $order_count = 0;
                    }
                    $inner_array_count++;
                }  
            }
            
            $inner_array_count = 1;
        }
        //print_r($order_count_array);
        $order_count_array = json_encode($order_count_array);
    }

    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
        $i = $j = $c = 0;

        for ($i = 0, $j = $points_polygon-1; $i < $points_polygon; $j = $i++) {
            if (($vertices_y[$i] >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
                $c = !$c;
            }
        }

        return $c;
    }
?>

<script>
  var map;
  var gpolygons = [];
  var infoWindow;

  function initialize() {
    var mapOptions = {
      zoom: 11,
      center: new google.maps.LatLng(9.005401,38.763611),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var destinations = [<?php echo $zone_latlng_merge_array;?>];
    var zone_name = <?php echo $zone_name_array;?>;
    var order_count = <?php echo $order_count_array;?>;
    var polygonOptions;
    var polygon;
    for (var i = 0; i < destinations.length; i++) {

        //showing zone/area ------START----------
        polygonOptions = {
            paths: destinations[i], //'path' was not a valid option - using 'paths' array according to Google Maps API
            labelContent: "ABCD",
            strokeColor: '#49407f',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#4bc4d3',
            fillOpacity: 0.5,
        };
        polygon = new google.maps.Polygon(polygonOptions);

        //map needs to be defined somewhere outside of this loop
        //I'll assume you already have that.
        polygon.setMap(map);
        //showing zone/area ------END----------

        //showing zone name ---------START----------
        //Define position of label
        var bounds = new google.maps.LatLngBounds();
        for (var J = 0; J  < destinations[i].length; J ++) {
          bounds.extend(destinations[i][J]);
        }

        var myLatlng = bounds.getCenter();//geting center points 

        //alert(myLatlng); 
        //Showing the lable name
        var mapLabel = new MapLabel({
                                      text: zone_name[i]+' ('+order_count[i]+')',
                                      position: myLatlng,
                                      map: map,
                                      fontSize: 18,
                                      fontWeight: 500,
                                      strokeColor: '#412f8f',
                                      fillColor: '#4bc4d3',
                                      strokeWeight: 1.3,
                                      align: 'center'
                                    });
                                    mapLabel.set('position', myLatlng);

                                    var obj = {};
                                    obj.poly = polygon;
                                    obj.label = mapLabel;
                                    gpolygons.push(obj);
        
        //showing zone name ---------END----------

        // Add a listener for the click event.  You can expand this to change the color of the polygon
        //google.maps.event.addListener(polygon, 'click', showArrays,'dog');
    }

    //infoWindow = new google.maps.InfoWindow();
  }

  /** @this {google.maps.Polygon} */
  function showArrays(event) {

    //Change the color here
    // toggle it
    if (this.get("fillColor") != '#0000ff') {
      this.setOptions({
        fillColor: '#0000ff'
      });
    } else {
      this.setOptions({
        fillColor: '#ff0000'
      });
    }

    // Since this polygon has only one path, we can call getPath()
    // to return the MVCArray of LatLngs.
    var vertices = this.getPath();

    var contentString = '<b>My polygon</b><br>' +
      'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
      '<br>';

    // Iterate over the vertices.
    for (var i = 0; i < vertices.getLength(); i++) {
      var xy = vertices.getAt(i);
      contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' + xy.lng();
    }

    // Replace the info window's content and position.
    infoWindow.setContent(contentString);
    infoWindow.setPosition(event.latLng);

    infoWindow.open(map);
  }

  google.maps.event.addDomListener(window, 'load', initialize);
</script>
 

