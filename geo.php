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
    display: inline-block;
  }
  #map{
  	width:50%;
  	height:400px;
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
    /*
        To create your own client id, client secret and token visit https://instagram.com/developer/
        Then go to Manage Clients
        Register a New Client
        Use "http://localhost" as your website uri and redirect uri
        new id and secret should be given to you
        to get token visit https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token
        Replace CLIENT_ID with your id and REDIRECT-URI with http://localhost, press enter and should redirect to you a url with your token
    */
    //http://davidwalsh.name/xbox-api
    $client_id = '9544d2a52af545ea911ea49e6c17e3a1';
    $client_secret = '4a95cc1c09664b16b0ed17d105144d85';
    $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";
    //http://jelled.com/instagram/access-token
    //if getting a 403 error visit https://teamtreehouse.com/forum/oauth-error-403

    //Need to create lat and lng as variables
    $request = "https://api.instagram.com/v1/media/search?lat=29.7575275&lng=-95.3580718&distance=1000&client_id=".$client_id;
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE);
    echo '<div style="height:400px;overflow:scroll;">';
    echo "<pre>";
   // print_r($results);
    echo "</pre>";
    echo '</div>';
 
?>
<div id="map"></div>
<div class="tags"></div>
  <h1>Hashtag</h1>
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
<div id="canvas-holder">
      <canvas id="chart-area" width="300" height="300"/>
    </div>
<script type="text/javascript">

  var interval = setInterval(function(){
    refresh_box()}, 6000);
  function refresh_box() {
    $(".tags").load('tags.php');
  }

</script>

<script type="text/javascript" src="https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyAKB7Oy4gwlex36Tm9Pq676FR6C8fwJq7k"></script>
  <script type="text/javascript">
      function initialize() {



        var locations = [

           <?php foreach($results['data'] as $locations){ ?>
                ["<?php echo $locations['caption']['from']['username']; ?>", <?php echo $locations['location']['latitude']; ?>,<?php echo $locations['location']['longitude'];?>],
          <?php }?>
           
                
        ];



        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: new google.maps.LatLng(<?php echo $results['data'][0]['location']['latitude']; ?>,<?php echo $results['data'][0]['location']['longitude']; ?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
     	



          var infowindow = new google.maps.InfoWindow();
          var marker, i;
          var markers = new Array();
          for (i=0; i<locations.length;i++){
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map:map
              });
              markers.push(marker);
              google.maps.event.addListener(marker, 'click', (function(marker, i){
                  return function(){
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                  }//return function
              })(marker, i));//eventlistener
            }//for loop




      function AutoCenter() {
        //  Create a new viewpoint bound
        var bounds = new google.maps.LatLngBounds();
        //  Go through each...
        $.each(markers, function (index, marker) {
            bounds.extend(marker.position);
        });
          //  Fit these bounds to the map
          map.fitBounds(bounds);
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
          //$hex = count($colors);        
          foreach($results['data'] as $likes){  
      ?>
          {
          value: <?php echo $likes['likes']['count'];?>,
          color: "",           
                
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