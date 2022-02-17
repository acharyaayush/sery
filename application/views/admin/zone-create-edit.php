<?php 
$zone_lat_long_for_edit_map = "";
$zone_lat_long_edit_map = "";
if(!empty($zone_data)){
    $zone_name = $zone_data[0]['zone_name'];
    $zone_id = $zone_data[0]['id'];
    $zone_latitude_longitude = json_decode($zone_data[0]['zone_latitude_longitude']);
    //print_r($zone_latitude_longitude);
    for ($i=0; $i < count($zone_latitude_longitude); $i++) { 
        $zone_lat_long_for_edit_map .= 'new google.maps.LatLng('.$zone_latitude_longitude[$i].'),';
        $zone_lat_long_edit_map = $zone_latitude_longitude[$i].'<br>';

    }
    $mode = 2;
}else{
    $mode = 1;
    $zone_id = "";
    $zone_name= "";
}
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Zone</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Zone</h4>
                    </div>
                    <div class="card-body">
                        <?php $this->load->view("admin/validation");?>
                        <form method="POST" action="<?php echo base_url('admin/Create_Update_Zone_Controller')?>" class="needs-validation" novalidate=""
                            >
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-12 col-md-2 col-lg-2 offset-md-1" >Zone Name</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" placeholder="Enter Zone Name" required="" maxlength="8" name="zone_name" value="<?php echo $zone_name;?>">
                                    <div class="invalid-feedback">
                                      Please enter zone name
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <!-- <input id="search_location" /> -->
                                <div id="map-canvas" style="height: 400px; width: auto;"></div>
                                 <textarea id="info" class="form-control" name="zones_lat_long" style="visibility: hidden;" required=""><?php echo $zone_lat_long_edit_map;?></textarea>
                            </div>
                            <div class="form-group text-center">
                                <input type="hidden" name="mode" value="<?php echo $mode;?>"/>
                                <input type="hidden" name="zone_id" value="<?php echo $zone_id;?>"/>
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a class="btn btn-primary" href="<?php echo base_url('admin/zone_management')?>"> Cancel</a>
                            </div>   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXAPSpjmIu_a1ZcAHiw9oB_pJozpbWTyM"></script>
<?php 

if($zone_lat_long_for_edit_map != ""){
    $lat_long = $zone_lat_long_for_edit_map;
}else{
    $lat_long = 'new google.maps.LatLng(10.51093,39.90425),
            new google.maps.LatLng(8.68943,42.04398),
            new google.maps.LatLng(8.68943,41.04398),
            new google.maps.LatLng(7.77797,39.10351),
            ';
}

?>
<!--- reffrence from https://dotnettec.com/google-maps-draw-polygon-get-coordinates/-->
<script type="text/javascript">
    var draggablePolygon; function InitMap() {
        var location = new google.maps.LatLng(8.68943,42.04398);
        var mapOptions = {
            zoom:<?php if($mode == 2){echo '8';}else{echo '7';}?>,
            center: location,
            mapTypeId: google.maps.MapTypeId.RoadMap
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        var shapeCoordinates = [
           /* new google.maps.LatLng(28.525416, 79.870605),
            new google.maps.LatLng(27.190518, 77.530518),
            new google.maps.LatLng(29.013807, 77.67334)*/
            <?php echo $lat_long ;?>
        ];

        // Construct the polygon
        draggablePolygon = new google.maps.Polygon({
            paths: shapeCoordinates,
            draggable: true,
            editable: true,
            strokeColor: '#49407f',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#4bc4d3',
            fillOpacity: 0.5
        });

        draggablePolygon.setMap(map);

        google.maps.event.addListener(draggablePolygon, "dragend", Getpolygoncoordinates);
        google.maps.event.addListener(draggablePolygon.getPath(), "insert_at", Getpolygoncoordinates);
        google.maps.event.addListener(draggablePolygon.getPath(), "remove_at", Getpolygoncoordinates);
        google.maps.event.addListener(draggablePolygon.getPath(), "set_at", Getpolygoncoordinates);

        google.maps.event.addListener(draggablePolygon.getPath(), 'set_at', function(index, obj) {
          console.log('Vertex moved on outer path.');
          console.log(obj.lat(), '--', obj.lng());

            google.maps.event.addListener(draggablePolygon, "dragend", Getpolygoncoordinates);
            google.maps.event.addListener(draggablePolygon.getPath(), "insert_at", Getpolygoncoordinates);
            google.maps.event.addListener(draggablePolygon.getPath(), "remove_at", Getpolygoncoordinates);
        });
    }

    function Getpolygoncoordinates() {
        var len = draggablePolygon.getPath().getLength();
        var strArray = "";
        for (var i = 0; i < len; i++) {
            strArray += draggablePolygon.getPath().getAt(i).toUrlValue(5) + "<br>";
        }

        document.getElementById('info').innerHTML = strArray;
    }
</script>


