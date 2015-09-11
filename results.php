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

    //$request = "https://api.instagram.com/v1/media/search?lat=".$lat."&lng=".$lng."&distance=1000&client_id=".$client_id;
    //$response = file_get_contents($request);
    //$results = json_decode($response, TRUE); 
?>
<!doctype html>
<html lang="en" ng-app="plunker">
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
  #map, #google{
  	width:100%;
  	height:400px;
  }
  h1#error{
    display: none;
  }
  h1.view{
    display: block;
  }
  </style>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>  
  <script src="moment.js"></script>
  <script src="livestamp.js"></script>
  <script src="Chart.min.js"></script>
  <script src="js/history.js"></script>
  <script src="js/jquery.jscroll.min.js"></script>
  <link rel="stylesheet" href="css/rzslider.css" />
  <link rel="stylesheet" href="css/demo.css" />
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet" >
</head>
<body ng-controller="MainCtrl">
  <div class="wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <a href="/20-Project/">Logo here</a>
        </div>

        <div class="col-md-8">
           <form action="results.php" method="get">
            <input type="text" id="location" placeholder="Search City" name="location"/>
            <button type="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="controls">
    <div class="container">
      <div class="row">
        <select id="range">
          <option value="1000">1 Mile</option>
          <option value="2000">2 Mile</option>
          <option value="3000">3 Mile</option>
          <option value="4000">4 Mile</option>
          <option value="5000">5 Mile</option>
        </select>
        
      </div>
    </div>
  </div>
  <h1 id="error">Sorry, try a better search...like a actual city</h1>
  
  <div id="google"></div>
  <div id="feed">
    <ul class="jscroll"></ul>
  </div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKB7Oy4gwlex36Tm9Pq676FR6C8fwJq7k"></script> 
<script type="text/javascript">
      
      var mapurl = "https://maps.googleapis.com/maps/api/geocode/json?address=<?php echo urlencode($_GET['location']);?>";
      var gmaps = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAKB7Oy4gwlex36Tm9Pq676FR6C8fwJq7k";
      var glat;
      var glng;
      var url;     


      var allOfIt = function(callback){
      //Google maps latitude and coordinates
        $.getJSON(mapurl, function (google) {
          if( google.results[0] == null){
            $('#error').css('display', 'block');
            $('#google').css('display', 'none');
            var tagSearch = "https://api.instagram.com/v1/tags/<?php echo $_GET['location'];?>/media/recent?access_token=<?php echo $token; ?>";
            $.getJSON(tagSearch, function (tag){
              for(var i = 0; i < 40; i++){
                $("#feed ul").append("<li><a target='_blank' href='" + tag.data[i].link + "'><img src='"+tag.data[i].images.low_resolution.url+"'/></a></li>");
                }
            });
            console.log('searching for tags');
            //code for results that are not location based
            //console.log('Sorry search for a city');
            
          } else {
            glat = google.results[0].geometry.location.lat;
            glng = google.results[0].geometry.location.lng;
            console.log('Google: ' + glat, glng);
            //This variable needs to be updated with new coordinates when map is moved
            //so it needs to be refered back to from outside the function
            //the new coordinates should update the feed and the icons on the map
            url = instagramURL(url);

            function instagramURL(url){
              
              url = "https://api.instagram.com/v1/media/search?lat=" + glat + "&lng=" + glng + "&distance=1000&client_id=<?php echo $client_id; ?>&count=40&callback=?";
              return url;
            }
            

            //Instagram Feed
            $.getJSON(url, function (response) {
              for(var i = 0; i < 40; i++){
                $("#feed ul").append("<li><a target='_blank' href='" + response.data[i].link + "'><img src='"+response.data[i].images.low_resolution.url+"'/></a></li>");
                }
            }); //end of response url $.getJSON requests            
            callback(url);        
          }  //else              
      });  //end of mapurl google $.getJSON requests
    }//end of allofit
    function updateCoor(glat){
      console.log('updateCoor()');
      console.log('updateGoogle()');
      //instagramURL();
      //this will update coordinates to update the feed
      // console.log(base + ilat + lat + ilng + lng + endpoint);
    }
    allOfIt(function(value){      
       $.getJSON(value, function (data) {                  
                  //console.log(value);

                  function initialize(){              
                      //Gets locations in array
                      var locations = [];                      
                      for(var a = 0; a < data.data.length; a++){
                          var name = data.data[a].user.username;
                          var lat = data.data[a].location.latitude;
                          var lng = data.data[a].location.longitude;
                          var mapStuff = [name, lat, lng];
                          locations.push(mapStuff);                            
                        }
                                      
                      var coordinates = new google.maps.LatLng(glat, glng);

                      var map = new google.maps.Map(document.getElementById('google'),{
                        zoom: 10,
                        center: coordinates           
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

                        google.maps.event.addListener(map, 'dragend', function (){   
                           
                              var control_center = map.getCenter();
                              glng = control_center.lng();
                              glat = control_center.lat(); 
                              
                                                                            
                          //return glat, glng;
                          console.log('dragend');
                          console.log(glng, glat);
                          updateCoor();
                          
                        });
                        
                    }
                    google.maps.event.addDomListener(window, 'load', initialize);                 
              });          
          })   
</script>   
</body>
</html>