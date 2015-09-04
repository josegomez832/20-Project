<?php
if (!empty($_GET['location'])){
  /**
   * Here we build the url we'll be using to access the google maps api
   */
  $maps_url = 'https://'.
  'maps.googleapis.com/'.
  'maps/api/geocode/json'.
  '?address=' . urlencode($_GET['location']);
  $maps_json = file_get_contents($maps_url);
  $maps_array = json_decode($maps_json, true);
  $lat = $maps_array['results'][0]['geometry']['location']['lat'];
  $lng = $maps_array['results'][0]['geometry']['location']['lng'];
  /**
   * Time to make our Instagram api request. We'll build the url using the
   * coordinate values returned by the google maps api
   */
  $instagram_url = 'https://'.
    'api.instagram.com/v1/media/search' .
    '?lat=' . $lat .
    '&lng=' . $lng .
    '&client_id=9544d2a52af545ea911ea49e6c17e3a1'; //replace "CLIENT-ID"
  $instagram_json = file_get_contents($instagram_url);
  $instagram_array = json_decode($instagram_json, true);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>geogram</title>
    <script src='https://maps.googleapis.com/maps/api/geocode/json?address=los%20angeles'></script>
    <script>  
    var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var mapOptions = {
      zoom: 8,
      center: latlng
    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
  }

  function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
    </script>
  </head>
  <bodyonload="initialize()">
 <div id="map" style="width: 320px; height: 480px;"></div>
  <div>
    <input id="address" type="textbox" value="Sydney, NSW">
    <input type="button" value="Encode" onclick="codeAddress()">
  </div>
  <form action="http://localhost:8888/urlencode/untitled.php" method="get">
    <input type="text" name="location"/>
    <button type="submit">Submit</button>
  </form>
    <br/>
    <?php
    if(!empty($instagram_array)){
      foreach($instagram_array['data'] as $key=>$image){
        echo '<img src="'.$image['images']['low_resolution']['url'].'" alt=""/><br/>';
      }
    }
    ?>
  </body>
</html>

<div id="map"></div>

  <ul>
    <?php foreach($results['data'] as $info){ ?>
    <li>  
      <span data-livestamp="<?php echo $info['created_time']; ?>"></span>
      <a href="<?php echo $info['link']; ?>" target="_blank">      
        <img src="<?php echo $info['images']['standard_resolution']['url']; ?>" class="img" />
      </a>
      <img src="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678087-heart-128.png" width="25px" height="25px" />
      <h4><?php echo $info['likes']['count']; ?></h4>
     </li>
<?php }?>
function initialize() {

    //Need geolocation
    //https://developers.google.com/maps/documentation/javascript/examples/map-geolocation

    var locations = [
      <?php foreach($results['data'] as $locations){ ?>
        ["<?php echo $locations['caption']['from']['username']; ?>", 
        <?php echo $locations['location']['latitude']; ?>,
        <?php echo $locations['location']['longitude'];?>],
      <?php }?>          
    ];
    /*
      These coordinates need to update when the map in viewport is moved
      https://developers.google.com/maps/documentation/javascript/events
      something to look at http://stackoverflow.com/questions/14285963/google-maps-get-viewport-latitude-and-longitude
      getBounds()  https://developers.google.com/maps/documentation/javascript/reference?hl=pt-BR#Map
    */
    var myLatLng = new google.maps.LatLng(<?php echo $results['data'][0]['location']['latitude']; ?>,<?php echo $results['data'][0]['location']['longitude']; ?>);

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });  

    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    var markers = new Array();
    for (i=0; i<locations.length;i++){
      marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map:map,
      animation: google.maps.Animation.DROP
    });      

    markers.push(marker);
    google.maps.event.addListener(marker, 'click', (function(marker, i){
      return function(){
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
        }
      })(marker, i));
    }         

    function AutoCenter() {
      //  Create a new viewpoint bound    
      var bounds = new google.maps.LatLngBounds();        
      //  Go through each...
      $.each(markers, function (index, marker) {
        bounds.extend(marker.position);
        console.log('autoCenter()');
        });
      //  Fit these bounds to the map
      map.fitBounds(bounds);              
      }
    AutoCenter();      

    google.maps.event.addListener(map, 'center_changed', function(){
      var control_center = map.getCenter();
      var lng = control_center.lng();
      var lat = control_center.lat();
      //parent.location.hash="&lng="+lng +"&lat="+lat;
      //console.log(map.getCenter());
      //console.log(lng +', ' +lat);
      console.log('center_changed');
      });
    }//initialize               
    google.maps.event.addDomListener(window, 'load', initialize);