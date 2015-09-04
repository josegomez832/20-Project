<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery.getJSON demo</title>
  <style>
  html, body,  { height: 100%; margin: 0; padding: 0;}
  img.img,a img.img {
    height: auto;
    width:100%;
    float: left;
  }
  a img.img{
    display: block;
  }
  ul{
    margin:0;
    padding:0;
    width:100%;
    display: block;
  }
  ul li{
    width:25%;
    margin:0;
    display: block;
    float: left;
  }
  form{
    width:100%;
    display:block;
    padding:10px 15px;
    height:61px;
  }
  .wrapper{

  }
  #map{
  	/*width:50%;
  	position: fixed;
    left:0;
    top:0;
    bottom:0;*/
  }
  .feed{
   /* width:50%;
    position: fixed;
    right:0;
    top:0;
    bottom:0;
    overflow: scroll;*/
  }
  </style>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script src="moment.js"></script>
  <script src="livestamp.js"></script>
  <script src="Chart.min.js"></script>
</head>
<body>


  <?php
      if(!empty($_GET['location'])){
        $maps_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_GET['location']);
        $maps_json = file_get_contents($maps_url);
        $maps_array = json_decode($maps_json, true);

        $lat = $maps_array['results'][0]['geometry']['location']['lat'];
        $lng = $maps_array['results'][0]['geometry']['location']['lng'];
      }

    $client_id = '9544d2a52af545ea911ea49e6c17e3a1';
    $client_secret = '4a95cc1c09664b16b0ed17d105144d85';
    $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";
   
    $request = "https://api.instagram.com/v1/media/search?lat=".$lat."&lng=".$lng."&distance=1000&client_id=".$client_id;
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE);
   
?>
  <form action="api.php" method="get">
    <input type="text" id="location" placeholder="Search City" name="location"/>
    <button type="submit">Submit</button>
  </form>
<p><?php echo $_GET['lat'];?></p>
<script>

   $(document).ready(function(){
      //jquery for input fields to show and hide depending on the option selected
   });
</script>
<div class="wrapper">
<div id="map"></div>
<?php
 /* <div id="canvas-holder">
      <canvas id="chart-area" width="300" height="300"/>
    </div>
    */
?>
<div class="feed">
  <ul>
    <?php foreach($results['data'] as $info){ ?>
    <li>	
      <span data-livestamp="<?php echo $info['created_time']; ?>"></span>
      <a href="<?php echo $info['link']; ?>" target="_blank">
        <img src="<?php echo $info['images']['standard_resolution']['url']; ?>" class="img" />
      </a>
      <img src="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678087-heart-128.png" width="25px" height="25px" />
      <h4><?php echo $info['likes']['count']; ?></h4>
	   <p>
	     <?php //foreach($info['tags'] as $tags){ 
		      //<strong>#<?php echo $tags; </strong>, 
	    //} ?>
	</p></li>
<?php }?>
</ul>
</div>
</div>

<script type="text/javascript">

  var interval = setInterval(function(){
    refresh_box()}, 6000);
  function refresh_box() {
    $(".tags").load('tags.php');
  }

</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKB7Oy4gwlex36Tm9Pq676FR6C8fwJq7k"></script>
  <script type="text/javascript">
      function initialize() {
        //Need geolocation
        //https://developers.google.com/maps/documentation/javascript/examples/map-geolocation

        var locations = [
           <?php foreach($results['data'] as $locations){ ?>
                ["<?php echo $locations['caption']['from']['username']; ?>", <?php echo $locations['location']['latitude']; ?>,<?php echo $locations['location']['longitude'];?>],
          <?php }?>          
        ];

        /*
            These coordinates need to update when the map in viewport is moved
            https://developers.google.com/maps/documentation/javascript/events
            something to look at http://stackoverflow.com/questions/14285963/google-maps-get-viewport-latitude-and-longitude
            getBounds()  https://developers.google.com/maps/documentation/javascript/reference?hl=pt-BR#Map
        */

        //
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
            }//return function
           })(marker, i));//eventlistener
          }//for loop


         // map.fitBounds();
          google.maps.event.addListener(map, 'center_changed', function(){
            var control_center = map.getCenter();
            var lng = control_center.lng();
            var lat = control_center.lat();
            parent.location.hash="&lat="+lat + "lng="+lng;
            console.log(map.getCenter());
            //console.log(lng +', ' +lat);
          }); 

          function AutoCenter() {
            //  Create a new viewpoint bound    
            var bounds = new google.maps.LatLngBounds();        
            //  Go through each...
            $.each(markers, function (index, marker) {
                bounds.extend(marker.position);
            });
              //  Fit these bounds to the map
              map.fitBounds(bounds);
              //console.log(bounds);
             // map.getBounds();
            }
            AutoCenter();
          }//initialize    

        
        google.maps.event.addDomListener(window, 'load', initialize);


    </script>
    <script>
    var hex =[
        
    ]
    var pieData = [
     <?php
          $colors = array('hex' => array(
                                          "color 1" => "#F7464A", 
                                          "color 2" => "#46BFBD", 
                                          "color 3" => "#FDB45C", 
                                          "color 3" => "#949FB1", 
                                          "color 4" => "#4D5360")
                );

             // foreach($colors['hex'] as $hex){
               // echo $hex;
              //}
         
          //$hex = count($colors);        
          foreach($results['data'] as $likes){  
      ?>
          {
          value: <?php echo $likes['likes']['count'];?>,

          color: "<?php echo $colors['hex']['color 4']; ?>",           
                
          label: "<?php echo $likes['user']['username'];?>"
        },


      <?php
         }
      ?>
       
      ];

      window.onload = function(){
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myPie = new Chart(ctx).Pie(pieData);
      };



  </script>
</body>
</html>